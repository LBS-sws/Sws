$(function ($) {
    var languageCookie = $.cookie("think_language").toLowerCase();
    switch (languageCookie){
        case "zh-tw":
            var otherStr = "其他";
            var Indoor_area = "建築面積：";
            var Indoor_area_a = "實用面積：";
            break;
        case "en-us":
            var otherStr = "Others";
            var Indoor_area = "Gross Floor Area：";
            var Indoor_area_a = "Saleable Area：";
            break;
        default:
            var otherStr = "其他";
            var Indoor_area = "建筑面积：";
            var Indoor_area_a = "实用面积：";
    }
    //城市變化
    $("#cityName").on("change",function () {
        var city_id = $(this).val();
        var area_id = $("select[name='area_id']").val();
        var that = this;
        var dataUrl = $(this).data("url");
        if(dataUrl == ""||dataUrl==undefined||dataUrl==null){
            alert("城市不存在！請刷新頁面");
            return false;
        }
        if(city_id == ""){
            //$(this).parents("div.row:first").next("div.row").slideUp(100);
        }
        $.ajax({
            type: "post",
            url: dataUrl,
            data: {"city_id":city_id},
            dataType: "json",
            success: function(data){
                if(data.status == 1){
                    resetArea(data["areaList"],data["prefix"]);
                    resetBusiness(data["businessList"],data["prefix"]);
                    if(data["otherBool"] == 1){
                        if(area_id === 0||area_id === '0'){
                            $("select[name='area_id']").append("<option value='0' selected>"+otherStr+"</option>");
                        }else{
                            $("select[name='area_id']").append("<option value='0'>"+otherStr+"</option>");
                        }
                    }
                    $("#door_unit").text("（"+data["unit"]+"）");
                    $("#cityName").parents("div.row:first").next("div.row").slideDown(100);
                }else{
                    //alert("城市不存在！請刷新頁面");
                    resetArea(false);
                    resetBusiness(data["businessList"],data["prefix"]);
                }
            }
        });
    });
    //業務變化
/*    $("#businessDiv").delegate(".onlyBusiness","change",function () {
        if($(this).is(':checked')) {
            var index = $(this).data("type");
            if(isNaN(index)){
                alert("error:page error");
                return false;
            }
            index = 1-parseInt(index,10);
            $("#businessDiv input[data-type='"+index+"']").prop("disabled",true);
        }else{
            if($("#businessDiv input:checked").length < 1){
                $("#businessDiv input").prop("disabled",false);
            }
        }
    });*/

    var cityObject = [];
    $("#cityName>option").each(function () {
        var id = $(this).val();
        if(id == ""){
            return true;
        }
        cityObject.push({
            "city_id":$(this).val(),
            "currency_type":$(this).data(""),
            "region_id":$(this).data("region"),
            "city_name":$(this).text(),
            "selected":$(this).prop("selected")
        });
    });

    //地區變化
    $("#region").on("change",function () {
        var region_id = $(this).val();
        var cn = $(this).find("option:selected").data("cn");
        if(cn == "cn"||cn == "CN"||cn == "Cn"||cn == "cN"){
            $("#indoor_area").text(Indoor_area);
        }else{
            $("#indoor_area").text(Indoor_area_a);
        }
        $("#cityName").html("<option value=''></option>");
        $.each(cityObject, function(key, val){
            if(val["region_id"] == region_id){
                if(val["selected"]){
                    cityObject[key]["selected"]=false;
                    $("#cityName").append("<option selected value='"+val["city_id"]+"' data-currency='"+val["currency_type"]+"'>"+val["city_name"]+"</option>");
                }else{
                    $("#cityName").append("<option value='"+val["city_id"]+"' data-currency='"+val["currency_type"]+"'>"+val["city_name"]+"</option>");
                }
            }
        });
        $("#cityName").trigger("change");
    }).trigger("change");

    //客戶類別變化
    $('select[name="house_type"]').on("change",function () {
        if($(this).val() == 1){
            window.open("http://www.lbshygiene.com.hk/tc/order","_blank");
            $(this).val(0);
        }
    })
});

//區域隨城市變化
function resetArea(areaList,prefix){
    var id = $("select[name='area_id']").val();
    $("select[name='area_id']").html("<option value=''></option>");
    if(areaList){
        for(var i = 0;i<areaList.length;i++){
            var data = areaList[i];
            if(id == data["id"]){
                $("select[name='area_id']").append("<option selected value='"+data["id"]+"' data-price='"+data["area_price"]+"'>"+data["area_name"+prefix]+"</option>");
            }else{
                $("select[name='area_id']").append("<option value='"+data["id"]+"' data-price='"+data["area_price"]+"'>"+data["area_name"+prefix]+"</option>");
            }
        }
    }
}

//業務隨城市變化
function resetBusiness(businessList,prefix){
    var lan = $.cookie("think_language").toLowerCase();
    switch (lan){
        case "zh-tw":
            var noneEx = "暫無業務";
            var noneRegion = "請選擇地區";
            var noneCity = "請選擇城市";
            var noneArea = "請選擇區域";
            var ptEx = "普通業務";
            var tsEx = "特殊業務";
            break;
        case "en-us":
            var noneRegion = "Please select the Region";
            var noneCity = "Please select the City";
            var noneArea = "Please select the Area";
            var noneEx = "None Business";
            var ptEx = "Common Business";
            var tsEx = "Special Business";
            break;
        default:
            var noneRegion = "请选择地区";
            var noneCity = "请选择城市";
            var noneArea = "请选择区域";
            var noneEx = "暂无业务";
            var ptEx = "普通业务";
            var tsEx = "特殊业务";
    }
    if(businessList){
        $("#businessDiv").html('');
        var idStr = $("#businessDiv").attr("data");
        var dataType = $("#businessDiv").data("type");
        $("#businessDiv").removeAttr("data");
        for(var i = 0;i<businessList.length;i++){
            var data = businessList[i];
            var html = '<label class="checkbox-inline">';
            var str =","+data['id']+",";
            if(idStr != undefined && idStr != null && idStr != ""){
                if(dataType == undefined || dataType == data["type"]){
                    if(idStr.indexOf(str) != -1){
                        html+='<input checked type="checkbox" data-type="'+data["type"]+'" class="onlyBusiness required" name="business_id[]" value="'+data['id']+'">';
                    }else {
                        html+='<input type="checkbox" data-type="'+data["type"]+'" class="onlyBusiness required" name="business_id[]" value="'+data['id']+'">';
                    }
                }else{
                    html+='<input type="checkbox" disabled data-type="'+data["type"]+'" class="onlyBusiness required" name="business_id[]" value="'+data['id']+'">';
                }
            }else {
                if(dataType == undefined || dataType == data["type"]){
                    html+='<input type="checkbox" data-type="'+data["type"]+'" class="onlyBusiness required" name="business_id[]" value="'+data['id']+'">';
                }else{
                    html+='<input type="checkbox" disabled data-type="'+data["type"]+'" class="onlyBusiness required" name="business_id[]" value="'+data['id']+'">';
                }
            }
            html+=data['name'+prefix]+'</label>';
            $("#businessDiv").append(html);
        }
    }else{
        if($("#region").val() == ""){
            $("#businessDiv").html('<div class="form-control-static">'+noneRegion+'</div>');
        }else if ($("#cityName").val() == ""){
            $("#businessDiv").html('<div class="form-control-static">'+noneCity+'</div>');
        }else{
            $("#businessDiv").html('<div class="form-control-static">'+noneEx+'</div>');
        }
    }
}