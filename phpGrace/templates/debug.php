<?php
//开启调试模式 后显示
if(!defined('PG_VERSION')) exit();?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>phpGrace - deBug</title>
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
.pg-content .span-primary-bold{color:#009688;font-weight:bolder;}
.pg-wrap{width:800px; margin:0 auto; margin-top:150px; background:#F8F8F8; padding:38px; border-radius:3px; border-top:5px solid #009688;}
</style>
</head>
<body>
<div class="pg-wrap">
	<div class="pg-title">
		<span>:(</span>  　出错了!
	</div>
	<div class="pg-content">
		<?php  if($this->getcode()!=0){ ?>
			<span class="span-primary-bold">错误代码：</span> <span style="color:#FF5722;"><?php echo $this->getcode();?></span><br/>
		<?php } ?> 
		<span class="span-primary-bold">错误信息 : </span><?php echo $this->getMessage();?><br />
	</div>
	<div class="pg-copy-right"  style="color:#009688">
		 [PG_DEBUG 调试模式] 
	</div>
</div>
</body>
</html>