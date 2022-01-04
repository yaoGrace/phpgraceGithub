<?php
/*********************************************
 *  框架核心文件
 *  @version   1.2.2
 *********************************************/

// 内存及运行时间起始记录
define('PG_START_MEMORY'    ,  memory_get_usage());
define('PG_START_TIME'      ,  microtime(true));
// 站点首页配置
define('PG_INDEX_FILE_NAME' , 'index.php');
// 页面后缀
if(!defined('PG_SUFFIX')){define('PG_SUFFIX' , false);}
// 框架版本
define('PG_VERSION'         ,  '1.2.2');
// 系统分隔符
define('PG_DS'              ,  DIRECTORY_SEPARATOR);
// 框架核心目录所在位置
define('PG_IN'              ,  dirname(__FILE__).PG_DS);
//整个应用的根目录绝对路径
define('PG_APP_ROOT'        ,   $_SERVER['DOCUMENT_ROOT']);

// 视图文件路径形式
// file : 文件形式     例 : 视图文件夹/控制器_方法.php
// dir  : 文件夹形式 例 : 视图文件夹/控制器/方法.php
if(!defined('PG_VIEW_TYPE')) {define('PG_VIEW_TYPE' , 'file');}

// 是否打开调试模式
if(!defined('PG_DEBUG'))     {define('PG_DEBUG'     , false);}
// 是否展示错误信息 [ 默认隐藏所有错误,运行报错服务器状态 500 ]
if(!defined('PG_SHOWERROR')) {define('PG_SHOWERROR' , false);}
//是否开启运行跟踪
if(!defined('PG_TRACE')){define('PG_TRACE',false);} 


// 是否自动展示视图, 如果项目为api接口不需要视图可以设置为 false
if(!defined('PG_AUTO_DISPLAY')){define('PG_AUTO_DISPLAY' , true);}
// 是否开启自定义路由
if(!defined('PG_ROUTE')){define('PG_ROUTE' , false);}
// 全局关闭缓存 [ 调试时可以开启此项来观察数据变化 ]
if(!defined('PG_CLOSE_CACHE')){define('PG_CLOSE_CACHE' , false);}
// 文件型 sessions 文件存放路径
if(!defined('PG_SESSION_DIR')){define('PG_SESSION_DIR' , './sessions');}
// session 存储类型  [file, memcache, redis]
if(!defined('PG_SESSION_TYPE')){define('PG_SESSION_TYPE' , 'file');}
// 是否全应用启动 session
if(!defined('PG_SESSION_START')){define('PG_SESSION_START' , false);}
//session 类似为 memcache 或 redis 时，对应的主机地址 [memcache 11211 redis 6379]
if(!defined('PG_SESSION_HOST')){define('PG_SESSION_HOST' , 'tcp://127.0.0.1:11211');}

// 应用所在目录
if(!defined('PG_PATH')){define('PG_PATH'  , 'application');}
// 网址分割线PG_URL_SPLITLINE 默认 / ，也可以自定义比如 - 
if(!defined('PG_URL_SPLITLINE')){define('PG_URL_SPLITLINE'  , '/');}
// 默认前端分组目录
define('DEFAULT_SROOT'         ,  'home');
// 控制器文件所在目录
define('PG_CONTROLLER'  , 'controllers');
// 视图文件所在目录
define('PG_VIEW'        , 'views');
// 模型文件所在目录
define('PG_MODEL'       , PG_IN.'models');
// 工具类文件所在目录
define('PG_TOOLS'       , 'tools');
// 语言包文件所在目录
define('PG_LANG_PACKAGE', 'lang');
// 全局配置文件名称
define('PG_CONF'        , '../config/config.php');
//	是否开启404页面展示 默认 true【开启】，false 【关闭】 
if(!defined('PG_404')){define('PG_404' , true);}
// 加载框架函数库
require(PG_IN.'graceFunctions.php');

// 检查并自动初始化配置
graceInitConfig();

// 基础控制器定义
class grace{
	
