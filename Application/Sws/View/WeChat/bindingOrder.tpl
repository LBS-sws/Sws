<!DOCTYPE html>
{__NOLAYOUT__}
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{%$Think.lang.loginTitle%}</title>
    <css href="__PUBLIC__/css/bootstrap.min.css"/>
    <css href="__PUBLIC__/weChat/css/login.css?{%$Think.config.DEFINE.webVersions%}"/>
    <js href="__PUBLIC__/weChat/js/jquery-2.1.1.min.js"/>
    <js href="__PUBLIC__/weChat/js/weChat.min.js"/>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <form action="{%:U('/sws/api/saveBinding')%}" class="form-horizontal" method="post" id="firstForm">
<!--
                <div class="form-group">
                    <label class="control-label col-lg-2">{%$Think.lang.order_code%}：</label>
                    <div class="col-lg-4 form-control-static">
                        {%$orderList.s_code%}
                    </div>
                </div>
                -->
                <p>&nbsp;</p>
                <div class="form-group">
                    <div class="col-lg-12 form-control-static">
                        <p class="text-success">请您填写在本公司下单的信息，以下信息为报价单的内容，请您仔细填写。</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">{%$Think.lang.order_code%}：</label>
                    <div class="col-lg-4">
                        <input class="form-control required" type="text" name="order_code" value="{%$order.order_code|default=''%}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">{%$Think.lang.order_name%}：</label>
                    <div class="col-lg-4">
                        <input class="form-control required" type="text" name="order_name" value="{%$order.order_name|default=''%}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">{%$Think.lang.order_phone%}：</label>
                    <div class="col-lg-4">
                        <input class="form-control checkPhone required" type="text" name="order_phone" value="{%$order.order_phone|default=''%}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-12 col-lg-offset-2">
                        <button type="submit" class="btn btn-primary btn-block">{%$Think.lang.save%}</button>
                        <a href="javascript:history.back(-1);" class="btn btn-info btn-block" style="display: none" id="btn-back">返回</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    wx.config({%$signPackage%});
    wx.ready(function () {   //
        var shareDate = {
            title: '史伟莎及时报价', // 分享标题
            desc: '我们将为您提供最快、最优惠的清洁灭虫服务', // 分享描述
            link:"{%:U('/sws/api/newOrder','',false,true)%}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'http://www.lbshygiene.com.hk/instantquote/Public/images/user-icon.png', // 分享图标
            success: function () {
                // 设置成功
            },
            cancel: function () {
                // 取消分享
            }
        }
        wx.onMenuShareAppMessage(shareDate);
        wx.onMenuShareWeibo(shareDate);
/*        wx.getLocation({
            type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            success: function (res) {
                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                var speed = res.speed; // 速度，以米/每秒计
                var accuracy = res.accuracy; // 位置精度
            }
        });*/
    });

    $(function ($) {
        $("#firstForm").validate();

        //顯示返回按鈕
        showBackButton();
    });

    function showBackButton() {
        var oldUrl = document.referrer;
        if(oldUrl !== ''){
            oldUrl = oldUrl.split("/sws/api/").pop();
            oldUrl = oldUrl.split("/").shift().toLowerCase();
            oldUrl = oldUrl.split(".").shift();
            if(oldUrl == "orderlist"){
                $("#btn-back").show();
            }
            //wx.closeWindow();
        }
    }
</script>
<notempty name="hint_title">
    <script>
        $(function ($) {
            var html ="<p class='text-center'>{%$hint_title%}</p>";
            openHindWindow(html);
        });
    </script>
</notempty>
<js href="__PUBLIC__/weChat/js/jquery.validate.min.js"/>
<js href="__PUBLIC__/weChat/js/jquery.validate.js?{%$Think.config.DEFINE.webVersions%}"/>
<js href="__PUBLIC__/weChat/js/main.js?{%$Think.config.DEFINE.webVersions%}"/>
</body>
</html>
