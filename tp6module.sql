/*
Navicat MySQL Data Transfer

Source Server         : test
Source Server Version : 50726
Source Host           : 127.0.0.1:3306
Source Database       : tp6module

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-10-09 12:21:28
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `ad`
-- ----------------------------
DROP TABLE IF EXISTS `ad`;
CREATE TABLE `ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `ad_code` varchar(1000) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ad
-- ----------------------------

-- ----------------------------
-- Table structure for `areas`
-- ----------------------------
DROP TABLE IF EXISTS `areas`;
CREATE TABLE `areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of areas
-- ----------------------------
INSERT INTO areas VALUES ('1', '0-100平米', '1576119349');
INSERT INTO areas VALUES ('2', '100-200平米', '1576119363');
INSERT INTO areas VALUES ('3', '200-500平米', '1576119435');
INSERT INTO areas VALUES ('4', '500-1000平米', '1576119391');
INSERT INTO areas VALUES ('5', '1000-2000平米', '1576119408');
INSERT INTO areas VALUES ('6', '2000平米以上', '1576119424');

-- ----------------------------
-- Table structure for `article`
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `keyword` varchar(200) DEFAULT NULL COMMENT '文章关键词',
  `preface` text COMMENT '文章前言',
  `content` text,
  `description` text NOT NULL,
  `status` tinyint(11) NOT NULL DEFAULT '1',
  `catid` int(11) NOT NULL,
  `thumb` varchar(1000) NOT NULL COMMENT '文件保存路径',
  `sort` int(11) NOT NULL DEFAULT '0',
  `del` tinyint(11) NOT NULL DEFAULT '0',
  `hit` int(11) NOT NULL DEFAULT '0' COMMENT '点击量',
  `link` varchar(225) DEFAULT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of article
-- ----------------------------
INSERT INTO article VALUES ('1', '重启键已经按下，无限极武汉物流配送仓安全复工', '', '严格履行疫情防控责任，防疫、复工两不误。', '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;随着国内的疫情防控形势持续向好，湖北的生活、经济和社会运行秩序正在逐步恢复。武汉也按下了“重启键”，各行各业都在稳步复工复产。</p><p><br/></p><p><img src=\"/fseditor/image/20200419/1587276323314449.jpeg\" title=\"1587276323314449.jpeg\" _src=\"/fseditor/image/20200419/1587276323314449.jpeg\" alt=\"2UYIf7MJvNb9psZ0T4VyL9ibKp76X0k9tGEa4DoLfANwXIFWicG60XyNc7OP8aib9EJaCqL0hQiaBASDJB0D62V3FA.jpeg\"/></p>', '', '1', '10', 'null', '0', '0', '43', '', '1587280354');
INSERT INTO article VALUES ('2', '中国经营网|无限极从源头坚守产品品质', '', '无限极将继续坚守初心，打造更高品质的中草药健康产品和服务。', '<p style=\"text-indent: 32px;\">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;</p><p style=\"text-indent: 32px;\">&nbsp;<strong>编者按：产品品质，是企业获得公众信任，赖以生存和发展的“根本”，而从源头确保原材料的品质，则是“根本”中的“根本”。作为一家致力于为消费者提供高品质健康产品与服务的企业，无限极始终认为，高品质的原材料才能成就高品质的健康产品。多年来，无限极通过原材料溯源之旅、打造“透明工厂”等向消费者和公众展示了公司对高品质的坚守和努力。</strong></p><p style=\"text-indent: 32px;\"><strong><span style=\"color:#3e3e3e;font-family:Helvetica Neue, Helvetica, PingFang SC, Tahoma, Hiragino Sans GB, Microsoft YaHei, 微软雅黑, Arial, sans-serif\"><span style=\"background-color: rgb(255, 255, 255);\"><br/></span></span></strong></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;无限极从源头坚守产品品质的理念和实践，得到了中国经营网、每日经济新闻等权威媒体的关注和报道。</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=\"color: rgb(79, 129, 189);\">&nbsp;<span style=\"text-decoration: underline;\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span></span><br/></p><p><span style=\"color: rgb(79, 129, 189); text-decoration: underline;\"><br/></span></p><p><span style=\"color:#4f81bd\">&nbsp;</span></p><p style=\"text-align: center;\"><span style=\"text-decoration: underline; font-size: 16px;\">本文转载自中国经营网</span></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><p style=\"text-align: center;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2020年特殊时期，人们对健康重要性有了新的认知。近日，中国保健协会副理事长周邦勇表示，特殊时期之后，人们对“储蓄”健康的需求将与日俱增，对健康产业来说，在特殊时期新催生的健康消费需求将得到进一步释放。</p><p><br/></p><p style=\"text-align: center;\"><img src=\"/fseditor/image/20200419/1587277779782780.png\" title=\"1587277779782780.png\" _src=\"/fseditor/image/20200419/1587277779782780.png\" alt=\"2UYIf7MJvNZGnQlKOPoEOTdWmF4sxnicKEyAxcWzgjOoD6BYvClibHt3EMFIx8zjhLD99jqHud0Ls7AFcrbMZOKQ.png\" width=\"690\" height=\"411\" style=\"width: 690px; height: 411px;\"/></p>', '', '1', '11', 'null', '0', '0', '61', '', '1587277861');

-- ----------------------------
-- Table structure for `article_attr`
-- ----------------------------
DROP TABLE IF EXISTS `article_attr`;
CREATE TABLE `article_attr` (
  `arid` int(11) NOT NULL COMMENT '文章表主键',
  `atid` int(11) NOT NULL COMMENT '属性表主键'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of article_attr
-- ----------------------------
INSERT INTO article_attr VALUES ('1', '3');

-- ----------------------------
-- Table structure for `attr`
-- ----------------------------
DROP TABLE IF EXISTS `attr`;
CREATE TABLE `attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `color` varchar(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of attr
-- ----------------------------
INSERT INTO attr VALUES ('1', '头条', 'red', '1', '1576044245');
INSERT INTO attr VALUES ('2', '推荐', 'blue', '1', '1576044258');
INSERT INTO attr VALUES ('3', '置顶', 'orange', '1', '1576044292');

-- ----------------------------
-- Table structure for `auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `auth_group`;
CREATE TABLE `auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` longtext NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of auth_group
-- ----------------------------
INSERT INTO auth_group VALUES ('1', '超级管理员', '1', '1,2,3,4,5,6,7,8,9,10,11,12,15,16,17,18,19,13,20,21,22,23,24,14,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105');
INSERT INTO auth_group VALUES ('2', '普通管理员', '1', '1,2,3,4,5,6,7,8,9,10,11,12,15,13,20,14,25,28,29,30,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105');

-- ----------------------------
-- Table structure for `auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `auth_group_access`;
CREATE TABLE `auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of auth_group_access
-- ----------------------------
INSERT INTO auth_group_access VALUES ('1', '1');
INSERT INTO auth_group_access VALUES ('2', '2');

-- ----------------------------
-- Table structure for `auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级分类id',
  `is_menu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '菜单',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------
INSERT INTO auth_rule VALUES ('1', 'Index/menu', '后端管理', '1', '1', '', '0', '0');
INSERT INTO auth_rule VALUES ('2', 'Index/index', '菜单管理', '1', '1', '', '1', '1');
INSERT INTO auth_rule VALUES ('3', 'Index/welcome', '欢迎页', '1', '1', '', '1', '1');
INSERT INTO auth_rule VALUES ('4', 'Index/logout', '退出', '1', '1', '', '1', '1');
INSERT INTO auth_rule VALUES ('5', 'Setting/menu', '系统设置', '1', '1', '', '0', '1');
INSERT INTO auth_rule VALUES ('6', 'Setting/index', '列表', '1', '1', '', '5', '1');
INSERT INTO auth_rule VALUES ('7', 'Setting/add', '新增', '1', '1', '', '5', '1');
INSERT INTO auth_rule VALUES ('8', 'Setting/update', '编辑', '1', '1', '', '5', '0');
INSERT INTO auth_rule VALUES ('9', 'Setting/del', '删除', '1', '1', '', '5', '0');
INSERT INTO auth_rule VALUES ('10', 'Setting/upload', '文件上传', '1', '1', '', '5', '0');
INSERT INTO auth_rule VALUES ('11', 'Auth/menu', '权限管理', '1', '1', '', '0', '1');
INSERT INTO auth_rule VALUES ('12', 'Auth/index', '用户管理', '1', '1', '', '11', '1');
INSERT INTO auth_rule VALUES ('13', 'Auth/rule', '规则管理', '1', '1', '', '11', '1');
INSERT INTO auth_rule VALUES ('14', 'Auth/group', '角色管理', '1', '1', '', '11', '1');
INSERT INTO auth_rule VALUES ('15', 'Auth/add', '新增', '1', '1', '', '12', '1');
INSERT INTO auth_rule VALUES ('16', 'Auth/update', '编辑', '1', '1', '', '12', '0');
INSERT INTO auth_rule VALUES ('17', 'Auth/del', '删除', '1', '1', '', '12', '0');
INSERT INTO auth_rule VALUES ('18', 'Auth/checkuname', '用户名检测', '1', '1', '', '12', '0');
INSERT INTO auth_rule VALUES ('19', 'Auth/resetpwd', '重置密码', '1', '1', '', '12', '0');
INSERT INTO auth_rule VALUES ('20', 'Auth/addrule', '新增', '1', '1', '', '13', '1');
INSERT INTO auth_rule VALUES ('21', 'Auth/updaterule', '编辑', '1', '1', '', '13', '0');
INSERT INTO auth_rule VALUES ('22', 'Auth/delrule', '删除', '1', '1', '', '13', '0');
INSERT INTO auth_rule VALUES ('23', 'Auth/scomp', '菜单状态', '1', '1', '', '13', '0');
INSERT INTO auth_rule VALUES ('24', 'Auth/stats', '修改状态', '1', '1', '', '13', '0');
INSERT INTO auth_rule VALUES ('25', 'Auth/addgroup', '新增', '1', '1', '', '14', '1');
INSERT INTO auth_rule VALUES ('26', 'Auth/updategroup', '编辑', '1', '1', '', '14', '0');
INSERT INTO auth_rule VALUES ('27', 'Auth/delgroup', '删除', '1', '1', '', '14', '0');
INSERT INTO auth_rule VALUES ('28', 'Models/menu', '模型管理', '1', '1', '', '0', '0');
INSERT INTO auth_rule VALUES ('29', 'Models/index', '列表', '1', '1', '', '28', '1');
INSERT INTO auth_rule VALUES ('30', 'Models/add', '新增', '1', '1', '', '28', '1');
INSERT INTO auth_rule VALUES ('31', 'Models/update', '编辑', '1', '1', '', '28', '0');
INSERT INTO auth_rule VALUES ('32', 'Models/del', '删除', '1', '1', '', '28', '0');
INSERT INTO auth_rule VALUES ('33', 'Cate/menu', '分类管理', '1', '1', '', '0', '1');
INSERT INTO auth_rule VALUES ('34', 'Cate/index', '列表', '1', '1', '', '33', '1');
INSERT INTO auth_rule VALUES ('35', 'Cate/add', '新增', '1', '1', '', '33', '1');
INSERT INTO auth_rule VALUES ('36', 'Cate/update', '编辑', '1', '1', '', '33', '0');
INSERT INTO auth_rule VALUES ('37', 'Cate/del', '删除', '1', '1', '', '33', '0');
INSERT INTO auth_rule VALUES ('38', 'Cate/upload', '上传', '1', '1', '', '33', '0');
INSERT INTO auth_rule VALUES ('39', 'Cate/checkcatname', '名称检测', '1', '1', '', '33', '0');
INSERT INTO auth_rule VALUES ('40', 'Article/menu', '文章管理', '1', '1', '', '0', '1');
INSERT INTO auth_rule VALUES ('41', 'Article/index', '列表', '1', '1', '', '40', '1');
INSERT INTO auth_rule VALUES ('42', 'Article/add', '新增', '1', '1', '', '40', '1');
INSERT INTO auth_rule VALUES ('43', 'Article/update', '编辑', '1', '1', '', '40', '0');
INSERT INTO auth_rule VALUES ('44', 'Article/del', '删除', '1', '1', '', '40', '0');
INSERT INTO auth_rule VALUES ('45', 'Article/upload', '上传', '1', '1', '', '40', '0');
INSERT INTO auth_rule VALUES ('46', 'Article/checktitle', '标题重复检测', '1', '1', '', '40', '0');
INSERT INTO auth_rule VALUES ('47', 'Article/sorts', '排序', '1', '1', '', '40', '0');
INSERT INTO auth_rule VALUES ('48', 'Article/sortcomp', '排序对比', '1', '1', '', '40', '0');
INSERT INTO auth_rule VALUES ('49', 'Article/totrach', '移到回收站/还原', '1', '1', '', '40', '0');
INSERT INTO auth_rule VALUES ('50', 'Article/totrachall', '批量移到回收站', '1', '1', '', '40', '0');
INSERT INTO auth_rule VALUES ('51', 'Article/backtrachall', '批量还原', '1', '1', '', '40', '0');
INSERT INTO auth_rule VALUES ('52', 'Article/trach', '回收站', '1', '1', '', '40', '1');
INSERT INTO auth_rule VALUES ('53', 'Attr/menu', '属性管理', '1', '1', '', '0', '0');
INSERT INTO auth_rule VALUES ('54', 'Attr/index', '列表', '1', '1', '', '53', '1');
INSERT INTO auth_rule VALUES ('55', 'Attr/add', '新增', '1', '1', '', '53', '1');
INSERT INTO auth_rule VALUES ('56', 'Attr/update', '编辑', '1', '1', '', '53', '0');
INSERT INTO auth_rule VALUES ('57', 'Attr/del', '删除', '1', '1', '', '53', '0');
INSERT INTO auth_rule VALUES ('58', 'Position/menu', '广告位管理', '1', '1', '', '0', '0');
INSERT INTO auth_rule VALUES ('59', 'Position/index', '列表', '1', '1', '', '58', '1');
INSERT INTO auth_rule VALUES ('60', 'Position/add', '新增', '1', '1', '', '58', '1');
INSERT INTO auth_rule VALUES ('61', 'Position/update', '编辑', '1', '1', '', '58', '10');
INSERT INTO auth_rule VALUES ('62', 'Position/del', '删除', '1', '1', '', '58', '0');
INSERT INTO auth_rule VALUES ('63', 'Ad/menu', '广告管理', '1', '1', '', '0', '1');
INSERT INTO auth_rule VALUES ('64', 'Ad/index', '列表', '1', '1', '', '63', '1');
INSERT INTO auth_rule VALUES ('65', 'Ad/add', '新增', '1', '1', '', '63', '1');
INSERT INTO auth_rule VALUES ('66', 'Ad/update', '编辑', '1', '1', '', '63', '0');
INSERT INTO auth_rule VALUES ('67', 'Ad/del', '删除', '1', '1', '', '63', '0');
INSERT INTO auth_rule VALUES ('68', 'Ad/upload', '上传', '1', '1', '', '63', '0');
INSERT INTO auth_rule VALUES ('69', 'Ad/checkname', '标题重复检测', '1', '1', '', '63', '0');
INSERT INTO auth_rule VALUES ('70', 'Links/menu', '友链管理', '1', '1', '', '0', '1');
INSERT INTO auth_rule VALUES ('71', 'Links/index', '列表', '1', '1', '', '70', '1');
INSERT INTO auth_rule VALUES ('72', 'Links/add', '新增', '1', '1', '', '70', '1');
INSERT INTO auth_rule VALUES ('73', 'Links/update', '编辑', '1', '1', '', '70', '0');
INSERT INTO auth_rule VALUES ('74', 'Links/del', '删除', '1', '1', '', '70', '0');
INSERT INTO auth_rule VALUES ('75', 'Links/upload', '上传', '1', '1', '', '70', '0');
INSERT INTO auth_rule VALUES ('76', 'Links/checkname', '名称重复性检测', '1', '1', '', '70', '0');
INSERT INTO auth_rule VALUES ('77', 'Fsmsg/menu', '留言管理', '1', '1', '', '0', '1');
INSERT INTO auth_rule VALUES ('78', 'Fsmsg/index', '列表', '1', '1', '', '77', '1');
INSERT INTO auth_rule VALUES ('79', 'Fsmsg/add', '新增', '1', '1', '', '77', '1');
INSERT INTO auth_rule VALUES ('80', 'Fsmsg/update', '编辑', '1', '1', '', '77', '0');
INSERT INTO auth_rule VALUES ('81', 'Fsmsg/del', '删除', '1', '1', '', '77', '0');
INSERT INTO auth_rule VALUES ('82', 'Spaces/menu', '类型管理', '1', '1', '', '0', '0');
INSERT INTO auth_rule VALUES ('83', 'Spaces/index', '列表', '1', '1', '', '82', '1');
INSERT INTO auth_rule VALUES ('84', 'Spaces/add', '新增', '1', '1', '', '82', '1');
INSERT INTO auth_rule VALUES ('85', 'Spaces/update', '编辑', '1', '1', '', '82', '0');
INSERT INTO auth_rule VALUES ('86', 'Spaces/del', '删除', '1', '1', '', '82', '0');
INSERT INTO auth_rule VALUES ('87', 'Areas/menu', '面积管理', '1', '1', '', '0', '0');
INSERT INTO auth_rule VALUES ('88', 'Areas/index', '列表', '1', '1', '', '87', '1');
INSERT INTO auth_rule VALUES ('89', 'Areas/add', '新增', '1', '1', '', '87', '1');
INSERT INTO auth_rule VALUES ('90', 'Areas/update', '编辑', '1', '1', '', '87', '0');
INSERT INTO auth_rule VALUES ('91', 'Areas/del', '删除', '1', '1', '', '87', '0');
INSERT INTO auth_rule VALUES ('92', 'Designers/menu', '设计师管理', '1', '1', '', '0', '0');
INSERT INTO auth_rule VALUES ('93', 'Designers/index', '列表', '1', '1', '', '92', '1');
INSERT INTO auth_rule VALUES ('94', 'Designers/add', '新增', '1', '1', '', '92', '1');
INSERT INTO auth_rule VALUES ('95', 'Designers/update', '编辑', '1', '1', '', '92', '0');
INSERT INTO auth_rule VALUES ('96', 'Designers/del', '删除', '1', '1', '', '92', '0');
INSERT INTO auth_rule VALUES ('97', 'Designers/upload', '上传', '1', '1', '', '92', '0');
INSERT INTO auth_rule VALUES ('98', 'Designers/checkname', '名称检测', '1', '1', '', '92', '0');
INSERT INTO auth_rule VALUES ('99', 'Cases/menu', '案例管理', '1', '1', '', '0', '0');
INSERT INTO auth_rule VALUES ('100', 'Cases/index', '列表', '1', '1', '', '99', '1');
INSERT INTO auth_rule VALUES ('101', 'Cases/add', '新增', '1', '1', '', '99', '1');
INSERT INTO auth_rule VALUES ('102', 'Cases/update', '编辑', '1', '1', '', '99', '0');
INSERT INTO auth_rule VALUES ('103', 'Cases/del', '删除', '1', '1', '', '99', '0');
INSERT INTO auth_rule VALUES ('104', 'Cases/upload', '上传', '1', '1', '', '99', '0');
INSERT INTO auth_rule VALUES ('105', 'Cases/checkname', '名称检测', '1', '1', '', '99', '0');

-- ----------------------------
-- Table structure for `cases`
-- ----------------------------
DROP TABLE IF EXISTS `cases`;
CREATE TABLE `cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_code` text NOT NULL COMMENT '图片',
  `title` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `description` text,
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL,
  `hit` int(11) NOT NULL DEFAULT '0' COMMENT '人气',
  `address` varchar(255) NOT NULL COMMENT '地址',
  `area` int(11) NOT NULL COMMENT '面积',
  `price` varchar(225) NOT NULL COMMENT '造价',
  `designer_id` int(11) NOT NULL COMMENT '设计表外键',
  `space_id` int(11) NOT NULL COMMENT '空间表外键',
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cases
-- ----------------------------

-- ----------------------------
-- Table structure for `cate`
-- ----------------------------
DROP TABLE IF EXISTS `cate`;
CREATE TABLE `cate` (
  `catid` int(11) NOT NULL AUTO_INCREMENT,
  `catname` varchar(120) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(120) NOT NULL,
  `keyword` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `mid` int(11) NOT NULL COMMENT '模型外键',
  `thumb` varchar(1024) NOT NULL,
  `ftitle` varchar(225) NOT NULL COMMENT '副标题',
  PRIMARY KEY (`catid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of cate
-- ----------------------------
INSERT INTO cate VALUES ('1', '公司要闻', '0', '', '', '', '1', '1587273768', '0', '1', 'null', '');
INSERT INTO cate VALUES ('2', '走进无限极', '0', '', '', '', '1', '1587273806', '0', '1', 'null', '');
INSERT INTO cate VALUES ('3', '健康人生', '0', '', '', '', '1', '1587273828', '0', '1', 'null', '');
INSERT INTO cate VALUES ('4', '社会责任', '0', '', '', '', '1', '1587273838', '0', '1', 'null', '');
INSERT INTO cate VALUES ('5', '服务大厅', '0', '', '', '', '1', '1587273854', '0', '1', 'null', '');
INSERT INTO cate VALUES ('6', '其他', '0', '', '', '', '1', '1587273865', '0', '1', 'null', '');
INSERT INTO cate VALUES ('7', '公司动态', '1', '', '', '', '1', '1587273879', '0', '1', 'null', '');
INSERT INTO cate VALUES ('8', '产品快讯', '1', '', '', '', '1', '1587273902', '0', '1', 'null', '');
INSERT INTO cate VALUES ('9', '事业无限', '1', '', '', '', '1', '1587273919', '0', '1', 'null', '');
INSERT INTO cate VALUES ('10', '公司新闻', '7', '', '', '', '1', '1587273936', '0', '1', 'null', '');
INSERT INTO cate VALUES ('11', '媒体报道', '7', '', '', '', '1', '1587273951', '0', '1', 'null', '');
INSERT INTO cate VALUES ('12', '数码平台推广', '7', '', '', '', '1', '1587273968', '0', '1', 'null', '');
INSERT INTO cate VALUES ('13', '地方风采', '7', '', '', '', '1', '1587273986', '0', '1', 'null', '');
INSERT INTO cate VALUES ('14', '产品资讯', '8', '', '', '', '1', '1587274006', '0', '1', 'null', '');
INSERT INTO cate VALUES ('15', '售后资讯', '8', '', '', '', '1', '1587274034', '0', '1', 'null', '');
INSERT INTO cate VALUES ('16', '海外培训', '9', '', '', '', '1', '1587274077', '0', '1', 'null', '');
INSERT INTO cate VALUES ('17', '规范专区', '9', '', '', '', '1', '1587274090', '0', '1', 'null', '');
INSERT INTO cate VALUES ('18', '董事长寄语', '2', '', '', '', '1', '1587274106', '0', '1', 'null', '');
INSERT INTO cate VALUES ('19', '公司介绍', '2', '', '', '', '1', '1587274167', '0', '1', 'null', '');
INSERT INTO cate VALUES ('20', '企业文化', '2', '', '', '', '1', '1587274180', '0', '1', 'null', '');
INSERT INTO cate VALUES ('21', '健康理念', '2', '', '', '', '1', '1587274200', '0', '1', 'null', '');
INSERT INTO cate VALUES ('22', '公司简介', '19', '', '', '', '1', '1587274218', '0', '1', 'null', '');
INSERT INTO cate VALUES ('23', '发展历程', '19', '', '', '', '1', '1587274234', '0', '1', 'null', '');
INSERT INTO cate VALUES ('24', '企业荣誉', '19', '', '', '', '1', '1587274247', '0', '1', 'null', '');
INSERT INTO cate VALUES ('25', '企业之歌', '19', '', '', '', '1', '1587274259', '0', '1', 'null', '');
INSERT INTO cate VALUES ('26', '思利及人', '20', '', '', '', '1', '1587274275', '0', '1', 'null', '');
INSERT INTO cate VALUES ('27', '自动波', '20', '', '', '', '1', '1587274299', '0', '1', 'null', '');
INSERT INTO cate VALUES ('28', '永远创业', '20', '', '', '', '1', '1587274317', '0', '1', 'null', '');
INSERT INTO cate VALUES ('29', '解读', '21', '', '', '', '1', '1587274337', '0', '1', 'null', '');
INSERT INTO cate VALUES ('30', '养生固本', '3', '', '', '', '1', '1587274354', '0', '1', 'null', '');
INSERT INTO cate VALUES ('31', '养生之道', '30', '', '', '', '1', '1587274367', '0', '1', 'null', '');
INSERT INTO cate VALUES ('32', '大咖说养生', '30', '', '', '', '1', '1587274382', '0', '1', 'null', '');
INSERT INTO cate VALUES ('33', '社会责任观', '4', '', '', '', '1', '1587274398', '0', '1', 'null', '');
INSERT INTO cate VALUES ('34', 'CSR报告', '4', '', '', '', '1', '1587274419', '0', '1', 'null', '');
INSERT INTO cate VALUES ('35', '思利及人公益基金会', '4', '', '', '', '1', '1587274451', '0', '1', 'null', '');
INSERT INTO cate VALUES ('36', '公益动态', '4', '', '', '', '1', '1587274486', '0', '1', 'null', '');
INSERT INTO cate VALUES ('37', '联系我们', '5', '', '', '', '1', '1587274504', '0', '1', 'null', '');
INSERT INTO cate VALUES ('38', '企业购', '5', '', '', '', '1', '1587274525', '0', '1', 'null', '');
INSERT INTO cate VALUES ('39', '客服热线', '37', '', '', '', '1', '1587274541', '0', '1', 'null', '');
INSERT INTO cate VALUES ('40', '专业售前跟进', '38', '', '', '', '1', '1587274573', '0', '1', 'null', '');
INSERT INTO cate VALUES ('41', '灵活物流配送', '38', '', '', '', '1', '1587274587', '0', '1', 'null', '');
INSERT INTO cate VALUES ('42', '尊享售后支持', '38', '', '', '', '1', '1587274608', '0', '1', 'null', '');
INSERT INTO cate VALUES ('43', '应用中心', '6', '', '', '', '1', '1587274622', '0', '1', 'null', '');
INSERT INTO cate VALUES ('44', '图库', '6', '', '', '', '1', '1587274652', '0', '1', 'null', '');
INSERT INTO cate VALUES ('45', '视频', '6', '', '', '', '1', '1587274683', '0', '1', 'null', '');

-- ----------------------------
-- Table structure for `designers`
-- ----------------------------
DROP TABLE IF EXISTS `designers`;
CREATE TABLE `designers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL COMMENT '姓名',
  `adv` varchar(225) NOT NULL COMMENT '头像',
  `ranks` varchar(255) NOT NULL COMMENT '头衔',
  `experience` int(11) NOT NULL COMMENT '经验',
  `title` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL,
  `hit` int(11) NOT NULL DEFAULT '0' COMMENT '人气',
  `recom` int(11) NOT NULL COMMENT '推荐指数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of designers
-- ----------------------------

-- ----------------------------
-- Table structure for `fsmsg`
-- ----------------------------
DROP TABLE IF EXISTS `fsmsg`;
CREATE TABLE `fsmsg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `from_ip` varchar(100) NOT NULL COMMENT '来源ip',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of fsmsg
-- ----------------------------
INSERT INTO fsmsg VALUES ('2', '测试', '15156061389', '1579403508', '3', '127.0.0.1', '测试');
INSERT INTO fsmsg VALUES ('3', '测试', '15156061388', '1579406402', '0', '127.0.0.1', '测试');

-- ----------------------------
-- Table structure for `i_user`
-- ----------------------------
DROP TABLE IF EXISTS `i_user`;
CREATE TABLE `i_user` (
  `id` int(12) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `tel` bigint(11) NOT NULL,
  `create_time` bigint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of i_user
-- ----------------------------
INSERT INTO i_user VALUES ('1', '胡歌', '$10$xUDlL9qjtImDNfByeX.Qo.uXxDvqUexPtQ/39uARstcaxE8FhFEGO', '15856325658', '1258456958');
INSERT INTO i_user VALUES ('2', '张学友', '$10$xUDlL9qjtImDNfByeX.Qo.uXxDvqUexPtQ/39uARstcaxE8FhFEGO', '18956854758', '1589657458');

-- ----------------------------
-- Table structure for `jmd`
-- ----------------------------
DROP TABLE IF EXISTS `jmd`;
CREATE TABLE `jmd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL COMMENT '加盟店名称',
  `addr` varchar(225) NOT NULL COMMENT '地址',
  `tel` varchar(120) NOT NULL COMMENT '电话',
  `create_time` int(11) NOT NULL,
  `jwd` varchar(120) NOT NULL COMMENT '经纬度',
  `keys` text NOT NULL COMMENT '关键词',
  `status` int(11) NOT NULL,
  `pid` int(11) NOT NULL COMMENT '品类id',
  `city` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of jmd
-- ----------------------------

-- ----------------------------
-- Table structure for `jms`
-- ----------------------------
DROP TABLE IF EXISTS `jms`;
CREATE TABLE `jms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL COMMENT '加盟店名称',
  `addr` varchar(225) DEFAULT NULL COMMENT '地址',
  `tel` varchar(120) DEFAULT NULL COMMENT '电话',
  `create_time` int(11) NOT NULL,
  `jwd` text NOT NULL COMMENT '技术',
  `keys` text NOT NULL COMMENT '关键词',
  `status` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL COMMENT '品类id',
  `city` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of jms
-- ----------------------------

-- ----------------------------
-- Table structure for `links`
-- ----------------------------
DROP TABLE IF EXISTS `links`;
CREATE TABLE `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `ad_code` varchar(1000) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of links
-- ----------------------------
INSERT INTO links VALUES ('1', '百度s', 'https://www.baidu.com', '[\"files\\/20200118\\/ed9352bbedd213fb6b34897abc3c3891.png\"]', '1', '1579340450');

-- ----------------------------
-- Table structure for `models`
-- ----------------------------
DROP TABLE IF EXISTS `models`;
CREATE TABLE `models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of models
-- ----------------------------
INSERT INTO models VALUES ('1', '文章模型', '1575943160');
INSERT INTO models VALUES ('2', '单页模型', '1575943168');
INSERT INTO models VALUES ('3', '案例模型', '1575943174');
INSERT INTO models VALUES ('4', '其他模型', '1579312881');

-- ----------------------------
-- Table structure for `pat`
-- ----------------------------
DROP TABLE IF EXISTS `pat`;
CREATE TABLE `pat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pat
-- ----------------------------

-- ----------------------------
-- Table structure for `position`
-- ----------------------------
DROP TABLE IF EXISTS `position`;
CREATE TABLE `position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '广告位名称',
  `description` text NOT NULL COMMENT '广告位描述',
  `create_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of position
-- ----------------------------
INSERT INTO position VALUES ('1', '小程序banner', '小程序banner', '1579396838', '1');
INSERT INTO position VALUES ('2', '首页轮播图', '首页轮播图', '1587288470', '1');

-- ----------------------------
-- Table structure for `setting`
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(225) NOT NULL,
  `keyword` text NOT NULL,
  `description` text NOT NULL,
  `tel` varchar(100) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `bah` varchar(100) DEFAULT NULL COMMENT '备案号',
  `ad_code` varchar(225) DEFAULT NULL COMMENT '微信',
  `qq` varchar(100) DEFAULT NULL COMMENT 'qq',
  `name` varchar(255) DEFAULT NULL,
  `jszc` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of setting
-- ----------------------------
INSERT INTO setting VALUES ('1', '网站标题', '网站关键词', '网站描述', '', '13966651771', 'xxxxxxxxxxxxxxxx', '[\"files\\/20200412\\/e5256fca7d4b7676d56b6150c2391730.gif\"]', 'xxxxxxxxx', '网站名称', 'xxxxxxxxx', 'xxxxxxxxx');

-- ----------------------------
-- Table structure for `spaces`
-- ----------------------------
DROP TABLE IF EXISTS `spaces`;
CREATE TABLE `spaces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of spaces
-- ----------------------------
INSERT INTO spaces VALUES ('1', '餐饮空间', '1576118538');
INSERT INTO spaces VALUES ('2', '教育培训', '1576118532');
INSERT INTO spaces VALUES ('3', '办公空间', '1576118555');
INSERT INTO spaces VALUES ('4', '店铺空间', '1576118566');
INSERT INTO spaces VALUES ('5', '会所空间', '1576118577');
INSERT INTO spaces VALUES ('6', '休闲空间', '1576118588');
INSERT INTO spaces VALUES ('7', '娱乐空间', '1576118598');
INSERT INTO spaces VALUES ('8', '酒店空间', '1576118872');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(120) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(11) NOT NULL DEFAULT '1',
  `login_ip` varchar(120) NOT NULL,
  PRIMARY KEY (`uid`) USING BTREE,
  KEY `uname` (`uname`) USING BTREE,
  KEY `uname_2` (`uname`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO users VALUES ('1', 'chloe', '$2y$2y$10$xUDlL9qjtImDNfByeX.Qo.uXxDvqUexPtQ/39uARstcaxE8FhFEGO', '1575942986', '1', '127.0.0.1');
INSERT INTO users VALUES ('2', 'admin', '$2y$2y$10$xUDlL9qjtImDNfByeX.Qo.uXxDvqUexPtQ/39uARstcaxE8FhFEGO', '1575943082', '1', '127.0.0.1');

-- ----------------------------
-- Table structure for `wxad`
-- ----------------------------
DROP TABLE IF EXISTS `wxad`;
CREATE TABLE `wxad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `ad_code` varchar(1000) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of wxad
-- ----------------------------
