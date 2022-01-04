<?php
/******************************************************
 * 框架常用函数文件
 * @version   1.2.0
 *****************************************************/

/* 行错误及异常处理  */
error_reporting(E_ALL);
ini_set('display_errors', 'off');
function graceErrorHandler($code, $message, $file, $line){ 
	if(!PG_SHOWERROR){
        #关闭报错模式
        exit();
	}else{
	    #开启报错模式
        define('systemErrors', json_encode(array($code, $message, $file, $line)));
        include(PG_IN.'templates'.PG_DS.'error.php');
        exit;   
    }
}
//设置用户自定义的错误处理程序，然后触发错误 
set_error_handler("graceErrorHandler");
//注册一个会在PHP中止时执行的函数
register_shutdown_function(function(){ 
    //获取最后发生的错误
	$error = error_get_last(); 
    if (!empty($error)){
		define('systemErrors', json_encode(array('0', $error['message'], $error['file'], $error['line'])));
		include(PG_IN.'templates'.PG_DS.'shutdown.php');
		exit;
    }
});
class graceException extends Exception{  
	// 调试模式打开后显示页面
	public function showBug(){
		if(PG_DEBUG){ include PG_IN.'templates'.PG_DS.'debug.php'; }
	}
}
//启动404错误页面
function PG_404_Check(){
    //如果控制器内的方法不存在 ,且开启404页面
    if(PG_404){ include(PG_IN.'templates'.PG_DS.'404.php'); exit;  }
}

// 运行追踪
function gracesTrace(){
	if(!PG_TRACE){return false;}
	include PG_IN.'templates'.PG_DS.'trace.php';
}

/* 全局配置文件检查 */
function graceInitConfig(){
	$configFile = PG_IN.'config.php';
	if(is_file($configFile)){return TRUE;}
	file_put_contents($configFile, file_get_contents(PG_IN.'templates'.PG_DS.'config.php'));
}

/* 框架类文件自动加载 */
function __graceAutoLoad($className){
	// 自定义控制器文件加载
	if(substr($className, -10) == 'Controller'){
		$fileUri = PG_PATH.'/'.PG_CONTROLLER.'/'.substr($className, 0, -10).'.php';
		if(is_file($fileUri)){require $fileUri;}
	}
	// 利用命名空间加载其它类文件
	else{
		$fileUri = PG_IN.substr($className, 9).'.php';
		if(PG_DS == '/'){$fileUri = str_replace('\\', '/', $fileUri);}
		if(is_file($fileUri)){require $fileUri;}
	}
}
spl_autoload_register('__graceAutoLoad'); 

/**
 * 功能 : 终止程序运行并输出一段消息
 * @param $msg 文本类型的消息内容 
 */
function pgExit($msg = ''){exit($msg);}

/**
 * 功能 : 打印某个变量
 * @param $var  变量
 * @param $type 默认 false 使用 print_r(), 否则使用 var_dump()
 */
function p($var, $type = false){
	if($type){var_dump($var);}else{print_r($var);}
}

/**
 * 功能 : 获取一个数据表操作对象
 * @param $tableName  数据表名称
 * @param $configName 默认 db , 对应的数据库一级2配置名称
 * @return 数据库操作对象
 */
function db($tableName, $configName = 'db'){
	$conf = sc($configName);
	return phpGrace\tools\db::getInstance($conf, $tableName, $configName);
}

/**
 * 功能 : 获取一个模型
 * @param $modelName  模型名称
 * @param $configName 默认 db , 对应的数据库一级2配置名称
 * @return 模型对象
 */
function model($modelName){
	$modelName = '\\phpGrace\\models\\'.$modelName;
	$model = new $modelName();
	return $model;
}

/**
 * 功能 : 工具实例化函数( 适用于不能使用命名空间的工具类 )
 * @param $args 动态参数
 * @return 对应的工具对象
 */
function tool($args){
	static $staticTools = array();
	$arguments = func_get_args();
	$className = array_shift($arguments);
	$className = '\\'.$className;
	if(empty($staticTools[$className])){
		$fileUri = PG_IN.PG_TOOLS.PG_DS.$className.'.php';
		if(!is_file($fileUri)){throw new graceException("类文件 {$className} 不存在");}
		include $fileUri;
		$staticTools[$className] = 1;
	}
	switch(count($arguments)){
		case 0 :
		return new $className();
		break;
		case 1 :
		return new $className($arguments[0]);
		break;
		case 2 :
		return new $className($arguments[0], $arguments[1]);
		break;
		case 3 :
		return new $className($arguments[0], $arguments[1], $arguments[2]);
		break;
		case 4 :
		return new $className($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
		break;
		case 5 :
		return new $className($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4]);
		break;
	}
}

