<?php 
// 注册一个会在PHP中止时执行的函数
if(!defined('PG_VERSION')) exit();?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>出错了 (:</title>
<style type="text/css">
*{margin:0; padding:0; font-size:15px;}
body{background:#FFFFFF; font-family:"微软雅黑"; color:#323233; padding:25px;}
.pg-title{font-size:32px; line-height:1.2em; padding-bottom:30px; border-bottom: 1px dashed #D1D1D1;}
.pg-title span{font-size:50px;}
.pg-content{line-height:2.5em; margin-top:28px;}
.pg-content span{color:#666666;}
.pg-copy-right{margin-top:28px; text-align:center;}
.pg-copy-right sup{font-size:10px;}
a{color:#3688ff;}
.pg-wrap{width:800px; margin:0 auto; margin-top:150px; background:#F8F8F8; padding:38px; border-radius:3px; border-top:5px solid #009688;}
</style> 
</head>
<body>
<?php $errors = json_decode(systemErrors);?>
<div class="pg-wrap">
	<div class="pg-title">
		<span style="padding-right:20px;">:(</span>出错了!  
	</div>
	<div class="pg-content">
		<span>错误遍码 : </span>  <?php echo $errors[0]?><br />
		<a href="" targent="_"></a>
		 <span>错误信息 : </span><?php echo $errors[1];?><br />   
		<span>相关文件 : </span><?php echo $errors[2];?><br />
		<span>错误位置 : </span><?php echo $errors[3];?>行<br />
	</div>
	<div class="pg-copy-right" style="color:#009688">
		 哎呀 , 出错啦  
	</div>
</div>  
</body>
</html>