	// url 解析后获得的数据
	public    $gets;
	// 核心数据表名
	public    $tableName  = null;
	// 数据表主键
	public    $tableKey   = null;
	// 数据表操作对象
	public    $db;
	// 数据排序规则
	public    $order      = null;
	// 是否过滤 $_POST 数据内的 < > , 可防止跨站攻击
	public    $postFilter = true;
	// 网页信息 array(页面标题, 页面关键字, 页面描述)
	public    $pageInfo   = array('', '', '');
	// 缓存对象
	protected $cacher     = null;
	// 缓存名称
	protected $cacheName; 
	
	// 构造函数
	public function __construct(){}
	
	// 初始化函数
	public function __init(){
		$this->templateDir = PG_PATH.'/'.PG_VIEW.'/';
		if($this->tableName != null){$this->db = db($this->tableName);}
		// 过滤 $_POST
		if(!empty($_POST)){
			define('PG_POST', true);
			if($this->postFilter){$_POST = str_replace(array('<','>', '"', "'"),array('&lt;','&gt;', '&quot;', ''), $_POST);}
		}else{
			define('PG_POST', false);
		}
		// 过滤 $_GET
		if(!empty($_GET)){$_GET = str_replace(array('<','>', '"', "'"),array('&lt;','&gt;', '&quot;',''), $_GET);}
		if(!empty($this->gets)){$this->gets = str_replace(array('<','>', '"', "'"),array('&lt;','&gt;', '&quot;',''), $this->gets);}
	}
	
	// 默认 index
	public function index(){}
	
	// 视图展示
	public function display($tplName = null){
		if(PG_VIEW_TYPE == 'file'){
			$tplUrl = is_null($tplName) ? $this->templateDir.PG_C.'_'.PG_M.'.php' : $this->templateDir.$tplName;
		}else{
			$tplUrl = is_null($tplName) ? $this->templateDir.PG_C.'/'.PG_M.'.php' : $this->templateDir.$tplName;
		}
		if(is_file($tplUrl)){include($tplUrl);}
	}
	
	// 语言包设置
	protected function setLang($langType){
		pgSetCookie('phpGraceLang', $langType);
	}
	
	// 输出 json 形式的信息并终止程序运行
	protected function json($data, $type = 'ok'){
		pgExit(json_encode(array('status' => $type, 'data' => $data)));
	}
	
	// 获取数据列表 
	protected function dataList($everyPagerNum = 20, $fields = '*'){
		if($this->order == null){$this->order = $this->tableKey.' desc';}
		$arr = $this->db->page($everyPagerNum)->order($this->order)->fetchAll($fields);
		$this->pager = $arr[1];
		return $arr[0];
	}
	
	// 利用 id 获取一条数据
	protected function getDataById(){
		if(empty($this->gets[0])){return null;}
		return $this->db->where($this->tableKey .' = ?', array(intval($this->gets[0])))->fetch();
	}
	
	// 获取一条数据并以默认值形式复制给对应表单元素 
	protected function getDefaultVal($exception = array()){
		if(empty($this->gets[0])){return null;}
		$data = $this->db->where($this->tableKey .' = ?', array(intval($this->gets[0])))->fetch();
		$jsonPreData = array();
		if(!empty($exception) && !is_array($exception)){$exception = explode(',', $exception);}
		foreach($data as $k => $v){
			if(!in_array($k, $exception)){
				$jsonPreData[$k] = $data[$k];
			}
		}
		echo '<script>$(function(){';
		echo 'var dataobject = '.json_encode($jsonPreData).';';
		if($data){
			foreach($data as $k => $v){if(!in_array($k, $exception)){echo '$("input[name='.$k.']").val(dataobject.'.$k.');';}}
		}
		echo '});</script>';
		return $data;
	}
	
	// 跳转到应用首页
	public function skipToIndex(){header('location:'.PG_SROOT); exit;}
	
	// 获取缓存对象
	protected function getCacher(){
		if(!empty($this->cacher)){return null;}
		$config         = sc('cache');
		if(empty($config)){throw new graceException('缓存设置错误',100009);}
		if(!in_array($config['type'], sc('allowCacheType'))){throw new graceException('缓存类型错误',100010);}
		$type           = strtolower($config['type']);
		$className      = 'phpGrace\\tools\\caches\\'.$type.'Cacher';
		$this->cacher   = $className::getInstance($config);
	}
	
