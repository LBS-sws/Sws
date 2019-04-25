<div class="container-sc">
    <h2>{%$Think.lang.area_list%}</h2>
    <div class="col-xs-12">
        <div class="pull-right ml-10">
            <a class="btn btn-primary" href="{%:U('/sws/area/add')%}">{%$Think.lang.add%}</a>
        </div>
        <table class="table table-hover table-bordered table-striped" id="example">
            <thead>
            <tr>
                <th>{%$Think.lang.area_name%}</th>
                <th>{%$Think.lang.city_name%}</th>
                <th>{%$Think.lang.car_fare%}</th>
                <th>{%$Think.lang.business_min_price%}</th>
                <th>{%$Think.lang.z_index%}</th>
                <th class="text-center">{%$Think.lang.operation%}</th>
            </tr>
            </thead>
            <tbody>
            <volist name="areaList" id="list">
                <tr>
                    <td>{%$list[$name]|default='未设置'%}</td>
                    <td>{%$list[$cityName]|default='未设置'%}</td>
                    <td>（{%$list.currency_type%}）{%$list.area_price%}</td>
                    <td>（{%$list.currency_type%}）{%$list.min_price%}</td>
                    <td>{%$list.z_index%}</td>
                    <td class="text-center">
                        <a href="{%:U('/sws/area/edit',array('index'=>$list['id']))%}">{%$Think.lang.update%}</a>
                        <a class="ml-10 table-delete" href="javascript:void(0);" data-href="{%:U('/sws/area/delete')%}" data-id="{%$list.id%}">{%$Think.lang.delete%}</a>
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