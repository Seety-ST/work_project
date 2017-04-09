<?php

defined('YUE_PROTOCOL_ROOT') || die('INC file not loaded!');

/**
 * 通信协议
 *         ( 说明: 参数传递 __debug = true 则表示调试状态,在协议中可以 用 $this->_debug 调试 )
 *
 * @author willike <chenwb@yueus.com>
 * @since 2015-7-30
 */
class yue_protocol_system {

    /**
     * @var string app name
     */
    private $_app_name = null;

    /**
     * @var boolean 是否调试
     */
    private $_debug = FALSE;

    /**
     * @var boolean  是否加密
     */
    private $_encrypt = FALSE;

    /**
     * @var string 请求连接
     */
    private $_uri = null;

    /**
     * @var mixed 用户数据
     */
    private $_input = array();

    /**
     * @var object 请求分发对象
     */
    private $request_obj = null;

    /**
     * @var object 抛出返回数据对象
     */
    private $response_obj = null;

    /**
     * 初始化
     */
    public function __construct() {
        // 协议请求分发
        $request_obj = new yue_protocol_request();
        $this->request_obj = $request_obj;
        $this->_app_name = $request_obj->get_input_item('app_name');  // 获取 app name
        $this->_debug = $request_obj->is_debug();  // 是否在调试
        // 协议返回抛出
        $this->response_obj = new yue_protocol_response($this->_debug);
        // 设置异常处理函数
        set_exception_handler(array($this, 'protocol_exception_handler'));
    }

    /**
     * 获取 app name
     *
     * @return string
     */
    private function get_app_name() {
        $input = $this->_input;
        if (empty($input)) {
            return $this->_app_name;
        }
        return trim($input['app_name']);
    }

    /**
     * 获取客户端的数据
     *
     * @access public
     * @param  array $options 参数数组
     *   array(
     *         'be_check_token' => true,     // 是否检测Token , 默认true (检查)
     *         'b_response' => true,   // 是否抛出数据 , 默认true (是)
     *    );
     * @return array
     */
    public function get_input(array $options) {
        // 格式化参数
        $be_check_token = isset($options['be_check_token']) ? (bool) $options['be_check_token'] : TRUE;  // 是否验证TOKEN
        $b_response = isset($options['b_response']) ? (bool) $options['b_response'] : TRUE;  // 是否抛出数据
        $input = $this->request_obj->get_input(FALSE);  // 输入数据
        return $this->get_input_process($input, $be_check_token, $b_response);
    }

    /**
     * 数据 处理
     *
     * @param array $input
     * @param boolean $token_check 是否进行token验证
     * @param boolean $b_response 是否抛出数据 ( 仅对结果有效 )
     * @return array
     */
    public function get_input_process($input, $token_check = TRUE, $b_response = TRUE) {
        $this->response_obj->set_json_ejection($b_response);  // 设置是否json抛出
        if (empty($input) || !is_array($input)) {
            return $this->response_obj->response(201);
        }
        // 组装 附加数据
        $uri = $this->request_obj->get_request_uri();
        $input['uri'] = $uri;
        $unique_sign = md5($input['sign_code'] . microtime(TRUE) . mt_rand(0, 9999));  // 唯一标识
        $input['unique_sign'] = substr($unique_sign, 8, 8);  // 唯一标识( 用于数据定位 )
        // 给 response赋值
        $this->response_obj->set_response_param($input, $this->_debug);
        $this->_input = $input;
        // 验证数据是否正确
        if (!$this->request_obj->is_input_valid($input)) {
            $errmsg = $this->request_obj->get_errmsg();
            return $this->response_obj->response(202, array(), $errmsg);   // 抛回错误
        }
        // 有效数据 写(访问)日志
        yue_protocol_log::yue_log($input, yue_protocol_log::VISIT_LOG);
        // 验证校验码是否正确
        $input_raw = $this->request_obj->get_input(TRUE);  // 原始数据
        if (!$this->request_obj->is_sign_valid($input['param'], $input['sign_code'], $input_raw)) {
            $errmsg = $this->request_obj->get_errmsg();
            return $this->response_obj->response(202, array(), $errmsg);   // 抛回错误
        }
        // 是否 token 授权验证
        $app_name = $this->get_app_name();  // 请求来源
        $detect = $this->request_obj->is_token_detect($app_name);  // 是否检测token ( -false 不检查 )
        if ($token_check !== FALSE) {
            if ($detect !== FALSE) {
                // 需要验证 access_token 授权
                $check_res = $this->request_obj->is_token_valid($input['param']['user_id'], $app_name, $input['param']['access_token'], $input['bypass']);
                if (empty($check_res)) {
                    $errmsg = $this->request_obj->get_errmsg();
                    return $this->response_obj->response(205, array(), $errmsg);   // 抛回错误
                }
            }
        }
        // 判断是否解密
        if ($input['is_enc'] == 1) {
            $encrypt_key = protocol_api_conf('ENCRYPT_KEY', 'config');  // 密钥
            $input['param'] = json_decode(protocol_api_decrypt(base64_decode($input['param']), $encrypt_key), true);
        }
        // 过滤html标签 ( UTF8 )
        $param = protocol_api_input_html_filter($input['param'], 'quotes', FALSE);
        // 判断数据是否需转编码
        $client_charset = protocol_api_conf('CLIENT_DATA_CHARSET', 'config');  // 客户端编码
        $service_charset = protocol_api_conf('SERVICE_DATA_CHARSET', 'config');  // 服务端编码
        if (protocol_api_charset_compare($client_charset, $service_charset) !== 0) {
            // 数据编码转换
            $param = protocol_api_charset_conv($param, $service_charset, $client_charset);
        }
        // 封包
        $data = array(
            'app_name' => $input['app_name'],
            'param' => $param,
            'version' => $input['version'], // 客户端版本
            'os_type' => $input['os_type'],
            'ctime' => $input['ctime'],
            'uri' => $uri, // 请求链接
            'method' => $this->request_obj->get_method(), // 请求方式
            'ip_address' => $this->request_obj->get_request_ip(),
        );
        // 返回数据给 服务器端
        return array('code' => 100, 'data' => $data, 'msg' => 'Legal request!');
    }