	// 进行缓存工作
	protected function cache($name, $id = null, $queryMethod, $timer = 3600, $isSuper = true){
		if(PG_CLOSE_CACHE){
			$queryRes    = $this->$queryMethod();
			$this->$name = $queryRes;
			return false;
		}
		$this->getCacher();
		$this->cacheName = graceCacheName($name, $id, $isSuper);
		$cachedRes = $this->cacher->get($this->cacheName);
		if($cachedRes){$this->$name = $cachedRes; return true;}
		$queryRes = $this->$queryMethod();
		$this->cacher->set($this->cacheName, $queryRes, $timer);
		$this->$name = $queryRes;
	}
	
	// 清除全部缓存
	public function clearCache(){
		$this->getCacher();
		$this->cacher->clearCache();
	}
	
	// 清除指定缓存
	public function removeCache($name, $id = null, $isSuper = true){
		$this->getCacher();
		$name = graceCacheName($name, $id, $isSuper);
		$this->cacher->removeCache($name);
	}
	
	// 初始化 gets 数据
	// 如果某个指定的数据为空则进行定义及赋值
	protected function initVal($key, $val = ''){
		if(empty($this->gets[$key])){$this->gets[$key] = $val;}
	}
	
	// 将 gets 指定数据整数化
	protected function intVal($key, $val = 0){
		if(empty($this->gets[$key])){
			$this->gets[$key] = 0;
		}else{
			$this->gets[$key] = intval($this->gets[$key]);
		}
	}
}

/* 模型基础类 */
class graceModel{
	
	// 数据表名称
	public $tableName    = null;
	// 数据表主键
	public $tableKey     = null;
	// 模型对象
	public static $obj   = null;
	// 模型名称
	public static $mname = null;
	// 数据操作对象
	public $m            = null;
	// 数据操作错误信息
	public $error        = null;
	// 缓存对象
	protected $cacher    = null;
	
	// 构造函数用于初始化获取数据表操作对象
	public function __construct(){
		if($this->tableName != null){$this->m = db($this->tableName);}
	}
	
	// 利用 id 查询一条数据
	public function findById($id, $fields = '*'){
		return $this->m->where($this->tableKey.' = ?', array($id))->fetch($fields);
	}
	
	// 获取刚刚运行的 sql 语句
	public function getSql(){return $this->m->getSql();}
	
	// 获取 数据操作过程中产生的错误信息
	public function error(){return $this->m->error();}
	
	// 在模型内实现缓存 - 获取缓存对象
	protected function getCacher(){
		if(!empty($this->cacher)){return null;}
		$config         = sc('cache');
		if(empty($config)){throw new graceException('缓存设置错误',100009);}
		if(!in_array($config['type'], sc('allowCacheType'))){throw new graceException('缓存类型错误',100010);}
		$type           = strtolower($config['type']);
		$className      = 'phpGrace\\tools\\caches\\'.$type.'Cacher';
		$this->cacher   = $className::getInstance($config);
	}
	
	// 在模型内设置缓存
	// 设置并获取缓存数据
	public function cache($name, $parameter = null, $queryMethod, $timer = 3600, $isSuper = true){
		if(PG_CLOSE_CACHE){return $this->$queryMethod();}
		$this->getCacher();
		$name             = graceCacheName($name, $parameter, $isSuper);
		$cachedRes        = $this->cacher->get($name);
		if($cachedRes){return $cachedRes;}
		$queryRes         = $this->$queryMethod();
		if(empty($queryRes)){return $queryRes;}
		$this->cacher->set($name, $queryRes, $timer);
		return $queryRes;
	}
	
	// 模型内清除指定缓存
	public function removeCache($name, $parameter = null, $isSuper = true){
		$this->getCacher();
		$name = graceCacheName($name, $parameter, $isSuper);
		$this->cacher->removeCache($name);
	}
	
}

/* 缓存基础类 */
class cacheBase{
	
	// 缓存对象
	protected $cacher     = null;
	// 缓存参数
	protected $parameters;
	
