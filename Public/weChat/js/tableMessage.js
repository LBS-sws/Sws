cookieLanguage = "zh-cn";
$(function ($) {
    var coum = $('#example thead>tr:first>th').length;
    if(coum !== 0){
        coum--;
    }
    var table =$('#example').DataTable({
        'order' : [coum,'desc']
    });
    $('#example').delegate(".table-delete","click",function () {
        var $that = $(this);
        var url = $(this).data("href");
        var id = $(this).data("id");
        var confirmStr = "是否删除本条数据？";
        var error_del = "删除失败，该数据有关联数据！";
        switch (cookieLanguage){
            case "zh-tw":
                confirmStr = "是否刪除本條數據？";
                error_del = "刪除失敗，該數據有關聯數據！";
                break;
            case "en-us":
                confirmStr = "Delete the data？";
                error_del = "Delete failed, this data has associated data！";

        }
        if(url == null || url == "" || url == undefined){
            return false;
        }
        if(id == null || id == "" || id == undefined || isNaN(id)){
            return false;
        }
        if(confirm(confirmStr)){
            $.ajax({
                type: "post",
                url: url,
                data: {id:id},
                dataType: "json",
                success: function(data){
                    if(data.status == 1){
                        table.rows($that.parents("tr")).remove().draw(false);
                    }else{
                        alert(error_del);
                    }
                }
            });
        }
    })
})
