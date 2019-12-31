
var languageValidate = {};
cookieLanguage = "zh-cn";//後期添加
switch (cookieLanguage.toLowerCase()){
    case "zh-tw":
        languageValidate = {
            required: "這是必填選項",
            remote: "請修正此字段",
            email: "請輸入有效的電子郵箱",
            url: "請輸入有效的地址",
            date: "請輸入有效的日期",
            dateISO: "請輸入有效的日期 (YYYY-MM-DD)",
            number: "請輸入有效的數字",
            digits: "只能輸入整數",
            creditcard: "請輸入有效的銀行卡號",
            equalTo: "你的輸入不同",
            extension: "請輸入有效的後綴",
            maxlength: $.validator.format("最多可以輸入 {0} 個字符"),
            minlength: $.validator.format("最少要輸入 {0} 個字符"),
            rangelength: $.validator.format("请輸入長度在 {0} 到 {1} 之間的字符串"),
            range: $.validator.format("請輸入範圍在 {0} 到 {1} 之間的數值"),
            max: $.validator.format("請輸入不大於 {0} 的數值"),
            min: $.validator.format("請輸入不小於 {0} 的數值"),
            exists:"該數據已存在",
            china:"只能是中文、字母或數字",
            fileImg:"選擇的文件只能是圖片",
            phone:"電話號碼格式不正確"
        };
        break;
    case "en-us":
        languageValidate = {
            required: "This field is required.",
            remote: "Please fix this field.",
            email: "Please enter a valid email address.",
            url: "Please enter a valid URL.",
            date: "Please enter a valid date.",
            dateISO: "Please enter a valid date (ISO).",
            dateDE: "Bitte geben Sie ein g眉ltiges Datum ein.",
            number: "Please enter a valid number.",
            numberDE: "Bitte geben Sie eine Nummer ein.",
            digits: "Please enter only digits",
            creditcard: "Please enter a valid credit card number.",
            equalTo: "Please enter the same value again.",
            accept: "Please enter a value with a valid extension.",
            maxlength: $.validator.format("Please enter no more than {0} characters."),
            minlength: $.validator.format("Please enter at least {0} characters."),
            rangelength: $.validator.format("Please enter a value between {0} and {1} characters long."),
            range: $.validator.format("Please enter a value between {0} and {1}."),
            max: $.validator.format("Please enter a value less than or equal to {0}."),
            min: $.validator.format("Please enter a value greater than or equal to {0}."),
            exists:"The data already exists",
            china:"Only Chinese or letters or Numbers",
            fileImg:"The selected file can only be the image",
            phone:"The telephone number is not in the correct format"
        }
        break;
    default:
        languageValidate = {
            required: "这是必填字段",
            remote: "请修正此字段",
            email: "请输入有效的电子邮件地址",
            url: "请输入有效的网址",
            date: "请输入有效的日期",
            dateISO: "请输入有效的日期 (YYYY-MM-DD)",
            number: "请输入有效的数字",
            digits: "只能输入整数",
            creditcard: "请输入有效的信用卡号码",
            equalTo: "你的输入不相同",
            extension: "请输入有效的后缀",
            maxlength: $.validator.format("最多可以输入 {0} 个字符"),
            minlength: $.validator.format("最少要输入 {0} 个字符"),
            rangelength: $.validator.format("请输入长度在 {0} 到 {1} 之间的字符串"),
            range: $.validator.format("请输入范围在 {0} 到 {1} 之间的数值"),
            max: $.validator.format("请输入不大于 {0} 的数值"),
            min: $.validator.format("请输入不小于 {0} 的数值"),
            exists:"该数据已存在",
            china:"只能是中文、字母或數字",
            fileImg:"选择的文件只能是图片",
            phone:"电话号码格式不正确"
        };
        break;
}
/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: ZH (Chinese, 中文 (Zhōngwén), 汉语, 漢語)
 */
