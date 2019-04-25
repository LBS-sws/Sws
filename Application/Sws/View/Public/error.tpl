
<!DOCTYPE html>
{__NOLAYOUT__}
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{%$msgTitle%}</title>
    <css href="__PUBLIC__/css/bootstrap.min.css"/>
    <js href="__PUBLIC__/js/jquery-3.2.1.min.js"/>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="media">
            <div class="media-left media-middle">
                <img src="__PUBLIC__/sws/img/error.jpg" width="400px;">
            </div>
            <div class="media-body media-middle">
                <h2>{%$error%}</h2>
                <div id="div_ex">{%$waitSecond%} {%$Think.lang.jump_time%}</div>
                <a href="{%$jumpUrl|U%}">{%$Think.lang.jump_url%}</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(function ($) {
        var time = '{%$waitSecond%}';
        time = parseInt(time,10);
        var inter = setInterval(function () {
            time--;
            if(time == 0){
                clearInterval(inter);
                window.location.href='{%$jumpUrl|U%}';
            }
            $("#div_ex").text(time+" {%$Think.lang.jump_time%}");
        },1000)
    })
</script>
</body>
</html>
