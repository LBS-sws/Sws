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
    <style>
        body{background: #ECECEC;}
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <ul class="list-unstyled order-menu">
                    <li class="<notin name='Think.get.type' value='pending,finish'>active</notin>"><a href="{%:U('/sws/api/orderList')%}">全部</a></li>
                    <li class="<eq name='Think.get.type' value='pending'>active</eq>"><a href="{%:U('/sws/api/orderList',array('type'=>'pending'))%}">待完成</a></li>
                    <li class="<eq name='Think.get.type' value='finish'>active</eq>"><a href="{%:U('/sws/api/orderList',array('type'=>'finish'))%}">已完成</a></li>
                </ul>
            </div>
        </div>
        <notin name='Think.get.type' value='pending,finish'>
        <div class="col-lg-12 col-lg-offset-2" style="padding-top: 10px;">
            <a type="button" href="{%:U('/sws/api/bindingOrder')%}" class="btn btn-primary btn-block">绑定订单</a>
        </div>
        </notin>
        <ul class="order-ul" id="order-ul">
            <volist name="orderList" id="list">
                <li>
                    <p class="text-right color-d44950 mb-0" data-id="status_str">{%$list.status_str%}</p>
                    <dl class="order-ul-dl">
                        <dt>{%$Think.lang.order_code%}：</dt>
                        <dd data-id="s_code">{%$list.s_code%}&nbsp;</dd>
                        <dt>{%$Think.lang.order_name%}：</dt>
                        <dd data-id="order_name">{%$list.order_name%}&nbsp;</dd>
                        <dt>{%$Think.lang.phone%}：</dt>
                        <dd data-id="phone">{%$list.phone%}&nbsp;</dd>
                        <dt>{%$Think.lang.order_time%}：</dt>
                        <dd data-id="lcd">{%$list.lcd%}&nbsp;</dd>
                        <dt>{%$Think.lang.Infestation%}：</dt>
                        <dd data-id="businessList_str">{%$list.businessList_str%}&nbsp;</dd>
                    </dl>
                    <div style="padding-left: 8px;">
                        <a class="pull-left media-middle" data-id="detail_href" href="{%$list.detail_href%}">查看详情>></a>
                        <p class="text-right media-middle color-d44950 mb-0" data-id="total_price">总价：{%$list.total_price%}</p>
                    </div>
                </li>
            </volist>
        </ul>
    </div>
</div>
<script>
    wx.config({%$signPackage%});
    wx.ready(function () {   //
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
/*        wx.getLocation({
            type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            success: function (res) {
                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                var speed = res.speed; // 速度，以米/每秒计
                var accuracy = res.accuracy; // 位置精度
            }
        });*/
    });
    $(function ($) {
        var ajaxBool = true;
        $("body").data("page",1);
        $(window).scroll( function() {
            var page = $("body").data("page");
            var maxHeight = $("body").height()-$(window).height();
            var thisHeight = $(document).scrollTop();
            if(thisHeight/maxHeight>0.9){
                if(ajaxBool){
                    page++;
                    $("body").data("page",page);
                    if($("#load_data").length>1){
                        $("#load_data").html("<p>正在加载数据....</p>");
                    }else{
                        $("body").append("<div class='text-center' id='load_data'><p>正在加载数据....</p></div>");
                    }
                    ajaxBool = false;
                    $.ajax({
                        type: "post",
                        url: "{%:U('/sws/api/orderList')%}",
                        data: {"type":"{%$Think.get.type%}","page":page},
                        dataType: "json",
                        success: function(date){
                            if(date.status == 1){
                                var orderList = date.orderList;
                                var num = 0;
                                var html = $("#order-ul>li:first").html();
                                for(var key in orderList){
                                    num++;
                                    var $li = $("<ul><li>"+html+"</li></ul>").contents();
                                    $li.find("*[data-id='status_str']").text(orderList[key]["status_str"]);
                                    $li.find("*[data-id='s_code']").text(orderList[key]["s_code"]);
                                    $li.find("*[data-id='order_name']").text(orderList[key]["order_name"]);
                                    $li.find("*[data-id='phone']").text(orderList[key]["phone"]);
                                    $li.find("*[data-id='lcd']").text(orderList[key]["lcd"]);
                                    $li.find("*[data-id='businessList_str']").text(orderList[key]["businessList_str"]);
                                    $li.find("*[data-id='total_price']").text(orderList[key]["total_price"]);
                                    $li.find("*[data-id='detail_href']").attr("href",orderList[key]["detail_href"]);
                                    $("#order-ul").append($li);
                                }
                                if(num < 7){
                                    $("#load_data").html("<p>全部加载完成，没有其它数据了</p>");
                                }else {
                                    ajaxBool = true;
                                    $("#load_data").remove();
                                }
                            }else {
                                page--;
                                $("body").data("page",page);
                                ajaxBool = true;
                                $("#load_data").html("<p>"+date.error+"</p>");
                            }
                        },
                        error:function () {
                            $("#load_data").html("<p>页面异常，请刷新重试!</p>");
                        }
                    });
                }
            }
        });
/*        $("body").on("vmousedown",function (e) {
            var startY = e.pageY;
            var maxHeight = $("body").height()-$(window).height();
            $("body").data("startY",startY);
            //console.log(startY);
            console.log($(document).scrollTop());
            console.log(maxHeight);
        });
        $("body").on("touchmove",function (e) {
            //console.log(333);
        });
        $("body").on("vmouseup",function (e) {
            var pageY = e.pageY;
            var startY = $("body").data("startY");
            if(startY-pageY>100){
                console.log("loading");
            }
        });*/
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
<js href="__PUBLIC__/weChat/js/main.js?{%$Think.config.DEFINE.webVersions%}"/>
</body>
</html>
