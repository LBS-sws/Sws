/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2018-02-28 15:20:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for quote_user
-- ----------------------------
DROP TABLE IF EXISTS `quote_user`;
CREATE TABLE `quote_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(10) NOT NULL COMMENT '姓名',
  `city_auth` varchar(255) DEFAULT NULL COMMENT '管轄城市',
  `auth` varchar(255) DEFAULT NULL COMMENT '權限',
  `password` varchar(255) NOT NULL COMMENT '密碼',
  `phone` varchar(255) DEFAULT NULL COMMENT '電話',
  `nickname` varchar(255) DEFAULT NULL COMMENT '用戶暱稱',
  `email` varchar(255) DEFAULT NULL COMMENT '用戶email',
  `email_hint` int(10) NOT NULL DEFAULT '0' COMMENT '郵件提示：0（無）、1（本地）、2（所有）',
  `old_email` int(2) NOT NULL DEFAULT '0' COMMENT '訂單過期提示。0：關閉  1：開啟',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='管理員';
