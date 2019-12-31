<div class="container-sc">
    <ol class="breadcrumb">
        <li><a href="{%:U('/sws/index/index')%}">{%$Think.lang.home%}</a></li>
        <li><a href="{%:U('/sws/order/index')%}">{%$Think.lang.order_manage%}</a></li>
        <li class="active">{%$Think.lang.detail%}</li>
    </ol>
    <div class="col-xs-12">
        <form action="{%:U('/sws/order/save')%}" class="form-horizontal" method="post" id="firstForm">
            <input name="id" type="hidden" value="{%$orderList.id|default=''%}">
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.order_code%}：</label>
                <div class="col-lg-4 form-control-static">
                    {%$orderList.s_code%}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.order_name%}：</label>
                <div class="col-lg-4 form-control-static">
                    {%$orderList.order_name%}（{%$orderList.appellation_ns%}）
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.kehu_lang%}：</label>
                <div class="col-lg-4 form-control-static">
                    <switch name="orderList.kehu_lang">
                        <case value="zh-cn">中文（简体）</case>
                        <case value="zh-tw">中文（繁體）</case>
                        <case value="en-us">English</case>
                        <default />无
                    </switch>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.email%}：</label>
                <div class="col-lg-2 form-control-static">
                    {%$orderList.email%}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.phone%}：</label>
                <div class="col-lg-4 form-control-static">
                    {%$orderList.phone%}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.city_name%}：</label>
                <div class="col-lg-4 form-control-static">
                    {%$orderList["city_name$prefix"]%}&nbsp;-&nbsp;{%$orderList["area_name$prefix"]%}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.address%}：</label>
                <div class="col-lg-4 form-control-static">
                    {%$orderList.address%}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.service_time%}：</label>
                <div class="col-lg-4 form-control-static">
                    {%$orderList.service_time%} {%$Think.lang.to%} {%$orderList.service_time_end%}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">
                    <eq name="orderList.web_prefix|strtolower" value="cn">
                        {%$Think.lang.Indoor_area%}：
                        <else/>
                        {%$Think.lang.Indoor_area_a%}：
                    </eq>
                </label>
                <div class="col-lg-4 form-control-static">
                    {%$orderList.door_in%}（{%$orderList.b_unit_ns%}）
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.car_fare%}：</label>
                <div class="col-lg-4 form-control-static">
                    {%$orderList.area_price%}（{%$orderList.currency_type%}）
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.business_min_price%}：</label>
                <div class="col-lg-4 form-control-static">
                    {%$orderList.min_price%}（{%$orderList.currency_type%}）
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.Infestation%}：</label>
                <div class="col-lg-8">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                        <tr>
                            <td>{%$Think.lang.business_name%}</td>
                            <td>{%$Think.lang.business_type%}</td>
                            <td>{%$Think.lang.a%}{%$orderList.b_unit|L%}{%$Think.lang.price%}（{%$orderList.currency_type%}）</td>
                            <td class="text-right">{%$Think.lang.total_price%}（{%$orderList.currency_type%}）</td>
                        </tr>
                        </thead>
                        <tbody>
                        <volist name="orderList.business_list" id="list">
                            <tr>
                                <td>{%$list["name$prefix"]%}</td>
                                <eq name="list.type" value="1">
                                    <td>{%$Think.lang.general_business%}</td>
                                    <else/>
                                    <td>{%$Think.lang.special_business%}</td>
                                </eq>
                                <td>{%$list.price%}</td>
                                <td class="text-right">{%$list.total_price%}</td>
                            </tr>
                        </volist>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                            <td class="text-right fa-2x">{%$orderList.total_price%}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">{%$Think.lang.order_time%}：</label>
                <div class="col-lg-2 form-control-static">
                    {%$orderList.lcd%}
                </div>
                <label class="control-label col-lg-1">{%$Think.lang.apply_lcu%}：</label>
                <div class="col-lg-1 form-control-static">
                    <eq name="orderList.lcu" value="客戶">
                        {%$Think.lang.client%}
                        <else/>
                        {%$orderList.lcu%}
                    </eq>
                </div>
                <label class="control-label col-lg-1">{%$Think.lang.from_order%}：</label>
                <div class="col-lg-1 form-control-static">
                    {%$orderList.from_order%}
                </div>
                <label class="control-label col-lg-1">{%$Think.lang.order_status%}：</label>
                <div class="col-lg-1 form-control-static text-{%$orderList.style%}">
                    {%$orderList.status|L%}
                </div>
            </div>

            <if condition="$orderList.status == 'reject'">
                <div class="form-group has-error">
                    <label class="control-label col-lg-2">{%$Think.lang.reject_remark%}：</label>
                    <div class="col-lg-4 form-control-static error">
                        {%$orderList.remark%}
                    </div>
                </div>
            </if>

            <div class="form-group">
                <div class="col-lg-8 col-lg-offset-2">
                    <neq name="orderList.status" value="finish">
                        <a class="btn btn-warning" href="{%:U('/sws/order/edit',array('index'=>$orderList['id']))%}">{%$Think.lang.update%}</a>
                    <else/>
                        <a class="btn btn-danger" id="orderBackLink" data-href="{%:U('/sws/order/back',array('index'=>$orderList['id']))%}">{%$Think.lang.order_back%}</a>
                    </neq>
                    <if condition="$orderList.status == 'reject' ||$orderList.status == 'modified' || $orderList.status == 'hesitate' || $orderList.status == 'send'">
                        <!--<button type="button" id="btn_service" class="btn btn-primary btn_service">{%$Think.lang.btn_service%}</button>-->
                        <button type="button" class="btn btn-success btn_finish">{%$Think.lang.btn_finish%}</button>
                    </if>
                    <if condition="$orderList.status == 'service' || $orderList.status == 'guest_service'">
                        <button type="button" class="btn btn-success btn_finish">{%$Think.lang.btn_finish%}</button>
                    </if>
                    <a class="btn btn-default" href="{%:U('/sws/order/index')%}">{%$Think.lang.btn_back%}</a>
                    <neq name="orderList.status" value="finish">
                        <button type="button" class="btn btn-danger btn_reject pull-right">{%$Think.lang.reject%}</button>
                    </neq>
                    <button type="button" id="btn-flow" style="margin-right: 5px;" class="btn btn-default pull-right">{%$Think.lang.flow%}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--拒絕彈出-->
