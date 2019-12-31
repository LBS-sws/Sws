<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{%$Think.lang.order_title%}</title>
    <css href="__PUBLIC__/css/bootstrap.min.css"/>
    <css href="__PUBLIC__/sws/css/order.css?{%$Think.config.DEFINE.webVersions%}"/>
    <js href="__PUBLIC__/js/jquery-3.2.1.min.js"/>
    <js href="__PUBLIC__/js/bootstrap.min.js"/>
    <js href="__PUBLIC__/js/jquery.cookie.js"/>
    <eq name="Think.LANG_SET" value="en-us">
        <style>
            @media (min-width: 1000px){
                .order-label{float: left;width: 130px;}
            }
        </style>
    </eq>
	<script>

var _hmt = _hmt || [];

(function() {

  var hm = document.createElement("script");

  hm.src = "https://hm.baidu.com/hm.js?ddf8b7b1dcceb762ab5a1ec4c9f230f0";

  var s = document.getElementsByTagName("script")[0];

  s.parentNode.insertBefore(hm, s);

})();

</script>

</head>
<body>
<assign name="DEFINE" value="$Think.config.DEFINE" />
<div class="container order-top">
    <div class="media">
        <div class="media-left media-middle">
            <img src="__PUBLIC__/sws/img/{%$Think.lang.img_1_1%}" height="150px" class="order_img_left">
        </div>
        <div class="media-body media-middle">
            <img src="__PUBLIC__/sws/img/{%$Think.lang.img_1_2%}" height="50px" class="order_img_middle">
        </div>
        <div class="media-right media-middle">
            <ul class="list-style list-inline order-language">
                <neq name="Think.LANG_SET" value="zh-tw">
                    <li><a href="{%:U('/sws/login/lag',array('lan'=>'zh-tw'))%}">繁</a></li>
                    <else/>
                    <li><a href="{%:U('/sws/login/lag',array('lan'=>'zh-tw'))%}" class="active">繁</a></li>
                </neq>
                <neq name="Think.LANG_SET" value="zh-cn">
                    <li><a href="{%:U('/sws/login/lag',array('lan'=>'zh-cn'))%}">简</a></li>
                    <else/>
                    <li><a href="{%:U('/sws/login/lag',array('lan'=>'zh-cn'))%}" class="active">简</a></li>
                </neq>
                <neq name="Think.LANG_SET" value="en-us">
                    <li><a href="{%:U('/sws/login/lag',array('lan'=>'en-us'))%}">EN</a></li>
                    <else/>
                    <li><a href="{%:U('/sws/login/lag',array('lan'=>'en-us'))%}" class="active">EN</a></li>
                </neq>
            </ul>
        </div>
    </div>