/**
 * graceRouter 
 * 功能 : 路由解析
 * @return array
*/
function graceRouter(){
	if(isset($_GET['pathInfo'])){$path = $_GET['pathInfo']; unset($_GET['pathInfo']);}else{$path = 'index/index';}
	if(PG_SUFFIX){$path = str_replace(PG_SUFFIX, '', $path);}
	$router = explode('/', $path);
	if(empty($router[0])){array_shift($router);}
	if(PG_ROUTE){
		$routerArray = require(PG_PATH.'/router.php');
		if(array_key_exists($router[0], $routerArray)){
			$newRouter    = array(); 
			$newRouter[0] = $routerArray[$router[0]][0];
			$newRouter[1] = $routerArray[$router[0]][1];
			if(!empty($routerArray[$router[0]][2]) && is_array($routerArray[$router[0]][2])){
				$newRouter = array_merge($newRouter, $routerArray[$router[0]][2]);	
			}
			define("PG_PAGE",  1);
			return $newRouter;
		};
	}
	$router[0] = isset($router[0]) ?  $router[0] : 'index';
	$router[1] = isset($router[1]) ?  $router[1] : 'index';
	for($i = 2; $i < count($router); $i++){
		if(preg_match('/^page_(.*)('.PG_SUFFIX.')*$/Ui', $router[$i], $matches)){
			define("PG_PAGE",  intval($matches[1]));
			array_splice($router, $i, 1);
		}
	}
	if(!defined("PG_PAGE")){define("PG_PAGE",  1);}
	return $router;
}


/**
 * 功能 : 修正 POST 参数
 * @param name  post提交的表单里面的name属性名称
 * @param value 修正后的值
 * @return value
 */
function initPOST($name, $value = ''){
	$_POST[$name] = empty($_POST[$name]) ? $value : $_POST[$name];
	return $_POST[$name];
}

/**
 * 当前分组内的自定义配置 [可按照格式进行自定义配置]
 * @param key1 配置名称1
 * @param key2 配置名称2
 * @return 对应配置值
 */
function c($key1, $key2 = null){
	static $config = null;
	if($config == null){$config = require PG_PATH.'/config.php';}
	if(is_null($key1)){return $config;}
	if(is_null($key2)){if(isset($config[$key1])){return $config[$key1];} return null;}
	if(isset($config[$key1][$key2])){return $config[$key1][$key2];}
	return null;
}

/**
 * 全局配置 [可按照格式进行自定义配置]
 * @param $key 配置名称1
 * @param $key 配置名称2
 */
function sc($key1 = null, $key2 = null){
	static $config = null;
	if($config == null){
		$config = require PG_IN.'config.php';
	}
	if(is_null($key1)){return $config;}
	if(is_null($key2)){if(isset($config[$key1])){return $config[$key1];} return null;}
	if(isset($config[$key1][$key2])){return $config[$key1][$key2];}
	return null;
}

/**
 * 时间、内存开销计算
 * @return array(耗时[毫秒], 消耗内存[K])
 */
function pgCost(){
	return array(
		round((microtime(true) - PG_START_TIME) * 1000, 2),
		round((memory_get_usage() - PG_START_MEMORY) / 1024, 2)
	);
}

/**
 * 开启 session
 */
function startSession(){
	switch(PG_SESSION_TYPE){
		case 'file' :
			if(!is_dir(PG_SESSION_DIR)){mkdir(PG_SESSION_DIR, 0777, true);}
			session_save_path(PG_SESSION_DIR);
		break;
		case 'memcache' :
			ini_set("session.save_handler", "memcache");
			ini_set("session.save_path", PG_SESSION_HOST);
		break;
		case 'redis':
			ini_set("session.save_handler", "redis");
			ini_set("session.save_path", PG_SESSION_HOST);
		break;
		default:
			if(!is_dir(PG_SESSION_DIR)){mkdir(PG_SESSION_DIR, 0777, true);}
			session_save_path(PG_SESSION_DIR);
	}
	session_start();
	session_write_close();
}

/**
 * 设置 session
 * @param $name session 名称
 * @param $val  对应的值
 */
function setSession($name, $val){
	session_start();
	if(is_array($val)){
		foreach($val as $k => $v){$_SESSION[$k] = $v;}
	}else{
		$_SESSION[$name] = $val;
	}
	session_write_close();
}

/**
 * 获取 session
 * @param $name session 名称
 */
function getSession($name){
	if(isset($_SESSION[$name])){return $_SESSION[$name];} 
	return null;
}

