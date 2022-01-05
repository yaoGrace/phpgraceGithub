<?php if(!defined('PG_VERSION')){exit;}?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>welcome to phpGrace</title>
</head>
<body>
	<div style="font-size:22px; line-height:2em; font-family:微软雅黑; padding:0 100px; margin-top:150px;">
		<span style="font-size:38px; font-family:微软雅黑;">(: 说明</span><br />
	</div>
	<div style="font-size:15px; line-height:2em; font-family:微软雅黑; padding:0 100px; margin-top:28px;">
		这个是一个网站应用后台分组示例，目录结构:<br />
		<pre style="font-family:微软雅黑; font-size:15px;">
|_ admin
	|_ app // 框架应用目录包含 控制器 视图 配置 语言包
	|_ statics // 静态资源文件夹， 视图中调用静态资源请使用 ./statics/***.静态文件 或 ./statics/***.静态文件
	// 如 : &lt;img src="statics/imgs/trace.png"/&gt; 或 &lt;img src="./statics/imgs/trace.png"/&gt;
		</pre>
	</div>
</body>
</html>