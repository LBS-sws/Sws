<div class="row">
    <form class="col-lg-6 col-lg-offset-3 kehu-div form-horizontal" action="{%:U('/sws/login/saveAddress')%}">
        <input type="hidden" name="id" value="{%$id%}" id="id">
        <input type="hidden" name="token" value="{%$token%}" id="token">
        <input type="hidden" name="address" id="address" value="">
        <p class="text-center">{%$Think.lang.address_01_06%}</p>
        <div class="form-group">
            <div class="col-xs-12 down_address">
                <input class="form-control" id="searchValue" autocomplete="off" data-error="{%$Think.lang.not_null%}" data-href="{%:U('/sws/login/ajaxSearch')%}">
                <div id="down_show" data-loading="{%$Think.lang.in_search%}" data-none="{%$Think.lang.relevant_none%}"></div>
            </div>
        </div>
        <div id="address-two" class="storey_div" style="display: none">
            <label style="padding-left: 0px;">{%$Think.lang.storey%}</label>
            <div>
                <input class="form-control" name="storey" id="storey">
            </div>
            <label>{%$Think.lang.room_number%}</label>
            <div style="padding-right: 0px;">
                <input class="form-control" name="room_number" id="room_number">
            </div>
        </div>
        <div id="address-div" style="display: none">
            <div class="form-group">
                <div class="col-xs-4 col-xs-offset-4">
                    <button type="submit" class="btn btn-kehu btn-primary btn-group-justified">
                        {%$Think.lang.submit%}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>