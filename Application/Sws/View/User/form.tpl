<div class="container-sc">
    <ol class="breadcrumb">
        <li><a href="{%:U('/sws/index/index')%}">{%$Think.lang.home%}</a></li>
        <li><a href="{%:U('/sws/user/index')%}">{%$Think.lang.user_manage%}</a></li>
        <notempty name="userList.id">
            <li class="active">{%$Think.lang.update%}</li>
            <else/>
            <li class="active">{%$Think.lang.add%}</li>
        </notempty>
    </ol>
    <h2>{%$Think.lang.user_form%}</h2>
    <div class="col-xs-12">
        <form action="{%:U('/sws/user/save')%}" class="form-horizontal" method="post" id="firstForm">
            <notempty name="userList.id">
                <input name="id" type="hidden" value="{%$userList.id|default=''%}" id="edit_id">
            </notempty>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.acc_number%}</label>
                <div class="col-lg-4">
                    <input class="form-control required checkChinaName ajaxCheckName" minlength="3" maxlength="15" data-url="{%:U('/sws/user/ajaxCheckUserName')%}" type="text" name="user_name" value="{%$userList.user_name|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.password%}</label>
                <div class="col-lg-4">

                    <notempty name="userList.id">
                        <div class="form-control-static"><a id="updatePwd" href="javascript:void(0);">{%$Think.lang.update%}</a></div>
                        <else/>
                        <input class="form-control required" type="password" name="password" value="">
                    </notempty>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.nickname%}</label>
                <div class="col-lg-4">
                    <input class="form-control" type="text" name="nickname" value="{%$userList.nickname|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.admin_lang%}</label>
                <div class="col-lg-4">
                    <select class="form-control required" name="lang">
                        <foreach name="langList" item="vo" key="k" >
                            <eq name="k" value="$userList.lang">
                                <option value="{%$k%}" selected>{%$vo%}</option>
                                <else/>
                                <option value="{%$k%}">{%$vo%}</option>
                            </eq>
                        </foreach>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.admin_email%}</label>
                <div class="col-lg-4">
                    <input class="form-control checkEmail required" type="text" name="email" value="{%$userList.email|default=''%}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.email_hint%}</label>
                <div class="col-lg-4">
                    <select class="form-control" name="email_hint">
                        <foreach name="emailList" item="vo" key="k" >
                            <eq name="k" value="$userList.email_hint">
                                <option value="{%$k%}" selected>{%$vo%}</option>
                                <else/>
                                <option value="{%$k%}">{%$vo%}</option>
                            </eq>
                        </foreach>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.old_email%}</label>
                <div class="col-lg-4">
                    <select class="form-control" name="old_email">
                        <option value="">{%$Think.lang.close%}</option>
                        <eq name="userList.old_email" value="1">
                            <option value="1" selected>{%$Think.lang.open%}</option>
                            <else/>
                            <option value="1">{%$Think.lang.open%}</option>
                        </eq>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.more_hint%}</label>
                <div class="col-lg-4">
                    <select class="form-control required" name="more_hint">
                        <option value="0">{%$Think.lang.close%}</option>

                        <if condition="$userList['more_hint']==1">
                            <option value="1" selected>{%$Think.lang.open%}</option>
                            <else/>
                            <option value="1">{%$Think.lang.open%}</option>
                        </if>

                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.city_auth%}</label>
                <div class="col-lg-8" style="padding-left: 5px;">
                    <div class="batch_check">
                        <label class="checkbox-inline" style="margin-left: 10px;">
                            <input type="checkbox" id="allCityAuth" value="">{%$Think.lang.all_city_auth%}
                        </label>
                        <foreach name="regionList" item="vo" key="k" >
                            <label class="checkbox-inline" style="margin-left: 10px;">
                                <input type="checkbox" class="check_all" value="{%$k%}">{%$vo%}
                            </label>
                        </foreach>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.city_detail%}</label>
                <div class="col-lg-8" style="padding-left: 5px;">
                    <div class="batch_div">
                        <foreach name="cityList" item="vo" key="k" >
                            <if condition="array_search($k,$userList['city_auth'])!==false">
                                <label class="checkbox-inline" style="margin-left: 10px;">
                                    <input type="checkbox" name="city_auth[]" checked value="{%$k%}" data-id="{%$vo.region_id%}">{%$vo["city_name$prefix"]%}
                                </label>
                                <else/>
                                <label class="checkbox-inline" style="margin-left: 10px;">
                                    <input type="checkbox" name="city_auth[]" value="{%$k%}" data-id="{%$vo.region_id%}">{%$vo["city_name$prefix"]%}
                                </label>
                            </if>
                        </foreach>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 text-right">
                <h3>{%$Think.lang.auth%}</h3>
            </div>
            <div class="col-lg-12">
                <div class="row">
                    <foreach name="Think.config.MENU" item="menu_list" key="menu_key" >
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="control-label col-lg-6">{%$menu_key|L%}</label>
                                <div class="col-lg-6">
                                    <select class="form-control" name="auth[]">
                                        <option value="">{%$Think.lang.close%}</option>

                                        <if condition="strpos($userList['auth'],$menu_list['auth'])!==false">
                                            <option value="{%$menu_list.auth%}" selected>{%$Think.lang.open%}</option>
                                            <else/>
                                            <option value="{%$menu_list.auth%}">{%$Think.lang.open%}</option>
                                        </if>

                                    </select>
                                </div>
                            </div>
                        </div>
                    </foreach>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-lg-offset-2">
                    <button type="submit" class="btn btn-primary">{%$Think.lang.save%}</button>
                    <a class="btn btn-default" href="{%:U('/sws/user/index')%}">{%$Think.lang.btn_back%}</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    cookieLanguage = '{%$Think.LANG_SET%}'.toLowerCase();
    $(function ($) {
        $("#updatePwd").on("click",function () {
            var $div = $(this).parents(".form-control-static:first");
            $div.after('<input class="form-control required" type="password" name="password" value="">');
            $div.remove();
        });
        $("#firstForm").validate();

        $("#allCityAuth").on("change",function () {
            if($(this).is(":checked")){
                $(".batch_div").find("input").prop("checked",true);
            }else{
                $(".batch_div").find("input").prop("checked",false);
            }
        });

        $(".check_all").on("change",function () {
            var region_id = $(this).val();
            if($(this).is(":checked")){
                $(".batch_div").find("input[data-id='"+region_id+"']").prop("checked",true);
            }else{
                $(".batch_div").find("input[data-id='"+region_id+"']").prop("checked",false);
            }
        });
    })
</script>
<js href="__PUBLIC__/sws/js/jquery.validate.min.js"/>
<js href="__PUBLIC__/sws/js/jquery.validate.js?{%$Think.config.DEFINE.webVersions%}"/>