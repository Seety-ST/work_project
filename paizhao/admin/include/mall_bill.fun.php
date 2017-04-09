<?php
// �˹�����sign_by ǩ����close_by �ر���accept_by �������ĸ���ɫ��sysϵͳ��buyer��ң�seller�̼ң�admin����Ա���ֹ���
function mall_bill_add_zh_role($role)
{
    switch ( $role )
    {
        case  "sys" :
            return $role.'ϵͳ';
            break;
        case  "buyer" :
            return $role.'���';
            break;
        case  "seller" :
            return $role.'�̼�';
            break;
        case  "admin" :
            return $role.'����Ա';
            break;
    }
}

function yue_iconv($source_lang, $target_lang, $source_string = '')
{
    static $chs = NULL;

    /* ����ַ���Ϊ�ջ����ַ�������Ҫת����ֱ�ӷ��� */
    if ($source_lang == $target_lang || $source_string == '' || preg_match("/[\x80-\xFF]+/", $source_string) == 0) {
        return $source_string;
    }

    if ($chs === NULL) {
        require_once(ROOT_PATH . 'includes/cls_iconv.php');
        $chs = new Chinese(ROOT_PATH);
    }

    return $chs->Convert($source_lang, $target_lang, $source_string);
}

function yue_admin_task_get_type_name($type_id)
{
    switch($type_id)
    {
        case 3:
            $type_name = '��ױ����';
            break;
        case 5:
            $type_name = 'Լ��ѵ';
            break;
        case 12:
            $type_name = '��������';
            break;
        case 31:
            $type_name = 'ģ�ط���';
            break;
        case 40:
            $type_name = 'Լ��Ӱʦ';
            break;
        case 41:
            $type_name = 'Լ��ʳ';
            break;
        case 43:
            $type_name = 'Լ��Ȥ';
            break;
        case 42:
            $type_name = 'Լ�';
            break;
        case 20:
            $type_name = '�渶';
            break;
        default:
            $type_name = '';
    }
    return $type_name;
}

function yue_admin_task_get_status_str($status)
{
    switch($status)
    {
        case 0:
            $str = '��֧��';
            break;
        case 1:
            $str = '��ȷ��';
            break;
        case 2:
            $str = '��ǩ��';
            break;
        case 7:
            $str = '�ѹر�';
            break;
        case 8:
            $str = '�����';
            break;
        default:
            $str = '';
    }
    return $str;
}