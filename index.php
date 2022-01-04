<?php
/**
 * 系统前端入口文件 
 */
define('PG_DEBUG'     , true);  # 开启调试模式
define('PG_SHOWERROR' , true);  # 开启运行报错
define('PG_TRACE'     , false);  # 开启追踪模式
define('PG_VIEW_TYPE' , 'dir');   # 模板路径模式 [ 目录模式 ]
//define('PG_SUFFIX'  , '.html'); # 自定义后缀 
define('PG_404'     ,   false);  # 关闭404报错
/**
 * 网址分割路由模式线 
 * 自定义：- 表示网址路径例如： admin-login-add.html
 * 默认  ：/ 表示网址路径例如： admin/login/add.html 
 **/
//define('PG_URL_SPLITLINE' ,'-');

include 'phpGrace/phpGrace.php';