<div class="flow-bg" id="flow-div">
    <div class="col-sm-offset-4 col-sm-4 flow-div">
        <h3>{%$Think.lang.flow%}</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>{%$Think.lang.order_code%}</th>
                <th>{%$Think.lang.status_lcu%}</th>
                <th>{%$Think.lang.status_lcu_time%}</th>
                <th>{%$Think.lang.status%}</th>
            </tr>
            </thead>
            <tbody>
            <foreach name="historyList" item="item">
                <tr>
                    <td>{%$item.s_code%}</td>

                    <eq name="item.lcu" value="客戶">
                        <td>{%$Think.lang.client%}</td>
                        <else/>
                        <td>{%$item.lcu%}</td>
                    </eq>
                    <td>{%$item.lcd%}</td>
                    <if condition="($item.status eq 'new') AND ($item.s_type eq 0)">
                        <td>{%$Think.lang.order_status_sales%}</td>
                        <else />
                        <if condition="($item.status eq 'update')">
                            <td>{%$Think.lang.quo_update%}</td>
                            <else />
                            <td>{%$item.status|L%}</td>
                        </if>
                    </if>
                </tr>
            </foreach>
            </tbody>
        </table>
        <div class="col-lg-12">
            <div class="row pb-15">
                <button class="btn btn-default pull-right btn-close">{%$Think.lang.close%}</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#btn-flow").on("click",function () {
            $("#flow-div").show();
            var maxHeight=$(window).height();
            var height = $("#flow-div>div").height();
            var top = (maxHeight-height)/2 +"px";
            $("#flow-div>div").stop().animate({
                top:top,
                opacity:1
            }, 500);
        });
        $("#flow-div,#flow-div .btn-close").on("click",function () {
            var maxHeight=$(window).height();
            $("#flow-div>div").stop().animate({
                top:maxHeight+"px",
                opacity:0
            }, 500,function () {
                $("#flow-div").hide();
            });
        });
        $("#flow-div>div").on("click",function () {
            return false;
        })
    })
</script>