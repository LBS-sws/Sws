<div class="flow-bg" id="order_acc">
    <div class="col-sm-offset-4 col-sm-4 flow-div">
        <h3>{%$Think.lang.btn_service%}</h3>
        <form class="form-horizontal" method="post" id="order_acc_form" action="{%:U('/sws/order/service')%}">
            <input name="id" type="hidden" value="{%$orderList.id|default=''%}">
            <div class="form-group">
                <label class="control-label col-lg-3">{%$Think.lang.address%}：</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control required" name="address" value="{%$orderList.address|htmlspecialchars_decode%}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3">{%$Think.lang.service_time%}：</label>
                <div class="col-lg-4">
                    <div class="input-group">
                        <div class="input-group-addon"><span class="fa fa-calendar"></span></div>
                        <input type="text" class="form-control required" id="start_date" name="service_time" placeholder="{%$Think.lang.start_date%}" value="{%$orderList.service_time%}">
                    </div>
                </div>
                <label class="control-label pull-left  hidden-xs">-</label>
                <div class="col-lg-4">
                    <div class="input-group">
                        <div class="input-group-addon"><span class="fa fa-calendar"></span></div>
                        <input type="text" class="form-control required" id="end_date" name="service_time_end" placeholder="{%$Think.lang.end_date%}" value="{%$orderList.service_time_end%}">
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-lg-3">{%$Think.lang.total_price%}：</label>
                <div class="col-lg-8 form-control-static">
                    <input type="number" min="0" class="number form-control required" name="total_price" value="{%$orderList.total_price%}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary pull-right">{%$Think.lang.submit%}</button>
                    <button type="button" class="btn btn-default pull-left btn-close">{%$Think.lang.close%}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(function () {
        $("#order_acc_form").validate();
        $(".btn_finish").on("click",function () {
            $("#order_acc_form").attr("action","{%:U('/sws/order/finish')%}");
            $("#order_acc").show();
            var maxHeight=$(window).height();
            var height = $("#order_acc>div").height();
            var top = (maxHeight-height)/2 +"px";
            $("#order_acc>div").css("top",(-1*height+"px")).stop().animate({
                top:top,
                opacity:1
            }, 500);
        });
        $("#btn_service").on("click",function () {
            $("#order_acc_form").attr("action","{%:U('/sws/order/service')%}");
            $("#order_acc").show();
            var maxHeight=$(window).height();
            var height = $("#order_acc>div").height();
            var top = (maxHeight-height)/2 +"px";
            $("#order_acc>div").css("top",(-1*height+"px")).stop().animate({
                top:top,
                opacity:1
            }, 500);
        });
        $("#order_acc,#order_acc .btn-close").on("click",function () {
            var maxHeight=$(window).height();
            var height = $("#order_acc>div").height();
            $("#order_acc>div").stop().animate({
                top:-1*height+"px",
                opacity:0
            }, 500,function () {
                $("#order_acc").hide();
            });
        });
        $("#order_acc>div").on("click",function () {
            return false;
        });
        $('#start_date').datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            language:'{%$lang%}',
            autoclose:true,
            todayBtn:true
        }).on('changeDate',function(ev){
            var starttime=$("#start_date").val();
            $("#end_date").datetimepicker('setStartDate',starttime);
        });
        $('#end_date').datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            language:'{%$lang%}',
            autoclose:true,
            todayBtn:true
        }).on('changeDate',function(ev) {
            var endtime = $("#end_date").val();
            $("#start_date").datetimepicker('setEndDate', endtime);
        });
        $("#order_acc_form button[type='submit']").on("click",function () {
            $("#order_acc_form").submit();
        });
    })
</script>