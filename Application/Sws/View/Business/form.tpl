<div class="container-sc">
    <ol class="breadcrumb">
        <li><a href="{%:U('/sws/index/index')%}">{%$Think.lang.home%}</a></li>
        <li><a href="{%:U('/sws/business/index')%}">{%$Think.lang.business_manage%}</a></li>
        <notempty name="businessList.id">
            <li class="active">{%$Think.lang.update%}</li>
            <else/>
            <li class="active">{%$Think.lang.add%}</li>
        </notempty>
    </ol>
    <h2>{%$Think.lang.business_form%}</h2>
    <div class="col-xs-12">
        <form action="{%:U('/sws/business/save')%}" class="form-horizontal" method="post" id="firstForm">
            <notempty name="businessList.id">
                <input name="id" type="hidden" value="{%$businessList.id|default=''%}" id="edit_id">
            </notempty>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.business_name%}{%$Think.lang.zh_cn%}</label>
                <div class="col-lg-4">
                    <input class="form-control required ajaxCheckName" data-url="{%:U('/sws/business/ajaxCheckBusinessName')%}" type="text" name="name" value="{%$businessList.name|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.business_name%}{%$Think.lang.zh_tw%}</label>
                <div class="col-lg-4">
                    <input class="form-control required ajaxCheckName" data-url="{%:U('/sws/business/ajaxCheckBusinessName')%}" type="text" name="name_tw" value="{%$businessList.name_tw|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.business_name%}{%$Think.lang.en_us%}</label>
                <div class="col-lg-4">
                    <input class="form-control required ajaxCheckName" data-url="{%:U('/sws/business/ajaxCheckBusinessName')%}" type="text" name="name_us" value="{%$businessList.name_us|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.city_name%}</label>
                <div class="col-lg-4">
                    <select class="form-control required" name="city_id" id="city_id">
                        <foreach name="cityList" item="vo" key="k" >
                            <eq name="k" value="$businessList.city_id">
                                <option value="{%$k%}" selected data-currency="{%$vo.currency_type%}"  data-unit="{%$vo.b_unit|L%}" >{%$vo[$cityName]|default='未設置'%}</option>
                                <else/>
                                <option value="{%$k%}" data-currency="{%$vo.currency_type%}"  data-unit="{%$vo.b_unit|L%}" >{%$vo[$cityName]|default='未設置'%}</option>
                            </eq>
                        </foreach>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.business_type%}</label>
                <div class="col-lg-4">
                    <select class="form-control required" name="type">
                        <eq name="businessList.type" value="1">
                            <option value="1" selected>{%$Think.lang.general_business%}</option>
                            <option value="0">{%$Think.lang.special_business%}</option>
                            <else/>
                            <option value="1">{%$Think.lang.general_business%}</option>
                            <option value="0" selected>{%$Think.lang.special_business%}</option>
                        </eq>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.a%}<span class="b_unit"></span>{%$Think.lang.price%}<span class="currency_type"></span></label>
                <div class="col-lg-4">
                    <input class="form-control number required" type="number" name="price" value="{%$businessList.price|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-lg-offset-2">
                    <button type="submit" class="btn btn-primary">{%$Think.lang.save%}</button>
                    <a class="btn btn-default" href="{%:U('/sws/business/index')%}">{%$Think.lang.btn_back%}</a>
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
            var unit = $(this).find('option:selected').data("unit");
            $(".currency_type").text("（"+currency_type+"）");
            $(".b_unit").text(unit);
            $('input.ajaxCheckName').focus().blur();
        }).trigger("change");

        $('select[name="type"]').on("change",function () {
            if($(this).val() == 1){
                $('input[name="price"]').parents(".form-group:first").show();
            }else{
                $('input[name="price"]').parents(".form-group:first").hide();
            }
        }).trigger("change");

        $("#firstForm").validate();
    })
</script>
<js href="__PUBLIC__/sws/js/jquery.validate.min.js"/>
<js href="__PUBLIC__/sws/js/jquery.validate.js?{%$Think.config.DEFINE.webVersions%}"/>