/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2018-05-04 14:34:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for quote_city
-- ----------------------------
DROP TABLE IF EXISTS `quote_city`;
CREATE TABLE `quote_city` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region_id` int(10) NOT NULL DEFAULT '1' COMMENT '地區id',
  `city_name` varchar(30) NOT NULL COMMENT '城市名稱',
  `city_name_us` varchar(255) DEFAULT NULL,
  `city_name_tw` varchar(255) DEFAULT NULL,
  `z_index` int(10) NOT NULL DEFAULT '0' COMMENT '城市的等級',
  `other_open` int(10) NOT NULL DEFAULT '1' COMMENT '是否開啟其它地區',
  `other_price` varchar(30) DEFAULT '0' COMMENT '其它地區的車費',
  `other_min` varchar(255) DEFAULT NULL COMMENT '其它區域的最低價格',
  `currency_type` varchar(255) NOT NULL DEFAULT 'RMB' COMMENT '貨幣類型',
  `b_unit` varchar(50) NOT NULL DEFAULT 'chi' COMMENT '面積單位',
  `company` varchar(255) DEFAULT NULL COMMENT '公司名字',
  `company_us` varchar(255) DEFAULT NULL,
  `company_tw` varchar(255) DEFAULT NULL,
  `seal` varchar(255) DEFAULT NULL COMMENT '印章',
  `terms_tw` text COMMENT '條款細則',
  `terms_us` text COMMENT '條款細則',
  `terms` text COMMENT '條款細則',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='城市表';
