
var request; //一次只能一個請求
$(function ($) {
    var ajaxBool = true; //一次只能一個請求
    var timeout; //定时器

    $("#down_show").scroll(function () {
        var nScrollHight = $(this)[0].scrollHeight - $(this).height();
        var nScrollTop = $(this)[0].scrollTop;
        var dataUrl = $(this).data("href");
        var nextToKen = $(this).data("nextToKen");
        var searchValue = $(this).data("value");
        var scroll = $(this).data("scroll");
        if(nextToKen==""||nextToKen==undefined){
            return false;
        }
        nextToKen = (nextToKen==""||nextToKen==undefined)?"over":nextToKen;
        var dataJSON = {
            "id":$("#id").val(),
            "token":$("#token").val(),
            "nextToKen":nextToKen,
            "searchValue":searchValue
        };
        if(nScrollTop/nScrollHight >=0.8&&scroll!="none"){
            if(ajaxBool){
                ajaxBool = false
            }else{
                return false;
            }
            $.ajax({
                type: "post",
                url: dataUrl,
                data: dataJSON,
                dataType: "json",
                success: function(data){
                    ajaxBool = true;
                    if(data.status == 1){
                        if(data.html==""){
                            $("#kehu-div-ul").data("scroll","none");
                        }
                        $("#down_show").data("nextToKen",data.nextToken);
                        $("#down_show>ul").append(data.html);
                    }else{
                        alert("頁面異常，請刷新重試2");
                    }
                },
                error:function () {
                    ajaxBool = true;
                }
            });
        }
    });
    $("#searchValue").on("change",function () {
       $("#address").val($(this).val());
    });

    $("#searchValue").on("input propertychange click",function (event) {
        $("#down_show").data("close",0);
        var oldValue = $("#searchValue").data("oldValue");
        var searchValue = $("#searchValue").val();
        if(searchValue==""||searchValue==undefined||searchValue==null||oldValue == searchValue){
            $("#down_show").show();
            return false;
        }
        if(request != null)
            request.abort();
        $("#down_show").show();
        $("#down_show").html("<span style='padding: 5px 7px;'>"+$("#down_show").data("loading")+"</span>");
        clearTimeout(timeout);
        timeout = setTimeout("searchAjax()",500);
    });

    $("#down_show").delegate("ul>li","click",function (event) {
        var text = "";
        if($("#down_show").data("none")=="No relevant address"){
            text+=$(this).children("span").text();
            $(this).children("small").each(function () {
                text+=","+$(this).text();
            });
        }else{
            text+=$(this).children("small").text();
            text+=$(this).children("span").text();
        }
        $("#searchValue").val(text);
        $("#address").val(text);
        $("#address-two").show();
        $("#address-div").show();
        $("#down_show").hide();
        return false;
    });
    $("body").on("click",function () {
        $("#down_show").hide();
        //return false;
    });
    $("#none_address").on("click",function () {
        var text = $("#none_address").parent("p").text();
        text = text.split('（');
        $("#none_address").parent("p").html(text[0]);
        $("#searchValue").off("input propertychange click");
        $("#address-div").show();
        $("#searchValue").on("focus",function () {
            $("#searchValue").parent("div").removeClass("has-error");
            $("#searchValue").next(".error").remove();
        });
    });

    $("form").submit(function () {
        var address = $("#address").val();
        var dataUrl = $("form:first").attr("action");
        var dataJSON = {
            "id":$("#id").val(),
            "token":$("#token").val(),
            "storey":$("#storey").val(),
            "room_number":$("#room_number").val(),
            "address":address
        };
        //console.log(address);
        if(address == "" || address == undefined){
            $("#searchValue").parent("div").addClass("has-error");
            if($("#searchValue").next(".error").length !== 1){
                $("#searchValue").after("<label class='error'>"+$("#searchValue").data("error")+"</label>");
            }
            return false;
        }
        $.ajax({
            type: "post",
            url: dataUrl,
            data: dataJSON,
            dataType: "json",
            success: function(data){
                var html ="<div style='position: fixed;top: 0px;left: 0px;width: 100%;height: 100%;background: rgba(0,0,0,.3);z-index: 999'>" +
                    "<div class='load-order'>"+data.html+"</div></div>";
                $("body").append(html);
                $("body").on("click",function () {
                    window.location.href = data.url;
                });
                return false;
            }
        });
        return false;
    })
});

function searchAjax() {
    var searchValue = $("#searchValue").val();
    var dataUrl = $("#searchValue").data("href");
    var dataJSON = {
        "id":$("#id").val(),
        "token":$("#token").val(),
        "searchValue":searchValue
    };

    request = $.ajax({
        type: "post",
        url: dataUrl,
        data: dataJSON,
        dataType: "json",
        success: function(data){
            $("#searchValue").data("oldValue",searchValue);
            ajaxBool = true;
            if(data.status == 1){
                $("#down_show").data("nextToKen",data.nextToken);
                $("#down_show").data("href",dataUrl);
                $("#down_show").data("value",searchValue);
                $("#down_show").data("scroll","true");
                $("#down_show").show();
                $("#down_show").html("");
                $("#down_show").scrollTop(0);
                if(data.html == ""){
                    $("#down_show").html("<span>"+$("#down_show").data("none")+"</span>");
                }else{
                    $("#down_show").html("<ul>"+data.html+"</ul>");
                }
                //$("#kehu-div-ul").scrollTop(0);
            }else{
                alert("頁面異常，請刷新重試2");
            }
        },
        error:function () {
            $("#searchValue").data("oldValue",null);
        }
    });
}
