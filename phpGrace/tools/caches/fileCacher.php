<?php
/*
 * 文件型缓存支持类
 * 作者 : 深海 5213606@qq.com
*/
namespace phpGrace\tools\caches;

class fileCacher{
	
	private static $cacher = null;
	private $cacheDir      = 'caches';
	
	private function __construct($config){
	    # 如果没有自定义缓存目录常量，则用默认的缓存目录地址
		if(!defined('PG_CACHE_DIR')){ 
			$this->cacheDir = PG_APP_ROOT.'/Cache/'.$this->cacheDir.'/';
		}else{
			$this->cacheDir = PG_CACHE_DIR;
		}
		if(!is_dir($this->cacheDir)){mkdir($this->cacheDir, 0777, true);}
	}
	
	public static function getInstance($config){
		if(self::$cacher == null){self::$cacher = new fileCacher($config);}
		return self::$cacher;
	}
	
	public function get($name){
		$cacheFile = $this->cacheDir.$name.'.php';
		if(!is_file($cacheFile)){return false;}
		$cacheData = require $cacheFile;
		$cacheData = unserialize($cacheData);
		if($cacheData['expire'] < time()){return false;}
		return $cacheData['data'];
	}
	
	public function set($name, $data, $expire){
		$cacheFile = $this->cacheDir.$name.'.php';
		$cacheContent = '<?php
if(!defined("PG_PATH")){exit();}
$data = <<<EOF
';
		$cacheData = array(
			'data'   => $data,
			'expire' => time() + $expire
		);
		$cacheData = str_replace('\\', '\\\\', serialize($cacheData));
		$cacheData = str_replace('$', '\$', $cacheData);
		$cacheContent .=  $cacheData.'
EOF;
return $data;';
		file_put_contents($cacheFile, $cacheContent);
	}
	
	public function removeCache($name){
		$cacheFile = $this->cacheDir.$name.'.php';
		if(!is_file($cacheFile)){return true;}
		unlink($cacheFile);
		return true;
	}
	
	public function clearCache(){
		$files = scandir($this->cacheDir);
		foreach($files as $v){
			if($v != '.' && $v != '..'){
				$cacheUrl = $this->cacheDir.$v;
				if(is_file($cacheUrl)){
					@unlink($cacheUrl);
				}
			}
		}
		return true;
	}
	
	public function close(){
		
	}
}