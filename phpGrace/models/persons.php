<?php
/*
 * 模型类演示 以  person 数据表为例
 * 作者 : 深海 5213606@qq.com
 * 一个自定义缓存类文件的例子, 实现了 persons 数据表 的列表数据及单条数据查询缓存
 * 具体的缓存代码请根据项目情况自行编写
*/
/*--- 对应的数据表, 将 sql 命令导入到您的数据库即可快速创建 persons 数据表  --- */
/*
-- ----------------------------
-- Table structure for `persons`
-- ----------------------------
DROP TABLE IF EXISTS `persons`;
CREATE TABLE `persons` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `age` smallint(3) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `class` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `classid` (`class`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of persons
-- ----------------------------
INSERT INTO `persons` VALUES ('5', '小米', '275', '1594885171', '一年一班');
INSERT INTO `persons` VALUES ('6', 'aa', '116', '1594885197', '一年一班');
INSERT INTO `persons` VALUES ('7', 'aa', '162', '1594885215', '一年一班');
INSERT INTO `persons` VALUES ('8', 'aa', '69', '1594885219', '一年一班');
INSERT INTO `persons` VALUES ('9', 'aa', '139', '1594886100', '一年一班');
*/
namespace phpGrace\models;
class persons extends \graceModel{
	
	// 注意使用缓存技术时 模型初始化不连接数据库节约开销
	// 不定义 public $tableName 属性就不会自动初始化数据表操作
	// 一旦缓存 一个功能执行只需要 0.几毫秒
	
	// 获取人员列表 使用缓存
	// 如果需要参数请 在函数上进行参数传递
	public function getList(){
		return $this->cache('personsList', '','__getList', 10);
	}
	
	// 获取人员数据 非缓存直接查询
	public function __getList(){
		$this->m = db('persons');
		return $this->m->order('id desc')->fetchAll();
	}
	
	// 获取一个具体人员 使用缓存
	// 利用函数进行参数传递
	public function getAPerson($parameter){
		// 将函数收到的参数记录在对象属性内，以便动态方法可以共享到此函数
		// 如果多个参数使用数组进行传递
		$this->parameter = $parameter;
		return $this->cache('person', $parameter ,'__getAPerson', 10);
	}
	
	// 获取人员数据 非缓存直接查询
	public function __getAPerson(){
		$this->m = db('persons');
		return $this->m->where('id = ?', $this->parameter)->fetch();
	}
	
}