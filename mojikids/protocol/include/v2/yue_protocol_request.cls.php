<?php

defined('YUE_PROTOCOL_ROOT') || die('INC file not loaded!');

/**
 * 协议请求分发类
 *
 * @author willike  <chenwb@yueus.com>
 * @since 2015-7-30
 * @version 1.0 Beta
 */
class yue_protocol_request {

    /**
     * @var string 请求方式
     */
    private $_request_method = null;

    /**
     * @var string 用户输入
     */
    private $_input = null;

    /**
     * @var array 参数
     */
    private $_params = array();

    /**
     * @var string 错误信息
     */
    private $_errmsg = null;

    /**
     * @var string 请求URI
     */
    private $_request_uri = null;

    /**
     * @var string 请求时IP
     */
    private $_request_ip = null;

    /**
     * @var boolean 是否调试
     */
    private $_debug = FALSE;

    /**
     * 初始化
     */
    public function __construct() {
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            // 禁止 magic quotes
            set_magic_quotes_runtime(FALSE);
        }
        $request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD'); // 请求方式
        $this->_request_method = $request_method;
        $this->_request_uri = filter_input(INPUT_SERVER, 'REQUEST_URI');   // 请求的URI
        $this->_request_ip = protocol_api_get_client_ip();  // 请求的IP
        // 根据请求方式获取客户端的数据
        switch ($request_method) {
            case 'POST':
                $input = (isset($_POST['req']) && !empty($_POST['req'])) ? $_POST['req'] : '';
                break;
            case 'GET':
                $input = (isset($_GET['req']) && !empty($_GET['req'])) ? $_GET['req'] : '';
                if (!empty($input)) {
                    $input = base64_decode(str_replace(' ', '+', $input));  // Base64会有“+”号，经过URL变为了空格
                }
                break;
            default:
                $input = null;
        }
//        var_dump($input);exit;
        $this->_input = $input;
        $params = array();
        if (!empty($input)) {
            $params = json_decode($input, TRUE);
//            var_dump($params);exit;
            if(empty($params)){
                $input = str_replace('\'','"',$input);
                $params = json_decode($input, TRUE);
                $this->_input = json_encode($params);
            }
            if (isset($params['path'])) {  // for web
                $this->_request_uri = '/mobile_app/' . trim($params['path'], '/');
            }
        }
        $this->_params = $params;  // 转为数组
        $this->_debug = $this->is_debug();  // 是否调试
    }

    /**
     * 获取 错误信息
     *
     * @return string
     */
    public function get_errmsg() {
        return $this->_errmsg;
    }

    /**
     * 返回客户端的请求方式
     *
     * @return string
     */
    public function get_method() {
        return $this->_request_method;
    }

    /**
     * 判断是否是GET请求
     *
     * @return boolean - true 是
     */
    public function is_get() {
        return $this->_request_method == 'GET' ? TRUE : FALSE;
    }

    /**
     * 判断是否是POST请求
     *
     * @return boolean - true 是
     */
    public function is_post() {
        return $this->_request_method == 'POST' ? TRUE : FALSE;
    }

    /**
     * 判断是否是PUT请求
     *
     * @return boolean - true 是
     */
    public function is_put() {
        return $this->_request_method == 'PUT' ? TRUE : FALSE;
    }

    /**
     * 判断是否是DELETE请求
     *
     * @return boolean - true 是
     */
    public function is_delete() {
        return $this->_request_method == 'DELETE' ? TRUE : FALSE;
    }

    /**
     * 返回客户端的参数
     *
     * @access public
     * @param boolean $raw 是否返回原始数据
     * @return mixed
     */
    public function get_input($raw = FALSE) {
        return ($raw === TRUE) ? $this->_input : $this->_params;
    }

    /**
     * 获取 某个数据项
     *
     * @param string $name
     * @return mixed
     */
    public function get_input_item($name) {
        $input = $this->_params;
        return isset($input[$name]) ? $input[$name] : null;
    }

    /**
     * 返回客户端的URI
     *
     * @return string
     */
    public function get_request_uri() {
        $request_uri = $this->_request_uri;
        return str_replace('//', '/', $request_uri);
    }

    /**
     * 返回 IP
     *
     * @return string IP
     */
    public function get_request_ip() {
        return $this->_request_ip;
    }

    /**
     * 获取 用户AGENT
     *
     * @return string
     */
    public function get_request_agent() {
        $agent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');  // 服务器信息
        return $agent;
    }

    /**
     * 是否调试
     *
     * @param array $input 输入数据
     * @return boolean - true 调试
     */
    public function is_debug($input = array()) {
        if (!protocol_api_conf('OPEN_DEBUG', 'config')) {
            return FALSE;
        }
        $input = empty($input) ? $this->_params : $input;
        $debug = isset($input['param']['__debug']) ? $input['param']['__debug'] : null;  // 是否调试
        return strtolower($debug) === 'true' ? TRUE : FALSE;
    }

    /**
     * 校验 数据是否有效
     *
     * @access public
     * @param array $data 校验数据
     * @return bolean -true 有效
     */
    public function is_input_valid($data) {
        // 数据项为空
        if (empty($data)) {
            $this->_errmsg = 'The client request data is empty';
            return FALSE;
        }
        // 判断客户端是否有传递操作系统类型
        if (empty($data['os_type'])) {
            $this->_errmsg = 'The client type is empty!';
            return FALSE;
        }
        // 判断客户端是否有传递时间
        if (empty($data['ctime'])) {
            $this->_errmsg = 'The client time sign is empty!';
            return FALSE;
        }
        // 判断客户端是否有传递签名
        if (empty($data['sign_code'])) {
            $this->_errmsg = 'The client sign is empty!';
            return FALSE;
        }
        // 判断客户端是否有传递APP NAME
        if (empty($data['app_name'])) {
            $this->_errmsg = 'The client app name is empty!';
            return FALSE;
        }
        $config = protocol_api_conf($data['app_name'], 'access');  // app 授权信息
        if (empty($config)) {
            // 没有该 app name
            $this->_errmsg = 'The client app name is invalid!';
            return FALSE;
        }
        // 判断客户端是否有传递加密的标识
        $is_enc = strval($data['is_enc']);
        if (strlen($is_enc) < 1) {
            $this->_errmsg = 'The encrypt key is empty!';
            return FALSE;
        }
        // 判断客户端是否有传递版本号
        if (empty($data['version'])) {
            $this->_errmsg = 'The client version is empty!';
            return FALSE;
        }
        // 判断版本号是否合法
        if (!preg_match('/^\d+\.\d+\.\d+/', $data['version'])) {
            $this->_errmsg = 'The client version is invalid!';
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 校验客户端的校验码是否正确
     *
     * @access public
     * @param  string $param 校验数据
     * @param string $sign_code 校验码
     * @param  string $raw_data 原始数据
     * @return boolean  -true 有效
     */
    public function is_sign_valid($param, $sign_code, $raw_data = null) {
        if (empty($sign_code)) {
            $this->_errmsg = 'The encrypt sign is empty!';
            return FALSE;
        }
        // 直接对 param 校验
        if (substr(md5('poco_' . json_encode($param) . '_app'), 5, -8) == $sign_code) {
            return TRUE;
        }
        // 使用 原始数据 匹配校验
        if (empty($raw_data)) {
            $this->_errmsg = 'Unable to validate the sign code!';
            return FALSE;
        }
        // 匹配出 param数据 JSON
        $match = array();
        if (preg_match("/\"param\":({.*})[,}]{1}|\"param\":(\[.*\])[,}]{1}|\"param\":\"(.*?)\"[,}]{1}|\"param\":(\d+\.?\d*)[,}]{1}|\"param\":(null)[,}]{1}/", $raw_data, $match)) {
            // 找到匹配的数据
            $param_json = '{}';
            foreach ($match as $k => $v) {
                if ($k > 0 && strlen($v) > 0) {
                    $param_json = $v;
                    break;
                }
            }
        }
        // 处理base64编码后经过JSON的转义符问题
//        if ($data['is_enc'] == 1) {
//            $param_json = str_replace('\/', '/', $param_json);
//        }
        // param JSON 数据验码
        $server_sign_code = substr(md5('poco_' . $param_json . '_app'), 5, -8);
        // 判断校验码是否一致
        if ($sign_code == $server_sign_code) {
            return TRUE;
        }
        // 记录错误
        $error_data = array(
            'client_sign_code' => $sign_code,
            'server_sign_code' => $server_sign_code,
            'param_json' => $param_json,
            'input_str' => $raw_data
        );
        // 写 签名验证日志
        $app_name = $this->get_input_item('app_name');
        yue_protocol_log::yue_log($error_data, yue_protocol_log::SIGN_ERROR_LOG, $app_name);
        $this->_errmsg = 'Could not access, due to encrypt sign mismatch!';
        return FALSE;
    }

    /**
     * 是否 校验token
     *
     * @param string $app_name
     * @return boolean - true 校验
     */
    public function is_token_detect($app_name) {
        if (empty($app_name)) {
            return TRUE;
        }
        $debug = protocol_api_conf('OPEN_DEBUG', 'config');
        if ($debug !== TRUE) {
            return TRUE;
        }
        $auth = protocol_api_conf('NON_TOKEN_AUTH', 'config');
        $auth_str = is_array($auth) ? implode(',', $auth) : $auth;  // 转为字符串
        if (strpos($auth_str, $app_name) === FALSE) {
            return TRUE;
        }
        $agent_match = protocol_api_conf('NON_TOKEN_AUTH_AGENT_MATCH', 'config');
        if (!empty($agent_match)) {
            $agent = $this->get_request_agent();
            if (strpos($agent, $agent_match) === false) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * 是否绕过 token 验证
     *
     * @param string $bypass  绕过验证 令牌
     * @return boolean - true 绕过token验证
     */
    public function is_token_bypass($bypass) {
        if (!protocol_api_conf('OPEN_DEBUG', 'config')) {
            return FALSE;
        }
        if (empty($bypass)) {
            return false;
        }
        $bypass_key = protocol_api_conf('APP_BYPASS_AUTH', 'config');
        if (empty($bypass_key)) {
            return false;
        }
        return (strcmp($bypass, $bypass_key) === 0) ? TRUE : FALSE;
    }

    /**
     * token 是否有效
     *
     * @param int $user_id 用户ID
     * @param string $app_name 应用名称
     * @param string $access_token 令牌
     * @return boolean -true 有效
     */
    public function is_token_valid($user_id, $app_name, $access_token, $bypass = null) {
        if (empty($user_id) || empty($app_name)) {
            $this->_errmsg = 'Illegal request!';
            return FALSE;
        }
        if ($this->is_token_bypass($bypass)) {  // 是否绕过token验证
            return true;
        }
        if (empty($access_token)) {
            $this->_errmsg = 'The token is empty!';
            return FALSE;
        }
        if (!class_exists('POCO_TDG')) {
            exit('PROTOCOL ERROR: The framework of YUEUS not introduced!');
        }
        $oauth_obj = new yue_protocol_oauth($app_name, $this->_debug);
        $token_info = $oauth_obj->get_access_info($user_id, $app_name, TRUE);   // 获取授权信息
        if (empty($token_info)) {
            $msg = $oauth_obj->get_errmsg();
            $this->_errmsg = empty($msg) ? 'Non-existent Token' : $msg;
            return FALSE;
        }
        // 判断token是否正确
        if (strcmp($token_info['access_token'], $access_token) != 0) {
            $this->_errmsg = 'The access token is invalid!';
            return FALSE;
        }
        // 判断是否过期
        if ($token_info['expire_time'] <= time()) {
            $this->_errmsg = 'The access token is overdue!';
            return FALSE;
        }
        return TRUE;
    }

}
