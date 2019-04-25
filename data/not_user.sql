/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2017-08-09 11:58:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for quote_area
-- ----------------------------
DROP TABLE IF EXISTS `quote_area`;
CREATE TABLE `quote_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(10) unsigned NOT NULL,
  `area_name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '區域的名字',
  `area_price` float(10,2) DEFAULT NULL COMMENT '區域的交通費',
  `z_index` int(11) DEFAULT '0' COMMENT '區域的層級',
  `min_price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '最小價格',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for quote_business
-- ----------------------------
DROP TABLE IF EXISTS `quote_business`;
CREATE TABLE `quote_business` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '業務名稱',
  `type` int(5) NOT NULL DEFAULT '1' COMMENT '業務類型：0（特殊業務）、1（普通業務）',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '每尺單價',
  `city_id` int(11) NOT NULL COMMENT '業務所屬城市',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='業務表';

-- ----------------------------
-- Table structure for quote_city
-- ----------------------------
DROP TABLE IF EXISTS `quote_city`;
CREATE TABLE `quote_city` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region_id` int(10) NOT NULL DEFAULT '1' COMMENT '地區id',
  `city_name` varchar(30) NOT NULL COMMENT '城市名稱',
  `z_index` int(10) NOT NULL DEFAULT '0' COMMENT '城市的等級',
  `other_open` int(10) NOT NULL DEFAULT '1' COMMENT '是否開啟其它地區',
  `other_price` varchar(30) DEFAULT '0' COMMENT '其它地區的車費',
  `currency_type` varchar(255) NOT NULL DEFAULT 'RMB' COMMENT '貨幣類型',
  `b_unit` varchar(50) NOT NULL DEFAULT 'chi' COMMENT '面積單位',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='城市表';

-- ----------------------------
-- Table structure for quote_order
-- ----------------------------
DROP TABLE IF EXISTS `quote_order`;
CREATE TABLE `quote_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(255) DEFAULT NULL COMMENT '訂單編號',
  `order_name` varchar(20) NOT NULL COMMENT '下單用戶',
  `appellation` varchar(11) NOT NULL COMMENT '用戶稱謂',
  `email` varchar(255) NOT NULL COMMENT '下單email',
  `phone` varchar(255) NOT NULL COMMENT '手機號碼',
  `house_type` int(11) NOT NULL COMMENT '客戶類別：0（住宅）、1（商業）、2（其它）',
  `city_id` int(11) unsigned NOT NULL COMMENT '城市id',
  `area_id` int(10) DEFAULT NULL COMMENT '區域id',
  `address` varchar(255) DEFAULT NULL COMMENT '詳細地址',
  `door_in` int(11) DEFAULT NULL COMMENT '室內面積',
  `door_out` int(11) DEFAULT NULL COMMENT '室外面積',
  `business_id` varchar(255) NOT NULL COMMENT '業務id（逗號分隔）',
  `number` varchar(30) NOT NULL COMMENT '治理次數:one(類型1）、two（類型2）、short（類型3）、five（類型4）',
  `question` text COMMENT '其它問題',
  `status` varchar(20) NOT NULL DEFAULT 'send' COMMENT '訂單狀態send/service/finish',
  `remark` varchar(255) DEFAULT NULL COMMENT '拒絕原因',
  `token` varchar(40) DEFAULT NULL COMMENT '用戶操作訂單的token',
  `total_price` float(30,2) DEFAULT NULL COMMENT '訂單總價',
  `lcu_ip` varchar(50) DEFAULT NULL COMMENT '下單用戶的ip地址',
  `luu_id` int(11) DEFAULT NULL COMMENT '處理訂單的管理員id',
  `lcd` datetime DEFAULT NULL COMMENT '下單時間',
  `lud` datetime DEFAULT NULL COMMENT '處理時間',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='即時報價訂單表';

-- ----------------------------
-- Table structure for quote_region
-- ----------------------------
DROP TABLE IF EXISTS `quote_region`;
CREATE TABLE `quote_region` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region_name` varchar(20) NOT NULL COMMENT '地區名稱',
  `region_email` varchar(255) DEFAULT NULL COMMENT '地區郵箱',
  `z_index` int(10) DEFAULT NULL COMMENT '層級',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='地區表（城市之上）';
