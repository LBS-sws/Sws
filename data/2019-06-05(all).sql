/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2019-06-05 17:57:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for quote_area
-- ----------------------------
DROP TABLE IF EXISTS `quote_area`;
CREATE TABLE `quote_area` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `city_id` int(10) NOT NULL,
  `area_name` varchar(255) NOT NULL COMMENT '區域的名字',
  `area_name_tw` varchar(100) DEFAULT NULL,
  `area_name_us` varchar(100) DEFAULT NULL,
  `area_price` float(10,2) DEFAULT NULL COMMENT '區域的交通費',
  `z_index` int(11) DEFAULT '0' COMMENT '區域的層級',
  `min_price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '最小價格',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for quote_business
-- ----------------------------
DROP TABLE IF EXISTS `quote_business`;
CREATE TABLE `quote_business` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '業務名稱',
  `name_tw` varchar(100) DEFAULT NULL,
  `name_us` varchar(100) DEFAULT NULL,
  `type` int(5) NOT NULL DEFAULT '1' COMMENT '業務類型：0（特殊業務）、1（普通業務）',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '每尺單價',
  `city_id` int(11) NOT NULL COMMENT '業務所屬城市',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=230 DEFAULT CHARSET=utf8 COMMENT='業務表';

-- ----------------------------
-- Table structure for quote_city
-- ----------------------------
DROP TABLE IF EXISTS `quote_city`;
CREATE TABLE `quote_city` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region_id` int(10) NOT NULL DEFAULT '1' COMMENT '地區id',
  `city_name` varchar(30) NOT NULL COMMENT '城市名稱',
  `city_name_tw` varchar(100) DEFAULT NULL,
  `city_name_us` varchar(100) DEFAULT NULL,
  `z_index` int(10) NOT NULL DEFAULT '0' COMMENT '城市的等級',
  `other_open` int(10) NOT NULL DEFAULT '1' COMMENT '是否開啟其它地區',
  `other_price` varchar(30) DEFAULT '0' COMMENT '其它地區的車費',
  `other_min` varchar(255) DEFAULT NULL COMMENT '其它區域的最低價格',
  `currency_type` varchar(255) NOT NULL DEFAULT 'RMB' COMMENT '貨幣類型',
  `b_unit` varchar(50) NOT NULL DEFAULT 'chi' COMMENT '面積單位',
  `company` varchar(255) DEFAULT NULL COMMENT '公司名字',
  `company_tw` varchar(255) DEFAULT NULL,
  `company_us` varchar(255) DEFAULT NULL,
  `seal` varchar(255) DEFAULT NULL COMMENT '印章',
  `terms` text,
  `terms_tw` text,
  `terms_us` text,
  `service_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COMMENT='城市表';

-- ----------------------------
-- Table structure for quote_email
-- ----------------------------
DROP TABLE IF EXISTS `quote_email`;
CREATE TABLE `quote_email` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email_prefix` varchar(255) NOT NULL COMMENT '郵箱尾綴',
  `email` varchar(255) NOT NULL COMMENT '郵箱官網',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for quote_order
-- ----------------------------
DROP TABLE IF EXISTS `quote_order`;
CREATE TABLE `quote_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_type` int(10) NOT NULL DEFAULT '1' COMMENT '0（特殊業務）、1（普通業務）、2（混合業務）',
  `order_code` varchar(255) DEFAULT NULL COMMENT '訂單編號',
  `order_name` varchar(100) NOT NULL COMMENT '下單用戶',
  `appellation` varchar(11) NOT NULL COMMENT '用戶稱謂',
  `email` varchar(255) NOT NULL COMMENT '下單email',
  `phone` varchar(255) NOT NULL COMMENT '手機號碼',
  `house_type` int(11) NOT NULL DEFAULT '0' COMMENT '客戶類別：0（住宅）、1（商業）、2（其它）',
  `city_id` int(11) unsigned NOT NULL COMMENT '城市id',
  `area_id` int(10) DEFAULT NULL COMMENT '區域id',
  `address` varchar(255) DEFAULT NULL COMMENT '詳細地址',
  `door_in` int(11) DEFAULT NULL COMMENT '室內面積',
  `door_out` int(11) DEFAULT NULL COMMENT '室外面積',
  `business_id` varchar(255) NOT NULL COMMENT '業務id（逗號分隔）',
  `number` varchar(30) NOT NULL DEFAULT '1' COMMENT '治理次數:one(類型1）、two（類型2）、short（類型3）、five（類型4）',
  `question` text COMMENT '其它問題',
  `remark` varchar(255) DEFAULT NULL COMMENT '拒絕原因',
  `token` varchar(40) DEFAULT NULL COMMENT '用戶操作訂單的token',
  `lcu_ip` varchar(50) DEFAULT NULL COMMENT '下單用戶的ip地址',
  `luu_id` int(11) DEFAULT NULL COMMENT '處理訂單的管理員id',
  `from_order` int(3) DEFAULT '0' COMMENT '1:微信下單  0：PC端',
  `lcd` datetime DEFAULT NULL COMMENT '下單時間',
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '處理時間',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1286 DEFAULT CHARSET=utf8 COMMENT='即時報價訂單表';

