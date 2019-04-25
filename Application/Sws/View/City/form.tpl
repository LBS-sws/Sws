<div class="container-sc">
    <ol class="breadcrumb">
        <li><a href="{%:U('/sws/index/index')%}">{%$Think.lang.home%}</a></li>
        <li><a href="{%:U('/sws/city/index')%}">{%$Think.lang.city_manage%}</a></li>
        <notempty name="cityList.id">
            <li class="active">{%$Think.lang.update%}</li>
            <else/>
            <li class="active">{%$Think.lang.add%}</li>
        </notempty>
    </ol>
    <div class="text-right">
        <a class="btn btn-danger" target="_blank" href="{%:U('/sws/city/test',array('index'=>$cityList['id']))%}">{%$Think.lang.read_PDF%}</a>
    </div>
    <h2>{%$Think.lang.city_form%}</h2>
    <div class="col-xs-12">
        <form action="{%:U('/sws/city/save')%}" class="form-horizontal" enctype="multipart/form-data" method="post" id="firstForm">
            <notempty name="cityList.id">
                <input name="id" type="hidden" value="{%$cityList.id|default=''%}" id="edit_id">
            </notempty>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.region_name%}</label>
                <div class="col-lg-4">
                    <select class="form-control required" name="region_id">
                        <option value=""></option>
                        <foreach name="regionList" item="vo" key="k" >
                            <eq name="k" value="$cityList.region_id">
                                <option value="{%$k%}" selected >{%$vo|default='未設置'%}</option>
                                <else/>
                                <option value="{%$k%}">{%$vo|default='未設置'%}</option>
                            </eq>
                        </foreach>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.city_name%}{%$Think.lang.zh_cn%}</label>
                <div class="col-lg-4">
                    <input class="form-control required ajaxCheckName" data-url="{%:U('/sws/city/ajaxCheckCityName')%}" type="text" name="city_name" value="{%$cityList.city_name|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.city_name%}{%$Think.lang.zh_tw%}</label>
                <div class="col-lg-4">
                    <input class="form-control required ajaxCheckName" data-url="{%:U('/sws/city/ajaxCheckCityName')%}" type="text" name="city_name_tw" value="{%$cityList.city_name_tw|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.city_name%}{%$Think.lang.en_us%}</label>
                <div class="col-lg-4">
                    <input class="form-control required ajaxCheckName" data-url="{%:U('/sws/city/ajaxCheckCityName')%}" type="text" name="city_name_us" value="{%$cityList.city_name_us|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.city_level%}</label>
                <div class="col-lg-4">
                    <input class="form-control number" type="number" name="z_index" value="{%$cityList.z_index|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.currency_type%}</label>
                <div class="col-lg-4">
                    <select class="form-control required" name="currency_type">
                        <foreach name="currList" item="curr">
                            <eq name="cityList.currency_type" value="$curr">
                                <option value="{%$curr%}" selected>{%$curr%}</option>
                                <else/>
                                <option value="{%$curr%}">{%$curr%}</option>
                            </eq>
                        </foreach>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.b_unit%}</label>
                <div class="col-lg-4">
                    <select class="form-control required" name="b_unit">
                        <option value=""></option>
                        <foreach name="unitList" item="unit_name" key="unit" >
                            <eq name="cityList.b_unit" value="$unit">
                                <option value="{%$unit%}" selected>{%$unit|L%}</option>
                                    <else/>
                                <option value="{%$unit%}">{%$unit|L%}</option>
                            </eq>
                        </foreach>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.city_other%}</label>
                <div class="col-lg-4">
                    <select class="form-control" name="other_open" id="other_open" data-equal="equal">
                        <eq name="cityList.other_open" value="1">
                            <option value="1" selected>{%$Think.lang.exist%}</option>
                            <option value="0">{%$Think.lang.no_exist%}</option>
                            <else/>
                            <option value="1">{%$Think.lang.exist%}</option>
                            <option value="0" selected>{%$Think.lang.no_exist%}</option>
                        </eq>
                    </select>
                </div>
            </div>
            <div class="downPriceDiv">
                <div class="form-group">
                    <label class="control-label col-lg-2">{%$Think.lang.other_price%}</label>
                    <div class="col-lg-4">
                        <input class="form-control number checkEmptyToName" type="number" name="other_price" value="{%$cityList.other_price|default=''%}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">{%$Think.lang.business_min_price%}</label>
                    <div class="col-lg-4">
                        <input class="form-control number checkEmptyToName" type="number" name="other_min" value="{%$cityList.other_min|default=''%}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-2 text-right"><h3>{%$Think.lang.company_info%}</h3></div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.company_name%}{%$Think.lang.zh_cn%}</label>
                <div class="col-lg-4">
                    <input class="form-control required" type="text" name="company" value="{%$cityList.company|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.company_name%}{%$Think.lang.zh_tw%}</label>
                <div class="col-lg-4">
                    <input class="form-control required" type="text" name="company_tw" value="{%$cityList.company_tw|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.company_name%}{%$Think.lang.en_us%}</label>
                <div class="col-lg-4">
                    <input class="form-control required" type="text" name="company_us" value="{%$cityList.company_us|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.company_TC%}{%$Think.lang.zh_cn%}</label>
                <div class="col-lg-8">
                    <textarea cols="70" rows="13"  name="terms" id="terms">{%$cityList.terms|default=''%}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.company_TC%}{%$Think.lang.zh_tw%}</label>
                <div class="col-lg-8">
                    <textarea cols="70" rows="13"  name="terms_tw" id="terms_tw">{%$cityList.terms_tw|default=''%}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.company_TC%}{%$Think.lang.en_us%}</label>
                <div class="col-lg-8">
                    <textarea cols="70" rows="13"  name="terms_us" id="terms_us">{%$cityList.terms_us|default=''%}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.company_seal%}</label>
                <div class="col-lg-4">
                    <empty name="cityList['seal']">
                        <input class="form-control required checkFileImg" type="file" name="seal" value="">
                        <else />
                        <img src="{%$cityList.seal|default=''%}" width="100px" height="100px" class="media-bottom">
                        <a id="imgUpdate" href="javascript:void(0);">{%$Think.lang.update%}</a>
                        <input class="form-control" type="hidden" name="seal" value="{%$cityList.seal|default=''%}">
                    </empty>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6 col-lg-offset-2 text-success">
                    {%$Think.lang.picture_seal%}100px×100px
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-lg-offset-2">
                    <button type="submit" class="btn btn-primary">{%$Think.lang.save%}</button>
                    <a class="btn btn-default" href="{%:U('/sws/city/index')%}">{%$Think.lang.btn_back%}</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    cookieLanguage = '{%$Think.LANG_SET%}'
    $(function ($) {
        $("#other_open").on("change",function () {
            if($(this).val() == 1){
                $(this).parents(".form-group:first").next(".downPriceDiv").show();
            }else{
                $(this).parents(".form-group:first").next(".downPriceDiv").hide();
            }
        }).trigger("change");
        $("#firstForm").validate();

        $("#imgUpdate").on("click",function () {
            $(this).parent("div").html('<input class="form-control required checkFileImg" type="file" name="seal" value="">');
        });

        var CKEditor = CKEDITOR.replace('terms',{
            filebrowserImageUploadUrl  :  "{%:U('/sws/city/ck_upload')%}",
            toolbar : 'Full',
            uiColor : '#9AB8F3',
            height:'200'
        });
        var CKEditor_tw = CKEDITOR.replace('terms_tw',{
            filebrowserImageUploadUrl  :  "{%:U('/sws/city/ck_upload')%}",
            toolbar : 'Full',
            uiColor : '#9AB8F3',
            height:'200'
        });
        var CKEditor_us = CKEDITOR.replace('terms_us',{
            filebrowserImageUploadUrl  :  "{%:U('/sws/city/ck_upload')%}",
            toolbar : 'Full',
            uiColor : '#9AB8F3',
            height:'200'
        });
    });
</script>
<js href="__PUBLIC__/sws/js/jquery.validate.min.js"/>
<js href="__PUBLIC__/ckeditor/ckeditor.js"/>
<js href="__PUBLIC__/sws/js/jquery.validate.js?{%$Think.config.DEFINE.webVersions%}"/>