<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{%$Think.lang.loginTitle%}</title>
    <css href="__PUBLIC__/css/bootstrap.min.css"/>
    <css href="__PUBLIC__/sws/css/login.css?{%$Think.config.DEFINE.webVersions%}"/>
    <js href="__PUBLIC__/js/jquery-3.2.1.min.js"/>
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
<div class="container">
    <div class="col-lg-12">
        <div class="row">
            <div class="main-box">
                <h2 class="text-center">{%$Think.lang.loginTitle%}</h2>
                <form action="/sws/Login/Login" class="form-horizontal" method="post" style="padding-top: 20px;" id="loginForm">
                    <div class="form-group">
                        <div class="col-lg-8 col-lg-offset-2">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="text" class="form-control checkChinaName required" name="username" placeholder="{%$Think.lang.username%}" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-8 col-lg-offset-2">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-pwd"><span class="glyphicon glyphicon-lock"></span></span>
                                <input type="password" class="form-control required" name="password" placeholder="{%$Think.lang.password%}" aria-describedby="basic-pwd">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 text-center">
                            <button class="btn btn-primary" type="submit">&nbsp;&nbsp;&nbsp;&nbsp;{%$Think.lang.login%}&nbsp;&nbsp;&nbsp;&nbsp;</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var cookieLanguage = '{%$Think.LANG_SET%}'.toLowerCase();
    if(cookieLanguage == ""){
        cookieLanguage = "zh-cn";
    }
    $(function ($) {
        $("form:first").validate({
            submitHandler:function(form){
                $.ajax({
                    type: "post",
                    url: "{%:U('/sws/login/loginAjax')%}",
                    data: $(form).serialize(),
                    dataType: "json",
                    success: function(data){
                        if(data.status == 1){
                            window.location.href='{%$oldUrl%}';
                        }else{
                            var $input = $("#loginForm").find("input[name='"+data.error+"']");
                            $input.parents(".form-group").addClass("has-error");
                            $input.parents(".form-group").find("div:first").append('<label id="'+data.error+'-error" class="error" for="'+data.error+'">'+data.content+'</label>');
                            return false;
                        }
                    }
                });
                return false;
            }
        });
    })
</script>
<js href="__PUBLIC__/sws/js/jquery.validate.min.js"/>
<js href="__PUBLIC__/sws/js/jquery.validate.js?{%$Think.config.DEFINE.webVersions%}"/>
<js href="__PUBLIC__/sws/js/login.js?{%$Think.config.DEFINE.webVersions%}"/>
</body>
</html>
