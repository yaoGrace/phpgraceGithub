<?php
/*********************************************************************
 *  项目基础构造
 *  @auther  :  yaoGrace
 *  @email   :  liukuaizhuan@qq.com 
 *  @version :  2.0.0
 *  github	 :  https://github.com/yaoGrace/phpgraceGithub
 *  重写内核
 *********************************************************************/
function graceCreateApp($name){ 
    $dirName = strtolower($name); #文件夹的名称全部转化为小写字母 
    if(is_dir(PG_PATH.'/'.$dirName)){exit($dirName."模块目录已经存在!");die;} 
	//创建外层目录
	mkdir(PG_PATH.'/'.$dirName, 0777, true);
	graceCreateAppIndexHtml(PG_PATH.'/'.$dirName);
	//创建控制器
	mkdir(PG_PATH.'/'.$dirName.'/'.PG_CONTROLLER, 0777, true);
	graceCreateAppIndexHtml(PG_PATH.'/'.$dirName.'/'.PG_CONTROLLER);
	graceCreateAppIndexController($dirName);
	//创建视图
	mkdir(PG_PATH.'/'.$dirName.'/'.PG_VIEW, 0777, true);
	graceCreateAppIndexHtml(PG_PATH.'/'.$dirName.'/'.PG_VIEW);
	graceCreateAppIndexView($dirName);
	//创建语言包
	mkdir(PG_PATH.'/'.$dirName.'/'.PG_LANG_PACKAGE, 0777, true);
	graceCreateAppIndexHtml(PG_PATH.'/'.$dirName.'/'.PG_LANG_PACKAGE);
	graceCreateAppLang($dirName); 
	exit('分组：'.$dirName .'模块已经创建成功!');
}

# 创建 index.html 防止爆目录
function graceCreateAppIndexHtml($dir){
	file_put_contents($dir.'/index.html', '<html></html>');
}
# 创建模块默认控制器
function graceCreateAppIndexController($dirName){
	$str = '<?php
/*
phpGrace 轻快的实力派！ 
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
	file_put_contents(PG_PATH.'/'.$dirName.'/'.PG_CONTROLLER.'/index.php', $str);
}
# 创建模块默认视图页面
function graceCreateAppIndexView($dirName){
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
		Welcome to phpGrace ! 
	</div>
</body>
</html>';
	if(PG_VIEW_TYPE == 'file'){
		file_put_contents(PG_PATH.'/'.$dirName.'/'.PG_VIEW.'/index_index.php', $str);
	}else{
		mkdir(PG_PATH.'/'.$dirName.'/'.PG_VIEW.'/index', 0777, true);
		file_put_contents(PG_PATH.'/'.$dirName.'/'.PG_VIEW.'/index/index.php', $str);
	}
}
# 创建模块语言包
function graceCreateAppLang($dirName){
	$str = "<?php
return array(
	'APP_NAME'     => 'phpGrace',
);";
	file_put_contents(PG_PATH.'/'.$dirName.'/'.PG_LANG_PACKAGE.'/zh.php', $str);
}