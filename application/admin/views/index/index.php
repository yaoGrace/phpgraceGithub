<?php if(!defined('PG_VERSION')){exit;}?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>welcome to phpGrace</title>
</head>
<body>
	<div style="font-size:22px; line-height:2em; font-family:微软雅黑; padding:0 100px; margin-top: 50px;">
		<span style="font-size:38px; font-family:微软雅黑;">(: 说明</span><br />
	</div>
	<div style="font-size:12px; line-height:2em; font-family:微软雅黑; padding:0 100px; margin-top:28px;">
		这个是一个网站应用文件及目录示例，目录结构:
		( <span style='color:red'>支持根目录下静态文件访问，不支持application目录下的静态目录访问</span>)<br />
		<pre style="font-family:微软雅黑; font-size:15px;">
|_ application 			//应用模块 ，所有应用都在此模块目录下
	|_ admin			// 后台模块
		|_ controllers  // 后台控制器目录 
		|_ views 		// 视图目录
		|_ sessions 	// session存储的目录
		|_ lang 		// 语言包
	|_ api				// api模块
		|_ controllers  // api的控制器目录
		|_ lang 		// 语言包 
		|_ sessions 	// session存储的目录
	|_ home				// 默认前台模块
		|_ controllers  // 前台的控制器目录
		|_ views 		// 视图目录
		|_ lang 		// 语言包
		|_ sessions 	// session存储的目录
|_ phpGrace	     // 核心目录
	|_  ....
	|_  ....
	|_  ....
	|_  ....
|_ statics		     // 静态文件目录 ：css,js,imgs等
|_ .htaccess 	     // 伪静态
|_ config.php           // 配置文件
|_ favicon.ico          // 图标
|_ index.php            // 入口文件

		</pre> 
	</div>  
</body>
</html>