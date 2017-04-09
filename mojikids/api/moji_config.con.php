<?php

/**
 * MOJI 配置
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-04
 */
return array(
    'homepage_banner' => 1997,   // 首页 banner 榜单ID   1997
    // 0待支付，1待确认，2待签到，7已关闭，8已完成
    'order_status' => array(
        0 => '待支付',
        1 => '待到店',
        2 => '待完成',
        7 => '已关闭',
        8 => '已完成'
    ),
    'order_info_icon' => array(
        0 => 'http://image19-d.yueus.com/yueyue/20170121/20170121121818_558580_10002_11194.png?168x168_130', // 待支付
        1 => 'http://image19-d.yueus.com/yueyue/20170121/20170121121742_626771_10002_11192.png?168x168_130', // 待到店
        2 => 'http://image19-d.yueus.com/yueyue/20170121/20170121121802_569511_10002_11193.png?168x168_130', // 待完成
        7 => 'http://image19-d.yueus.com/yueyue/20170121/20170121121828_793007_10002_11195.png?168x168_130', // 已关闭
        8 => 'http://image19-d.yueus.com/yueyue/20170121/20170121121838_854604_10002_11196.png?168x168_130', // 已完成
    ),
);