    /**
     * 数据返回给客户端
     *
     * @access public
     * @param array $options 返回数据
     *      array( 'code' => 200, 'data' => array(), 'message' => 'xxxx' );
     * @param boolean $is_conv 是否转编码 ( 默认-true, 转编码 )
     * @return void
     */
    public function output($options, $is_conv = TRUE) {
        $code = empty($options['code']) ? 200 : $options['code'];  // 响应码
        $message = empty($options['message']) ? protocol_api_conf($code, 'code') : $options['message'];  // 返回信息
        $data = empty($options['data']) ? array() : protocol_api_handle_image_cdn($options['data']);  // 处理图片的CDN问题
        $input = $this->_input;
        // int型 转 字符串 2015-12-23
        $version = $input['version'];
        if (protocol_api_conf('OUTPUT_INT_TO_STRING', 'config') === true) {
            $data = protocol_api_output_process($data, $version);
        }
        return $this->response_obj->response($code, $data, $message, $this->_encrypt, $is_conv);
    }

    /**
     * oauth 对象
     * 
     * @param string $app_name 客户端类型
     * @return object
     */
    private function yue_protocol_oauth_obj($app_name) {
        if (!class_exists('POCO_TDG')) {
            exit('PROTOCOL ERROR: Without the framework of YUEUS included!');
        }
        $oauth_obj = new yue_protocol_oauth($app_name, $this->_debug);
        return $oauth_obj;
    }

    /**
     * 创建一个 授权 ( 写入数据 )
     *
     * @access public
     * @param int $user_id 用户ID
     * @param string $app_name 授权来源名称
     * @param boolean $auto_create 是否自动创建
     * @param boolean $b_use_cache 是否获取缓存数据
     * @param boolean $not_response 不自动抛出
     * @return array
     */
    public function get_access_info($user_id, $app_name, $auto_create = false, $b_use_cache = TRUE, $not_response = true) {
        $app_name = empty($app_name) ? $this->get_app_name() : $app_name;
        $oauth_obj = $this->yue_protocol_oauth_obj($app_name);
        $oauth_info = $oauth_obj->get_access_info($user_id, $app_name, $b_use_cache);
        if (empty($oauth_info)) {  // 没有 token
            if ($not_response === false) {
                $errmsg = $oauth_obj->get_errmsg();
                return $this->response_obj->response(217, array(), $errmsg);   // 抛回错误
            }
            if ($auto_create !== TRUE) {
                return array();
            }
            // 自动创建
            $oauth_info = $oauth_obj->create_access_info($user_id, $app_name);
        }
        if ($oauth_info['expire_time'] - time() < 3600) {
            // token 快过期, 则自动更新
            $refresh_token = $oauth_info['refresh_token'];
            $oauth_info = $oauth_obj->update_by_refresh_token($user_id, $refresh_token, $app_name);
        }
        return $oauth_info;
    }