	// 构造函数
	public function __construct(){
		$config         = sc('cache');
		if(empty($config)){throw new graceException(' 缓存设置错误',100009);}
		if(!in_array($config['type'], sc('allowCacheType'))){throw new graceException('缓存类型错误',100010);}
		$type = strtolower($config['type']);
		$className      = 'phpGrace\\tools\\caches\\'.$type.'Cacher';
		$this->cacher   = $className::getInstance($config);
	}
	
	// 设置并获取缓存数据
	public function cache($name, $parameter = null, $queryMethod, $timer = 3600, $isSuper = true){
		$this->parameters = $parameter;
		if(PG_CLOSE_CACHE){return $this->$queryMethod();}
		$name             = graceCacheName($name, $parameter, $isSuper);
		$cachedRes        = $this->cacher->get($name);
		if($cachedRes){return $cachedRes;}
		$queryRes         = $this->$queryMethod();
		if(empty($queryRes)){return $queryRes;}
		$this->cacher->set($name, $queryRes, $timer);
		return $queryRes;
	}
	
	// 删除缓存
	public function removeCache($name, $parameter = null, $isSuper = true){
		$name = graceCacheName($name, $parameter, $isSuper);
		$this->cacher->removeCache($name);
	}
	
}

// 框架启动
try{
    /**
     *判断必须的3个文件是否加载，如未加载完整，则退出
     * index.php              根目录下的入口文件
     * phpGrace/phpGrace.php  核心文件
     * phpGrace/graceFunctions.php   函数文件
     **/
	$includedFiles = get_included_files();
	if(count($includedFiles) < 3){exit;}  
	header('content-type:text/html; charset=utf-8'); #定义默认语言编码
	if(PG_SESSION_START){startSession();}  # 检查是否需要开启session
	//if(!is_dir(PG_PATH)){include PG_IN.'graceCreate.php'; graceCreateApp();}  #如果模块不存在，自动创建
	$router = graceRouter();               # 路由解析基础url为数组 
	$groupName = $router[0];               # 分组模块 
    $mode = '/^([a-z]|[A-Z]|[0-9])+$/Uis';
    $res  = preg_match($mode, $groupName);
    if(!$res){$groupName = DEFAULT_SROOT;} # 模块如果起名不规则，修正为默认模块
    define('PG_SROOT',$groupName);         # 分组名确定下来了 ，分组名称常量 PG_SROOT
    p('<BR/><span style="color:red">当前分组：'.PG_SROOT.'</span><BR/>');// -----------------------------------------------调试输出
    $controllerName = $router[1];          # 读取控制器 
    $resCtr  = preg_match($mode, $controllerName);
    if(!$resCtr){$controllerName = 'index';}  # 控制器如果起名不规则，修正为index控制器
    $controllerFile = PG_PATH.'/'.PG_SROOT.'/'.PG_CONTROLLER.'/'.$controllerName.'.php'; #控制器文件实际目录
    if(!is_file($controllerFile)){            # 检查控制器文件是否真实存在
        $controllerName = 'index';            #  不存在就修正为默认
        $controllerFile = PG_PATH.'/'.PG_SROOT.'/'.PG_CONTROLLER.'/index.php';  # 重定义为默认首页的index控制器
        PG_404_Check();                       # 检查是否开启报错404模式
    } 
    p(' 控制器相对目录:'.$controllerFile.'<br/>');    // -----------------------------------------------调试输出 
    p('当前控制器名称：'.$controllerName.'<br/>');
    p($includedFiles);
    require $controllerFile;                  # 引入控制器文件 
    p($controllerFile);
    define('PG_C', $controllerName);          # 控制器确定下来，控制器常量 PG_C
    p("<span style='color:red'>当前控制器：".PG_C."</span><br/>");      // -----------------------------------------------调试输出
    $controllerName = $controllerName.'Controller'; 
    $controller = new $controllerName;        # 实例化控制器
    if(!$controller instanceof grace){throw new graceException('[ '.$controllerName.' ] 该控制器 必须继承自 grace',100000);}
	
	 

	 
	 //运行追踪
    if(PG_TRACE){gracesTrace();} 
}catch(graceException $e){$e->showBug();} 
  