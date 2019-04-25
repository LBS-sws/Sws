<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><block name="title">实时报价系統</block></title>
    <css href="__PUBLIC__/css/style.css"/>
    <css href="__PUBLIC__/css/bootstrap.min.css"/>
    <css href="__PUBLIC__/css/font-awesome.min.css"/>
    <css href="__PUBLIC__/css/main.css"/>
    <css href="__PUBLIC__/sws/css/main.css"/>
    <css href="__PUBLIC__/sws/css/dataTable.css"/>
    <block name="css"><!--css--></block>
    <js href="__PUBLIC__/js/jquery-3.2.1.min.js"/>
    <js href="__PUBLIC__/js/jquery.cookie.js"/>
    <block name="js"><!--js--></block>
</head>
<body>
<include file="Public/left"/>
<div class="main">
    <a href="#" id="toggle" aria-hidden="true" class="fa fa-2x fa-bars">&nbsp;</a>
    <block name="main"><!--主内容--></block>
</div>
<block name="footer"><!--底部--></block>
<script>
    $(function () {
        $("#toggle").on("click",function () {
            $("#nav").toggle("fast");
        });
        if($("#toggle").is(":visible")){
            $("#toggle").trigger("click");
        }
    })
</script>
</body>
</html>
