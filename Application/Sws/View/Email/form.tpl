<div class="container-sc">
    <ol class="breadcrumb">
        <li><a href="{%:U('/sws/index/index')%}">{%$Think.lang.home%}</a></li>
        <li><a href="{%:U('/sws/email/index')%}">{%$Think.lang.email_manage%}</a></li>
        <notempty name="emailList.id">
            <li class="active">{%$Think.lang.update%}</li>
            <else/>
            <li class="active">{%$Think.lang.add%}</li>
        </notempty>
    </ol>
    <h2>{%$Think.lang.email_form%}</h2>
    <div class="col-xs-12">
        <form action="{%:U('/sws/email/save')%}" class="form-horizontal" method="post" id="firstForm">
            <notempty name="emailList.id">
                <input name="id" type="hidden" value="{%$emailList.id|default=''%}" id="edit_id">
            </notempty>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.email_prefix%}</label>
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">@</span>
                        <input class="form-control required ajaxCheckName" data-url="{%:U('/sws/email/ajaxCheckEmailName')%}" type="text" name="email_prefix" value="{%$emailList.email_prefix|default=''%}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.email_import%}</label>
                <div class="col-lg-4">
                    <input class="form-control required" type="text" name="email" value="{%$emailList.email|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-lg-offset-2">
                    <button type="submit" class="btn btn-primary">{%$Think.lang.save%}</button>
                    <a class="btn btn-default" href="{%:U('/sws/email/index')%}">{%$Think.lang.btn_back%}</a>
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