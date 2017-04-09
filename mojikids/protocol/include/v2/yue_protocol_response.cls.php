<?php

defined('YUE_PROTOCOL_ROOT') || die('INC file not loaded!');

/**
 * �����׳� ( ���ؿͻ��� )
 *
 * @author willike <chenwb@yueus.com>
 * @since 2015-7-31
 * @version 1.0 Beta 2
 */
final class yue_protocol_response {

    /**
     * @var array ��������
     */
    private $_input = array();

    /**
     * @var string ��������
     */
    private $_uri = '';

    /**
     * @var string �Ƿ����
     */
    private $_debug = FALSE;

    /**
     * @var boolean JSON �׳� ( ���ؿͻ��� )
     */
    private $_json_ejection = TRUE;

    /**
     * ��ʼ��
     *
     * @param array $input �û�����
     * @param boolean $debug �Ƿ����
     */
    public function __construct($debug = FALSE) {
        $this->_debug = ($debug === TRUE) ? TRUE : FALSE;
    }

    /**
     * ���� ����
     *
     * @param array $input
     * @param boolean $debug
     * @return void
     */
    public function set_response_param($input, $debug = FALSE) {
        $this->_input = $input;
        $this->_uri = $input['uri'];
        $this->_debug = ($debug === TRUE) ? TRUE : FALSE;
    }

    /**
     * �Ƿ� json ����
     *
     * @param boolean $ejection ����
     * @return void
     */
    public function set_json_ejection($ejection = TRUE) {
        $this->_json_ejection = ($ejection === FALSE) ? FALSE : TRUE;
    }

    /**
     * ��Ӧ�ͻ���
     *
     * @access public
     * @param int $code ��������״̬��
     * @param mixed ��������
     * @param string $message ��ʾ��Ϣ
     * @param boolean $b_encrypt �����Ƿ����
     * @param boolean $b_conv �Ƿ�ת���� ( -true ת�� )
     * @return void
     */
    public function response($code, $data = array(), $message = null, $b_encrypt = FALSE, $b_conv = TRUE) {
        if (empty($message)) {  // ������ ����
            $message = protocol_api_conf($code, 'code');
            $message = (empty($message) || is_array($message)) ? 'protocol warning' : $message;
        }
        // �ж������Ƿ���ת����
        if ($b_conv !== FALSE) {
            $client_charset = protocol_api_conf('CLIENT_DATA_CHARSET', 'config');  // �ͻ��˱���
            $service_charset = protocol_api_conf('SERVICE_DATA_CHARSET', 'config');  // ����˱���
            if (protocol_api_charset_compare($client_charset, $service_charset) !== 0) {
                // ���ݱ���ת��
                $message = protocol_api_charset_conv($message, $client_charset, $service_charset);  // ��ʾ��Ϣ����ת��
                $data = protocol_api_charset_conv($data, $client_charset, $service_charset);
            }
        }
        $input = $this->_input;   // ��������
        // ��� Э�����ݴ��� ��ʱ
        $runtime = empty($GLOBALS['mojikids_protocol_start_time']) ? 0 : bcsub(microtime(TRUE), $GLOBALS['mojikids_protocol_start_time'], 4); // ��ʱ
        unset($GLOBALS['mojikids_protocol_start_time']);
        // ��װ�׳�����
        $apiver = protocol_api_conf('PROTOCOL_VERSION', 'config'); // Э��汾
        $ret_arr = array(
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'runtime' => $runtime, // ��ʱ
            'base' => trim($input['unique_sign']), // Ψһ��ʶ
            'appver' => trim($input['version']), // app �汾
            'apiver' => $apiver, // Э��汾
        );
        if ($code != 200) {  // ��Ȩδͨ��
            yue_protocol_log::yue_log($input, yue_protocol_log::ACCESS_ERROR_LOG, null, $ret_arr);
        }
        // �Ƿ�������
        if ($this->_debug) {
            unset($input['param']['__debug']);  // ȥ������
            $ret_arr['_input'] = $input;  // �û�����
        }
        // תjson, ���滻JSON�еĻ��лس���Ϊ'\n'
        $ret_json = str_replace(array("\r\n", "\n\r"), '\n', json_encode($ret_arr));
        // �Ƿ����
        if ($b_encrypt === TRUE) {
            $encrypt_key = protocol_api_conf('ENCRYPT_KEY', 'config');  // ��Կ
            $ret_json = protocol_api_encrypt($ret_json, $encrypt_key);  // ����
        }
        // д(��ʱ)��־ 2015-9-2
        if ($runtime > 2) {
            // ����2��,д����ѯ��־
            $input['runtime'] = $runtime; // ��ʱ
            $input['apiver'] = $apiver;
            yue_protocol_log::yue_log($input, yue_protocol_log::SLOWQ_LOG);
        }
        // �Ƿ��ƹ�token��֤
        $by_pass = $input['bypass'];
        if (!empty($by_pass)) {
            $protocol_request_obj = new yue_protocol_request();
            $pass_res = $protocol_request_obj->is_token_bypass($by_pass);
            if ($pass_res === TRUE) {  // �ƹ�token��֤, д��־
                $input['runtime'] = $runtime; // ��ʱ
                $input['apiver'] = $apiver;
                yue_protocol_log::yue_log($input, yue_protocol_log::BYPASS_LOG);
            }
        }
        if ($this->_json_ejection === FALSE) {
            // ֱ�ӷ��� �����׳�
            return $ret_json;
        }
        // �׳�����
        header('Content-Type:application/json; charset=utf-8');  // ���巵�ظ�ʽ
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');  // ������
        header('Expires: 0');
        exit($ret_json);
    }

}