/**
 * 销毁指定的 session
 * @param $name session 名称
 */
function removeSession($name){
	session_start();
	if(is_array($name)){
		foreach($name as $k){
			if(isset($_SESSION[$k])){unset($_SESSION[$k]);}
		}
	}else{
		if(isset($_SESSION[$name])){unset($_SESSION[$name]);}
	}
	session_write_close();
}

/**
 * 设置 cookie
 * @param $name   cookie 名称
 * @param $val    对应的值
 * @param $expire 有效时间
 */
function pgSetCookie($name, $val, $expire = 31536000){
	$expire += time();
	@setcookie($name, $val, $expire, '/');
	$_COOKIE[$name] = $val;
}

/**
 * 获取 cookie
 * @param $name cookie 名称
 * @return 具体 cookie 值或 null
 */
function pgGetCookie($name){if(isset($_COOKIE[$name])){return $_COOKIE[$name];} return null;}

/**
 * 删除指定 cookie
 * @param $name cookie 名称
 */
function pgRemoveCookie($name){
	setcookie($name, 'null', time() - 1000, '/');
}

/**
 * 获取语言
 * @param $key 语言包键名称
 * @return 具体的值或者null 
 */
function lang($key){
	static $Lang = null;
	if(is_null($Lang)){
		$langName = empty($_COOKIE['phpGraceLang']) ? 'zh' : $_COOKIE['phpGraceLang'];
		$langFile = PG_PATH.'/'.PG_LANG_PACKAGE.'/'.$langName.'.php';
		if(is_file($langFile)){
			$Lang = require $langFile;
		}else{
			throw new graceException('语言包文件不存在',100002);
		}
	}
	if(isset($Lang[$key])){return $Lang[$key];}
	return null;
}

/**
 * 路径解析
 * @param $c      控制器名称
 * @param $m      方法名称
 * @param $params 参数 : 数组或字符串模式
 * @param $page   页码
 * @return 具体的值或者null 
 */
function u($c, $m, $params = '', $page = null){
	$suffix = PG_SUFFIX ? PG_SUFFIX : '';
	$page = $page != null ? '/page_'.$page : '';
	if(is_array($params)){
		return PG_SROOT.$c.'/'.$m.'/'.implode('/', $params).$page.$suffix;
	}else{
		if($params != ''){
			return PG_SROOT.$c.'/'.$m.'/'.$params.$page.$suffix;
		}else{
			return PG_SROOT.$c.'/'.$m.$page.$suffix;
		}
	}
}

/**
 * 生成一个 token [ cookie 模式 ]
 * @return 具体的值或者null
 */
function setToken(){
	$token = uniqid();
	pgSetCookie('__gracetoken__', $token);
	return $token;
}

/**
 * 获取 token [ cookie 模式 ]，并销毁
 * @return 具体的值或者null
 */
function getToken(){
	$token = pgGetCookie('__gracetoken__');
	pgRemoveCookie('__gracetoken__');
	return $token;
}

/**
 * 去除空白字符
 * @param $str 需要替换的字符串
 * @return     替换后的结果
 */
function trimAll($str){
    $qian=array(" ","　","\t","\n","\r");
    $hou=array("","","","","");
    return str_replace($qian,$hou,$str); 
}

/**
 * 输出 option 选中状态
 * @param $val1 比对值1
 * @param $val2 比对值2
 */
function isSelected($val1, $val2){if($val1 == $val2){echo ' selected="selected"';}}

/**
 * 将数值转换为 select 菜单的 option
 * @param $data      数据数组
 * @param $currentId 默认选项对应的键
 */
function dataToOption($data, $currentId = 0){
	foreach($data as $k => $v){
		if($currentId == $k){
			echo "<option value=\"{$k}\" selected=\"selected\">{$v}</option>".PHP_EOL;
		}else{
			echo "<option value=\"{$k}\">{$v}</option>".PHP_EOL;
		}
	}
}

/** 
 * 规划缓存命名 
 * @param $name      缓存名称
 * @param $parameter 缓存影响参数
 * @param $isSuper   是否为全局缓存
 * @return 缓存名称
 */
function graceCacheName($name, $parameter = '', $isSuper = true){
	$cacheConfig = sc('cache');
	$parameter   = is_array($parameter) ? implode('_', $parameter) : $parameter;
	$cacheName   = $isSuper ? $cacheConfig['pre'].$name.$parameter : $cacheConfig['pre'].PG_C.'_'.PG_M.'_'.$name.$parameter;
	if(empty($cacheConfig['name2md5'])){ return $cacheName; }
	return md5($cacheName);
}