$.extend($.validator.messages, languageValidate);
$.validator.setDefaults({
    errorPlacement: function(error, element) {
        element.parents(".form-group").addClass("has-error");
        error.appendTo(element.parents(".form-group").find("div:first"));
    },
    success: function(label) {
        // set &nbsp; as text for IE
        label.parents(".form-group").removeClass("has-error");
        label.remove();
        //label.addClass("valid").text("Ok!")
    }
})

/*
* 異步驗證是否重複
* data-url:異步地址   edit_id:編輯id
*/
jQuery.validator.addMethod("ajaxCheckName", function(value, element) {
    if(value != null && value != undefined && value !=""){
        var url = $(element).data("url");
        var that = this;
        if(url == null || url == undefined || url == ""){
            return false;
        }
        var result = false;
        // 设置同步
        $.ajaxSetup({
            async: false
        });
        var param = {
            "name": value,
            "id":$("#edit_id").val(),
            "value":$(element).attr("name"),
            "city_id":$("#city_id").val()
        };
        $.post(url, param, function(data){
            result = (data.status != 1);
        });
        // 恢复异步
        $.ajaxSetup({
            async: true
        });
        return result;
    }else{
        return true;
    }
}, languageValidate["exists"]);


/*
 * 如果某個值不為空，那麼這個也不能為空
 * data-name:某個值的name
 */
jQuery.validator.addMethod("checkEmptyToName", function(value, element) {
    if($("select[data-equal='equal']").length == 1){
        var val = $("select[data-equal='equal']").val();
        if(val != ""&& val !=undefined && val!=null){
            if(value == ""|| value ==undefined || value==null){
                return false;
            }
        }
        return true;
    }else{
        return true;
    }
}, languageValidate["required"]);
/*
 * 如果某個值不為空，那麼這個也不能為空
 * data-name:某個值的name
 */
jQuery.validator.addMethod("checkEmptyInputToName", function(value, element) {
    if($("input[data-equal='equal']").length == 1){
        var val = $("input[data-equal='equal']").val();
        if(val != ""&& val !=undefined && val!=null){
            if(value == ""|| value ==undefined || value==null){
                return false;
            }
        }
        return true;
    }else{
        return true;
    }
}, languageValidate["required"]);
/*
 * 如果某個值不為空，那麼這個也不能為空
 * data-name:某個值的name
 */
jQuery.validator.addMethod("checkEmptyInputToName", function(value, element) {
    if($("input[data-equal='equal']").length == 1){
        var val = $("input[data-equal='equal']").val();
        if(val != ""&& val !=undefined && val!=null){
            if(value == ""|| value ==undefined || value==null){
                return false;
            }
        }
        return true;
    }else{
        return true;
    }
}, languageValidate["required"]);
/*
 * 如果某個值不為空，那麼這個也不能為空
 * data-name:某個值的name
 */
jQuery.validator.addMethod("checkPhone", function(value, element) {
    //var re = /^1\d{10}$/;
    var re = /(^[0-9]{7,11}$)/;
    if (re.test(value)) {
        return true;
    } else {
        return false;
    }
}, languageValidate["phone"]);
/*
 * 如果某個值不為空，那麼這個也不能為空
 * data-name:某個值的name
 */
jQuery.validator.addMethod("checkEmail", function(value, element) {
    if(value != ""){
        var re = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
        if (re.test(value)) {
            return true;
        } else {
            return false;
        }
    }else {
        return true;
    }
}, languageValidate["email"]);
/*
 * 如果某個值不為空，那麼這個也不能為空
 * data-name:某個值的name
 */
jQuery.validator.addMethod("checkChinaName", function(value, element) {
    var re = /^[0-9 a-zA-Z\u4E00-\u9FA5]+$/g;
    if (re.test(value)) {
        return true;
    } else {
        return false;
    }
}, languageValidate["china"]);
/*
 * 如果某個值不為空，那麼這個也不能為空
 * data-name:某個值的name
 */
jQuery.validator.addMethod("checkFileImg", function(value, element) {
    if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(value)) {
        return false;
    } else {
        return true;
    }
}, languageValidate["fileImg"]);