    /**
     * 刷新 TOKEN
     *
     * @param int $user_id
     * @param string $app_name
     * @param string $refresh_token TOKEN
     * @param array $access_info 授权信息
     * @return array
     */
    public function refresh_access_info($user_id, $app_name, $refresh_token, $access_info = array()) {
        $user_id = empty($user_id) ? intval($access_info['user_id']) : intval($user_id);
        if (empty($app_name)) {
            $app_id = $access_info['app_id'];
            $apps = protocol_api_conf(null, 'access');
            foreach ($apps as $value) {
                if ($app_id == $value['app_id']) {
                    $app_name = $value['app_name'];
                    break;
                }
            }
            $app_name = empty($app_name) ? $this->_app_name : $app_name;
        }
        if (empty($user_id)) {
            return $this->response_obj->response(216, '', 'No login!');
        }
        if (empty($app_name)) {
            return $this->response_obj->response(216, '', 'App name is empty!');
        }
        $refresh_token = empty($refresh_token) ? trim($access_info['refresh_token']) : trim($refresh_token);
        if (empty($refresh_token)) {
            $data = array(
                'user_id' => $user_id,
                'app_name' => $app_name,
            );
            return $this->response_obj->response(216, $data, 'refresh token is empty!');
        }
        $oauth_obj = $this->yue_protocol_oauth_obj($app_name);
        $oauth_info = $oauth_obj->update_by_refresh_token($user_id, $refresh_token, $app_name);
        if (empty($oauth_info)) {
            return $this->response_obj->response(216, '', $oauth_obj->get_errmsg());
        }
        return $oauth_info;
    }

    /**
     * 重置 TOKEN
     *
     * @param int $user_id
     * @param string $app_name
     * @param string $refresh_token TOKEN
     * @param array $access_info 授权信息
     * @return array
     */
    public function reset_access_info($user_id, $app_name) {
        $user_id = intval($user_id);
        $app_name = empty($app_name) ? $this->_app_name : $app_name;
        if (empty($user_id) || empty($app_name)) {
            return $this->response_obj->response(216, '', 'App name is empty!');
        }
        $oauth_obj = $this->yue_protocol_oauth_obj($app_name);
        $oauth_info = $oauth_obj->create_access_info($user_id, $app_name);
        if (empty($oauth_info)) {
            return $this->response_obj->response(216, '', $oauth_obj->get_errmsg());
        }
        return $oauth_info;
    }

    /**
     * TOKEN 是否过期
     *
     * @param int $user_id
     * @param string $app_name
     * @param string $access_token TOKEN
     * @param array $access_info 授权信息
     * @return boolean - true 已过期
     */
    public function is_access_expire($user_id, $app_name, $access_token, $access_info = array()) {
        $user_id = empty($user_id) ? $access_info['user_id'] : $user_id;
        if (empty($app_name)) {
            $app_id = $access_info['app_id'];  // 通过 app_id 获取 app name
            $apps = protocol_api_conf(null, 'access');
            foreach ($apps as $value) {
                if ($app_id == $value['app_id']) {
                    $app_name = $value['app_name'];
                    break;
                }
            }
            $app_name = empty($app_name) ? $this->get_app_name() : $app_name;
        }
        $access_token = empty($access_token) ? $access_info['access_token'] : $access_token;
        if (empty($user_id) || empty($app_name) || empty($access_token)) {
            return TRUE;
        }
        $oauth_obj = $this->yue_protocol_oauth_obj($app_name);
        return $oauth_obj->is_access_expire($user_id, $access_token, $app_name);
    }

    /**
     * 异常处理
     *
     * @param Exception|object $ex 错误对象
     * @return bool|void
     */
    public function protocol_exception_handler(Exception $ex) {
        // 写错误日志
        $path = YUE_PROTOCOL_ROOT . 'log/PROTOCOL_ERROR_HANDLER/';
        if (!is_dir($path)) {
            mkdir($path, 0777, TRUE);
        }
        $file = $path . '/err-' . date('Y-m') . '.log';
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');  // uri
        $msg = $ex->getMessage(); // 错误信息
        $msg = mb_convert_encoding($msg, protocol_api_conf('CLIENT_DATA_CHARSET'), protocol_api_conf('SERVICE_DATA_CHARSET'));  // 转编码
        if (empty($_REQUEST['req'])) {
            $param = array(
                'cookie' => $_COOKIE,
                'request' => $_REQUEST,
            );
            $input = json_encode($param);
        } else {
            $input = $_REQUEST['req'];  // 用户输入
        }
        $agent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');  // 服务器信息
        $md5 = substr(md5(time() . $uri . protocol_api_get_rand_string(8)), 8, 16); // 快速定位
        $data = date('Y-m-d H:i:s') . '^$^' . $md5 . '^$^' . $uri . '^$^' . $msg . '^$^' . $input . '^$^' . $agent . PHP_EOL; // 日志内容
        file_put_contents($file, $data, LOCK_EX | FILE_APPEND);  // 独占锁
        exit('SYSTEM ERROR(' . $md5 . '):  An system error occured, pls call yueus administrator!');
    }

}
