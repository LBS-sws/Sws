<!DOCTYPE html>
{__NOLAYOUT__}
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{%$Think.lang.loginTitle%}</title>
    <js href="__PUBLIC__/weChat/js/jquery-2.1.1.min.js"/>
    <js href="__PUBLIC__/weChat/js/weChat.min.js"/>
</head>
<body>
<script>
    wx.config({%$signPackage%});
    wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
        wx.closeWindow();
    });
    $(function ($) {
        wx.closeWindow();
    })
</script>
</body>
</html>
