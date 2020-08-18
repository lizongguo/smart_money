/*
 Navicat Premium Data Transfer

 Source Server         : lee
 Source Server Type    : MySQL
 Source Server Version : 50717
 Source Host           : localhost:3306
 Source Schema         : smart_money

 Target Server Type    : MySQL
 Target Server Version : 50717
 File Encoding         : 65001

 Date: 18/08/2020 16:19:48
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for address
-- ----------------------------
DROP TABLE IF EXISTS `address`;
CREATE TABLE `address`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `accept_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `gender` tinyint(2) NULL DEFAULT 0 COMMENT '性别：1先生，2女生',
  `telphone` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `province` int(10) NOT NULL DEFAULT 0 COMMENT '省',
  `city` int(10) NOT NULL DEFAULT 0 COMMENT '市',
  `area` int(10) NOT NULL DEFAULT 0 COMMENT '区',
  `address` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '详细地址',
  `full_address` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '全地址',
  `long` decimal(10, 7) NULL DEFAULT 0.0000000 COMMENT '经度',
  `lat` decimal(10, 7) NULL DEFAULT 0.0000000 COMMENT '维度',
  `is_default` tinyint(1) NULL DEFAULT 0 COMMENT '默认',
  `areas` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '省市区',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `userTokens_userId`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户地址管理' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of address
-- ----------------------------
INSERT INTO `address` VALUES (5, 13, '余滔', 1, '13438019615', 0, 0, 0, '成都市大业路一号', '', 104.0688400, 30.6527600, 1, '', '2019-05-10 14:17:56', '2019-06-05 11:12:57');
INSERT INTO `address` VALUES (12, 13, '余先生', 1, '13438019615', 0, 0, 0, '四川省成都市武侯区石羊场仁和东街21号', '', 104.0380200, 30.5795800, 0, '', '2019-05-21 18:01:23', '2019-06-05 11:12:57');
INSERT INTO `address` VALUES (15, 16, '汤圆', 2, '18111635331', 0, 0, 0, '四川省成都市金牛区', '', 104.0529300, 30.6901500, 1, '', '2019-05-24 14:47:42', '2019-05-24 14:47:42');
INSERT INTO `address` VALUES (17, 18, '张华', 2, '18482160070', 0, 0, 0, '东大街芷泉段88号(东方广场对面)', '', 30.6470340, 104.0857880, 0, '', '2019-05-26 23:09:13', '2019-06-03 14:57:21');
INSERT INTO `address` VALUES (29, 1, 'Mr.Z', 2, '18482160070', 0, 0, 0, '四川省成都市锦江区大观苑二期', '四川省成都市锦江区大观苑二期', 104.1324100, 30.6124300, 1, '四川省,成都市,锦江区', '2020-06-19 23:51:56', '2020-07-01 17:18:37');
INSERT INTO `address` VALUES (30, 3, '刘钢', 1, '18628103745', 0, 0, 0, '四川省成都市锦江区财富中心', '四川省成都市锦江区财富中心', 104.0703350, 30.6521450, 1, '四川省,成都市,锦江区', '2020-06-23 17:03:13', '2020-06-23 17:03:13');
INSERT INTO `address` VALUES (31, 2, '汪洋', 2, '13540471003', 0, 0, 0, '四川省成都市金牛区蜀兴中街', '四川省成都市金牛区蜀兴中街', 104.0279770, 30.6866700, 1, '四川省,成都市,金牛区', '2020-07-01 22:34:00', '2020-07-01 22:34:10');
INSERT INTO `address` VALUES (32, 4, '张三', 1, '13145671234', 0, 0, 0, '四川省成都市锦江区1111', '四川省成都市锦江区1111', 104.0832900, 30.6561800, 0, '四川省,成都市,锦江区', '2020-07-02 11:00:35', '2020-07-02 11:00:35');

-- ----------------------------
-- Table structure for admin_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_permissions`;
CREATE TABLE `admin_permissions`  (
  `admin_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`admin_id`, `permission_id`) USING BTREE,
  INDEX `admin_permissions_admin_id_permission_id_index`(`admin_id`, `permission_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_permissions
-- ----------------------------
INSERT INTO `admin_permissions` VALUES (4, 6, '2020-06-18 14:36:31', '2020-06-18 14:36:31');
INSERT INTO `admin_permissions` VALUES (5, 7, '2020-07-15 15:38:14', '2020-07-15 15:38:14');
INSERT INTO `admin_permissions` VALUES (7, 7, '2020-07-28 10:44:18', '2020-07-28 10:44:18');

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `role` int(11) NOT NULL DEFAULT 0,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `access_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `remarks` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `shop_id` int(10) NULL DEFAULT 0 COMMENT '店铺id',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(1) NULL DEFAULT 0 COMMENT '删除标记',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `admins_phone_unique`(`phone`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES (1, '超级管理员', '/upload/20200818/152712_7cb3fe34098209258167079a9632426a.jpeg', 'admin@admin.com', '13800000000', 1, '$2y$10$PMUg5QIrcyAmOMfDAOjYmuWpb/ougkM4mmFvX7tcymWrPTuLZxmL.', '610012c2522a77e02d25a7c4ca5351c4', 'lWrFatRJPnSrnKxxQlgaCV5ZYNdN4bOjMHVXFb7lnZ20phuUrTRp1zR6TaqX', '', 0, '2019-03-06 13:52:49', '2020-08-18 15:29:27', 0);

-- ----------------------------
-- Table structure for attachment
-- ----------------------------
DROP TABLE IF EXISTS `attachment`;
CREATE TABLE `attachment`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `object` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `object_id` int(11) NOT NULL DEFAULT 0,
  `file_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `thumb_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `size` int(11) NOT NULL DEFAULT 0,
  `face_back` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `attachment-object-object_id-key`(`object`, `object_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 120 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of attachment
-- ----------------------------
INSERT INTO `attachment` VALUES (47, 'shop', 0, '/upload/20190506/142444_a495e75e64c764246262d22b7133e47d.jpg', '/upload/20190506/thumb_142444_a495e75e64c764246262d22b7133e47d.jpg', 'u=2832286636,1376626166&fm=26&gp=0.jpg', 40858, '', '2019-05-06 14:24:44', '2019-05-06 14:24:44', 0);
INSERT INTO `attachment` VALUES (48, 'shop', 0, '/upload/20190506/142459_db07df9b5208f0e89aa495bc004a2bd0.jpg', '/upload/20190506/thumb_142459_db07df9b5208f0e89aa495bc004a2bd0.jpg', 'u=2832286636,1376626166&fm=26&gp=0.jpg', 40858, '', '2019-05-06 14:24:59', '2019-05-06 14:24:59', 0);
INSERT INTO `attachment` VALUES (49, 'adminAvatar', 0, '/upload/20190506/144503_b148572b03fb527ce81da7d0b9fa988e.jpg', '/upload/20190506/thumb_144503_b148572b03fb527ce81da7d0b9fa988e.jpg', 'u=2832286636,1376626166&fm=26&gp=0.jpg', 40858, '', '2019-05-06 14:45:03', '2019-05-06 14:45:03', 0);
INSERT INTO `attachment` VALUES (50, 'goods', 0, '/upload/20190506/144538_5d8f23ce9c6b2eddb7c386d29dfef2d0.jpg', '/upload/20190506/thumb_144538_5d8f23ce9c6b2eddb7c386d29dfef2d0.jpg', 'u=2832286636,1376626166&fm=26&gp=0.jpg', 40858, '', '2019-05-06 14:45:38', '2019-05-06 14:45:38', 0);
INSERT INTO `attachment` VALUES (51, 'goods', 0, '/upload/20190506/144617_89aeade4a347e5faa1c6141a045f0fcf.jpg', '/upload/20190506/thumb_144617_89aeade4a347e5faa1c6141a045f0fcf.jpg', 'u=2832286636,1376626166&fm=26&gp=0.jpg', 40858, '', '2019-05-06 14:46:17', '2019-05-06 14:46:17', 0);
INSERT INTO `attachment` VALUES (52, 'news', 0, '/upload/20190506/144716_526e77046682b1f43db7e5735827df7b.jpg', '/upload/20190506/thumb_144716_526e77046682b1f43db7e5735827df7b.jpg', 'u=2832286636,1376626166&fm=26&gp=0.jpg', 40858, '', '2019-05-06 14:47:16', '2019-05-06 14:47:16', 0);
INSERT INTO `attachment` VALUES (53, 'goods', 0, '/upload/20190506/151801_39b0eb5446fc3541ac9df26b6e63bc24.jpg', '/upload/20190506/thumb_151801_39b0eb5446fc3541ac9df26b6e63bc24.jpg', '597a9e7b647883a07af660d3daeca879.jpg', 381799, '', '2019-05-06 15:18:01', '2019-05-06 15:18:01', 0);
INSERT INTO `attachment` VALUES (54, 'goods', 0, '/upload/20190506/151923_1cdc00bf2929a4c9c50ed54dadbfd2f5.jpg', '/upload/20190506/thumb_151923_1cdc00bf2929a4c9c50ed54dadbfd2f5.jpg', '6cd03912eab77d52e07687eafef053d5.jpg', 108169, '', '2019-05-06 15:19:23', '2019-05-06 15:19:23', 0);
INSERT INTO `attachment` VALUES (55, 'goods', 0, '/upload/20190506/152030_854177b1bbd52a4a6ce40643f130b1d0.jpg', '/upload/20190506/thumb_152030_854177b1bbd52a4a6ce40643f130b1d0.jpg', '49b797bed387ff3e211a8b407326ef66.jpg', 31366, '', '2019-05-06 15:20:30', '2019-05-06 15:20:30', 0);
INSERT INTO `attachment` VALUES (56, 'news', 0, '/upload/20190506/152143_d45f87974651d5c5dc8bfd9a19e8446c.jpg', '/upload/20190506/thumb_152143_d45f87974651d5c5dc8bfd9a19e8446c.jpg', '6cd03912eab77d52e07687eafef053d5.jpg', 108169, '', '2019-05-06 15:21:44', '2019-05-06 15:21:44', 0);
INSERT INTO `attachment` VALUES (57, 'news', 0, '/upload/20190506/152236_9d7eab746fffcd90240ebdb9ed68cc23.jpg', '/upload/20190506/thumb_152236_9d7eab746fffcd90240ebdb9ed68cc23.jpg', '597a9e7b647883a07af660d3daeca879.jpg', 381799, '', '2019-05-06 15:22:36', '2019-05-06 15:22:36', 0);
INSERT INTO `attachment` VALUES (58, 'news', 0, '/upload/20190506/152304_ca52391fdde2dc1fa0d3043cbd2bad94.jpg', '/upload/20190506/thumb_152304_ca52391fdde2dc1fa0d3043cbd2bad94.jpg', '49b797bed387ff3e211a8b407326ef66.jpg', 31366, '', '2019-05-06 15:23:04', '2019-05-06 15:23:04', 0);
INSERT INTO `attachment` VALUES (59, 'avatar', 0, '/upload/20190506/181539_40ec4baf6a8ab20cf0469a53ccb5a596.jpeg', '/upload/20190506/thumb_181539_40ec4baf6a8ab20cf0469a53ccb5a596.jpeg', '20190506_181531.jpeg', 8474, '', '2019-05-06 18:15:39', '2019-05-06 18:15:39', 0);
INSERT INTO `attachment` VALUES (60, 'avatar', 0, '/upload/20190506/181814_caef30bf8f2a3cb3ad01a126dc5ab5c0.jpeg', '/upload/20190506/thumb_181814_caef30bf8f2a3cb3ad01a126dc5ab5c0.jpeg', '20190506_181810.jpeg', 8474, '', '2019-05-06 18:18:14', '2019-05-06 18:18:14', 0);
INSERT INTO `attachment` VALUES (61, 'avatar', 0, '/upload/20190506/182050_2605b960b35851ea57afa18ef37f3659.jpeg', '/upload/20190506/thumb_182050_2605b960b35851ea57afa18ef37f3659.jpeg', '20190506_182045.jpeg', 8474, '', '2019-05-06 18:20:50', '2019-05-06 18:20:50', 0);
INSERT INTO `attachment` VALUES (62, 'ad', 0, '/upload/20190507/094516_eec50b1f1279d5e6336fdfb8ebeea258.jpg', '/upload/20190507/thumb_094516_eec50b1f1279d5e6336fdfb8ebeea258.jpg', 'timg (1).jpg', 27502, '', '2019-05-07 09:45:17', '2019-05-07 09:45:17', 0);
INSERT INTO `attachment` VALUES (63, 'ad', 0, '/upload/20190507/094538_d078b18126c4d8f4a2a7d1cc73357037.jpg', '/upload/20190507/thumb_094538_d078b18126c4d8f4a2a7d1cc73357037.jpg', '148411717.jpg', 44541, '', '2019-05-07 09:45:38', '2019-05-07 09:45:38', 0);
INSERT INTO `attachment` VALUES (64, 'content', 0, '/upload/20190507/094810_1a190ada85483b85bb74482f559086e1.jpg', '/upload/20190507/thumb_094810_1a190ada85483b85bb74482f559086e1.jpg', 'timg (1).jpg', 27502, '', '2019-05-07 09:48:10', '2019-05-07 09:48:10', 0);
INSERT INTO `attachment` VALUES (65, 'content', 0, '/upload/20190507/094834_de779a39e8f2c9158ff803988a8cd6c0.jpg', '/upload/20190507/thumb_094834_de779a39e8f2c9158ff803988a8cd6c0.jpg', 'timg.jpg', 24283, '', '2019-05-07 09:48:34', '2019-05-07 09:48:34', 0);
INSERT INTO `attachment` VALUES (66, 'content', 0, '/upload/20190507/094842_b956a73d2d458d35b0dc51f130a1b8d6.jpg', '/upload/20190507/thumb_094842_b956a73d2d458d35b0dc51f130a1b8d6.jpg', 'timg.jpg', 24283, '', '2019-05-07 09:48:42', '2019-05-07 09:48:42', 0);
INSERT INTO `attachment` VALUES (67, 'content', 0, '/upload/20190507/113601_21a324f199e6c07083420aeb1f531be2.jpg', '/upload/20190507/thumb_113601_21a324f199e6c07083420aeb1f531be2.jpg', '001.jpg', 27502, '', '2019-05-07 11:36:01', '2019-05-07 11:36:01', 0);
INSERT INTO `attachment` VALUES (68, 'content', 0, '/upload/20190507/113606_165f841f1492a4b0ba26dc54dd6d7f08.jpg', '/upload/20190507/thumb_113606_165f841f1492a4b0ba26dc54dd6d7f08.jpg', '002.jpg', 24283, '', '2019-05-07 11:36:06', '2019-05-07 11:36:06', 0);
INSERT INTO `attachment` VALUES (69, 'news', 0, '/upload/20190507/113622_eb757a65d2bb30d4a2ea75c1341b27e2.jpg', '/upload/20190507/thumb_113622_eb757a65d2bb30d4a2ea75c1341b27e2.jpg', '004.jpg', 40858, '', '2019-05-07 11:36:22', '2019-05-07 11:36:22', 0);
INSERT INTO `attachment` VALUES (70, 'shop', 0, '/upload/20190507/114458_d289950af184cfa59565eca53ef165a4.jpg', '/upload/20190507/thumb_114458_d289950af184cfa59565eca53ef165a4.jpg', '004.jpg', 40858, '', '2019-05-07 11:44:58', '2019-05-07 11:44:58', 0);
INSERT INTO `attachment` VALUES (71, 'goods', 0, '/upload/20190507/115240_a20897b9135345ef589c075ec466e3e7.jpg', '/upload/20190507/thumb_115240_a20897b9135345ef589c075ec466e3e7.jpg', '005.jpg', 69708, '', '2019-05-07 11:52:40', '2019-05-07 11:52:40', 0);
INSERT INTO `attachment` VALUES (72, 'goods', 0, '/upload/20190507/115828_0b60450dc9a93a27733ab64aac6c0d77.jpg', '/upload/20190507/thumb_115828_0b60450dc9a93a27733ab64aac6c0d77.jpg', '006.jpg', 62932, '', '2019-05-07 11:58:28', '2019-05-07 11:58:28', 0);
INSERT INTO `attachment` VALUES (73, 'ad', 0, '/upload/20190508/123734_02e0e9e389b6cd2174a6465bab8b5556.jpg', '/upload/20190508/thumb_123734_02e0e9e389b6cd2174a6465bab8b5556.jpg', '图层 3.jpg', 236298, '', '2019-05-08 12:37:34', '2019-05-08 12:37:34', 0);
INSERT INTO `attachment` VALUES (74, 'avatar', 0, '/upload/20190508/151357_894474ce286ff3b8e0f16db92cd8c871.jpeg', '/upload/20190508/thumb_151357_894474ce286ff3b8e0f16db92cd8c871.jpeg', '20190508_151354.jpeg', 13816, '', '2019-05-08 15:13:57', '2019-05-08 15:13:57', 0);
INSERT INTO `attachment` VALUES (75, 'avatar', 0, '/upload/20190508/151412_c69dc6f3cdd62dd79e803d6ced9a5e30.jpeg', '/upload/20190508/thumb_151412_c69dc6f3cdd62dd79e803d6ced9a5e30.jpeg', '20190508_151411.jpeg', 13816, '', '2019-05-08 15:14:12', '2019-05-08 15:14:12', 0);
INSERT INTO `attachment` VALUES (76, 'avatar', 0, '/upload/20190508/154133_88bdc2e7770652b8d5bc27c1ce44df72.jpeg', '/upload/20190508/thumb_154133_88bdc2e7770652b8d5bc27c1ce44df72.jpeg', '20190508_154131.jpeg', 15439, '', '2019-05-08 15:41:33', '2019-05-08 15:41:33', 0);
INSERT INTO `attachment` VALUES (77, 'shopShow', 0, '/upload/20190528/113409_55651e8aeafbff2007d9c9ab513da626.jpg', '', '004.jpg', 40858, '', '2019-05-28 11:34:09', '2019-05-28 11:34:09', 0);
INSERT INTO `attachment` VALUES (78, 'avatar', 0, '/upload/20190529/151743_74d994f8a2a82c6756c6642f37d0fad7.jpeg', '/upload/20190529/thumb_151743_74d994f8a2a82c6756c6642f37d0fad7.jpeg', '20190529_151734.jpeg', 12328, '', '2019-05-29 15:17:43', '2019-05-29 15:17:43', 0);
INSERT INTO `attachment` VALUES (79, 'news', 0, '/upload/20190529/155447_13d2932a2d51fdb0aa675888f2194365.jpg', '/upload/20190529/thumb_155447_13d2932a2d51fdb0aa675888f2194365.jpg', '004.jpg', 40858, '', '2019-05-29 15:54:47', '2019-05-29 15:54:47', 0);
INSERT INTO `attachment` VALUES (80, 'content', 0, '/upload/20190529/155501_8287a2d6bd60f5c870d4d871cf085091.jpg', '/upload/20190529/thumb_155501_8287a2d6bd60f5c870d4d871cf085091.jpg', '006.jpg', 62932, '', '2019-05-29 15:55:01', '2019-05-29 15:55:01', 0);
INSERT INTO `attachment` VALUES (81, 'content', 0, '/upload/20190529/155501_4c3ecc199ac83887240f5776b08a5745.jpg', '/upload/20190529/thumb_155501_4c3ecc199ac83887240f5776b08a5745.jpg', '005.jpg', 69708, '', '2019-05-29 15:55:01', '2019-05-29 15:55:01', 0);
INSERT INTO `attachment` VALUES (82, 'news', 0, '/upload/20190530/155504_367224ace0b9c499052bde2396a417da.jpg', '/upload/20190530/thumb_155504_367224ace0b9c499052bde2396a417da.jpg', 'u=1845867135,551781954&fm=72.jpg', 11416, '', '2019-05-30 15:55:04', '2019-05-30 15:55:04', 0);
INSERT INTO `attachment` VALUES (83, 'content', 0, '/upload/20190530/160649_87a44821f2d35a021f45dda9f62d2ce7.jpeg', '/upload/20190530/thumb_160649_87a44821f2d35a021f45dda9f62d2ce7.jpeg', '76e10c4132a441ecb9c942fb8fc7f202.jpeg', 90935, '', '2019-05-30 16:06:49', '2019-05-30 16:06:49', 0);
INSERT INTO `attachment` VALUES (84, 'shop', 0, '/upload/20190531/110722_f0f8f7e42702ccb60c4b9ba1c702adb3.jpg', '/upload/20190531/thumb_110722_f0f8f7e42702ccb60c4b9ba1c702adb3.jpg', '微信图片_20190531110539.jpg', 209835, '', '2019-05-31 11:07:22', '2019-05-31 11:07:22', 0);
INSERT INTO `attachment` VALUES (85, 'goods', 0, '/upload/20190531/111451_6437b030aad727069496bdd81905ce89.jpg', '/upload/20190531/thumb_111451_6437b030aad727069496bdd81905ce89.jpg', '006.jpg', 62932, '', '2019-05-31 11:14:51', '2019-05-31 11:14:51', 0);
INSERT INTO `attachment` VALUES (86, 'goods', 0, '/upload/20190531/111514_804703aa41d4cf4f8ef3865d188d1c0b.jpg', '/upload/20190531/thumb_111514_804703aa41d4cf4f8ef3865d188d1c0b.jpg', '005.jpg', 69708, '', '2019-05-31 11:15:14', '2019-05-31 11:15:14', 0);
INSERT INTO `attachment` VALUES (87, 'goods', 0, '/upload/20190531/112246_4b3f1397b7e5f7489fcea7d4bd59ed4d.jpg', '/upload/20190531/thumb_112246_4b3f1397b7e5f7489fcea7d4bd59ed4d.jpg', '1334110436456.jpg', 19312, '', '2019-05-31 11:22:46', '2019-05-31 11:22:46', 0);
INSERT INTO `attachment` VALUES (88, 'goods', 0, '/upload/20190531/112448_c1a5f06b6a958cedbc99a92d7505e587.jpg', '/upload/20190531/thumb_112448_c1a5f06b6a958cedbc99a92d7505e587.jpg', 'dcd3e53b398113fc.jpg', 71122, '', '2019-05-31 11:24:48', '2019-05-31 11:24:48', 0);
INSERT INTO `attachment` VALUES (89, 'shop', 0, '/upload/20200610/152328_6196065ed3df9deea9c14cd0c01f829d.png', '/upload/20200610/thumb_152328_6196065ed3df9deea9c14cd0c01f829d.png', 'WechatIMG1026.png', 353524, '', '2020-06-10 15:23:28', '2020-06-10 15:23:28', 0);
INSERT INTO `attachment` VALUES (90, 'shop', 0, '/upload/20200610/152513_4019c4e1eb560001a91d12e43eabe7a1.png', '/upload/20200610/thumb_152513_4019c4e1eb560001a91d12e43eabe7a1.png', 'WechatIMG1026.png', 353524, '', '2020-06-10 15:25:13', '2020-06-10 15:25:13', 0);
INSERT INTO `attachment` VALUES (91, 'shop', 0, '/upload/20200610/153828_68eaa703b376e9f66f7102c55f28d617.jpg', '/upload/20200610/thumb_153828_68eaa703b376e9f66f7102c55f28d617.jpg', 'picc.jpg', 40535, '', '2020-06-10 15:38:28', '2020-06-10 15:38:28', 0);
INSERT INTO `attachment` VALUES (92, 'shopShow', 0, '/upload/20200610/153934_48965e53a5a96a7777cecb3ff2ed712f.jpg', '', 'timg.jpg', 9512, '', '2020-06-10 15:39:34', '2020-06-10 15:39:34', 0);
INSERT INTO `attachment` VALUES (93, 'shop', 0, '/upload/20200610/154000_c321297d9414dad2719b2f9c2fe6aa48.jpg', '/upload/20200610/thumb_154000_c321297d9414dad2719b2f9c2fe6aa48.jpg', 'timg.jpg', 9512, '', '2020-06-10 15:40:00', '2020-06-10 15:40:00', 0);
INSERT INTO `attachment` VALUES (94, 'shopShow', 0, '/upload/20200610/154016_66cef7e92b3d5aaad63d4dcf252a613d.jpg', '', 'picc.jpg', 40535, '', '2020-06-10 15:40:16', '2020-06-10 15:40:16', 0);
INSERT INTO `attachment` VALUES (95, 'shopShow', 0, '/upload/20200610/154127_c4191c3f9e33dc263d9dc343983c6891.jpg', '', 'upload@_@2019@_@05@_@27@_@20190527153349764.jpg', 390034, '', '2020-06-10 15:41:27', '2020-06-10 15:41:27', 0);
INSERT INTO `attachment` VALUES (96, 'news', 0, '/upload/20200617/163255_12762a4346d58070ab007fbcec72aeca.png', '/upload/20200617/thumb_163255_12762a4346d58070ab007fbcec72aeca.png', 'QQ截图20200617163239.png', 259477, '', '2020-06-17 16:32:55', '2020-06-17 16:32:55', 0);
INSERT INTO `attachment` VALUES (97, 'news', 0, '/upload/20200617/163316_ea9b11828a67c84d63dd4abdbb1e2c93.png', '/upload/20200617/thumb_163316_ea9b11828a67c84d63dd4abdbb1e2c93.png', 'QQ截图20200617163239.png', 259477, '', '2020-06-17 16:33:16', '2020-06-17 16:33:16', 0);
INSERT INTO `attachment` VALUES (98, 'content', 0, '/upload/20200623/111043_a2a3b5a7ab37505c65bfd38220cd9038.png', '/upload/20200623/thumb_111043_a2a3b5a7ab37505c65bfd38220cd9038.png', 'QQ截图20200623103359.png', 234784, '', '2020-06-23 11:10:43', '2020-06-23 11:10:43', 0);
INSERT INTO `attachment` VALUES (99, 'content', 0, '/upload/20200623/111043_fb08b103827e5302ceba571cbb393d40.png', '/upload/20200623/thumb_111043_fb08b103827e5302ceba571cbb393d40.png', 'QQ截图20200623103350.png', 307415, '', '2020-06-23 11:10:43', '2020-06-23 11:10:43', 0);
INSERT INTO `attachment` VALUES (100, 'content', 0, '/upload/20200623/111106_50f91d4bb88019034fa414590a0a0e08.png', '/upload/20200623/thumb_111106_50f91d4bb88019034fa414590a0a0e08.png', 'QQ截图20200623103350.png', 307415, '', '2020-06-23 11:11:06', '2020-06-23 11:11:06', 0);
INSERT INTO `attachment` VALUES (101, 'content', 0, '/upload/20200623/111106_b1c53f7b4a1210aaa3998c7bd9475ca6.png', '/upload/20200623/thumb_111106_b1c53f7b4a1210aaa3998c7bd9475ca6.png', 'QQ截图20200623103359.png', 234784, '', '2020-06-23 11:11:06', '2020-06-23 11:11:06', 0);
INSERT INTO `attachment` VALUES (102, 'content', 0, '/upload/20200623/111129_ef6aac5ed22e7a6c827bfff461c7dcab.png', '/upload/20200623/thumb_111129_ef6aac5ed22e7a6c827bfff461c7dcab.png', 'QQ截图20200623103350.png', 307415, '', '2020-06-23 11:11:29', '2020-06-23 11:11:29', 0);
INSERT INTO `attachment` VALUES (103, 'content', 0, '/upload/20200623/111226_a6b44627430acaf427cba1c82b62b68e.png', '/upload/20200623/thumb_111226_a6b44627430acaf427cba1c82b62b68e.png', 'QQ截图20200623103359.png', 234784, '', '2020-06-23 11:12:26', '2020-06-23 11:12:26', 0);
INSERT INTO `attachment` VALUES (104, 'content', 0, '/upload/20200623/111226_18ff1ee95b20267a7dd6b3815039a780.png', '/upload/20200623/thumb_111226_18ff1ee95b20267a7dd6b3815039a780.png', 'QQ截图20200623103350.png', 307415, '', '2020-06-23 11:12:26', '2020-06-23 11:12:26', 0);
INSERT INTO `attachment` VALUES (105, 'content', 0, '/upload/20200623/111255_f48dfdda06ae273b032fc78e69f084ec.png', '/upload/20200623/thumb_111255_f48dfdda06ae273b032fc78e69f084ec.png', 'QQ截图20200623103350.png', 307415, '', '2020-06-23 11:12:55', '2020-06-23 11:12:55', 0);
INSERT INTO `attachment` VALUES (106, 'content', 0, '/upload/20200623/111255_0d5d7ecf877ee758f488d6b68e9cb18a.png', '/upload/20200623/thumb_111255_0d5d7ecf877ee758f488d6b68e9cb18a.png', 'QQ截图20200623103359.png', 234784, '', '2020-06-23 11:12:55', '2020-06-23 11:12:55', 0);
INSERT INTO `attachment` VALUES (107, 'content', 0, '/upload/20200623/111323_b8cb6644642548d9d67ec2ac08ac3580.png', '/upload/20200623/thumb_111323_b8cb6644642548d9d67ec2ac08ac3580.png', 'QQ截图20200623103350.png', 307415, '', '2020-06-23 11:13:23', '2020-06-23 11:13:23', 0);
INSERT INTO `attachment` VALUES (108, 'content', 0, '/upload/20200623/111340_ff42bf00b97bdcbfcd3ef484ce7b592d.png', '/upload/20200623/thumb_111340_ff42bf00b97bdcbfcd3ef484ce7b592d.png', 'QQ截图20200623103350.png', 307415, '', '2020-06-23 11:13:40', '2020-06-23 11:13:40', 0);
INSERT INTO `attachment` VALUES (109, 'content', 0, '/upload/20200623/111423_8db38b4e55c79dead5e7d38578677637.png', '/upload/20200623/thumb_111423_8db38b4e55c79dead5e7d38578677637.png', 'QQ截图20200623103350.png', 307415, '', '2020-06-23 11:14:23', '2020-06-23 11:14:23', 0);
INSERT INTO `attachment` VALUES (110, 'content', 0, '/upload/20200623/111447_1b30697fc5f73f0e54f9f3c14e7c7ec0.png', '/upload/20200623/thumb_111447_1b30697fc5f73f0e54f9f3c14e7c7ec0.png', 'QQ截图20200623103359.png', 234784, '', '2020-06-23 11:14:47', '2020-06-23 11:14:47', 0);
INSERT INTO `attachment` VALUES (111, 'content', 0, '/upload/20200623/111447_7cf435d4946b2c5e08c4ed1d26393134.png', '/upload/20200623/thumb_111447_7cf435d4946b2c5e08c4ed1d26393134.png', 'QQ截图20200623103350.png', 307415, '', '2020-06-23 11:14:47', '2020-06-23 11:14:47', 0);
INSERT INTO `attachment` VALUES (112, 'news', 0, '/upload/20200706/105044_72b3d920150980cef4d396c3f463d3f1.jpeg', '/upload/20200706/thumb_105044_72b3d920150980cef4d396c3f463d3f1.jpeg', '招商加盟.jpeg', 130639, '', '2020-07-06 10:50:44', '2020-07-06 10:50:44', 0);
INSERT INTO `attachment` VALUES (113, 'content', 0, '/upload/20200706/105103_09616563dd0819fd80d25b5f5ae44197.jpeg', '/upload/20200706/thumb_105103_09616563dd0819fd80d25b5f5ae44197.jpeg', '招商加盟.jpeg', 130639, '', '2020-07-06 10:51:03', '2020-07-06 10:51:03', 0);
INSERT INTO `attachment` VALUES (114, 'news', 0, '/upload/20200706/105325_de08d61c326c4a26306af7cc8c17be27.jpeg', '/upload/20200706/thumb_105325_de08d61c326c4a26306af7cc8c17be27.jpeg', '关于我们.jpeg', 24771, '', '2020-07-06 10:53:25', '2020-07-06 10:53:25', 0);
INSERT INTO `attachment` VALUES (115, 'content', 0, '/upload/20200706/110033_2e8cab7caf9159651899cd9639891b01.jpg', '/upload/20200706/thumb_110033_2e8cab7caf9159651899cd9639891b01.jpg', 'zsjm.jpg', 130639, '', '2020-07-06 11:00:33', '2020-07-06 11:00:33', 0);
INSERT INTO `attachment` VALUES (116, 'content', 0, '/upload/20200706/110103_728fbb8fd59a63b910989b2cb3edd921.jpg', '/upload/20200706/thumb_110103_728fbb8fd59a63b910989b2cb3edd921.jpg', 'zsjm.jpg', 130639, '', '2020-07-06 11:01:03', '2020-07-06 11:01:03', 0);
INSERT INTO `attachment` VALUES (117, 'content', 0, '/upload/20200706/110215_2ab8de6a985a15a9de24cd9cf6a66d9c.jpg', '/upload/20200706/thumb_110215_2ab8de6a985a15a9de24cd9cf6a66d9c.jpg', 'zsjm.jpg', 130639, '', '2020-07-06 11:02:15', '2020-07-06 11:02:15', 0);
INSERT INTO `attachment` VALUES (118, 'content', 0, '/upload/20200706/110228_30ef268e6ae43b57a80ad5f01c71a887.jpg', '/upload/20200706/thumb_110228_30ef268e6ae43b57a80ad5f01c71a887.jpg', 'zsjm.jpg', 130639, '', '2020-07-06 11:02:28', '2020-07-06 11:02:28', 0);
INSERT INTO `attachment` VALUES (119, 'adminAvatar', 0, '/upload/20200818/152712_7cb3fe34098209258167079a9632426a.jpeg', '/upload/20200818/152712_7cb3fe34098209258167079a9632426a.jpeg', 'jhk-1522393434757.jpeg', 1578291, '', '2020-08-18 15:27:13', '2020-08-18 15:27:13', 0);

-- ----------------------------
-- Table structure for fund
-- ----------------------------
DROP TABLE IF EXISTS `fund`;
CREATE TABLE `fund`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '备注信息',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '删除标记',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fund_stock
-- ----------------------------
DROP TABLE IF EXISTS `fund_stock`;
CREATE TABLE `fund_stock`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fund_id` int(10) NULL DEFAULT 0,
  `stock_id` int(10) NULL DEFAULT 0,
  `amount` bigint(20) NULL DEFAULT 0 COMMENT '总份额',
  `position_cost` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '成本',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '删除标记',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `uri` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `permission_id` int(11) NULL DEFAULT 0,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (4, 2, 8, '角色管理', 'fa-user', '/admin/roles', 0, NULL, '2019-03-07 17:35:01');
INSERT INTO `menu` VALUES (5, 2, 9, '权限管理', 'fa-ban', '/admin/permissions', 0, NULL, '2019-03-07 17:35:01');
INSERT INTO `menu` VALUES (6, 2, 10, '菜单管理', 'fa-bars', '/admin/menu', 0, NULL, '2019-03-07 17:35:01');
INSERT INTO `menu` VALUES (10, 1, 2, '控制台', 'fa-tachometer', '/admin/console', 0, '2019-03-07 16:07:22', '2019-03-11 12:34:39');
INSERT INTO `menu` VALUES (11, 0, 26, '设置', 'fa-cog', '', 1, '2019-03-07 16:34:13', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (14, 0, 2, '我的设置', 'fa-user', '', 0, '2019-03-07 16:40:13', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (15, 14, 3, '基本资料', 'fa-edit', '/admin/account', 4, '2019-03-07 16:41:35', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (16, 14, 4, '修改密码', 'fa-transgender-alt', '/admin/account/changePassword', 0, '2019-03-07 16:43:50', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (17, 0, 20, '用户', 'fa-users', '', 1, '2019-03-07 16:44:30', '2020-06-18 14:18:28');
INSERT INTO `menu` VALUES (19, 17, 22, '管理员', 'fa-user-secret', '/admin/admins', 1, '2019-03-07 16:46:07', '2020-06-18 14:18:19');
INSERT INTO `menu` VALUES (20, 11, 27, '菜单管理', 'fa-align-justify', '/admin/menu', 0, '2019-03-07 17:40:35', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (21, 11, 28, '角色管理', 'fa-venus-double', '/admin/roles', 1, '2019-03-07 17:41:31', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (22, 11, 29, '权限管理', 'fa-ban', '/admin/permissions', 1, '2019-03-07 17:42:49', '2020-06-18 14:32:34');
INSERT INTO `menu` VALUES (24, 23, 6, '内容分类', 'fa-certificate', '/admin/category', 1, '2019-03-13 11:07:49', '2020-06-17 16:23:09');
INSERT INTO `menu` VALUES (25, 23, 7, '内容管理', 'fa-newspaper-o', '/admin/news', 1, '2019-03-13 11:08:57', '2020-06-17 16:23:23');
INSERT INTO `menu` VALUES (26, 23, 8, '广告横幅', 'fa-tv', '/admin/ads', 1, '2019-03-14 10:59:42', '2020-06-23 10:28:31');
INSERT INTO `menu` VALUES (29, 28, 10, '店铺管理', 'fa-shopping-bag', '/admin/shops', 1, '2019-04-08 10:54:45', '2020-06-18 14:36:48');
INSERT INTO `menu` VALUES (30, 28, 11, '桌位管理', 'fa-reddit-alien', '/admin/desks', 0, '2019-04-08 12:39:48', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (31, 28, 12, '排队管理', 'fa-exchange', '/admin/queueType', 0, '2019-04-08 15:04:05', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (33, 32, 14, '分类管理', 'fa-align-justify', '/admin/goodsCategory', 0, '2019-04-09 11:14:50', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (34, 32, 15, '菜品管理', 'fa-bitbucket', '/admin/goods', 0, '2019-04-09 11:16:10', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (36, 35, 17, '预约列表', 'fa-calendar-check-o', '/admin/booking', 0, '2019-04-10 17:49:39', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (38, 37, 25, '优惠活动', 'fa-adn', '/admin/activity', 0, '2019-04-11 14:08:50', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (41, 40, 19, '订单管理', 'fa-cutlery', '/admin/orders', 0, '2019-04-15 15:03:15', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (44, 43, 999, '订单一览', 'fa-bars', '/admin/takeorder', 6, '2020-06-18 14:35:50', '2020-06-18 14:35:50');
INSERT INTO `menu` VALUES (48, 47, 999, '监播一览', 'fa-bars', '/admin/jianbo', 7, '2020-07-15 16:14:13', '2020-07-15 16:14:13');

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE,
  INDEX `password_resets_token_index`(`token`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `http_method` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `http_path` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_name_unique`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES (1, '超级管理者', 'administrator', '', '*', '2019-03-04 17:45:27', '2019-03-05 13:13:11');

-- ----------------------------
-- Table structure for role_menu
-- ----------------------------
DROP TABLE IF EXISTS `role_menu`;
CREATE TABLE `role_menu`  (
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `role_menu_role_id_menu_id_index`(`role_id`, `menu_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_menu
-- ----------------------------
INSERT INTO `role_menu` VALUES (1, 10, '2019-03-07 16:07:22', '2019-03-07 16:07:22');
INSERT INTO `role_menu` VALUES (2, 10, '2019-03-07 16:07:22', '2019-03-07 16:07:22');
INSERT INTO `role_menu` VALUES (1, 3, '2019-03-07 16:31:02', '2019-03-07 16:31:02');
INSERT INTO `role_menu` VALUES (1, 4, '2019-03-07 16:32:30', '2019-03-07 16:32:30');
INSERT INTO `role_menu` VALUES (1, 5, '2019-03-07 16:32:49', '2019-03-07 16:32:49');
INSERT INTO `role_menu` VALUES (1, 6, '2019-03-07 16:33:33', '2019-03-07 16:33:33');
INSERT INTO `role_menu` VALUES (1, 11, '2019-03-07 16:34:13', '2019-03-07 16:34:13');
INSERT INTO `role_menu` VALUES (1, 20, '2019-03-07 17:40:35', '2019-03-07 17:40:35');
INSERT INTO `role_menu` VALUES (1, 21, '2019-03-07 17:41:31', '2019-03-07 17:41:31');
INSERT INTO `role_menu` VALUES (1, 28, '2019-04-08 10:53:45', '2019-04-08 10:53:45');
INSERT INTO `role_menu` VALUES (1, 30, '2019-04-08 12:39:48', '2019-04-08 12:39:48');
INSERT INTO `role_menu` VALUES (1, 31, '2019-04-08 15:04:05', '2019-04-08 15:04:05');
INSERT INTO `role_menu` VALUES (1, 32, '2019-04-09 11:11:53', '2019-04-09 11:11:53');
INSERT INTO `role_menu` VALUES (1, 33, '2019-04-09 11:14:50', '2019-04-09 11:14:50');
INSERT INTO `role_menu` VALUES (1, 34, '2019-04-09 11:16:10', '2019-04-09 11:16:10');
INSERT INTO `role_menu` VALUES (1, 19, '2019-04-09 12:49:33', '2019-04-09 12:49:33');
INSERT INTO `role_menu` VALUES (2, 33, '2019-04-09 12:54:33', '2019-04-09 12:54:33');
INSERT INTO `role_menu` VALUES (2, 34, '2019-04-09 13:29:03', '2019-04-09 13:29:03');
INSERT INTO `role_menu` VALUES (1, 35, '2019-04-10 17:48:35', '2019-04-10 17:48:35');
INSERT INTO `role_menu` VALUES (1, 36, '2019-04-10 17:49:39', '2019-04-10 17:49:39');
INSERT INTO `role_menu` VALUES (1, 37, '2019-04-11 14:08:08', '2019-04-11 14:08:08');
INSERT INTO `role_menu` VALUES (1, 38, '2019-04-11 14:08:50', '2019-04-11 14:08:50');
INSERT INTO `role_menu` VALUES (1, 39, '2019-04-12 11:25:36', '2019-04-12 11:25:36');
INSERT INTO `role_menu` VALUES (1, 40, '2019-04-15 15:02:00', '2019-04-15 15:02:00');
INSERT INTO `role_menu` VALUES (1, 41, '2019-04-15 15:03:15', '2019-04-15 15:03:15');
INSERT INTO `role_menu` VALUES (1, 24, '2020-06-17 16:23:00', '2020-06-17 16:23:00');
INSERT INTO `role_menu` VALUES (1, 25, '2020-06-17 16:23:23', '2020-06-17 16:23:23');
INSERT INTO `role_menu` VALUES (1, 23, '2020-06-17 16:25:43', '2020-06-17 16:25:43');
INSERT INTO `role_menu` VALUES (1, 17, '2020-06-18 14:18:28', '2020-06-18 14:18:28');
INSERT INTO `role_menu` VALUES (1, 22, '2020-06-18 14:32:34', '2020-06-18 14:32:34');
INSERT INTO `role_menu` VALUES (2, 43, '2020-06-18 14:33:12', '2020-06-18 14:33:12');
INSERT INTO `role_menu` VALUES (2, 44, '2020-06-18 14:35:50', '2020-06-18 14:35:50');
INSERT INTO `role_menu` VALUES (1, 29, '2020-06-18 14:36:48', '2020-06-18 14:36:48');
INSERT INTO `role_menu` VALUES (1, 26, '2020-06-23 10:28:31', '2020-06-23 10:28:31');
INSERT INTO `role_menu` VALUES (2, 45, '2020-07-03 17:56:17', '2020-07-03 17:56:17');
INSERT INTO `role_menu` VALUES (2, 46, '2020-07-03 17:57:17', '2020-07-03 17:57:17');
INSERT INTO `role_menu` VALUES (1, 47, '2020-07-15 16:12:33', '2020-07-15 16:12:33');
INSERT INTO `role_menu` VALUES (4, 47, '2020-07-15 16:12:33', '2020-07-15 16:12:33');
INSERT INTO `role_menu` VALUES (1, 48, '2020-07-15 16:14:13', '2020-07-15 16:14:13');
INSERT INTO `role_menu` VALUES (4, 48, '2020-07-15 16:14:13', '2020-07-15 16:14:13');
INSERT INTO `role_menu` VALUES (1, 49, '2020-07-28 16:05:15', '2020-07-28 16:05:15');

-- ----------------------------
-- Table structure for role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions`  (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `role_permissions_role_id_permission_id_index`(`role_id`, `permission_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_permissions
-- ----------------------------
INSERT INTO `role_permissions` VALUES (1, 1, NULL, NULL);
INSERT INTO `role_permissions` VALUES (1, 2, NULL, NULL);
INSERT INTO `role_permissions` VALUES (2, 5, NULL, NULL);
INSERT INTO `role_permissions` VALUES (2, 3, NULL, NULL);
INSERT INTO `role_permissions` VALUES (3, 6, NULL, NULL);
INSERT INTO `role_permissions` VALUES (4, 7, NULL, NULL);
INSERT INTO `role_permissions` VALUES (5, 8, NULL, NULL);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_unique`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, '超级管理员', 'administrator', '2019-03-01 15:37:14', '2019-03-06 16:31:26');

-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `alias` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '别称',
  `category` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `shop_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '店铺ID',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `setting-name-key`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 52 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setting
-- ----------------------------
INSERT INTO `setting` VALUES (1, 'postprandial_settlement', '1', '餐后结算', '结算设置', 1);
INSERT INTO `setting` VALUES (2, 'queue_state', '1', '排号功能', '排号设置', 1);
INSERT INTO `setting` VALUES (3, 'queue_postpone_number', '0', '过号顺延数', '排号设置', 1);
INSERT INTO `setting` VALUES (4, 'queue_sms_number', '1', '短信通知位', '排号设置', 1);
INSERT INTO `setting` VALUES (5, 'takeout_state', '1', '外卖功能', '外卖设置', 1);
INSERT INTO `setting` VALUES (6, 'takeout_auto_receipt', '0', '自动接单', '外卖设置', 1);
INSERT INTO `setting` VALUES (7, 'takeout_store_code', 'yangfugui001', '门店编号', '外卖设置', 1);
INSERT INTO `setting` VALUES (8, 'booking_state', '1', '预约功能', '预约设置', 1);
INSERT INTO `setting` VALUES (9, 'sms_signName', '洋富柜儿', '', '短信配置', 0);
INSERT INTO `setting` VALUES (10, 'sms_accessKeyId', 'LTAIGtH2FdVZgm0l', '', '短信配置', 0);
INSERT INTO `setting` VALUES (11, 'sms_accessKeySecret', '32FtdgodNOrrStJjthpsEkyMxzxNiy', '', '短信配置', 0);
INSERT INTO `setting` VALUES (12, 'postprandial_settlement', '0', '餐后结算', '结算设置', 2);
INSERT INTO `setting` VALUES (13, 'queue_state', '1', '排号功能', '排号设置', 2);
INSERT INTO `setting` VALUES (14, 'queue_postpone_number', '0', '过号顺延数', '排号设置', 2);
INSERT INTO `setting` VALUES (15, 'queue_sms_number', '1', '短信通知位', '排号设置', 2);
INSERT INTO `setting` VALUES (16, 'takeout_state', '1', '外卖功能', '外卖设置', 2);
INSERT INTO `setting` VALUES (17, 'takeout_auto_receipt', '0', '自动接单', '外卖设置', 2);
INSERT INTO `setting` VALUES (18, 'takeout_store_code', 'yangfugui002', '门店编号', '外卖设置', 2);
INSERT INTO `setting` VALUES (19, 'booking_state', '0', '预约功能', '预约设置', 2);
INSERT INTO `setting` VALUES (20, 'wx_app_state', '1', '', '微信小程序', 0);
INSERT INTO `setting` VALUES (21, 'wx_app_id', 'wx7a7cf81b8736f72a', '', '微信小程序', 0);
INSERT INTO `setting` VALUES (22, 'wx_app_secret', '0080ff040c5790d8c826ed3087e8853e', '', '微信小程序', 0);
INSERT INTO `setting` VALUES (23, 'wx_mch_id', '1518360381', '', '微信小程序', 0);
INSERT INTO `setting` VALUES (24, 'wx_mch_key', 'M8o10ChF06kV8jvXwBPs0tu6fnjDFVW6', '', '微信小程序', 0);
INSERT INTO `setting` VALUES (25, 'push_state', '1', '', '推送', 0);
INSERT INTO `setting` VALUES (26, 'push_accessKeyId', 'LTAIGtH2FdVZgm0l', '', '推送', 0);
INSERT INTO `setting` VALUES (27, 'push_accessKeySecret', '32FtdgodNOrrStJjthpsEkyMxzxNiy', '', '推送', 0);
INSERT INTO `setting` VALUES (28, 'push_androidAppKey', '26012368', '', '推送', 0);
INSERT INTO `setting` VALUES (29, 'push_iosAppKey', '26012368', '', '推送', 0);
INSERT INTO `setting` VALUES (30, 'takeout_price', '0.01', '', '外卖配置', 0);
INSERT INTO `setting` VALUES (31, 'takeout_tableware_price', '0.00', '', '外卖配置', 0);
INSERT INTO `setting` VALUES (32, 'takeout_distance', '0', '', '外卖配置', 0);
INSERT INTO `setting` VALUES (33, 'other_service_state', '1', '', '外卖配置', 0);
INSERT INTO `setting` VALUES (34, 'wx_access_token_expires_in', '1594010818', '微信授权过期时间', '微信小程序', 0);
INSERT INTO `setting` VALUES (35, 'wx_access_token', '35_S3wNQ982ByrmiP5fg0MSw4ZUBPW_L0839-THMHYDWE_iM_Ygx24JegQQ1VgEfuc93bT-AJqTCfpcKFOin9I1JEaAqSBI_KpF3rZ6FDvX6XMkAeiHRlxh_juaPKZUBCsQKUahfiAXyiFLWGY7YOXiAGADTC', '微信授权TOKEN', '微信小程序', 0);
INSERT INTO `setting` VALUES (36, 'takeout_company', 'dianwoda', '', '外卖配置', 0);
INSERT INTO `setting` VALUES (37, 'takeout_appKey', 't1000186', '', '外卖配置', 0);
INSERT INTO `setting` VALUES (38, 'takeout_appSecret', 'bca0f639472e59a86cd5275f226ceb9b', '', '外卖配置', 0);
INSERT INTO `setting` VALUES (39, 'ali_app_state', '1', '', '支付宝小程序', 0);
INSERT INTO `setting` VALUES (40, 'ali_app_id', '2018121962577500', '', '支付宝小程序', 0);
INSERT INTO `setting` VALUES (41, 'ali_alipayrsaPublicKey', 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAkdYctWnHd6Y50H7W9BxjOGLa2VWUjf+dxEPuw3ClpEDUo0cXr1rYYF63Yd78k/d1+Y0/r5ZbUjLAQoZlquzQQ+hi927lU1xPeoJyjSasj3/wj+uSAv0fCqA8JIvrHXeCrkUlzoN8Q++BXhohxMmJgSRv8AviOOr9noltsIH4u+9FkQzCI93PFHIrl4q9EfYat7P1NvcqNdTnapswi2pcDzEaIw0QXHi7ivtmlOyhe2Re5RadLO1qDTm8YzNLjXasVmpq0AzkA0k8J6elzFSvKJD9HfxJaD3oT0B+WyXEAC9n+DJIUcLFM/WgJ84bMzmWLWEoLuL67s8c0/Ho0UU3jwIDAQAB', '', '支付宝小程序', 0);
INSERT INTO `setting` VALUES (42, 'ali_rsaPrivateKey', 'MIIEowIBAAKCAQEAwLBOiGyvoQugbb8frrLZb4YWW0EdNwjI1smf7R4ZCMLG92tfAwKBK0nDuFNQdSLpOZCRlBLWEowMXPWjyfZSOH6AvV84W2nbSt7qxFvuBok/2nXD5hLXHnZbdv+wjsw2l/N3OIamLyNkVkqsu75PJbXY1oXxyFtK2BYglAdLkCSZkfChaxyWkHQLC0W4uyrN2BCCRlR9bE44vGvzVNoTuf0PN+jIpjIvgCc5/X/8R9TF5e1QoRxM9QxwKIYTqVJfUmgfcY3Hubjh0hZBKQEgta0VKzDzmqvQuW3taLx/+T0iyI/ExNCAbri2CJ5qaiZch4E6wVtsH1xRb+FhhzJJNQIDAQABAoIBAC1/tBuMpm/9odUoQx/qRWOLIu5LUs2y+lGVbHj+GWUzMO4tYVMBnWSOV3PH4IC4TJ/2Hhn71KaugSDWM+W1jzad0GJnAJS9SzXDq2XQW0UZ1YUNN8O1ASzFP9W6jIM7f0ykBavIR6dr0P57RYxTZLb/2ILXP/9SZzrdBrZNfq87PE+gSkVjh0OYtar0AR7GQm0VwbwkKVVKnAEotZAPl6rS1NC9YNvD9wwSQ2mFCoRD1HAkI6YhKBdpsk3Zp7ZKOOI24KKB7m8os7eRaGq4EfjPmmSzp1B8sfhPJWJTykeKEN0cWbYsXnmYYzyjiNlEVlT8sn6YAza5uVLhoTX0JCUCgYEA39YqRA+/bbuBAA8IIKZ1Rb3KEvAV4HUPGl6TWqQ9PU5ixFBlm9XSLqzuPODtzU46zXJKpcsqzAaASrC5YM4cRpOzTiPiZeZC61Dz+ocL5YMmN4bwMM3tVOG5aYJ/UO6rELdc8Y2P/5NwmMmTZHzIRYnYoJVZNIdHdlk2JZ6/2iMCgYEA3GBdqruhPSkvkZVM1CsE4lrBiG8cMj2apSUK9RXV5fJ3zornDPd39dcOLzkuvnqUe7Ptgq6O42bZq6/yUwIWh7T3cB/i2eRqODU//K8mqpDHOwES66h/pYfI7yN6RC10I1GDcO0jiu6F9Dx3VmDRFeTHGWXZuTpVmoMO4uog6McCgYA6VP6oxA3YE/A3SrOMhrSzGxWpP8YDu53W+mSeT4TiECZvEKCaLuvaXBit5tQyF7v9RFatxDd/+gW+8TUuRChcQCuPJozej1ZLKsqaNE1mX3o7KEA6B8BcyYJfO7HgLoKIFbD0BjdLnGnQd1+g5V/vt0+r8Z/Qr5xw/Ci/PxKyTwKBgD7PfILn/XnIHlW5Hu+t3zOAuH5hZMDxC/2bxDa8ZX2nkPweXOI2OkuoYtOU0bzahS2Ix94iUHmB2/JyMHf2NWOycX/UpryBvMCOdNFZPoUIxLANi039dXxBakS2cOezqNFUL0llXWcAus802LKW36EE1rZncBm6BaIHTpvgLUcLAoGBAMTBJGlrexmKhNeN81lHvrhgkoC9JSIMMVqbhyi+W/Xnz+KBdTg4tOnY+Zizw9GxsNuIdUlb5DFD/RF6FVSJFpdFobvl23EOw/dGUflFTM7QvV7Zt3O5fH+ZC9dKgTosUb7XapEOPrzrpZdj3ISz8pd93XB+N0Fd6cGBDlw9INFP', '', '支付宝小程序', 0);
INSERT INTO `setting` VALUES (43, 'takeout_sourceId', 'TEST2018-a444-4e50-b785-f48ba984bd9c', '', '外卖配置', 0);
INSERT INTO `setting` VALUES (44, 'postprandial_settlement', '0', '餐后结算', '结算设置', 3);
INSERT INTO `setting` VALUES (45, 'queue_state', '0', '排号功能', '排号设置', 3);
INSERT INTO `setting` VALUES (46, 'queue_postpone_number', '0', '过号顺延数', '排号设置', 3);
INSERT INTO `setting` VALUES (47, 'queue_sms_number', '3', '短信通知位', '排号设置', 3);
INSERT INTO `setting` VALUES (48, 'takeout_state', '1', '外卖功能', '外卖设置', 3);
INSERT INTO `setting` VALUES (49, 'takeout_auto_receipt', '0', '自动接单', '外卖设置', 3);
INSERT INTO `setting` VALUES (50, 'takeout_store_code', '', '门店编号', '外卖设置', 3);
INSERT INTO `setting` VALUES (51, 'booking_state', '0', '预约功能', '预约设置', 3);

-- ----------------------------
-- Table structure for stock
-- ----------------------------
DROP TABLE IF EXISTS `stock`;
CREATE TABLE `stock`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '描述',
  `sort` int(11) NULL DEFAULT 99 COMMENT '排序号',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '删除标记',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for stock_statis
-- ----------------------------
DROP TABLE IF EXISTS `stock_statis`;
CREATE TABLE `stock_statis`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `stock_id` int(10) NULL DEFAULT 0,
  `hold_num` int(6) NULL DEFAULT 0,
  `amout` bigint(20) NULL DEFAULT 0,
  `position_cost` decimal(10, 2) NULL DEFAULT 0.00,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '删除标记',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_tokens
-- ----------------------------
DROP TABLE IF EXISTS `user_tokens`;
CREATE TABLE `user_tokens`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `access_token` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '登录token',
  `device_type` enum('android','ios','wechat','alipay') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '设备类型',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `userTokens_userId`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '登录token' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_tokens
-- ----------------------------
INSERT INTO `user_tokens` VALUES (1, 1, 'fede7465a8f733fd56deb7bd6d24f306', 'wechat', '2020-06-11 16:53:17', '2020-06-11 16:53:17');
INSERT INTO `user_tokens` VALUES (2, 2, '8c1833bf4e61773eb9836b2274d7ebdb', 'wechat', '2020-06-23 16:19:45', '2020-06-23 16:19:45');
INSERT INTO `user_tokens` VALUES (3, 3, 'a4a166b82768d1701510cd2f602266ba', 'wechat', '2020-06-23 16:20:12', '2020-06-23 16:20:12');
INSERT INTO `user_tokens` VALUES (4, 4, '011408d76ebd1583e7c9bd953ce87528', 'wechat', '2020-07-02 10:57:48', '2020-07-02 10:57:48');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '用户昵称',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `role` tinyint(4) UNSIGNED NOT NULL DEFAULT 2 COMMENT '1：店员、2：用餐用户',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '电话',
  `password` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '登录密码',
  `wx_open_id` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '微信openid',
  `ali_open_id` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '支付宝openid',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '个人备注',
  `last_login_at` timestamp(0) NULL DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `state` tinyint(4) NULL DEFAULT 1 COMMENT '1正常，2禁用',
  `point` int(10) NULL DEFAULT 0 COMMENT '积分',
  `gender` tinyint(1) NULL DEFAULT 0 COMMENT '性别(1:男 2:女)',
  `birthday` date NULL DEFAULT NULL COMMENT '生日',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `users_wxOpenId_idx`(`wx_open_id`) USING BTREE,
  INDEX `users_aliOpenId_idx`(`ali_open_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'zengxm', 'https://wx.qlogo.cn/mmopen/vi_32/Wbk2zapQaibKlgbPboiaHpYYWcB1SUzjKt3HYic8aBe3icDibl6CuTVichVb6Tn3J8ZUZNUGhQI0LCNwLOyCrK2a7j8w/132', 2, '18482160080', '0', 'ooOho5BmuG7L9nmQBxKbtVkMz-3o', '', '', '2020-07-06 10:46:58', '182.149.66.40', 1, 100, 1, '2020-06-03', '2020-06-11 16:53:17', '2020-07-06 10:46:58', 0);
INSERT INTO `users` VALUES (2, 'young', 'https://wx.qlogo.cn/mmopen/vi_32/Pj1FD5JTX1NlicsfbcxHowLG6DUHmyib7OpMJzKdLnfn4AGPwAd0xV7ibbA048omvEcJE4zPS1WUfC8em4uTztoJg/132', 2, '13540471003', '0', 'ooOho5MMWnQ9Nk7jP4NVUpQ5KdTQ', '', '', '2020-07-06 10:58:01', '110.185.91.134', 1, 20, 1, NULL, '2020-06-23 16:19:45', '2020-07-06 10:58:01', 0);
INSERT INTO `users` VALUES (3, '不说', 'https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIFzTicymCPicQxiaPovkuBC4ex82Kh5ibouzBUFeChZdJbuE8skAiagGu7WoRelaCpsRRFqgkcZjU8cOg/132', 2, '18628103745', '0', 'ooOho5JgDiYfwn0DS0CpoLIt7LWU', '', '', '2020-07-03 09:28:45', '182.144.180.111', 1, 10, 1, NULL, '2020-06-23 16:20:12', '2020-07-03 09:28:45', 0);
INSERT INTO `users` VALUES (4, 'clearlee', 'https://wx.qlogo.cn/mmopen/vi_32/LeRpbqscjIkQAzGmwAxOibncrogXm9BJ243RXZIljk2fg9uVWDItVq9P9wChqZYfrCQIicsvzsdcvn3ckq5T9ckA/132', 2, '13330906436', '0', 'ooOho5DomJ7N0SB3Lcb0qUoLlnMs', '', '', '2020-07-02 10:59:40', '110.184.9.95', 1, 0, 1, NULL, '2020-07-02 10:57:48', '2020-07-02 10:59:40', 0);

SET FOREIGN_KEY_CHECKS = 1;
