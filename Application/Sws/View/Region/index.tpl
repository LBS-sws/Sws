<div class="container-sc">
    <h2>{%$Think.lang.region_list%}</h2>
    <div class="col-xs-12">
        <div class="pull-right ml-10">
            <a class="btn btn-primary" href="{%:U('/sws/region/add')%}">{%$Think.lang.add%}</a>
        </div>
        <table class="table table-hover table-bordered table-striped" id="example">
            <thead>
            <tr>
                <th>{%$Think.lang.region_name%}</th>
                <th>{%$Think.lang.z_index%}</th>
                <th>{%$Think.lang.web_prefix%}</th>
                <th>{%$Think.lang.www_fix%}</th>
                <th>{%$Think.lang.calculation%}</th>
                <th class="text-center">{%$Think.lang.operation%}</th>
            </tr>
            </thead>
            <tbody>
            <volist name="regionList" id="list">
                <tr>
                    <td>{%$list[$name]|default='未设置'%}</td>
                    <td>{%$list.z_index%}</td>
                    <td>{%$list.web_prefix%}</td>
                    <td>{%$list.www_fix|removeOneAndEnd%}</td>

                    <if condition="$list['calculation']==1">
                        <td>{%$Think.lang.yes%}</td>
                        <else/>
                        <td>{%$Think.lang.no%}</td>
                    </if>
                    <td class="text-center">
                        <a href="{%:U('/sws/region/edit',array('index'=>$list['id']))%}">{%$Think.lang.update%}</a>
                        <a class="ml-10 table-delete" href="javascript:void(0);" data-href="{%:U('/sws/region/delete')%}" data-id="{%$list.id%}">{%$Think.lang.delete%}</a>
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