<div class="container-sc">
    <h2>{%$Think.lang.user_list%}</h2>
    <div class="col-xs-12">
        <div class="pull-right ml-10">
            <a class="btn btn-primary" href="{%:U('/sws/user/add')%}">{%$Think.lang.add%}</a>
        </div>
        <table class="table table-hover table-bordered table-striped" id="example">
            <thead>
            <tr>
                <th>{%$Think.lang.acc_number%}</th>
                <th>{%$Think.lang.nickname%}</th>
                <th>{%$Think.lang.admin_email%}</th>
                <th>{%$Think.lang.email_hint%}</th>
                <th class="text-center">{%$Think.lang.operation%}</th>
            </tr>
            </thead>
            <tbody>
            <volist name="userList" id="list">
                <tr>
                    <td>{%$list.user_name%}</td>
                    <td>{%$list.nickname%}</td>
                    <td>{%$list.email%}</td>
                    <td>{%$hintList[$list[email_hint]]%}</td>
                    <td class="text-center">
                        <a href="{%:U('/sws/user/edit',array('index'=>$list['id']))%}">{%$Think.lang.update%}</a>
                        <a class="ml-10 table-delete" href="javascript:void(0);" data-href="{%:U('/sws/user/delete')%}" data-id="{%$list.id%}">{%$Think.lang.delete%}</a>
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