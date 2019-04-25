/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2018-05-05 11:44:59
*/

SET FOREIGN_KEY_CHECKS=0;

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
  `send_email` int(11) DEFAULT '0' COMMENT '是否已經發送過郵箱提示：0無，1是',
  `kehu_set` int(2) NOT NULL DEFAULT '0' COMMENT '0:客戶可以操作  1:客戶不允許操作',
  `kehu_lang` varchar(255) DEFAULT NULL COMMENT '客戶選擇的語言',
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` datetime DEFAULT NULL,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 COMMENT='訂單的狀態表';
