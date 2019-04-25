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
    </div>
</div>
<div style="background: #e73828;height: 15px;margin-top: -62px;"></div>
<div style="background: #ec682a;height: 15px;"></div>
<div class="container">
    <div class="col-lg-12">
        <div class="row">
            <div class="order-div">
                <form class="form-horizontal orderForm" method="post" id="orderForm" action="">

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
                                                <eq name="app_key" value="$orderList['appellation']">
                                                    <input type="radio" class="required" name="appellation" checked disabled value="{%$app_key%}">{%$appellation|L%}
                                                    <else/>
                                                    <input type="radio" class="required" name="appellation" disabled value="{%$app_key%}">{%$appellation|L%}
                                                </eq>
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
                                        <input class="form-control checkChinaName required" disabled type="text" name="order_name" value="{%$orderList.order_name%}">
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
                                        <input class="form-control checkEmail required" disabled type="email" name="email" value="{%$orderList.email%}">
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
                                        <input class="form-control checkPhone required" disabled type="text" name="phone" value="{%$orderList.phone%}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <if condition="count($regionList) eq 1">
                        <input type="hidden"  name="region" id="region" value="{%$orderList['region_id']%}">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div>
                                        <div class="order-model-left">
                                            <label class="control-label order-label">{%$Think.lang.city%}：</label>
                                        </div>
                                        <div class="order-model-body">
                                            <select class="form-control required" name="region" disabled>
                                                <option value=""></option>
                                                <foreach name="cityList" item="vo" key="k" >
                                                    <eq name="k" value="$orderList['city_id']">
                                                        <option value="{%$k%}" selected>{%$vo%}</option>
                                                        <else/>
                                                        <option value="{%$k%}">{%$vo%}</option>
                                                    </eq>
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
                                            <select class="form-control required" name="area_id" disabled id="area_id">
                                                <option value=""></option>
                                                <foreach name="areaList" item="vo" key="k" >
                                                    <eq name="k" value="$orderList['area_id']">
                                                        <option value="{%$k%}" selected>{%$vo%}</option>
                                                        <else/>
                                                        <option value="{%$k%}">{%$vo%}</option>
                                                    </eq>
                                                </foreach>
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
                                            <select class="form-control required" name="region" disabled id="region">
                                                <option value=""></option>
                                                <foreach name="regionList" item="vo" key="k" >
                                                    <eq name="k" value="$orderList['region_id']">
                                                        <option value="{%$k%}" selected>{%$vo%}</option>
                                                        <else/>
                                                        <option value="{%$k%}" >{%$vo%}</option>
                                                    </eq>
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
                                            <select class="form-control required" name="region" disabled>
                                                <option value=""></option>
                                                <foreach name="cityList" item="vo" key="k" >
                                                    <eq name="k" value="$orderList['city_id']">
                                                        <option value="{%$k%}" selected>{%$vo%}</option>
                                                        <else/>
                                                        <option value="{%$k%}">{%$vo%}</option>
                                                    </eq>
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
                                            <select class="form-control required" name="area_id" disabled id="area_id">
                                                <option value=""></option>
                                                <foreach name="areaList" item="vo" key="k" >
                                                    <eq name="k" value="$orderList['area_id']">
                                                        <option value="{%$k%}" selected>{%$vo%}</option>
                                                        <else/>
                                                        <option value="{%$k%}">{%$vo%}</option>
                                                    </eq>
                                                </foreach>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </if>
                    <div class="row">
                        <div class="col-lg-3 col-lg-3-5">
                            <div class="form-group">
                                <div>
                                    <div class="order-model-left">
                                        <label class="control-label order-label">
                                            <eq name="orderList.web_prefix|strtolower" value="cn">
                                                {%$Think.lang.Indoor_area%}：
                                                <else/>
                                                {%$Think.lang.Indoor_area_a%}：
                                            </eq>
                                        </label>
                                    </div>
                                    <div class="order-model-body">
                                        <input type="number" class="form-control required number digits" disabled name="door_in" min="1" value="{%$orderList.door_in%}">
                                    </div>
                                    <div class="order-model-left">
                                        <label class="form-control-static text-break" id="door_unit">{%$orderList.b_unit|L%}</label>
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
                                        <foreach name="businessList" item="vo" key="k" >
                                            <label class="checkbox-inline">
                                                <if condition="in_array($k,$orderList['business_id'])">
                                                    <input type="checkbox" class="onlyBusiness required" disabled checked name="business_id[]" value="{%$k%}">{%$vo%}
                                                    <else />
                                                    <input type="checkbox" class="onlyBusiness required" disabled name="business_id[]" value="{%$k%}">{%$vo%}
                                                </if>
                                            </label>
                                        </foreach>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <div class="col-lg-10 text-left col-lg-offset-1 form-control-static font-bold-16 order_detail_bottom">
                            <switch name="orderList.order_type">
                                <case value="1">
                                    {%$Think.lang.order_now_1_1_1%}
                                    <a class="email_import" target="_blank" href="{%$email_import|default='#'%}">{%$Think.lang.order_now_1_1_2%}</a>
                                    {%$Think.lang.order_now_1_1_3%}
                                    <span class="">{%$Think.lang.order_now_1_2%}</span>{%$Think.lang.thank_you%}
                                </case>
                                <case value="2">
                                    {%$Think.lang.order_now_1_1_1%}
                                    <a class="email_import" target="_blank" href="{%$email_import|default='#'%}">{%$Think.lang.order_now_1_1_2%}</a>
                                    {%$Think.lang.order_now_1_1_3%}
                                    <br>
                                    <eq name="orderList.region_name" value="中国">
                                        <span class="">{%$Think.lang.email_hybrid_1%}</span>
                                        <else/>
                                        <span class="">{%$Think.lang.email_hybrid_2%}</span>
                                    </eq>
                                    {%$Think.lang.thank_you%}
                                </case>
                                <default />
                                <eq name="orderList.region_name" value="中国">
                                    <span class="">{%$Think.lang.email_hybrid_1%}</span>
                                    <else/>
                                    <span class="">{%$Think.lang.email_hybrid_2%}</span>
                                </eq>
                                {%$Think.lang.thank_you%}
                            </switch>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top: 20px;">
                        <div class="col-lg-12 text-center">
                            <a class="btn btn-primary plr-30" href="{%$closeUrl%}">{%$Think.lang.close%}</a>
                        </div>
                    </div>
                </form>
            </div>
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
    $(function ($) {
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
</body>
</html>
