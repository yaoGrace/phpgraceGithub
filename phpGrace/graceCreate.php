<?php
/********************************************************
 * 项目基础构建 （自动创建）
 * @version   1.1 Beta
 **********************************************************/
function graceCreateApp(){
	//创建外层目录
	mkdir(PG_PATH, 0777, true);
	graceCreateAppIndexHtml(PG_PATH);
	//创建控制器
	mkdir(PG_PATH.'/'.PG_CONTROLLER, 0777, true);
	graceCreateAppIndexHtml(PG_PATH.'/'.PG_CONTROLLER);
	graceCreateAppIndexController();
	//创建视图
	mkdir(PG_PATH.'/'.PG_VIEW, 0777, true);
	graceCreateAppIndexHtml(PG_PATH.'/'.PG_VIEW);
	graceCreateAppIndexView();
	//创建语言包
	mkdir(PG_PATH.'/'.PG_LANG_PACKAGE, 0777, true);
	graceCreateAppIndexHtml(PG_PATH.'/'.PG_LANG_PACKAGE);
	graceCreateAppLang();
	//创建分组配置
	file_put_contents(PG_PATH.'/'.PG_CONF, '<?php return array();?>');
	//路由文件
	file_put_contents(PG_PATH.'/router.php', '<?php return array();?>');
	//伪静态文件
	file_put_contents('./.htaccess', '<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ ./index.php?pathInfo=$1 [QSA,PT,L]
</IfModule>');
}

function graceCreateAppIndexHtml($dir){
	file_put_contents($dir.'/index.html', '<html></html>');
}

function graceCreateAppIndexController(){
	$str = '<?php
/*
phpGrace  轻快的实力派！ 
*/
class indexController extends grace{
	
	//__init 函数会在控制器被创建时自动运行用于初始化工作，如果您要使用它，请按照以下格式编写代码即可：
	/*
	public function __init(){
		parent::__init();
		//your code ......
	}
	*/
	public function index(){
		//系统会自动调用视图 index_index.php
	}
	
}';
	file_put_contents(PG_PATH.'/'.PG_CONTROLLER.'/index.php', $str);
}

function graceCreateAppIndexView(){
	$str = '<?php if(!defined(\'PG_VERSION\')){exit;}?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>welcome to phpGrace</title>
</head>
<body>
	<div style="font-size:22px; line-height:1.8em; font-family:微软雅黑; padding:100px;">
		<span style="font-size:60px; font-family:微软雅黑;">(: </span><br />
		Welcome to phpGrace ! <a href="http://liukuaizhuan.gitee.io/phpgrace" target="_blank">访问开发手册</a>
	</div>
</body>
</html>';
	if(PG_VIEW_TYPE == 'file'){
		file_put_contents(PG_PATH.'/'.PG_VIEW.'/index_index.php', $str);
	}else{
		mkdir(PG_PATH.'/'.PG_VIEW.'/index', 0777, true);
		file_put_contents(PG_PATH.'/'.PG_VIEW.'/index/index.php', $str);
	}
}

function graceCreateAppLang(){
	$str = "<?php
return array(
	'APP_NAME'     => 'phpGrace',
);";
	file_put_contents(PG_PATH.'/'.PG_LANG_PACKAGE.'/zh.php', $str);
}