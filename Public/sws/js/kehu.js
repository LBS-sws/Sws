$(function ($) {
    var ajaxBool = true; //一次只能一個請求
    $("#btn01,#btn02,#btn-ip").on("click",function () {
        var searchValue = $("#searchValue").val();
        var dataUrl = $(this).data("href");
        if(dataUrl == ""){
            alert("頁面異常，請刷新重試");
            return false;
        }
        if(ajaxBool){
            ajaxBool = false
        }else{
            return false;
        }
        if(searchValue=="" && $(this).attr("id") == "btn01"){
            dataUrl = $("#btn02").data("href");
        }
        if(dataUrl == $("#btn02").data("href")){
            searchValue = "";
        }
        var dataJSON = {
            "id":$("#id").val(),
            "token":$("#token").val(),
            "searchValue":searchValue
        };
        $.ajax({
            type: "post",
            url: dataUrl,
            data: dataJSON,
            dataType: "json",
            success: function(data){
                ajaxBool = true;
                if(data.status == 1){
                    $("#kehu-div-ul").data("page",1);
                    $("#kehu-div-ul").data("href",dataUrl);
                    $("#kehu-div-ul").data("value",searchValue);
                    $("#kehu-div-ul").data("scroll","true");
                    $("#kehu-div-ul").html(data.html);
                    $("#kehu-div-ul").scrollTop(0);
                }else{
                    alert("頁面異常，請刷新重試2");
                }
            },
            error:function () {
                ajaxBool = true;
            }
        });
    });

    $("#kehu-div-ul").delegate(".ajaxSelect","click",function () {
        var dataUrl = $("#btn02").data("href");
        var searchValue = $(this).data("value");
        var dataJSON = {
            "id":$("#id").val(),
            "token":$("#token").val(),
            "searchValue":searchValue
        };
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
                    $("#kehu-div-ul").data("page",1);
                    $("#kehu-div-ul").data("href",dataUrl);
                    $("#kehu-div-ul").data("value",searchValue);
                    $("#kehu-div-ul").data("scroll","true");
                    $("#kehu-div-ul").html(data.html);
                    $("#kehu-div-ul").scrollTop(0);
                }else{
                    alert("頁面異常，請刷新重試2");
                }
            },
            error:function () {
                ajaxBool = true;
            }
        });
    });

    $("#kehu-div-ul").delegate(".okSelect","click",function () {
        var address = $(this).parents(".media:first").find(".media-body:first").text();
        $("#address-div").show();
        $("#address-span>b").text(address);
        $("#address").val(address);
    });

    $("#kehu-div-ul").scroll(function () {
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
                        $("#kehu-div-ul").data("page",page);
                        $("#kehu-div-ul").append(data.html);
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

    $("form").submit(function () {
        var address = $("#address").val();
        var dataUrl = $("form:first").attr("action");
        var dataJSON = {
            "id":$("#id").val(),
            "token":$("#token").val(),
            "address":address
        };
        if(address == "" || address == undefined){
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
