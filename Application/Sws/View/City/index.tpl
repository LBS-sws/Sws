<div class="container-sc">
    <h2>{%$Think.lang.city_list%}</h2>
    <div class="col-xs-12">
        <div class="pull-right ml-10">
            <a class="btn btn-primary" href="{%:U('/sws/city/add')%}">{%$Think.lang.add%}</a>
        </div>
        <table class="table table-hover table-bordered table-striped" id="example">
            <thead>
            <tr>
                <th>{%$Think.lang.city_name%}</th>
                <th>{%$Think.lang.city_level%}</th>
                <th>{%$Think.lang.city_other%}</th>
                <th>{%$Think.lang.other_price%}</th>
                <th>{%$Think.lang.city_other%}{%$Think.lang.business_min_price%}</th>
                <th>{%$Think.lang.b_unit%}</th>
                <th>{%$Think.lang.region_name%}</th>
                <th class="text-center">{%$Think.lang.operation%}</th>
            </tr>
            </thead>
            <tbody>
            <volist name="cityList" id="list">
                <tr>
                    <td>{%$list["city_name$prefix"]|default='未设置'%}</td>
                    <td>{%$list.z_index%}</td>
                    <eq name="list.other_open" value="0">
                        <td>{%$Think.lang.no_exist%}</td>
                        <else/>
                        <td>{%$Think.lang.exist%}</td>
                    </eq>
                    <empty name="list.other_price">
                        <td>{%$list.other_price%}</td>
                        <else/>
                        <td>（{%$list.currency_type%}）{%$list.other_price%}</td>
                    </empty>
                    <empty name="list.other_min">
                        <td>{%$list.other_min%}</td>
                        <else/>
                        <td>（{%$list.currency_type%}）{%$list.other_min%}</td>
                    </empty>
                    <td>{%$list.b_unit|L%}</td>
                    <td>{%$list["region_name$prefix"]|default='未设置'%}</td>
                    <td class="text-center">
                        <a href="{%:U('/sws/city/edit',array('index'=>$list['id']))%}">{%$Think.lang.update%}</a>
                        <a class="ml-10 table-delete" href="javascript:void(0);" data-href="{%:U('/sws/city/delete')%}" data-id="{%$list.id%}">{%$Think.lang.delete%}</a>
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