</div>
<div style="background: #e73828;height: 15px;margin-top: -62px;"></div>
<div style="background: #ec682a;height: 15px;"></div>
<div class="container">
    <div class="col-lg-12">
        <div class="row">
            <div class="order-div">
                <form class="form-horizontal orderForm" method="post" id="orderForm" action="{%:U('/sws/login/orderSave')%}">
                    <input type="hidden" name="token" value="{%$token%}">
                    <input type="hidden" name="door_type" value="InDoor">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <div>
                                    <div class="order-model-left">
                                        <label class="control-label order-label">{%$Think.lang.appellation%}：</label>
                                    </div>
                                    <div class="order-model-body">
                                        <foreach name="DEFINE['appellation_list']" item="appellation" key="app_key">
                                            <label class="radio-inline">
                                                <input type="radio" class="required" name="appellation" value="{%$app_key%}">{%$appellation|L%}
                                            </label>
                                        </foreach>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-lg-offset-2">
                            <div class="form-group">
                                <div>
                                    <div class="order-model-left">
                                        <label class="control-label order-label">{%$Think.lang.order_name%}：</label>
                                    </div>
                                    <div class="order-model-body">
                                        <input class="form-control checkChinaName required" minlength="2" maxlength="50" type="text" name="order_name">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <div>
                                    <div class="order-model-left">
                                        <label class="control-label order-label">{%$Think.lang.order_email_only%}：</label>
                                    </div>
                                    <div class="order-model-body">
                                        <input class="form-control checkEmail required" type="email" name="email">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-lg-offset-2">
                            <div class="form-group">
                                <div>
                                    <div class="order-model-left">
                                        <label class="control-label order-label">{%$Think.lang.order_phone%}：</label>
                                    </div>
                                    <div class="order-model-body">
                                        <input class="form-control checkPhone required" type="text" name="phone">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <if condition="count($regionList) eq 1">
                        <select class="form-control" style="display: none" name="region" id="region">
                            <foreach name="regionList" item="vo" key="k" >
                                <option value="{%$k%}" data-cn="{%$vo[web_prefix]%}">{%$vo[$regionName]%}</option>
                            </foreach>
                        </select>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div>
                                        <div class="order-model-left">
                                            <label class="control-label order-label">{%$Think.lang.city%}：</label>
                                        </div>
                                        <div class="order-model-body">
                                            <select class="form-control required" name="city_id" id="cityName" data-url="{%:U('/sws/login/changeCity')%}">
                                                <option value=""></option>
                                                <foreach name="cityList" item="vo" key="k" >
                                                    <option value="{%$k%}" data-currency="{%$vo.currency_type%}" data-region="{%$vo.region_id%}">{%$vo[$cityName]%}</option>
                                                </foreach>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-lg-offset-4">
                                <div class="form-group">
                                    <div>
                                        <div class="order-model-left">
                                            <label class="control-label order-label">{%$Think.lang.area%}：</label>
                                        </div>
                                        <div class="order-model-body">
                                            <select class="form-control required" name="area_id" id="area_id">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <else />
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div>
                                        <div class="order-model-left">
                                            <label class="control-label order-label">{%$Think.lang.region%}：</label>
                                        </div>
                                        <div class="order-model-body">
                                            <select class="form-control required" name="region" id="region">
                                                <option value=""></option>
                                                <foreach name="regionList" item="vo" key="k" >
                                                    <option value="{%$k%}" data-cn="{%$vo[web_prefix]%}">{%$vo[$regionName]%}</option>
                                                </foreach>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-lg-offset-1-5">
                                <div class="form-group">
                                    <div>
                                        <div class="order-model-left">
                                            <label class="control-label order-label">{%$Think.lang.city%}：</label>
                                        </div>
                                        <div class="order-model-body">
                                            <select class="form-control required" name="city_id" id="cityName" data-url="{%:U('/sws/login/changeCity')%}">
                                                <option value=""></option>
                                                <foreach name="cityList" item="vo" key="k" >
                                                    <option value="{%$k%}" data-currency="{%$vo.currency_type%}" data-region="{%$vo.region_id%}">{%$vo[$cityName]%}</option>
                                                </foreach>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-lg-offset-1-5">
                                <div class="form-group">
                                    <div>
                                        <div class="order-model-left">
                                            <label class="control-label order-label">{%$Think.lang.area%}：</label>
                                        </div>
                                        <div class="order-model-body">
                                            <select class="form-control required" name="area_id" id="area_id">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </if>
                    <div class="row show">
                        <div class="col-lg-3 col-lg-3-5">
                            <div class="form-group">
                                <div>
                                    <div class="order-model-left">
                                        <label class="control-label order-label" id="indoor_area">{%$Think.lang.Indoor_area%}：</label>
                                    </div>
                                    <div class="order-model-body">
                                        <input type="number" class="form-control required number digits" name="door_in" min="1">
                                    </div>
                                    <div class="order-model-left">
                                        <label class="form-control-static text-break" id="door_unit">&nbsp;</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div>
                                    <div class="order-model-left">
                                        <label class="control-label order-label">{%$Think.lang.Infestation%}：</label>
                                    </div>
                                    <div class="order-model-body" id="businessDiv">
                                        <div class="form-control-static">{%$Think.lang.select_city%}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <div class="col-lg-12 text-center">
                            <button type="submit" class="btn btn-primary">{%$Think.lang.submit%}</button>
                            <button type="reset" class="btn btn-default bg-e6e6e6 ml-20">{%$Think.lang.reset%}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="row">
        </div>
    </div>
</div>
<div class="footer-bottom">
    <div class="container">
        <div class="order-bottom">
            <img src="__PUBLIC__/sws/img/{%$Think.lang.img_2%}" height="50px">
        </div>
    </div>
    <div style="background: #e73828;height: 15px;margin-top: -41px;"></div>
    <div style="background: #ec682a;height: 15px;"></div>
</div>
<script>
    cookieLanguage = '{%$Think.LANG_SET%}'.toLowerCase();
    $(function ($) {
        $("form:first").validate({
            submitHandler:function(form){
                var html ="<div style='position: fixed;top: 0px;left: 0px;width: 100%;height: 100%;background: rgba(0,0,0,.3);z-index: 999'><div class='load-order'><span class='glyphicon fa-spin glyphicon-refresh'></span>{%$Think.lang.submitTitle%}</div></div>";
                $("body").append(html);
                form.submit();
            }
        });

        $(window).resize(function(){
            var height = $(".orderForm:first").outerHeight();
            var top = $(".orderForm:first").offset().top;
            var maxHeight = $(window).height();
            height = parseInt(height,10);
            top = parseInt(top,10);
            maxHeight = parseInt(maxHeight,10);
            if((height+top)>maxHeight){
                $(".footer-bottom").css("position","static");
            }else{
                $(".footer-bottom").css("position","absolute");
            }
        }).trigger("resize");
    })
</script>
<js href="__PUBLIC__/sws/js/jquery.validate.min.js"/>
<js href="__PUBLIC__/sws/js/jquery.validate.js?{%$Think.config.DEFINE.webVersions%}"/>
<js href="__PUBLIC__/sws/js/order.js?{%$Think.config.DEFINE.webVersions%}"/>
</body>
</html>
