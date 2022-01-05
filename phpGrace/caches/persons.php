<?php
/*
 * 缓存类演示 以  person 数据表为例
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
namespace phpGrace\caches;

class persons extends \cacheBase{
	
	// 外部调用使用 cache 函数，自定义类只需实现数据获取算法
	public function __getPersonsList(){
		// 创建 数据表操作对象
		echo 'I am __getPersonsList, i have runed ......';
		$db = db('persons');
		return $db->order('id desc')->fetchAll();
	}
	
	public function __getAPerson(){
		echo 'I am __getAPerson, i have runed ......';
		$db = db('persons');
		return $db->where('id = ?', array($this->personid))->fetch();
	}
	
}