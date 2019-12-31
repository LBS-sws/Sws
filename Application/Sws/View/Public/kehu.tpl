<div class="row">
    <form class="col-lg-6 col-lg-offset-3 kehu-div form-horizontal" action="{%:U('/sws/login/saveAddress')%}">
        <input type="hidden" name="id" value="{%$id%}" id="id">
        <input type="hidden" name="token" value="{%$token%}" id="token">
        <input type="hidden" name="address" id="address" value="">
        <p class="text-center">{%$Think.lang.address_01_03%}</p>
        <div class="form-group">
            <div class="col-xs-4 col-xs-offset-4">
                <button type="button" class="btn btn-kehu btn-group-justified" id="btn-ip" data-href="{%:U('/sws/login/ajaxIp')%}">
                    <span class="glyphicon glyphicon-map-marker"></span>{%$Think.lang.address_01_04%}
                </button>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-8 col-xs-offset-2 text-center kehu-div-or">
                <span>{%$Think.lang.address_01_05%}</span>
            </div>
        </div>
        <p class="text-center">{%$Think.lang.address_01_06%}</p>
        <div class="form-group">
            <div class="col-xs-8 col-xs-offset-2">
                <input class="form-control" id="searchValue">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-4 col-xs-offset-4">
                <button type="button" class="btn btn-kehu btn-group-justified" id="btn01" data-href="{%:U('/sws/login/ajaxSearch')%}">
                    <span class="glyphicon glyphicon-search"></span>{%$Think.lang.address_01_07%}
                </button>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-8 col-xs-offset-2 text-center kehu-div-or">
                <span>{%$Think.lang.address_01_05%}</span>
            </div>
        </div>
        <p class="text-center">{%$Think.lang.address_01_08%}</p>
        <div class="form-group">
            <div class="col-xs-4 col-xs-offset-4">
                <button type="button" class="btn btn-kehu btn-group-justified" id="btn02" data-href="{%:U('/sws/login/ajaxSelect')%}">
                    <span class="glyphicon glyphicon-search"></span>{%$Think.lang.address_01_07%}
                </button>
            </div>
        </div>
        <div id="address-div" style="display: none">
            <p>&nbsp;</p>
            <p class="text-center">{%$Think.lang.address_01_11%}</p>
            <p class="text-center" id="address-span"><b>ddddddddddddddddddddddddddddddddddd</b></p>
            <div class="form-group">
                <div class="col-xs-4 col-xs-offset-4">
                    <button type="submit" class="btn btn-kehu btn-group-justified">
                        {%$Think.lang.address_01_12%}
                    </button>
                </div>
            </div>
        </div>
        <p>&nbsp;</p>
        <h3 class="text-center">{%$Think.lang.address_01_09%}</h3>
        <ul class="list-unstyled kehu-div-ul" id="kehu-div-ul">
        </ul>
    </form>
</div>