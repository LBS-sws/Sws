
function openHindWindow(html) {
    var body = "";
    body+="<div class='hind-bg' id='hind_open_div'><div class='hind-div'>"+html+"</div></div>";
    $("body").append(body);
    $("#hind_open_div").show();
    var height = $("#hind_open_div>.hind-div").height();
    height = (-1*height/2)+"px";
    $("#hind_open_div>.hind-div").stop().animate({
        opacity:1,
        marginTop:height
    },500);
    $("#hind_open_div").on("click",function () {
        $(this).off("click").remove();
    });
    $("#hind_open_div>.hind-div").on("click",function () {
        return false;
    });
}
function setAddressToData(data) {
    if (data == ""||data==null){
        var $region = $("#city_open_div .scroll-city").eq(0).find("li.active:first");
        var $city = $("#city_open_div .scroll-city").eq(1).find("li.active:first");
        var $area = $("#city_open_div .scroll-city").eq(2).find("li.active:first");
        $("#region").val($region.data("id"));
        $("#city").val($city.data("id"));
        $("#area").val($area.data("id"));
        $("#address").val('');
        $("#latitude").val('');
        $("#longitude").val('');
        $("#address_show").val($region.text()+" "+$city.text()+" "+$area.text());
    }else {
        $("#region").val(data["region"]);
        $("#city").val(data["city"]);
        $("#area").val(data["area"]);
        $("#address").val(data["address"]);
        $("#address_show").val(data["address"]);
        $("#latitude").val(data["res"]["latitude"]);
        $("#longitude").val(data["res"]["longitude"]);
    }
    $("#city_open_div").hide();
}

function createCityChangeDiv(data) {
    var body = "";
    body+="<div class='hind-bg' id='city_open_div'><div class='city-bg-div'>";
    body+="<ul class='city-title-top'><li class='text-left'><button class='btn btn-default btn-close'>取消</button></li><li class='text-center  btn-marker'><span class='glyphicon glyphicon-map-marker'></span><span>&nbsp;当前位置</span></li><li class='text-right'><button class='btn btn-primary btn-ok'>确定</button></li></ul>"
    body+="<ul class='city-title-name'><li><span>地区</span></li><li><span>城市</span></li><li><span>区域</span></li></ul>";
    body+="<div class='city-change-div' onselectstart='return false'>";
    body+="<div class='scroll-city'><span class='scroll-hidden'></span><ul class='list-unstyled'><li>北京1</li><li>北京2</li></ul></div>";
    body+="<div class='scroll-city'><span class='scroll-hidden'></span><ul class='list-unstyled'><li>北京1</li><li>北京2</li><li>北京3</li></ul></div>";
    body+="<div class='scroll-city'><span class='scroll-hidden'></span><ul class='list-unstyled'><li>北京1</li><li>北京2</li><li>北京3</li><li>北京4</li></ul></div>";
    body+="</div>";
    body+="</div></div>";


    $("body").append(body);
    $("#address_show").on("click",function () {
        $("#city_open_div").show();
    });
    $("#city_open_div,#city_open_div .btn-close").on("click",function () {
        $("#city_open_div").hide();
    });
    $("#city_open_div .btn-ok").on("click",function () {
        setAddressToData('');
    });
    $("#city_open_div>.city-bg-div").on("click",function () {//防止事件冒泡
        return false;
    });
    $("#city_open_div").show();
    changeCity(0,data);
    $("#city_open_div").hide();

    //滾輪滑動事件
    var timer=0;
    $(".scroll-city>ul").on("touchstart",function () {
        $(this).data("start",0);
    });
    $(".scroll-city>ul").on("touchend",function () {
        $(this).data("start",1);
    });
    $(".scroll-city>ul").on("scroll",function () {
        var $that = $(this);
        var oldHeight = $(this).scrollTop();
        var oneHeight = $that.find("li:first").height();
        var thisNum = Math.round(oldHeight/oneHeight);
        var liNum = $(this).find("li").length;
        //$(this).find("li").eq(thisNum+2).addClass("active").siblings().removeClass("active");
        if(timer!=0){
            clearInterval(timer);// 每次滚动前 清除一次
        }
        if(liNum>9){ //數量少於4個則不做循環滾動
            if(oldHeight/oneHeight == 0){
                oldHeight = oneHeight*(liNum-6);
                $(this).stop().animate({scrollTop:oldHeight+"px"},0);
            }
            if(oldHeight/oneHeight == liNum-5){
                oldHeight = oneHeight;
                $(this).stop().animate({scrollTop:oldHeight+"px"},0);
            }
        }

        timer = setInterval(function() {
            var nowHeight = $that.scrollTop();
            var start = $that.data("start");
            if (nowHeight == oldHeight&&start == 1) { //滾動條停止
                clearInterval(timer);
                var num = Math.round(nowHeight/oneHeight);
                //num=num==0;
                $that.find("li").eq(num+2).addClass("active").siblings().removeClass("active");
                nowHeight = num*oneHeight+"px";
                $that.stop().animate({scrollTop:nowHeight},50);

                var index = $that.parent(".scroll-city").index();
                var thisData = data;
                index++;
                num = $that.find("li").eq(num+2).data("id");
                if(index == 1){
                    thisData = data[num]["list"];
                }else if (index == 2){
                    var key =$(".scroll-city:first>ul>li.active").data("id");
                    thisData = data[key]["list"][num]["list"];
                }
                changeCity(index,thisData);
                //resetScrollUl(true);
            }else {
                oldHeight = nowHeight;
            }
        }, 100);
    });
}

function changeCity(num,data) {
    if(num<3){
        var list = null;
        if (data != null){
            var $ul = $('.city-change-div>.scroll-city').eq(num).find("ul:first");
            $ul.html("");
            console.log(num);
            for (var key in data){
                var html = "";
                if (list == null){
                    list = data[key]["list"];
                }
                if(num == 1){
                    html=" data-unit='"+data[key]["b_unit"]+"'";
                }
                $ul.append("<li data-id='"+data[key]["id"]+"'"+html+">"+data[key]["name"]+"</li>");
            }
            resetScrollUl($ul);

            if(num == 1){ //城市單位
                console.log($ul.find("li.active:first").data("unit"));
                $("#door_in").text($ul.find("li.active:first").data("unit"));
            }
        }
        num++;
        changeCity(num,list);
    }
}

//滾輪補全
function resetScrollUl($ul) {
    var liNum = $ul.find("li").length;
    //$ul.find("li:first").addClass("active");
    var oneHeight = $ul.find("li:first").addClass("active").height();
    if (liNum >0&&liNum<5){
        $ul.append("<li>&nbsp;</li><li>&nbsp;</li>");
        $ul.prepend("<li>&nbsp;</li><li>&nbsp;</li>");
    }else if (liNum>=5){
        var html = $ul.find("li:gt("+(liNum-4)+")").clone();
        $ul.append($ul.find("li:lt(3)").clone());
        $ul.prepend(html);
        $ul.stop().animate({scrollTop:oneHeight+"px"},0);
    }else {
        $ul.append("<li>&nbsp;</li>");
    }
}
