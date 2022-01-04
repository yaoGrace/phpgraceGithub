<?php
/**
 * 系统前端入口文件
 * @version   1.2.0
 */
define('PG_DEBUG'     , false); // 开启调试模式
define('PG_SHOWERROR' , TRUE); // 开启运行报错
define('PG_VIEW_TYPE', 'dir'); // 模板路径模式 [ 目录模式 ]
include 'phpGrace/phpGrace.php';