<div class="container-sc">
    <h2>{%$Think.lang.order_list%}</h2>
    <div class="col-xs-12">
        <table class="table table-hover table-bordered" id="example">
            <thead>
            <tr>
                <th width="9%" data-data="s_code">{%$Think.lang.order_code%}</th>
                <th width="9%" data-data="order_name">{%$Think.lang.order_name%}</th>
                <th width="6%" data-data="appellation_ns" class="hidden-xs">{%$Think.lang.appellation%}</th>
                <th width="9%" data-data="phone" class="hidden-xs">{%$Think.lang.phone%}</th>
                <th width="7%" data-data="city_name">{%$Think.lang.city_name%}</th>
                <th width="7%" data-data="area_name" class="hidden-xs">{%$Think.lang.area_name%}</th>
                <th width="16%" data-data="business_name" data-orderable="false" class="hidden-xs">{%$Think.lang.Infestation%}</th>
                <th width="10%" data-data="lcd">{%$Think.lang.order_time%}</th>
                <th width="7%" data-data="from_order">{%$Think.lang.from_order%}</th>
                <th width="11%" data-data="status">{%$Think.lang.order_status%}</th>
                <th width="7%" data-data="operation" data-orderable="false"  data-searchable="false" class="text-center">{%$Think.lang.operation%}</th>
            </tr>
            </thead>
            <tbody>
            <volist name="orderList" id="list">
                <tr class="{%$list.style%}">
                    <td>{%$list.s_code%}</td>
                    <td>{%$list.order_name%}</td>
                    <td class="hidden-xs">{%$list.appellation_ns%}</td>
                    <td class="hidden-xs">{%$list.phone%}</td>
                    <td>{%$list["city_name$prefix"]%}</td>
                    <td class="hidden-xs">{%$list["area_name$prefix"]%}</td>
                    <td class="hidden-xs">{%$list.business_name%}</td>
                    <td>{%$list.lcd%}</td>
                    <td>{%$list.from_order%}</td>
                    <td>{%$list.status%}</td>
                    <td class="text-center">
                        <a href="{%:U('/sws/order/detail',array('index'=>$list['id']))%}">
                            {%$Think.lang.detail%}
                        </a>
                    </td>
                </tr>
            </volist>
            </tbody>
        </table>
    </div>
</div>
<js href="__PUBLIC__/sws/js/jquery.dataTables.min.js"/>
<js href="__PUBLIC__/sws/js/jquery.dataTables.js?{%$Think.config.DEFINE.webVersions%}"/>
<!--
<js href="__PUBLIC__/sws/js/tableMessage.js?{%$Think.config.DEFINE.webVersions%}"/>
-->
<style>
    #example_processing{ padding-top: 7px;text-align: center;font-weight: 700;}
</style>
<script>
    function goHome() {
        window.location.href="{%:U('/sws/index/index')%}"
    }
    $(function () {
        var targets = $("#example>thead>tr>th").length;
        targets-=1;
        var pos = {
            "processing": true,
            "serverSide": true,
            "ajax" : "{%:U('/sws/order/ajaxLoad')%}",
            "columnDefs": [
                {
                    "targets": [targets],
                    "data": "id",
                    "render": function(data, type, full) {
                        //console.log(data);
                        var url = "{%:U('/sws/order/detail',array('index'=>"DATA_ID"))%}";
                        url = url.replace("DATA_ID",data);
                        return "<a href='"+url+"'>{%$Think.lang.detail%}</a>";
                    }
                }
            ],
            "createdRow":function(row,data,index){
                $('td',row).eq(3).addClass("hidden-xs");
                $('td',row).eq(4).addClass("hidden-xs");
                $('td',row).eq(6).addClass("hidden-xs");
                $('td',row).eq(7).addClass("hidden-xs");
                $('td',row).eq(10).addClass("text-center");
            },
        };
        $("#example").dataTable(pos);
    })
</script>