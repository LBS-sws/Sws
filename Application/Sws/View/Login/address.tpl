<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{%$Think.lang.loginTitle%}</title>
    <css href="__PUBLIC__/css/bootstrap.min.css"/>
    <css href="__PUBLIC__/sws/css/order.css?{%$Think.config.DEFINE.webVersions%}"/>
    <js href="__PUBLIC__/js/jquery-3.2.1.min.js"/>
    <js href="__PUBLIC__/js/bootstrap.min.js?{%$Think.config.DEFINE.webVersions%}"/>
    <js href="__PUBLIC__/js/jquery.cookie.js"/>
    <style>
        p{font-size: 17px;}
        .image_top{padding: 35px 0px;}
        .storey_div{margin-bottom: 10px;}
        .storey_div>*{display: table-cell;}
        .storey_div>label{padding: 0px 10px;white-space: nowrap;text-align: center}
        .storey_div>div{padding-right:10px;width: 30%}
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <p class="text-center image_top"><img src="__PUBLIC__/sws/img/{%$Think.lang.img_1_1%}" width="150px"/></p>
            <switch name="type">
                <case value="a">
                    <!--
                    <p class="text-center">{%$Think.lang.address_01%}</p>
                    <p class="text-center">{%$Think.lang.address_01_02%}</p>
                    <p class="text-center">{%$Think.lang.address_02%}</p>
                    <p class="text-center">
                        <span>{%$Think.lang.address_03%}: (852) 2302 0991&nbsp;&nbsp;&nbsp;&nbsp;{%$Think.lang.address_04%}: 400 864 9998</span><br/>
                        <span>{%$Think.lang.address_05%}: (853) 2871 9588&nbsp;&nbsp;&nbsp;&nbsp;{%$Think.lang.address_06%}: 0800 002 678</span>
                    </p>
                    -->
                    <include file="Public:kehu" />
                    <js href="__PUBLIC__/sws/js/kehu.js?{%$Think.config.DEFINE.webVersions%}"/>
                </case>
                <case value="b">
                    <p class="text-center">{%$Think.lang.address_07%}</p>
                    <p class="text-center">{%$Think.lang.address_02%}</p>
                    <p class="text-center">
                        <span>{%$Think.lang.address_03%}: (852) 2302 0991&nbsp;&nbsp;&nbsp;&nbsp;{%$Think.lang.address_04%}: 400 864 9998</span><br/>
                        <span>{%$Think.lang.address_05%}: (853) 2871 9588&nbsp;&nbsp;&nbsp;&nbsp;{%$Think.lang.address_06%}: 0800 002 678</span>
                    </p>
                </case>
                <case value="c">
                    <p class="text-center">{%$Think.lang.address_08%}</p>
                </case>
                <default />
                <p class="text-center">{%$type%}</p>
                <p class="text-center">{%$Think.lang.address_10%}: <a href="{%:U('/sws/login/order')%}">{%$service%}</a></p>
            </switch>
        </div>
    </div>
</div>
</body>
</html>
