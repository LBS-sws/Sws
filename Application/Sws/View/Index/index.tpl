<h3 class="col-lg-12">{%$Think.lang.week_status%}：</h3>
<div class="col-lg-10 col-lg-offset-1">
    <div class="row">
        <div class="col-lg-6">
            <div class="index-box bg-primary">
                <p>{%$Think.lang.order_sum%}：</p>
                <p class="text-right"><strong>{%$count%}</strong></p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="index-box bg-info">
                <p>{%$Think.lang.order_load_sum%}：</p>
                <p class="text-right"><strong>{%$count2%}</strong></p>
            </div>
        </div>
    </div>
</div>
<h3 class="col-lg-12">{%$Think.lang.order_count%}：</h3>
<form class="col-lg-10 col-lg-offset-1" method="post" action="{%:U('/sws/index/downExcel')%}">
    <div class="col-lg-2 pb-15">
        <div class="row">
            <select class="form-control" id="city_id" name="city_id">
                <option value="0">{%$Think.lang.all_city%}</option>
                <foreach name="cityList" item="city_name" key="city_key" >
                    <option value="{%$city_key%}">{%$city_name%}</option>
                </foreach>
            </select>
        </div>
    </div>
    <div class="form-inline text-right">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><span class="fa fa-calendar"></span></div>
                <input type="text" class="form-control" id="start_date" name="start_date" placeholder="{%$Think.lang.start_date%}">
            </div>
        </div>
        <div class="form-group hidden-xs">
            <span class="form-control-static text-left">-</span>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><span class="fa fa-calendar"></span></div>
                <input type="text" class="form-control" id="end_date" name="end_date" placeholder="{%$Think.lang.end_date%}">
            </div>
        </div>
        <button type="button" id="export" class="btn btn-primary">{%$Think.lang.export%}</button>
    </div>
    <div class="canvas-div">
        <canvas width="1400" height="400" id="canvas" ></canvas>
    </div>
</form>

<js href="__PUBLIC__/sws/js/Chart.js?{%$Think.config.DEFINE.webVersions%}"/>
<js href="__PUBLIC__/js/bootstrap-datetimepicker.min.js"/>
<js href="__PUBLIC__/js/datetimepicker-lan.js?{%$Think.config.DEFINE.webVersions%}"/>
<script>
    $(function ($) {
        $('#start_date').datetimepicker({
            format: 'yyyy-mm-dd',
            language:'{%$lang%}',
            minView:'month',
            autoclose:true,
            todayBtn:true
        }).on('changeDate',function(ev){
            var starttime=$("#start_date").val();
            $("#end_date").datetimepicker('setStartDate',starttime);
        });
        $('#end_date').datetimepicker({
            format: 'yyyy-mm-dd',
            language:'{%$lang%}',
            minView:'month',
            autoclose:true,
            todayBtn:true
        }).on('changeDate',function(ev) {
            var endtime = $("#end_date").val();
            $("#start_date").datetimepicker('setEndDate', endtime);
        });

        $("#nav>ul>li:first").addClass("active");

        $("#city_id").on("change",function () {
            var $that = $(this);
            $.ajax({
                type: "post",
                url: "{%:U('/sws/index/ajaxTongJi')%}",
                data: {"city_id":$that.val()},
                dataType: "json",
                success: function(fs){
                    fs = fs["info"];
                    var data = {
                        title:"{%$thisYear%}{%:L('order_count_year')%}",
                        atrX:"{%:L('order_number')%}",
                        labels : fs["arrMonth"],
                        datasets : [
                            {
                                fillColor : "rgba(151,187,205,0.6)",
                                strokeColor : "rgba(151,187,205,1)",
                                pointColor : "rgba(151,187,205,1)",
                                pointStrokeColor : "#fff",
                                data : fs["arrCount"]
                            }
                        ]
                    };
                    var ctx = $("#canvas").get(0).getContext("2d");
                    var myNewChart = new Chart(ctx);
                    var options = {
                        datasetFill : false,
                        animation : false,
                        scaleFontSize : 16,
                    };
                    var line = myNewChart.Line(data,options);
                }
            });
        }).trigger("change");

        //導出excel
        $("#export").on("click",function () {
            $("#top-open-title").trigger("click");
            var start_time = $("#start_date").val();
            var end_time = $("#end_date").val();
            if(start_time == ""){
                openTopTitleDiv("<span class='error'>"+"{%$Think.lang.start_date%}{%$Think.lang.not_null%}"+"</span>");
            }else if(end_time == ""){
                openTopTitleDiv("<span class='error'>"+"{%$Think.lang.end_date%}{%$Think.lang.not_null%}"+"</span>");
            }else if(end_time < start_time){
                openTopTitleDiv("<span class='error'>"+"{%$Think.lang.start_end_error%}"+"</span>");
            }else{
                $("form:first").submit();
            }
        })
    });
</script>