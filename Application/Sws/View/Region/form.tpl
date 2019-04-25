<div class="container-sc">
    <ol class="breadcrumb">
        <li><a href="{%:U('/sws/index/index')%}">{%$Think.lang.home%}</a></li>
        <li><a href="{%:U('/sws/region/index')%}">{%$Think.lang.region_manage%}</a></li>
        <notempty name="regionList.id">
            <li class="active">{%$Think.lang.update%}</li>
            <else/>
            <li class="active">{%$Think.lang.add%}</li>
        </notempty>
    </ol>
    <h2>{%$Think.lang.region_form%}</h2>
    <div class="col-xs-12">
        <form action="{%:U('/sws/region/save')%}" class="form-horizontal" method="post" id="firstForm">
            <notempty name="regionList.id">
                <input name="id" type="hidden" value="{%$regionList.id|default=''%}" id="edit_id">
            </notempty>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.region_name%}{%$Think.lang.zh_cn%}</label>
                <div class="col-lg-4">
                    <input class="form-control required ajaxCheckName" data-url="{%:U('/sws/region/ajaxCheckRegionName')%}" type="text" name="region_name" value="{%$regionList.region_name|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.region_name%}{%$Think.lang.zh_tw%}</label>
                <div class="col-lg-4">
                    <input class="form-control required ajaxCheckName" data-url="{%:U('/sws/region/ajaxCheckRegionName')%}" type="text" name="region_name_tw" value="{%$regionList.region_name_tw|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.region_name%}{%$Think.lang.en_us%}</label>
                <div class="col-lg-4">
                    <input class="form-control required ajaxCheckName" data-url="{%:U('/sws/region/ajaxCheckRegionName')%}" type="text" name="region_name_us" value="{%$regionList.region_name_us|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.z_index%}</label>
                <div class="col-lg-4">
                    <input class="form-control number" type="text" name="z_index" value="{%$regionList.z_index|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.web_prefix%}</label>
                <div class="col-lg-4">
                    <input class="form-control required" type="text" name="web_prefix" value="{%$regionList.web_prefix|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.www_fix%}</label>
                <div class="col-lg-4">
                    <input class="form-control required" type="text" name="www_fix" value="{%$regionList.www_fix|default=''%}">
                </div>
                <div class="col-lg-4">
                    <p class="form-control-static text-primary">{%$Think.lang.www_fix_p%}</p>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.calculation%}</label>
                <div class="col-lg-4">
                    <select class="form-control required" name="calculation">
                        <option value="0">{%$Think.lang.no%}</option>

                        <if condition="$regionList['calculation']==1">
                            <option value="1" selected>{%$Think.lang.yes%}</option>
                            <else/>
                            <option value="1">{%$Think.lang.yes%}</option>
                        </if>

                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-lg-offset-2">
                    <button type="submit" class="btn btn-primary">{%$Think.lang.save%}</button>
                    <a class="btn btn-default" href="{%:U('/sws/region/index')%}">{%$Think.lang.btn_back%}</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    cookieLanguage = '{%$Think.LANG_SET%}'
    $(function ($) {
        $("#firstForm").validate();
    })
</script>
<js href="__PUBLIC__/sws/js/jquery.validate.min.js"/>
<js href="__PUBLIC__/sws/js/jquery.validate.js?{%$Think.config.DEFINE.webVersions%}"/>