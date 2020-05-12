/*
Navicat MySQL Data Transfer

Source Server         : 本地mysql
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : myproject

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-05-12 23:53:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for banner
-- ----------------------------
DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner` (
  `banner_id` int(20) NOT NULL AUTO_INCREMENT,
  `goods_id` int(20) NOT NULL,
  `create_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`banner_id`),
  KEY `test` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of banner
-- ----------------------------
INSERT INTO `banner` VALUES ('1', '7', '2020-04-17 22:02:36', '2020-04-17 22:02:38', null);
INSERT INTO `banner` VALUES ('29', '23', '2020-05-10 03:28:47', null, null);
INSERT INTO `banner` VALUES ('30', '24', '2020-05-10 17:46:34', null, null);
INSERT INTO `banner` VALUES ('31', '25', '2020-05-10 20:56:30', null, null);

-- ----------------------------
-- Table structure for banner_img
-- ----------------------------
DROP TABLE IF EXISTS `banner_img`;
CREATE TABLE `banner_img` (
  `img_id` int(20) NOT NULL AUTO_INCREMENT,
  `banner_id` int(20) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` int(10) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`img_id`),
  KEY `banner_id` (`banner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of banner_img
-- ----------------------------
INSERT INTO `banner_img` VALUES ('1', '1', '/20200408174546oziMV1mI/7.jpg', '1', '2020-04-17 22:03:27', '2020-04-17 22:03:27', null);
INSERT INTO `banner_img` VALUES ('2', '1', '/20200408174546oziMV1mI/轮播1.png', '2', '2020-04-17 22:03:39', '2020-04-17 22:03:39', null);
INSERT INTO `banner_img` VALUES ('17', '29', '/20200408174546oziMV1mI/bannerImages/20200510/19bcb51aa42803d762ba30ef4f62b9fb.jpg', '1', '2020-05-10 03:28:47', null, null);
INSERT INTO `banner_img` VALUES ('18', '30', '/20200408174546oziMV1mI/bannerImages/20200510/42dfbc01bb40ea5d8e09bd3727a53704.jpg', '1', '2020-05-10 17:46:34', null, null);
INSERT INTO `banner_img` VALUES ('19', '31', '/20200408174546oziMV1mI/bannerImages/20200510/4c09406382f74294c4894cfaf8d6a8a3.jpg', '1', '2020-05-10 20:56:30', null, null);

-- ----------------------------
-- Table structure for business
-- ----------------------------
DROP TABLE IF EXISTS `business`;
CREATE TABLE `business` (
  `business_id` int(100) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `order_no` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `start_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `end_time` datetime NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1:交易中、2:取消交易、3:交易完成',
  PRIMARY KEY (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of business
-- ----------------------------

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `category_id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('1', '书籍', '/static/images/book-active.svg', '/static/images/book.svg', '2020-04-29 23:25:24', '2020-04-29 23:25:24', null);
INSERT INTO `category` VALUES ('2', '运动器材', '/static/images/sport-active.svg', '/static/images/sport.svg', '2020-04-29 23:25:30', '2020-04-29 23:25:30', null);
INSERT INTO `category` VALUES ('3', '电子产品', '/static/images/electronic-active.svg', '/static/images/electronic.svg', '2020-04-29 23:25:37', '2020-04-29 23:25:37', null);
INSERT INTO `category` VALUES ('4', '其他', '/static/images/other-active.svg', '/static/images/other.svg', '2020-04-29 23:31:00', '2020-04-29 23:31:00', null);

-- ----------------------------
-- Table structure for category_goods
-- ----------------------------
DROP TABLE IF EXISTS `category_goods`;
CREATE TABLE `category_goods` (
  `category_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`,`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of category_goods
-- ----------------------------

-- ----------------------------
-- Table structure for collection_goods
-- ----------------------------
DROP TABLE IF EXISTS `collection_goods`;
CREATE TABLE `collection_goods` (
  `user_id` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `goods_id` int(20) NOT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`goods_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of collection_goods
-- ----------------------------
INSERT INTO `collection_goods` VALUES ('20200408174546oziMV1mI', '4', null);
INSERT INTO `collection_goods` VALUES ('20200408174546oziMV1mI', '7', null);
INSERT INTO `collection_goods` VALUES ('20200408174546oziMV1mI', '23', null);
INSERT INTO `collection_goods` VALUES ('20200512171434oziMV1of', '25', null);

-- ----------------------------
-- Table structure for goods
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods` (
  `goods_id` int(20) NOT NULL AUTO_INCREMENT,
  `owner` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(20) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock` int(255) NOT NULL,
  `price` float(10,2) NOT NULL,
  `main_img_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of goods
-- ----------------------------
INSERT INTO `goods` VALUES ('1', '20200408174546oziMV1mI', '1', '说的那句爱神的箭阿三打算上帝啊是对啊手段是手段是对啊是对啥的哈苏大户说的那句爱神的箭阿三打算上帝啊是对啊手段是手段是对啊是对啥的哈苏大户说的那句爱神的箭阿三打算上帝啊是对啊手段是手段是对啊是对啥的哈苏大户说的那句爱神的箭阿三打算上帝啊是对啊手段是手段是对啊是对啥的哈苏大户说的那句爱神的箭阿三打算上帝啊是对啊手段是手段是对啊是对啥的哈苏大户说的那句爱神的箭阿三打算上帝啊是对啊手段是手段是对啊是对啥的哈苏大户说的那句爱神的箭阿三打算上帝啊是对啊手段是手段是对啊是对啥的哈苏大户说的那句爱神的箭阿三打算上帝啊是对', '2', '10.00', '/20200408174546oziMV1mI/1.jpg', '2020-05-10 03:33:53', '2020-05-10 03:33:53', null);
INSERT INTO `goods` VALUES ('2', '20200408174546oziMV1mI', '1', '商品测试二', '2', '99.00', '/20200408174546oziMV1mI/2.jpg', '2020-05-10 03:33:54', '2020-05-10 03:33:54', null);
INSERT INTO `goods` VALUES ('3', '20200408174546oziMV1mI', '1', '商品测试三', '2', '1000.00', '/20200408174546oziMV1mI/3.jpg', '2020-05-10 03:33:55', '2020-05-10 03:33:55', null);
INSERT INTO `goods` VALUES ('4', '20200408174546oziMV1mI', '1', '商品测试四', '2', '20.00', '/20200408174546oziMV1mI/1.jpg', '2020-05-10 03:33:56', '2020-05-10 03:33:56', null);
INSERT INTO `goods` VALUES ('5', '20200408174546oziMV1mI', '1', '商品测试五', '2', '20.00', '/20200408174546oziMV1mI/5.jpg', '2020-05-03 23:09:57', '2020-05-03 23:09:57', null);
INSERT INTO `goods` VALUES ('6', '20200408174546oziMV1mI', '1', '商品测试六', '2', '20.00', '/20200408174546oziMV1mI/6.jpg', '2020-05-03 23:09:58', '2020-05-03 23:09:58', null);
INSERT INTO `goods` VALUES ('7', '20200408174546oziMV1mI', '1', '新东方 (2020)考研英语高分写作\r\n考研英语写作经典备考书目，2020全新改版，震撼上市，美籍外教全书审订，新东方考研英语写作主讲道长王江涛经典代表作！', '2', '20.00', '/20200408174546oziMV1mI/7.jpg', '2020-05-04 17:44:49', '2020-05-04 17:44:49', null);
INSERT INTO `goods` VALUES ('23', '20200408174546oziMV1mI', '4', '发布测试', '1', '123.00', '/20200408174546oziMV1mI/bannerImages/20200510/19bcb51aa42803d762ba30ef4f62b9fb.jpg', '2020-05-10 03:32:28', '2020-05-10 03:32:28', null);
INSERT INTO `goods` VALUES ('24', '20200408174546oziMV1mI', '4', '发布测试二', '1', '1.00', '/20200408174546oziMV1mI/bannerImages/20200510/42dfbc01bb40ea5d8e09bd3727a53704.jpg', '2020-05-10 17:46:34', '2020-05-10 17:46:34', null);
INSERT INTO `goods` VALUES ('25', '20200408174546oziMV1mI', '4', '发布测试三', '1', '1.00', '/20200408174546oziMV1mI/bannerImages/20200510/4c09406382f74294c4894cfaf8d6a8a3.jpg', '2020-05-10 20:56:30', '2020-05-10 20:56:30', null);

-- ----------------------------
-- Table structure for goods_properties
-- ----------------------------
DROP TABLE IF EXISTS `goods_properties`;
CREATE TABLE `goods_properties` (
  `proper_id` int(20) NOT NULL AUTO_INCREMENT,
  `goods_id` int(20) NOT NULL,
  `trade_code` int(5) NOT NULL DEFAULT '1',
  `new` int(5) DEFAULT NULL,
  `other` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`proper_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of goods_properties
-- ----------------------------
INSERT INTO `goods_properties` VALUES ('1', '7', '1', '9', '商品属性测试数据', '2020-04-19 21:40:36', '2020-04-19 21:40:36', null);
INSERT INTO `goods_properties` VALUES ('4', '11', '1', '9', '', '2020-05-10 02:46:12', null, null);
INSERT INTO `goods_properties` VALUES ('5', '12', '1', '9', '', '2020-05-10 02:48:39', null, null);
INSERT INTO `goods_properties` VALUES ('6', '13', '1', '9', '', '2020-05-10 02:57:01', null, null);
INSERT INTO `goods_properties` VALUES ('7', '14', '1', '9', '', '2020-05-10 02:57:07', null, null);
INSERT INTO `goods_properties` VALUES ('8', '15', '1', '9', '', '2020-05-10 02:57:54', null, null);
INSERT INTO `goods_properties` VALUES ('9', '16', '1', '9', '', '2020-05-10 02:59:21', null, null);
INSERT INTO `goods_properties` VALUES ('10', '17', '1', '9', '', '2020-05-10 03:00:08', null, null);
INSERT INTO `goods_properties` VALUES ('11', '18', '1', '9', '', '2020-05-10 03:00:33', null, null);
INSERT INTO `goods_properties` VALUES ('12', '19', '1', '9', '', '2020-05-10 03:03:23', null, null);
INSERT INTO `goods_properties` VALUES ('13', '20', '1', '9', 'nice', '2020-05-10 03:07:45', null, null);
INSERT INTO `goods_properties` VALUES ('14', '21', '1', '9', '啊手段', '2020-05-10 03:12:37', null, null);
INSERT INTO `goods_properties` VALUES ('15', '22', '1', '9', '', '2020-05-10 03:15:17', null, null);
INSERT INTO `goods_properties` VALUES ('16', '23', '1', '9', '暂无', '2020-05-10 03:28:47', null, null);
INSERT INTO `goods_properties` VALUES ('17', '24', '1', '9', '', '2020-05-10 17:46:34', null, null);
INSERT INTO `goods_properties` VALUES ('18', '25', '1', '4', '', '2020-05-10 20:56:30', null, null);

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `order_no` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `order_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_main_img_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `buyer_id` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '买家id',
  `buyer_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `buyer_mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `buyer_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_id` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '商家id',
  `store_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_price` decimal(10,2) DEFAULT NULL COMMENT '订单价格',
  `total_count` int(20) DEFAULT NULL,
  `trade_code` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '交易方式',
  `status` tinyint(5) DEFAULT NULL COMMENT '订单状态',
  `create_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of order
-- ----------------------------

-- ----------------------------
-- Table structure for order_item
-- ----------------------------
DROP TABLE IF EXISTS `order_item`;
CREATE TABLE `order_item` (
  `item_id` int(100) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `goods_id` int(20) NOT NULL,
  `snap_img` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '商品图片快照',
  `snap_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '名称快照',
  `snap_price` float(10,2) DEFAULT NULL COMMENT '价格快照',
  `count` int(10) NOT NULL COMMENT '购买数量',
  `total_price` decimal(10,2) DEFAULT NULL COMMENT '商品总价',
  `create_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of order_item
-- ----------------------------
INSERT INTO `order_item` VALUES ('1', 'A5119438477', '2', 'http://www.zwl.com/upload/20200408174546oziMV1mI/2.jpg', '商品测试二', '99.00', '1', '99.00', '2020-05-11 21:44:40', '2020-05-11 21:44:40', null);

-- ----------------------------
-- Table structure for trade_dict
-- ----------------------------
DROP TABLE IF EXISTS `trade_dict`;
CREATE TABLE `trade_dict` (
  `code` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `on` tinyint(1) DEFAULT '1',
  `create_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of trade_dict
-- ----------------------------
INSERT INTO `trade_dict` VALUES ('1', '线下交易', '1', '2020-05-05 23:54:32', null);
INSERT INTO `trade_dict` VALUES ('2', '邮寄', '0', '2020-05-05 23:53:44', null);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `open_id` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('20200408174546oziMV1mI', 'oziMV1mI3Q9aNitNkLzV4kjQ6TlQ', '测试用户一', '12345678910', '2020-04-16 00:05:08', '2020-04-16 00:05:08', null);
INSERT INTO `user` VALUES ('20200512133344oziMV1uw', 'oziMV1uw3FWtOSJMYLbhGtsx-lQc', null, null, '2020-05-12 13:33:44', '2020-05-12 13:33:44', null);
INSERT INTO `user` VALUES ('20200512171434oziMV1of', 'oziMV1ofkbUNrW1a_D-rPR4qMMUM', null, null, '2020-05-12 17:14:34', '2020-05-12 17:14:34', null);
INSERT INTO `user` VALUES ('20200512173326oziMV1pi', 'oziMV1piPU-KVIlb0GdY8FbrRdao', null, null, '2020-05-12 17:33:26', '2020-05-12 17:33:26', null);
INSERT INTO `user` VALUES ('20200512173921oziMV1nw', 'oziMV1nwFKlFDfefmgKG_bAEGwrk', null, null, '2020-05-12 17:39:21', '2020-05-12 17:39:21', null);

-- ----------------------------
-- Table structure for user_address
-- ----------------------------
DROP TABLE IF EXISTS `user_address`;
CREATE TABLE `user_address` (
  `address_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `detail` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user_address
-- ----------------------------
INSERT INTO `user_address` VALUES ('1', '20200408174546oziMV1mI', '测试用户名称', '15816658173', '中国', '广东省', '广州市', '荔湾区', '西坑小区', '2020-05-11 16:09:18', null);
