<?php

/**
 * Э������
 *
 * @author willike <chenwb@yueus.com>
 * @since 2015-7-30
 */
return array(
    'PROTOCOL_VERSION' => 'v2', // Э��汾
    // ���ݿ�
    'SERVER_ID' => 101, // ԼԼЭ�����ݿ������
    'DB_NAME' => 'm_service_db', // Э�����ݿ�
    // ���� ��ȫ
    'ENCRYPT_KEY' => 'poco_xxtea_v1234', // ������Կ
    'DATA_LIMITED' => 0, // ������Ч��,����֤�� 0 ( ��λ: �� )
    'SIGN_CODE_LIMITED' => 0, // ǩ����Ч��, ����֤�� 0 ( ��λ: �� )
    // ����
    'TOKEN_EXPIRE' => 15, // TOKEN��Ч��, �������� 0 ( ��λ: �� )
    // ����
    'CLIENT_DATA_CHARSET' => 'UTF-8', // �ͻ���(APP)���ݱ����ʽ
    'SERVICE_DATA_CHARSET' => 'GBK', // �����(���ݿ�)���ݱ����ʽ
    'OUTPUT_INT_TO_STRING' => true, // int��ת�ַ���
    // ����
    'OPEN_DEBUG' => TRUE, // �Ƿ������� ( - true ���� ) ����,������������ʱ��
    'OPEN_VISIT_LOGGER' => TRUE, // �Ƿ���(����)��־��¼ ( -true ���� )
    'OPEN_EVENT_LOGGER' => TRUE, // �Ƿ���(�¼�)��־��¼ ( -true ���� )
    'OPEN_RUNTIME_LOGGER' => TRUE, // �Ƿ���(��ʱ)��־��¼ ( -true ���� )
    'OPEN_SLOWQUERY_LOGGER' => TRUE, // �Ƿ���(����ѯ)��־��¼ ( -true ����, ���鿪�� )
    'OPEN_TOKEN_LOGGER' => TRUE, // �Ƿ���(TOKEN)��־��¼ ( -true ����, ���鿪�� )
    'NON_TOKEN_AUTH' => 'poco_yuepai_api', // ����TOKEN��֤(֧������Ͷ��ŷָ����ַ���) ��OPEN_DEBUG ����
    'NON_TOKEN_AUTH_AGENT_MATCH' => 'YUEUS(api',  // ��TOKEN��֤,��֤AGENT ( Ϊ�ղ���֤ )
    'APP_BYPASS_AUTH' => 'this.is@willike:2APP4pass:)',  // APP �ƹ�token��֤ ( for APP ����,Ϊ������֤ )
);
