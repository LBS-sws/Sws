<?php

return array(
    "superUser"=>"Franco",//超級管理員
    "webVersions"=>"1.0.3.5",//網頁版本號
    "close_open_prefix"=>"pestcontrol",//訂單完成后關閉的後綴

    "appellation_list"=>array("Mr","Miss","Ms","Mrs"),//稱謂類型
    //"house_type"=>array("Domestic","Commerical","Others"),//住宅類型
    "house_type"=>array("Domestic"),//住宅類型
    "service_list"=>array( //治理類型
        "1"=>"OneService",
        //"2"=>"TwoService",
        //"3"=>"ShortService",
        //"5"=>"FiveService"
    ),
    //"door_type"=>array("InOutDoor","InDoor","OutDoor"), //治理範圍
    "door_type"=>array("InDoor"), //治理範圍


// 配置邮件发送服务器
    'Mail'=>array(

        'MAIL_HOST' =>'smtp.163.com',//smtp服务器的名称
        'MAIL_PORT' =>25,//smtp服务器的端口
        'MAIL_USERNAME' =>'shenchao90001@163.com',//你的邮箱名
        'MAIL_FROM' =>'shenchao90001@163.com',//发件人地址
        'MAIL_PASSWORD' =>'shenchao123',//邮箱密码
    ),
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    'MAIL_FROMNAME'=>'史偉莎|史伟莎|LBS Hygiene',//发件人姓名
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件

    //dvgbgwawuwngfhjh
);
