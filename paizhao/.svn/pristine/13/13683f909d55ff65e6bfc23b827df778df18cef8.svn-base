<?php
// 此功能是sign_by 签到人close_by 关闭人accept_by 接受人四个角色：sys系统，buyer买家，seller商家，admin管理员（手工）
function mall_bill_add_zh_role($role)
{
    switch ( $role )
    {
        case  "sys" :
            return $role.'系统';
            break;
        case  "buyer" :
            return $role.'买家';
            break;
        case  "seller" :
            return $role.'商家';
            break;
        case  "admin" :
            return $role.'管理员';
            break;
    }
}

function yue_iconv($source_lang, $target_lang, $source_string = '')
{
    static $chs = NULL;

    /* 如果字符串为空或者字符串不需要转换，直接返回 */
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
            $type_name = '化妆服务';
            break;
        case 5:
            $type_name = '约培训';
            break;
        case 12:
            $type_name = '场地租赁';
            break;
        case 31:
            $type_name = '模特服务';
            break;
        case 40:
            $type_name = '约摄影师';
            break;
        case 41:
            $type_name = '约美食';
            break;
        case 43:
            $type_name = '约有趣';
            break;
        case 42:
            $type_name = '约活动';
            break;
        case 20:
            $type_name = '面付';
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
            $str = '待支付';
            break;
        case 1:
            $str = '待确认';
            break;
        case 2:
            $str = '待签到';
            break;
        case 7:
            $str = '已关闭';
            break;
        case 8:
            $str = '已完成';
            break;
        default:
            $str = '';
    }
    return $str;
}