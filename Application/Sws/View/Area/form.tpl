<div class="container-sc">
    <ol class="breadcrumb">
        <li><a href="{%:U('/sws/index/index')%}">{%$Think.lang.home%}</a></li>
        <li><a href="{%:U('/sws/area/index')%}">{%$Think.lang.area_manage%}</a></li>
        <notempty name="areaList.id">
            <li class="active">{%$Think.lang.update%}</li>
            <else/>
            <li class="active">{%$Think.lang.add%}</li>
        </notempty>
    </ol>
    <h2>{%$Think.lang.area_form%}</h2>
    <div class="col-xs-12">
        <form action="{%:U('/sws/area/save')%}" class="form-horizontal" method="post" id="firstForm">
            <notempty name="areaList.id">
                <input name="id" type="hidden" value="{%$areaList.id|default=''%}" id="edit_id">
            </notempty>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.area_name%}{%$Think.lang.zh_cn%}</label>
                <div class="col-lg-4">
                    <input class="form-control required ajaxCheckName" data-url="{%:U('/sws/area/ajaxCheckAreaName')%}" type="text" name="area_name" value="{%$areaList.area_name|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.area_name%}{%$Think.lang.zh_tw%}</label>
                <div class="col-lg-4">
                    <input class="form-control required ajaxCheckName" data-url="{%:U('/sws/area/ajaxCheckAreaName')%}" type="text" name="area_name_tw" value="{%$areaList.area_name_tw|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.area_name%}{%$Think.lang.en_us%}</label>
                <div class="col-lg-4">
                    <input class="form-control required ajaxCheckName" data-url="{%:U('/sws/area/ajaxCheckAreaName')%}" type="text" name="area_name_us" value="{%$areaList.area_name_us|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.city_name%}</label>
                <div class="col-lg-4">
                    <select class="form-control required" name="city_id" id="city_id">
                        <foreach name="cityList" item="vo" key="k" >
                            <eq name="k" value="$areaList.city_id">
                                <option value="{%$k%}" selected data-currency="{%$vo.currency_type%}" >{%$vo[$cityName]|default='未設置'%}</option>
                            <else/>
                                <option value="{%$k%}" data-currency="{%$vo.currency_type%}" >{%$vo[$cityName]|default='未設置'%}</option>
                            </eq>
                        </foreach>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.car_fare%}<span class="currency_type"></span></label>
                <div class="col-lg-4">
                    <input class="form-control number required" type="number" name="area_price" value="{%$areaList.area_price|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.business_min_price%}<span class="currency_type"></span></label>
                <div class="col-lg-4">
                    <input class="form-control number required" type="number" name="min_price" value="{%$areaList.min_price|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.z_index%}</label>
                <div class="col-lg-4">
                    <input class="form-control number number" type="number" name="z_index" value="{%$areaList.z_index|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-lg-offset-2">
                    <button type="submit" class="btn btn-primary">{%$Think.lang.save%}</button>
                    <a class="btn btn-default" href="{%:U('/sws/area/index')%}">{%$Think.lang.btn_back%}</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    cookieLanguage = '{%$Think.LANG_SET%}'.toLowerCase();
    $(function ($) {
        $("select[name='city_id']").on("change",function () {
            var currency_type = $(this).find('option:selected').data("currency");
            $(".currency_type").text("（"+currency_type+"）");
            $('input.ajaxCheckName').focus().blur();
        }).trigger("change");

        $("#firstForm").validate();
    })
</script>
<js href="__PUBLIC__/sws/js/jquery.validate.min.js"/>
<js href="__PUBLIC__/sws/js/jquery.validate.js?{%$Think.config.DEFINE.webVersions%}"/>