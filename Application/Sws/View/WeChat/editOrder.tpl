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
    <dl class="dl-horizontal order-view-look">
        <dt>{%$Think.lang.order_code%}：</dt>
        <dd>{%$order.s_code|default=' '%}</dd>
        <dt>{%$Think.lang.order_name%}：</dt>
        <dd>{%$order.order_name|default=' '%}</dd>
        <dt>{%$Think.lang.email%}：</dt>
        <dd>{%$order.email|default=' '%}</dd>
        <dt>{%$Think.lang.phone%}：</dt>
        <dd>{%$order.phone|default=' '%}</dd>
        <dt>{%$Think.lang.city_name%}：</dt>
        <dd>{%$order.city_name|default=' '%}&nbsp;&nbsp;{%$order.area_name|default=' '%}</dd>

        <notempty  name="order.address">
            <dt>{%$Think.lang.address%}：</dt>
            <dd>{%$order.address|default=' '%}</dd>
        </notempty >
        <notempty  name="order.service_time">
            <dt>{%$Think.lang.service_time%}：</dt>
            <dd>{%$order.service_time|default=' '%}</dd>
        </notempty >

        <dt>{%$Think.lang.Indoor_area%}：</dt>
        <dd>{%$order.door_in|default=' '%}</dd>

        <dt>{%$Think.lang.Infestation%}：</dt>
        <dd>{%$order.businessList_str|default=' '%}</dd>

        <dt>{%$Think.lang.order_time%}：</dt>
        <dd>{%$order.lcd|default=' '%}</dd>

        <dt>{%$Think.lang.order_status%}：</dt>
        <dd>{%$order.status|L%}</dd>
    </dl>
    <div class="col-lg-12 text-right" style="margin-bottom: 10px;">
        <label>{%$Think.lang.total_price%}：</label>
        {%$order.total_price|default=' '%}
    </div>
    <div class="col-lg-12 text-right">
        <eq name="order.payOrder" value="open">
            <a href="{%:U('/sws/api/payOrder',array('id'=>$order['order_sta_id']))%}" class="btn btn-primary">微信支付</a>
        </eq>
        <!--
        <a href="{%:U('/sws/api/orderList')%}" class="btn btn-default">返回列表</a>
        -->
        <button type="button" class="btn btn-default " id="btn-back">返回</button>
    </div>
</div>
<script>
    wx.config({%$signPackage%});
    wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
        var shareDate = {
            title: '史伟莎及时报价', // 分享标题
            desc: '我们将为您提供最快、最优惠的清洁灭虫服务', // 分享描述
            link: "{%:U('/sws/api/newOrder','',false,true)%}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
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
    });
    $("#btn-back").on("click",function () {
        var oldUrl = document.referrer;
        if(oldUrl === ''){
            wx.closeWindow();
        }else{
            oldUrl = oldUrl.split("/sws/api/").pop();
            oldUrl = oldUrl.split("/").shift().toLowerCase();
            oldUrl = oldUrl.split(".").shift();
            if(oldUrl == "payorder"){
                window.location.href = "{%:U('/sws/api/orderList')%}";
            }else if(oldUrl == "neworder"){
                wx.closeWindow();
            }else {
                history.back(-1);
            }
        }
    })
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
