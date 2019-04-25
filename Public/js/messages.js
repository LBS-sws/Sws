
$.extend($.validator.messages, {
    required: "必填",
    remote: "请修正该字段",
    email: "电子邮件格式不正确",
    url: "网址格式不正确",
    date: "日期格式不正确",
    dateISO: "请输入合法的日期 (ISO).",
    number: "请输入数字",
    digits: "只能输入整数",
    creditcard: "请输入合法的信用卡号",
    equalTo: "请再次输入相同的值",
    accept: "请输入拥有合法后缀名的字符",
    maxlength: $.validator.format("请输入一个 长度最多是 {0} 的字符"),
    minlength: $.validator.format("请输入一个 长度最少是 {0} 的字符"),
    rangelength: $.validator.format("请输入 一个长度介于 {0} 和 {1} 之间的字符"),
    range: $.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
    max: $.validator.format("请输入一个最大为{0} 的值"),
    min: $.validator.format("请输入一个最小为{0} 的值")
});

$.validator.addMethod("af",function(value,element,params){
	var re = /^[a-zA-Z0-9_]{4,15}$/;
    return re.test(value);
},"必须是长度4-15的字母或数字");