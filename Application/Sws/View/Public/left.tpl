<div role="navigation" id="nav" class="closed" aria-hidden="false" style="transition: max-height 400ms; position: relative;">
    <div class="user-header text-center">
        <div><img src="__PUBLIC__/images/user-icon.png"></div>
        <div class="user-login">
            <h4 class="system-title">{%$Think.lang.loginTitle%}</h4>
            <span>{%$Think.lang.acc_number%}：{%$Think.session.user.user_name%}</span>
            <span>{%$Think.lang.nickname%}：{%$Think.session.user.nickname%}</span>
            <div>
                <neq name="Think.LANG_SET" value="zh-cn">
                    <a href="{%:U('/sws/index/zhcn',array('return'=>CURRENT_URL))%}">中文（简体）</a>
                </neq>
                <neq name="Think.LANG_SET" value="zh-tw">
                    <a href="{%:U('/sws/index/zhtw',array('return'=>CURRENT_URL))%}">中文（繁體）</a>
                </neq>
                <neq name="Think.LANG_SET" value="en-us">
                    <a href="{%:U('/sws/index/enus',array('return'=>CURRENT_URL))%}">English</a>
                </neq>
            </div>
        </div>
    </div>
    <ul>
        <li>
            <a href="{%:U('/sws/index/index')%}">
                <i class="fa fa-home fa-fw media-middle"></i>
                <span class="media-middle">{%$Think.lang.home%}</span>
            </a>
        </li>
        <foreach name="Think.config.MENU" item="menu_list" key="menu_key" >
            <if condition="strpos(session('user')['auth'],$menu_list['auth'])!==false">
                <eq name="Think.CONTROLLER_NAME" value="$menu_list.action">
                    <li class="active">
                        <else/>
                    <li>
                </eq>
                <a href="{%$menu_list.url|U%}">
                    <i class="{%$menu_list.icon%} fa-fw media-middle"></i>
                    <span class="media-middle">{%$menu_key|L%}</span>
                </a>
                </li>
            </if>
        </foreach>
        <li>
            <a href="{%:U('/sws/login/loginOut')%}">
                <i class="fa fa-power-off fa-fw media-middle"></i>
                <span class="media-middle">{%$Think.lang.loginOut%}</span>
            </a>
        </li>
    </ul>
</div>