<style>
    .ptDiv,.tsDiv{border:1px solid #ddd;padding:10px;}
    .ptDiv{margin-bottom: 10px;}
    .tsDiv>.checkbox-inline:first-of-type{margin-left: 10px;}
    .ptDiv>.checkbox-inline:first-of-type{margin-left: 10px;}
    .ptDiv>.business-type,.tsDiv>.business-type{margin-bottom: 0px;}
</style>
<div class="container-sc">
    <ol class="breadcrumb">
        <li><a href="{%:U('/sws/index/index')%}">{%$Think.lang.home%}</a></li>
        <li><a href="{%:U('/sws/order/index')%}">{%$Think.lang.order_manage%}</a></li>
        <li><a href="{%:U('/sws/order/detail',array('index'=>$orderList['id']))%}">{%$Think.lang.detail%}</a></li>
        <li class="active">{%$Think.lang.update%}</li>
    </ol>
    <h2>{%$Think.lang.order_form%}</h2>
    <assign name="DEFINE" value="$Think.config.DEFINE" />
    <div class="col-xs-12">
        <form action="{%:U('/sws/order/save')%}" class="form-horizontal" method="post" id="firstForm">
            <input name="id" type="hidden" value="{%$orderList.id|default=''%}">

            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.order_code%}：</label>
                <div class="col-lg-4 form-control-static">
                    {%$orderList.s_code%}
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.appellation%}：</label>
                <div class="col-lg-4">
                    <foreach name="DEFINE['appellation_list']" item="appellation" key="app_key">
                        <label class="radio-inline">
                            <eq name="app_key" value="$orderList.appellation">
                                <input type="radio" class="required" name="appellation" checked value="{%$app_key%}">{%$appellation|L%}
                                <else/>
                                <input type="radio" class="required" name="appellation" value="{%$app_key%}">{%$appellation|L%}
                            </eq>
                        </label>
                    </foreach>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.order_name%}：</label>
                <div class="col-lg-4">
                    <input class="form-control checkChinaName required" type="text" name="order_name" value="{%$orderList.order_name%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.order_email%}：</label>
                <div class="col-lg-4">
                    <input class="form-control checkEmail required" type="email" name="email" value="{%$orderList.email%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.order_phone%}：</label>
                <div class="col-lg-4">
                    <input class="form-control checkPhone required" type="text" name="phone" value="{%$orderList.phone%}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.service_time%}：</label>
                <div class="col-lg-2">
                    <div class="input-group">
                        <div class="input-group-addon"><span class="fa fa-calendar"></span></div>
                        <input type="text" class="form-control" id="start_date" name="service_time" placeholder="{%$Think.lang.start_date%}" value="{%$orderList.service_time%}">
                    </div>
                </div>
                <label class="control-label pull-left  hidden-xs">-</label>
                <div class="col-lg-2">
                    <div class="input-group">
                        <div class="input-group-addon"><span class="fa fa-calendar"></span></div>
                        <input type="text" class="form-control" id="end_date" name="service_time_end" placeholder="{%$Think.lang.end_date%}" value="{%$orderList.service_time_end%}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.region_name%}：</label>
                <div class="col-lg-4">
                    <select class="form-control required" name="region" id="region">
                        <option value=""></option>
                        <foreach name="regionList" item="vo" key="k" >
                            <eq name="k" value="$orderList.region_id">
                                <option value="{%$k%}" data-cn="{%$vo[web_prefix]%}" selected>{%$vo["region_name$prefix"]%}</option>
                                <else/>
                                <option value="{%$k%}" data-cn="{%$vo[web_prefix]%}" >{%$vo["region_name$prefix"]%}</option>
                            </eq>
                        </foreach>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.city_name%}：</label>
                <div class="col-lg-4">
                    <select class="form-control required" name="city_id" id="cityName" data-url="{%:U('/sws/login/changeCity')%}">
                        <option value=""></option>
                        <foreach name="cityList" item="vo" key="k" >
                            <eq name="k" value="$orderList.city_id">
                                <option value="{%$k%}" data-currency="{%$vo.currency_type%}" data-region="{%$vo.region_id%}" selected>{%$vo["city_name$prefix"]%}</option>
                                <else/>
                                <option value="{%$k%}" data-currency="{%$vo.currency_type%}" data-region="{%$vo.region_id%}">{%$vo["city_name$prefix"]%}</option>
                            </eq>
                        </foreach>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.area_name%}：</label>
                <div class="col-lg-4">
                    <select class="form-control required" name="area_id">
                        <option value=""></option>
                        <option value="{%$orderList.area_id%}" selected></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.address%}：</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control" name="address" value="{%$orderList.address|htmlspecialchars_decode%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2" id="indoor_area">
                    <eq name="orderList.web_prefix|strtolower" value="cn">
                        {%$Think.lang.Indoor_area%}：
                        <else/>
                        {%$Think.lang.Indoor_area_a%}：
                    </eq>
                </label>
                <div class="col-lg-2">
                    <input type="number" class="form-control required number digits" name="door_in" min="1" value="{%$orderList.door_in%}">
                </div>
                <div class="col-lg-2" style="padding: 0px;">
                    <label class="form-control-static" id="door_unit"></label>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.Infestation%}：</label>
                <div class="col-lg-6">
                    <div class="col-lg-12" id="businessDiv" data="{%$orderList.business_id%}" data-type="{%$orderList.s_type%}">
                        <div class="form-control-static">{%$Think.lang.select_city%}</div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.total_price%}：</label>
                <div class="col-lg-4 form-control-static">
                    <input type="number" min="0" class="number form-control required" name="total_price" value="{%$total_price%}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-8 col-lg-offset-2">
                    <button type="submit" class="btn btn-primary">{%$Think.lang.save%}</button>
                    <a class="btn btn-default"  href="{%:U('/sws/order/detail',array('index'=>$orderList['id']))%}">{%$Think.lang.btn_back%}</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    cookieLanguage = '{%$Think.LANG_SET%}'.toLowerCase();
    $(function ($) {
        $('#start_date').datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            language:'{%$lang%}',
            autoclose:true,
            todayBtn:true
        }).on('changeDate',function(ev){
            var starttime=$("#start_date").val();
            $("#end_date").datetimepicker('setStartDate',starttime);
        });
        $('#end_date').datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            language:'{%$lang%}',
            autoclose:true,
            todayBtn:true
        }).on('changeDate',function(ev) {
            var endtime = $("#end_date").val();
            $("#start_date").datetimepicker('setEndDate', endtime);
        });
        $("#firstForm").validate();

        $("#businessDiv").delegate(".onlyBusiness","click",function () {
            if($(this).data("type")==1){
                $.ajax({
                    type: "post",
                    url: "{%:U('/sws/order/ajaxTotalPrice')%}",
                    data: $("#firstForm").serialize(),
                    dataType: "json",
                    success: function(data){
                        if(data.status == 1){
                            $("input[name='total_price']").val(data.price);
                        }
                    }
                });
            }
        })
    })
</script>
<js href="__PUBLIC__/sws/js/jquery.validate.min.js"/>
<js href="__PUBLIC__/sws/js/jquery.validate.js?{%$Think.config.DEFINE.webVersions%}"/>
<js href="__PUBLIC__/sws/js/order.js?{%$Think.config.DEFINE.webVersions%}"/>
<js href="__PUBLIC__/js/bootstrap-datetimepicker.min.js"/>
<js href="__PUBLIC__/js/datetimepicker-lan.js?{%$Think.config.DEFINE.webVersions%}"/>