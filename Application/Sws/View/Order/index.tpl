<div class="container-sc">
    <h2>{%$Think.lang.order_list%}</h2>
    <div class="col-xs-12">
        <table class="table table-hover table-bordered" id="example">
            <thead>
            <tr>
                <th width="9%">{%$Think.lang.order_code%}</th>
                <th width="9%">{%$Think.lang.order_name%}</th>
                <th width="7%" class="hidden-xs">{%$Think.lang.appellation%}</th>
                <th width="10%" class="hidden-xs">{%$Think.lang.phone%}</th>
                <th width="9%">{%$Think.lang.city_name%}</th>
                <th width="9%" class="hidden-xs">{%$Think.lang.area_name%}</th>
                <th width="19%" class="hidden-xs">{%$Think.lang.Infestation%}</th>
                <th width="11%">{%$Think.lang.order_time%}</th>
                <th width="10%">{%$Think.lang.order_status%}</th>
                <th width="7%" class="text-center">{%$Think.lang.operation%}</th>
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
                    <td>{%$list.status|L%}</td>
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
<js href="__PUBLIC__/sws/js/tableMessage.js?{%$Think.config.DEFINE.webVersions%}"/>