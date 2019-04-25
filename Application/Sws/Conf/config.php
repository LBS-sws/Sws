<?php

return array(
    //'配置项'=>'配置值'
    /* 调试工具 */
    'SHOW_PAGE_TRACE'=> false,
    /* Cookie设置 */
    'COOKIE_EXPIRE'         =>  0,       // Cookie有效期
    'COOKIE_DOMAIN'         =>  '',      // Cookie有效域名
    'COOKIE_PATH'           =>  '/',     // Cookie路径
    'COOKIE_PREFIX'         =>  '',      // Cookie前缀 避免冲突
    'COOKIE_SECURE'         =>  false,   // Cookie安全传输
    'COOKIE_HTTPONLY'       =>  '',      // Cookie httponly设置
    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'instantquote_db',          // 数据库名
    'MD5_PREFIX'               =>  'bubble000',          // MD5前綴
    'DB_USER'               =>  'instantquote',      // 用户名
    'DB_PWD'                =>  'Swisher168',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'quote_',    // 数据库表前缀
    'DB_PARAMS'          	=>  array(), // 数据库连接参数
    'DB_DEBUG'  			=>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO'           =>  '', // 指定从服务器序号
    /* 数据缓存设置 */
    'DATA_CACHE_TIME'       =>  0,      // 数据缓存有效期 0表示永久缓存
    'DATA_CACHE_COMPRESS'   =>  false,   // 数据缓存是否压缩缓存
    'DATA_CACHE_CHECK'      =>  false,   // 数据缓存是否校验缓存
    'DATA_CACHE_PREFIX'     =>  '',     // 缓存前缀
    'DATA_CACHE_TYPE'       =>  'File',  // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
    'DATA_CACHE_PATH'       =>  TEMP_PATH,// 缓存路径设置 (仅对File方式缓存有效)
    'DATA_CACHE_KEY'        =>  '',	// 缓存文件KEY (仅对File方式缓存有效)
    'DATA_CACHE_SUBDIR'     =>  false,    // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
    'DATA_PATH_LEVEL'       =>  1,        // 子目录缓存级别
    /* 错误设置 */
    'ERROR_MESSAGE'         =>  '页面错误！请稍后再试～',//错误显示信息,非调试模式有效
    'ERROR_PAGE'            =>  '',	// 错误定向页面
    'SHOW_ERROR_MSG'        =>  false,    // 显示错误信息
    'TRACE_MAX_RECORD'      =>  100,    // 每个级别的错误信息 最大记录数

    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => 'Public:error',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Public:success',

    /* SESSION设置 */
    'SESSION_AUTO_START'    =>  true,    // 是否自动开启Session
    'SESSION_OPTIONS'       =>  array('name'=>'user','expire'=>3600), // session 配置数组 支持type name id path expire domain 等参数
    'SESSION_TYPE'          =>  '', // session hander类型 默认无需设置 除非扩展了session hander驱动
    'SESSION_PREFIX'        =>  '', // session 前缀
    //'VAR_SESSION_ID'      =>  'user',     //sessionID的提交变量
    // 布局设置
    'TMPL_ENGINE_TYPE'      =>  'Think',     // 默认模板引擎 以下设置仅对使用Think模板引擎有效
    'TMPL_CACHFILE_SUFFIX'  =>  '.php',      // 默认模板缓存后缀
    'TMPL_DENY_FUNC_LIST'   =>  'echo,exit',    // 模板引擎禁用函数
    'TMPL_DENY_PHP'         =>  false, // 默认模板引擎是否禁用PHP原生代码
    'TMPL_L_DELIM'          =>  '{%',            // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'          =>  '%}',            // 模板引擎普通标签结束标记
    'TMPL_VAR_IDENTIFY'     =>  'array',     // 模板变量识别。留空自动判断,参数为'obj'则表示对象
    'TMPL_STRIP_SPACE'      =>  true,       // 是否去除模板文件里面的html空格与换行
    'TMPL_CACHE_ON'         =>  true,        // 是否开启模板编译缓存,设为false则每次都会重新编译
    'TMPL_CACHE_PREFIX'     =>  '',         // 模板缓存前缀标识，可以动态改变
    'TMPL_CACHE_TIME'       =>  0,         // 模板缓存有效期 0 为永久，(以数字为值，单位:秒)
    'TMPL_LAYOUT_ITEM'      =>  '{__CONTENT__}', // 布局模板的内容替换标识
    'LAYOUT_ON'             =>  true, // 是否启用布局
    'LAYOUT_NAME'           =>  'Layout/main', // 当前布局名称 默认为layout
    'URL_MODEL'           =>  3,
    'LOAD_EXT_CONFIG' => array('MENU'=>'menu_left',"DEFINE"=>'define'),
    //語言配置
    'LANG_SWITCH_ON' => true, // 开启语言包功能
    'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
    'DEFAULT_LANG' => 'zh-tw', // 默认语言
    'LANG_LIST' => 'zh-tw,zh-cn,en-us', // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE' => '1', // 默认语言切换变量);

);