-- ----------------------------
-- Table structure for quote_order_bus
-- ----------------------------
DROP TABLE IF EXISTS `quote_order_bus`;
CREATE TABLE `quote_order_bus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `bus_type` int(11) DEFAULT '1' COMMENT '害蟲的類型',
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3647 DEFAULT CHARSET=utf8 COMMENT='訂單含有的害蟲表';

-- ----------------------------
-- Table structure for quote_order_his
-- ----------------------------
DROP TABLE IF EXISTS `quote_order_his`;
CREATE TABLE `quote_order_his` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sta_id` int(11) NOT NULL COMMENT '訂單狀態的id',
  `status` varchar(255) DEFAULT NULL COMMENT '訂單歷史狀態',
  `lcu` varchar(255) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5967 DEFAULT CHARSET=utf8 COMMENT='訂單記錄表';

-- ----------------------------
-- Table structure for quote_order_sta
-- ----------------------------
DROP TABLE IF EXISTS `quote_order_sta`;
CREATE TABLE `quote_order_sta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `s_code` varchar(255) DEFAULT NULL COMMENT '訂單編號',
  `order_id` int(11) NOT NULL,
  `s_type` int(11) NOT NULL DEFAULT '1' COMMENT '0（特殊業務）、1（普通業務）',
  `status` varchar(255) NOT NULL DEFAULT 'send' COMMENT '訂單狀態send/service/finish',
  `total_price` float(30,2) DEFAULT NULL COMMENT '訂單總價',
  `remark` varchar(255) DEFAULT NULL COMMENT '拒絕原因',
  `service_time` datetime DEFAULT NULL COMMENT '服務日期',
  `service_time_end` datetime DEFAULT NULL,
  `send_email` int(11) DEFAULT '0' COMMENT '是否已經發送過期提示郵件：0未發送，1已發送',
  `kehu_set` int(2) NOT NULL DEFAULT '0' COMMENT '0:客戶可以操作  1:客戶不允許操作',
  `kehu_lang` varchar(255) DEFAULT NULL COMMENT '客戶下單語言',
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1634 DEFAULT CHARSET=utf8 COMMENT='訂單的狀態表';

-- ----------------------------
-- Table structure for quote_order_wechat
-- ----------------------------
DROP TABLE IF EXISTS `quote_order_wechat`;
CREATE TABLE `quote_order_wechat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(100) NOT NULL,
  `order_sta_id` int(11) NOT NULL,
  `latitude` varchar(100) DEFAULT NULL COMMENT '纬度',
  `longitude` varchar(100) DEFAULT NULL COMMENT '经度',
  `weChat_state` int(3) NOT NULL DEFAULT '0' COMMENT '狀態（無用） 0:未付款',
  `weChat_remark` text COMMENT '客戶對報價的評價',
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='微信訂單綁定';

-- ----------------------------
-- Table structure for quote_region
-- ----------------------------
DROP TABLE IF EXISTS `quote_region`;
CREATE TABLE `quote_region` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region_name` varchar(20) NOT NULL COMMENT '地區名稱',
  `region_name_tw` varchar(100) DEFAULT NULL,
  `region_name_us` varchar(100) DEFAULT NULL,
  `z_index` int(10) DEFAULT NULL COMMENT '層級',
  `web_prefix` varchar(255) DEFAULT 'cn' COMMENT '訂單編號前綴',
  `www_fix` varchar(255) DEFAULT NULL COMMENT '域名後綴（多個後綴用|分割）',
  `calculation` int(2) NOT NULL DEFAULT '0' COMMENT '害虫合并计算 0：分開計算  1：合併計算',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='地區表（城市之上）';

-- ----------------------------
-- Table structure for quote_user
-- ----------------------------
DROP TABLE IF EXISTS `quote_user`;
CREATE TABLE `quote_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) NOT NULL COMMENT '姓名',
  `city_auth` varchar(255) DEFAULT NULL COMMENT '管轄城市',
  `auth` varchar(255) DEFAULT NULL COMMENT '權限',
  `password` varchar(255) NOT NULL COMMENT '密碼',
  `phone` varchar(255) DEFAULT NULL COMMENT '電話',
  `nickname` varchar(255) DEFAULT NULL COMMENT '用戶暱稱',
  `email` varchar(255) DEFAULT NULL COMMENT '用戶email',
  `email_hint` int(10) NOT NULL DEFAULT '0' COMMENT '郵件提示：0（無）、1（開啟）、2（一般害蟲）、3（特殊害蟲）',
  `old_email` int(2) NOT NULL DEFAULT '0' COMMENT '訂單過期提示。0：關閉  1：開啟',
  `lang` varchar(255) DEFAULT 'zh-cn' COMMENT '管理員語言',
  `more_hint` int(2) NOT NULL DEFAULT '0' COMMENT '需專人聯繫提示  0：關閉  1：開啟',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COMMENT='管理員';