<div class="open-div">
    <div class="col-lg-4 col-lg-offset-4">
        <div class="alert bg-fff alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p class="text-center">&nbsp;</p>
            <form class="form-horizontal" method="post" id="lastForm" action="{%:U('/sws/order/reject')%}">
                <input name="id" type="hidden" value="{%$orderList.id|default=''%}">
                <div class="form-group">
                    <label class="col-lg-3 control-label">{%$Think.lang.reject_remark%}：</label>
                    <div class="col-lg-9">
                        <textarea name="remark" class="form-control required" rows="5"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-12 text-center">
                        <button type="submit" class="btn btn-primary">{%$Think.lang.submit%}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--流程彈出-->
<include file="Site/flow" />

<!--接受功能彈出-->
<include file="Site/order_acc" btn_id="btn_service"/>
<script>
    cookieLanguage = '{%$Think.LANG_SET%}'.toLowerCase();
    $(function ($) {
/*        $(".btn_service").on("click",function () {
            $("#firstForm").attr("action","{%:U('/sws/order/service')%}");
            $("#firstForm").submit();
        });*/
        $(".btn_reject").on("click",function () {
            $(".open-div").show();
        });
        $(".open-div>div").on("click",function () {
            return false;
        });
        $(".open-div,.alert>.close").on("click",function () {
            $(".open-div").hide();
        });

        $("#lastForm .btn").on("click",function () {
            $("#lastForm").submit();
        });
        $("#firstForm").validate();
        $("#lastForm").validate();
        
        $("#orderBackLink").on("click",function () {
            if(confirm("{%$Think.lang.order_back_title%}")){
                var href = $(this).data("href");
                window.location.href = href;
            }
        })
    });
</script>
<js href="__PUBLIC__/sws/js/jquery.validate.min.js"/>
<js href="__PUBLIC__/sws/js/jquery.validate.js?{%$Think.config.DEFINE.webVersions%}"/>
<js href="__PUBLIC__/js/bootstrap-datetimepicker.min.js"/>
<js href="__PUBLIC__/js/datetimepicker-lan.js?{%$Think.config.DEFINE.webVersions%}"/>