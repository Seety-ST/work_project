<?php

defined('YUE_PROTOCOL_ROOT') || die('INC file not loaded!');

/**
 * ͨ��Э��
 *         ( ˵��: �������� __debug = true ���ʾ����״̬,��Э���п��� �� $this->_debug ���� )
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
     * @var boolean �Ƿ����
     */
    private $_debug = FALSE;

    /**
     * @var boolean  �Ƿ����
     */
    private $_encrypt = FALSE;

    /**
     * @var string ��������
     */
    private $_uri = null;

    /**
     * @var mixed �û�����
     */
    private $_input = array();

    /**
     * @var object ����ַ�����
     */
    private $request_obj = null;

    /**
     * @var object �׳��������ݶ���
     */
    private $response_obj = null;

    /**
     * ��ʼ��
     */
    public function __construct() {
        // Э������ַ�
        $request_obj = new yue_protocol_request();
        $this->request_obj = $request_obj;
        $this->_app_name = $request_obj->get_input_item('app_name');  // ��ȡ app name
        $this->_debug = $request_obj->is_debug();  // �Ƿ��ڵ���
        // Э�鷵���׳�
        $this->response_obj = new yue_protocol_response($this->_debug);
        // �����쳣������
        set_exception_handler(array($this, 'protocol_exception_handler'));
    }

    /**
     * ��ȡ app name
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
     * ��ȡ�ͻ��˵�����
     *
     * @access public
     * @param  array $options ��������
     *   array(
     *         'be_check_token' => true,     // �Ƿ���Token , Ĭ��true (���)
     *         'b_response' => true,   // �Ƿ��׳����� , Ĭ��true (��)
     *    );
     * @return array
     */
    public function get_input(array $options) {
        // ��ʽ������
        $be_check_token = isset($options['be_check_token']) ? (bool) $options['be_check_token'] : TRUE;  // �Ƿ���֤TOKEN
        $b_response = isset($options['b_response']) ? (bool) $options['b_response'] : TRUE;  // �Ƿ��׳�����
        $input = $this->request_obj->get_input(FALSE);  // ��������
        return $this->get_input_process($input, $be_check_token, $b_response);
    }

    /**
     * ���� ����
     *
     * @param array $input
     * @param boolean $token_check �Ƿ����token��֤
     * @param boolean $b_response �Ƿ��׳����� ( ���Խ����Ч )
     * @return array
     */
    public function get_input_process($input, $token_check = TRUE, $b_response = TRUE) {
        $this->response_obj->set_json_ejection($b_response);  // �����Ƿ�json�׳�
        if (empty($input) || !is_array($input)) {
            return $this->response_obj->response(201);
        }
        // ��װ ��������
        $uri = $this->request_obj->get_request_uri();
        $input['uri'] = $uri;
        $unique_sign = md5($input['sign_code'] . microtime(TRUE) . mt_rand(0, 9999));  // Ψһ��ʶ
        $input['unique_sign'] = substr($unique_sign, 8, 8);  // Ψһ��ʶ( �������ݶ�λ )
        // �� response��ֵ
        $this->response_obj->set_response_param($input, $this->_debug);
        $this->_input = $input;
        // ��֤�����Ƿ���ȷ
        if (!$this->request_obj->is_input_valid($input)) {
            $errmsg = $this->request_obj->get_errmsg();
            return $this->response_obj->response(202, array(), $errmsg);   // �׻ش���
        }
        // ��Ч���� д(����)��־
        yue_protocol_log::yue_log($input, yue_protocol_log::VISIT_LOG);
        // ��֤У�����Ƿ���ȷ
        $input_raw = $this->request_obj->get_input(TRUE);  // ԭʼ����
        if (!$this->request_obj->is_sign_valid($input['param'], $input['sign_code'], $input_raw)) {
            $errmsg = $this->request_obj->get_errmsg();
            return $this->response_obj->response(202, array(), $errmsg);   // �׻ش���
        }
        // �Ƿ� token ��Ȩ��֤
        $app_name = $this->get_app_name();  // ������Դ
        $detect = $this->request_obj->is_token_detect($app_name);  // �Ƿ���token ( -false ����� )
        if ($token_check !== FALSE) {
            if ($detect !== FALSE) {
                // ��Ҫ��֤ access_token ��Ȩ
                $check_res = $this->request_obj->is_token_valid($input['param']['user_id'], $app_name, $input['param']['access_token'], $input['bypass']);
                if (empty($check_res)) {
                    $errmsg = $this->request_obj->get_errmsg();
                    return $this->response_obj->response(205, array(), $errmsg);   // �׻ش���
                }
            }
        }
        // �ж��Ƿ����
        if ($input['is_enc'] == 1) {
            $encrypt_key = protocol_api_conf('ENCRYPT_KEY', 'config');  // ��Կ
            $input['param'] = json_decode(protocol_api_decrypt(base64_decode($input['param']), $encrypt_key), true);
        }
        // ����html��ǩ ( UTF8 )
        $param = protocol_api_input_html_filter($input['param'], 'quotes', FALSE);
        // �ж������Ƿ���ת����
        $client_charset = protocol_api_conf('CLIENT_DATA_CHARSET', 'config');  // �ͻ��˱���
        $service_charset = protocol_api_conf('SERVICE_DATA_CHARSET', 'config');  // ����˱���
        if (protocol_api_charset_compare($client_charset, $service_charset) !== 0) {
            // ���ݱ���ת��
            $param = protocol_api_charset_conv($param, $service_charset, $client_charset);
        }
        // ���
        $data = array(
            'app_name' => $input['app_name'],
            'param' => $param,
            'version' => $input['version'], // �ͻ��˰汾
            'os_type' => $input['os_type'],
            'ctime' => $input['ctime'],
            'uri' => $uri, // ��������
            'method' => $this->request_obj->get_method(), // ����ʽ
            'ip_address' => $this->request_obj->get_request_ip(),
        );
        // �������ݸ� ��������
        return array('code' => 100, 'data' => $data, 'msg' => 'Legal request!');
    }

    /**
     * ���ݷ��ظ��ͻ���
     *
     * @access public
     * @param array $options ��������
     *      array( 'code' => 200, 'data' => array(), 'message' => 'xxxx' );
     * @param boolean $is_conv �Ƿ�ת���� ( Ĭ��-true, ת���� )
     * @return void
     */
    public function output($options, $is_conv = TRUE) {
        $code = empty($options['code']) ? 200 : $options['code'];  // ��Ӧ��
        $message = empty($options['message']) ? protocol_api_conf($code, 'code') : $options['message'];  // ������Ϣ
        $data = empty($options['data']) ? array() : protocol_api_handle_image_cdn($options['data']);  // ����ͼƬ��CDN����
        $input = $this->_input;
        // int�� ת �ַ��� 2015-12-23
        $version = $input['version'];
        if (protocol_api_conf('OUTPUT_INT_TO_STRING', 'config') === true) {
            $data = protocol_api_output_process($data, $version);
        }
        return $this->response_obj->response($code, $data, $message, $this->_encrypt, $is_conv);
    }

    /**
     * oauth ����
     * 
     * @param string $app_name �ͻ�������
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
     * ����һ�� ��Ȩ ( д������ )
     *
     * @access public
     * @param int $user_id �û�ID
     * @param string $app_name ��Ȩ��Դ����
     * @param boolean $auto_create �Ƿ��Զ�����
     * @param boolean $b_use_cache �Ƿ��ȡ��������
     * @param boolean $not_response ���Զ��׳�
     * @return array
     */
    public function get_access_info($user_id, $app_name, $auto_create = false, $b_use_cache = TRUE, $not_response = true) {
        $app_name = empty($app_name) ? $this->get_app_name() : $app_name;
        $oauth_obj = $this->yue_protocol_oauth_obj($app_name);
        $oauth_info = $oauth_obj->get_access_info($user_id, $app_name, $b_use_cache);
        if (empty($oauth_info)) {  // û�� token
            if ($not_response === false) {
                $errmsg = $oauth_obj->get_errmsg();
                return $this->response_obj->response(217, array(), $errmsg);   // �׻ش���
            }
            if ($auto_create !== TRUE) {
                return array();
            }
            // �Զ�����
            $oauth_info = $oauth_obj->create_access_info($user_id, $app_name);
        }
        if ($oauth_info['expire_time'] - time() < 3600) {
            // token �����, ���Զ�����
            $refresh_token = $oauth_info['refresh_token'];
            $oauth_info = $oauth_obj->update_by_refresh_token($user_id, $refresh_token, $app_name);
        }
        return $oauth_info;
    }

    /**
     * ˢ�� TOKEN
     *
     * @param int $user_id
     * @param string $app_name
     * @param string $refresh_token TOKEN
     * @param array $access_info ��Ȩ��Ϣ
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
     * ���� TOKEN
     *
     * @param int $user_id
     * @param string $app_name
     * @param string $refresh_token TOKEN
     * @param array $access_info ��Ȩ��Ϣ
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
     * TOKEN �Ƿ����
     *
     * @param int $user_id
     * @param string $app_name
     * @param string $access_token TOKEN
     * @param array $access_info ��Ȩ��Ϣ
     * @return boolean - true �ѹ���
     */
    public function is_access_expire($user_id, $app_name, $access_token, $access_info = array()) {
        $user_id = empty($user_id) ? $access_info['user_id'] : $user_id;
        if (empty($app_name)) {
            $app_id = $access_info['app_id'];  // ͨ�� app_id ��ȡ app name
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
     * �쳣����
     *
     * @param Exception|object $ex �������
     * @return bool|void
     */
    public function protocol_exception_handler(Exception $ex) {
        // д������־
        $path = YUE_PROTOCOL_ROOT . 'log/PROTOCOL_ERROR_HANDLER/';
        if (!is_dir($path)) {
            mkdir($path, 0777, TRUE);
        }
        $file = $path . '/err-' . date('Y-m') . '.log';
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');  // uri
        $msg = $ex->getMessage(); // ������Ϣ
        $msg = mb_convert_encoding($msg, protocol_api_conf('CLIENT_DATA_CHARSET'), protocol_api_conf('SERVICE_DATA_CHARSET'));  // ת����
        if (empty($_REQUEST['req'])) {
            $param = array(
                'cookie' => $_COOKIE,
                'request' => $_REQUEST,
            );
            $input = json_encode($param);
        } else {
            $input = $_REQUEST['req'];  // �û�����
        }
        $agent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');  // ��������Ϣ
        $md5 = substr(md5(time() . $uri . protocol_api_get_rand_string(8)), 8, 16); // ���ٶ�λ
        $data = date('Y-m-d H:i:s') . '^$^' . $md5 . '^$^' . $uri . '^$^' . $msg . '^$^' . $input . '^$^' . $agent . PHP_EOL; // ��־����
        file_put_contents($file, $data, LOCK_EX | FILE_APPEND);  // ��ռ��
        exit('SYSTEM ERROR(' . $md5 . '):  An system error occured, pls call yueus administrator!');
    }

}
