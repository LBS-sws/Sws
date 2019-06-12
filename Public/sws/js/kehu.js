$(function ($) {
    var ajaxBool = true; //一次只能一個請求
    var request; //一次只能一個請求

    $("#down_show").scroll(function () {
        var nScrollHight = $(this)[0].scrollHeight - $(this).height();
        var nScrollTop = $(this)[0].scrollTop;
        var dataUrl = $(this).data("href");
        var page = $(this).data("page");
        var searchValue = $(this).data("value");
        var scroll = $(this).data("scroll");
        page = parseInt(page,10);
        page++;
        var dataJSON = {
            "id":$("#id").val(),
            "token":$("#token").val(),
            "page":page,
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
                        $("#down_show").data("page",page);
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

    $("#searchValue").on("input propertychange focus",function () {
        var searchValue = $("#searchValue").val();
        var dataUrl = $(this).data("href");
        var dataJSON = {
            "id":$("#id").val(),
            "token":$("#token").val(),
            "searchValue":searchValue
        };
        if(request != null)
            request.abort();
        request = $.ajax({
                type: "post",
                url: dataUrl,
                data: dataJSON,
                dataType: "json",
                success: function(data){
                    ajaxBool = true;
                    if(data.status == 1){
                        $("#down_show").data("page",1);
                        $("#down_show").data("href",dataUrl);
                        $("#down_show").data("value",searchValue);
                        $("#down_show").data("scroll","true");
                        $("#down_show").show();
                        if(data.html == ""){
                            $("#down_show").html("<span>沒有相關地址</span>");
                        }else{
                            $("#down_show").html("<ul>"+data.html+"</ul>");
                        }
                        //$("#kehu-div-ul").scrollTop(0);
                    }else{
                        alert("頁面異常，請刷新重試2");
                    }
                },
                error:function () {
                }
            });
    });

    $("#down_show").delegate("ul>li","click",function (event) {
        $("#searchValue").val($(this).text());
        $("#address").val($(this).text());
        $("#address-two").show();
        $("#address-div").show();
    });
    $("body").on("click",function () {
        $("#down_show").hide();
    });
    $("#none_address").on("click",function () {
        $("#searchValue").off("input propertychange focus");
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
