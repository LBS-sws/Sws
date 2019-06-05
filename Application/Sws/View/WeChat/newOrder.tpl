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
    <form action="{%:U('/sws/api/newOrder')%}" id="firstForm" method="post" class="form-horizontal" style="padding-top: 20px;">
        <input type="hidden" name="token" value="{%$token|default=''%}">
        <input type="hidden" name="region_id" id="region" value="{%$order.region_id|default=''%}">
        <input type="hidden" name="city_id" id="city" value="{%$order.city_id|default=''%}">
        <input type="hidden" name="area_id" id="area" value="{%$order.area_id|default=''%}">
        <input type="hidden" name="address" id="address" value="{%$order.address|default=''%}">
        <input type="hidden" name="latitude" id="latitude" value="{%$order.latitude|default=''%}">
        <input type="hidden" name="longitude" id="longitude" value="{%$order.longitude|default=''%}">
        <div class="form-group">
            <label class="control-label col-lg-2">{%$Think.lang.order_name%}：</label>
            <div class="col-lg-4">
                <input class="form-control required" type="text" name="order_name" value="{%$order.order_name|default=''%}">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2">{%$Think.lang.appellation%}：</label>
            <div class="col-lg-4">
                <div class="input-group">
                <assign name="DEFINE" value="$Think.config.DEFINE" />
                <foreach name="DEFINE['appellation_list']" item="appellation" key="app_key">
                    <label class="radio-inline">
                        <eq name="app_key" value="$orderList['appellation']">
                            <input type="radio" class="required" name="appellation" checked value="{%$app_key%}">{%$appellation|L%}
                            <else/>
                            <input type="radio" class="required" name="appellation" value="{%$app_key%}">{%$appellation|L%}
                        </eq>
                    </label>
                </foreach>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2">{%$Think.lang.order_phone%}：</label>
            <div class="col-lg-4">
                <input class="form-control checkPhone required" type="text" name="phone" value="{%$order.phone|default=''%}">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2">{%$Think.lang.order_email_only%}：</label>
            <div class="col-lg-4">
                <input class="form-control checkEmail required" type="email" name="email" value="{%$order.email|default=''%}">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2">{%$Think.lang.address%}：</label>
            <div class="col-lg-4">
                <input class="form-control required" type="text" id="address_show" name="address_show" value="{%$order.address_show|default=''%}" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2">{%$Think.lang.Indoor_area%}：</label>
            <div class="col-lg-4">
                <div class="input-group">
                    <input class="form-control  required number digits" type="number" min="1" name="door_in" value="{%$order.door_in|default=''%}">
                    <span class="input-group-addon" id="door_in"></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2">{%$Think.lang.Infestation%}：</label>
            <div class="col-lg-4">
                <div class="input-group">
                <foreach name="businessList" item="vo">
                    <label class="checkbox-inline">
                        <if condition="in_array($vo,$order['business'])">
                            <input type="checkbox" class="onlyBusiness required" checked name="business[]" value="{%$vo%}">{%$vo%}
                            <else />
                            <input type="checkbox" class="onlyBusiness required" name="business[]" value="{%$vo%}">{%$vo%}
                        </if>
                    </label>
                </foreach>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary btn-block">生成订单</button>
            </div>
        </div>
    </form>
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
        $("#firstForm").validate();
        createCityChangeDiv({%$cityList%});

        var ajaxBool = true;
        $("#city_open_div .btn-marker").on("click",function () {
            if (ajaxBool){
                var html = "<div class='hind-bg show' id='ajaxBool'><div class='ajaxBool'>正在获取地址，请耐心等待</div></div>";
                $("body").append(html);
                ajaxBool = false;
                //獲取當前位置
                wx.getLocation({
                    type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
                    success: function (res) {
                        $.ajax({
                            type: "post",
                            url: "{%:U('/sws/api/ajaxLocation')%}",
                            data: {"res":res.latitude+","+res.longitude},
                            dataType: "json",
                            success: function(date){
                                if(date.status == 1){
                                    ajaxBool = true;
                                    var list = date.list;
                                    list["res"] = res;
                                    setAddressToData(list);
                                    $("#ajaxBool").remove();
                                }else {
                                    ajaxBool = false;
                                    $("#ajaxBool").remove();
                                    openHindWindow("<p class='text-center'>对不起，该地区暂不支持微信下单</p>");
                                }
                            },
                            error:function () {
                                ajaxBool = false;
                                $("#ajaxBool").remove();
                                openHindWindow("<p class='text-center'>页面异常，请刷新重试!</p>");
                            }
                        });
                    },
                    error:function () {
                        ajaxBool = true;
                        $("#ajaxBool").remove();
                        openHindWindow("<p class='text-center'>地址获取失败，请重试</p>");
                    },
                    cancel:function () {
                        ajaxBool = true;
                        $("#ajaxBool").remove();
                        openHindWindow("<p class='text-center'>用户拒绝授权获取地理位置</p>");
                    }
                });
            }
        });
    });
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
