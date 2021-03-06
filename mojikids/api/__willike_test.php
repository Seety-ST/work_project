<?php

/**
 * 接口测试 - 陈伟标
 *
 * @author willike <chenwb@yueus.com>
 * @since 2016-06-21
 */
$path = filter_input(INPUT_POST, 'path');   // 文件路径
if (empty($path)) {
    $raw_data_str = $HTTP_RAW_POST_DATA;   // 支持 raw 数据
    if (empty($raw_data_str)) {
        header(filter_input(INPUT_SERVER, 'SERVER_PROTOCOL') . ' 400 Bad Request');
        exit();
    }
    $raw_data_arr = json_decode($raw_data_str, TRUE);
    if (empty($raw_data_arr)) {
        header(filter_input(INPUT_SERVER, 'SERVER_PROTOCOL') . ' 406 Not Acceptable');
        exit();
    }
    $path = $raw_data_arr['path'];
    if (empty($path)) {
        header(filter_input(INPUT_SERVER, 'SERVER_PROTOCOL') . ' 401 unauthorized');
        exit();
    }
    $param = $raw_data_arr;
}
if (strpos($path, 'mall/') === FALSE && strpos($path, 'ssl/') === FALSE) {
    // 手机 api 接口
    $dir = str_replace('\\', '/', dirname(__FILE__));  // 当前文件夹路径
    $file = $dir . '/' . (strpos($path, '.php') ? $path : $path . '.php');
    if (!file_exists($file)) {
        header(filter_input(INPUT_SERVER, 'SERVER_PROTOCOL') . ' 404 not found');
        exit();
    }
    $base_url = 'http://www.mojikids.com/api/';
    $url = str_replace($dir, $base_url, $file);  // 拼装 url
} else {
    // 通用接口 ( 登录,注册等 )
    $path = trim(trim($path), '.');
    $url = 'http://www.mojikids.com/api/' . (strpos($path, '.php') ? $path : $path . '.php');
//    $url = 'https://ypays.yueus.com' . '/' . (strpos($path, '.php') ? $path : $path . '.php');
}
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    header(filter_input(INPUT_SERVER, 'SERVER_PROTOCOL') . ' 410 gone');
    exit();
}
foreach ($_POST as $key => $value) {
    if ($key == 'path') {
        continue;
    }
    // 处理内容中的json
    if (strpos($value, '{"') === 0) {
        $slen = strlen($value);
        $kpos = strpos($value, '}');
        if ($kpos == $slen || $kpos == ($slen - 1)) {
            $value = json_decode($value, true);
        }
    }
    $param[$key] = $value;   // 组装参数
}
isset($param['user_id']) || $param['user_id'] = 117452;  // 添加用户ID

isset($param['request_platform']) || $param['request_platform'] = 'api';

$os = filter_input(INPUT_GET, 'os');
if ($os == 'android') {
    $app_name = 'poco_yuepai_android';
} elseif ($os == 'ios') {
    $app_name = 'poco_yuepai_iphone';
} elseif ($os == 'weixin') {
    $app_name = 'poco_yuepai_web';
} else {
    $app_name = 'poco_yuepai_api';
}
// 传递参数
$post_data = array(
    'version' => '4.6.0',
    'os_type' => 'api',
    'ctime' => time(),
    'app_name' => $app_name, // 'poco_yuepai_api',
    'sign_code' => substr(md5('poco_' . json_encode($param) . '_app'), 5, -8),
    'is_enc' => 0,
    'param' => $param,
);
//var_dump($url, $post_data);exit;
$cov_data = iconv('GBK', 'UTF-8', json_encode($post_data));  // 数据转编码
//var_dump($cov_data);exit;
header('Content-type: application/json; charset=utf-8');   // 定义文件格式
echo mobile_app_curl($url, $cov_data);  // 直接输出结果

/**
 * cURL 获取数据
 *
 * @param string $url 链接
 * @param array $post_data POST数据
 * @return string
 */
function mobile_app_curl($url, $post_data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);
//    curl_setopt($ch, CURLOPT_COOKIE, "visitor_flag=1386571300; visitor_r=; cmt_hash=2746320925; _topbar_introduction=1; lastphoto_show_mode=list; session_id=67cd1e92439b03d60254f6afd6ada9c7; session_ip=112.94.240.51; session_ip_location=101029001; session_auth_hash=05d30ac6bf7bb8d1902df17a936ce6a4; g_session_id=3808f8022c9c8c16b8f5b6b7ddeb57c7; member_id=65849144; fav_userid=65849144; remember_userid=65849144; nickname=Mr.Ceclian; fav_username=Mr.Ceclian; activity_level=fans; pass_hash=f5544bdf101337398cbb8b07a3b05fe6");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('req' => $post_data));
    curl_setopt($ch, CURLOPT_USERAGENT, 'YUEUS(api 1.0 beta)');  // 在HTTP请求中包含一个"User-Agent: "头的字符串
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

exit;
