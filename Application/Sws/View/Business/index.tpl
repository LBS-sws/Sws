<div class="container-sc">
    <h2>{%$Think.lang.business_list%}</h2>
    <div class="col-xs-12">
        <div class="pull-right ml-10">
            <a class="btn btn-primary" href="{%:U('/sws/business/add')%}">{%$Think.lang.add%}</a>
        </div>
        <table class="table table-hover table-bordered table-striped" id="example">
            <thead>
            <tr>
                <th>{%$Think.lang.business_name%}</th>
                <th>{%$Think.lang.city_name%}</th>
                <th>{%$Think.lang.business_type%}</th>
                <th>{%$Think.lang.b_unit%}</th>
                <th>{%$Think.lang.unit_price%}</th>
                <th class="text-center">{%$Think.lang.operation%}</th>
            </tr>
            </thead>
            <tbody>
            <volist name="businessList" id="list">
                <tr>
                    <td>{%$list[$name]|default='未设置'%}</td>
                    <td>{%$list[$cityName]|default='未设置'%}</td>
                    <eq name="list.type" value="1">
                        <td>{%$Think.lang.general_business%}</td>
                        <else/>
                        <td>{%$Think.lang.special_business%}</td>
                    </eq>
                    <td>{%$list.b_unit|L%}</td>
                    <eq name="list.type" value="1">
                        <td>（{%$list.currency_type%}）{%$list.price%}</td>
                        <else/>
                        <td>{%$Think.lang.special_none%}</td>
                    </eq>
                    <td class="text-center">
                        <a href="{%:U('/sws/business/edit',array('index'=>$list['id']))%}">{%$Think.lang.update%}</a>
                        <a class="ml-10 table-delete" href="javascript:void(0);" data-href="{%:U('/sws/business/delete')%}" data-id="{%$list.id%}">{%$Think.lang.delete%}</a>
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