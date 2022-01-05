
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://at.alicdn.com/t/font_2548137_rkkkrkm96ad.css?spm=a313x.7781069.1998910419.370&file=font_2548137_rkkkrkm96ad.css" />
<title>404</title>
<style type="text/css">
*{
    margin:0;
    padding:0;
    font-family:"Helvetica Neue",Helvetica,"PingFang SC","Microsoft YaHei","微软雅黑";
}
a{color:#2F4056;text-decoration:none!important;}
body{background:#F8F8F8;}
#t404{font-size:258px; margin:20px 0px; color:#c2ccd1;line-height:280px;height:280px;postion:flex;}
.pg-wrap{margin-top:150px; text-align:center;height:400px;}
</style>
</head>
<body>
<div class="pg-wrap">
    <div id="t404" class="iconfont icon-404"></div>
    <div style="font-size:15px; color:#c2ccd1; line-height:88px;">
        您所访问的页面不存在&nbsp;&nbsp;&nbsp;·&nbsp;&nbsp;&nbsp;<a href="/" onclick="turnUrl()">请点击这里返回主页</a>
    </div>
</div>
<script type="text/javascript">
var t, downSecond = 5;
document.getElementById("downTime").innerHTML = downSecond + "秒";
t = setInterval(function() { 
	downSecond --;
	document.getElementById("downTime").innerHTML = downSecond + "秒";
	if(downSecond == 0) {
		clearInterval(t);
		turnUrl();
	}
}, 1000);
function turnUrl() {
	if(history.length > 1) {
		history.back();
	} else {
		window.location = '<?php echo str_replace(PG_INDEX_FILE_NAME, '', $_SERVER['PHP_SELF']);?>';
	}
}
</script>
</body>
</html>