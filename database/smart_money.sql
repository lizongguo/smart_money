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

 Date: 26/08/2020 10:07:28
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
INSERT INTO `admin_permissions` VALUES (1, 1, '2020-08-24 17:32:11', '2020-08-24 17:32:11');
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
INSERT INTO `admins` VALUES (1, 'ClearLee', '/upload/20200818/152712_7cb3fe34098209258167079a9632426a.jpeg', 'admin@admin.com', '13800000000', 1, '$2y$10$PMUg5QIrcyAmOMfDAOjYmuWpb/ougkM4mmFvX7tcymWrPTuLZxmL.', '610012c2522a77e02d25a7c4ca5351c4', 'lWrFatRJPnSrnKxxQlgaCV5ZYNdN4bOjMHVXFb7lnZ20phuUrTRp1zR6TaqX', '', 0, '2019-03-06 13:52:49', '2020-08-25 19:01:51', 0);

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
  `ranking` int(5) NULL DEFAULT 0 COMMENT '排名',
  `type` tinyint(1) NULL DEFAULT 0 COMMENT '类型',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '删除标记',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 50 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of fund
-- ----------------------------
INSERT INTO `fund` VALUES (1, '全国社保基金一一七组合', '', '', 0, 3, '2020-08-18 17:23:11', '2020-08-18 18:26:30', 0);
INSERT INTO `fund` VALUES (2, '全国社保基金一零八组合', '', '', 0, 3, '2020-08-18 18:59:00', '2020-08-18 18:59:00', 0);
INSERT INTO `fund` VALUES (3, '全国社保基金五零三组合', '', '', 0, 3, '2020-08-19 15:05:34', '2020-08-19 15:05:34', 0);
INSERT INTO `fund` VALUES (4, '全国社保基金四一四组合', '', '', 0, 3, '2020-08-19 15:05:50', '2020-08-19 15:05:50', 0);
INSERT INTO `fund` VALUES (5, '全国社保基金一一六组合', '', '', 0, 3, '2020-08-19 15:07:59', '2020-08-19 15:07:59', 0);
INSERT INTO `fund` VALUES (6, '全国社保基金一一五组合', '', '', 0, 3, '2020-08-19 15:08:20', '2020-08-19 15:08:20', 0);
INSERT INTO `fund` VALUES (7, '全国社保基金一一一组合', '', '', 0, 3, '2020-08-19 15:08:35', '2020-08-19 15:08:35', 0);
INSERT INTO `fund` VALUES (8, '全国社保基金一零一组合', '', '', 0, 3, '2020-08-19 15:08:57', '2020-08-19 15:08:57', 0);
INSERT INTO `fund` VALUES (9, '博时基金管理有限公司-社保基金四一九组合', '', '', 0, 3, '2020-08-19 15:09:25', '2020-08-19 15:09:25', 0);
INSERT INTO `fund` VALUES (10, '全国社保基金六零三组合', '', '', 0, 3, '2020-08-19 15:10:00', '2020-08-19 15:10:00', 0);
INSERT INTO `fund` VALUES (11, '全国社保基金一一零组合', '', '', 0, 3, '2020-08-19 15:10:21', '2020-08-19 15:10:21', 0);
INSERT INTO `fund` VALUES (12, '全国社保基金一零五组合', '', '', 0, 3, '2020-08-19 15:10:38', '2020-08-19 15:10:38', 0);
INSERT INTO `fund` VALUES (13, '全国社保基金六零一组合', '', '', 0, 3, '2020-08-19 15:16:15', '2020-08-19 15:16:15', 0);
INSERT INTO `fund` VALUES (14, '全国社保基金四零六组合', '', '', 0, 3, '2020-08-19 15:16:37', '2020-08-19 15:16:37', 0);
INSERT INTO `fund` VALUES (15, '全国社保基金一零九组合', '', '', 0, 3, '2020-08-19 15:17:01', '2020-08-19 15:17:01', 0);
INSERT INTO `fund` VALUES (16, '广发基金管理有限公司-社保基金四二零组合', '', '', 0, 3, '2020-08-19 15:17:43', '2020-08-19 15:17:43', 0);
INSERT INTO `fund` VALUES (17, '国泰基金管理有限公司-社保基金四二一组合', '', '', 0, 3, '2020-08-19 15:17:53', '2020-08-19 15:17:53', 0);
INSERT INTO `fund` VALUES (18, '全国社保基金五零二组合', '', '', 0, 3, '2020-08-19 15:18:34', '2020-08-19 15:18:34', 0);
INSERT INTO `fund` VALUES (19, '全国社保基金一零三组合', '', '', 0, 3, '2020-08-19 15:18:45', '2020-08-19 15:18:45', 0);
INSERT INTO `fund` VALUES (20, '全国社保基金五零四组合', '', '', 0, 3, '2020-08-19 15:24:09', '2020-08-19 15:24:09', 0);
INSERT INTO `fund` VALUES (21, '全国社保基金一零六组合', '', '', 0, 3, '2020-08-19 15:25:47', '2020-08-19 15:25:47', 0);
INSERT INTO `fund` VALUES (22, '全国社保基金一一三组合', '', '', 0, 3, '2020-08-19 15:26:20', '2020-08-19 15:26:20', 0);
INSERT INTO `fund` VALUES (23, '博时基金管理有限公司-社保基金16011组合', '', '', 0, 3, '2020-08-19 15:26:46', '2020-08-19 15:26:46', 0);
INSERT INTO `fund` VALUES (24, '全国社保基金五零一组合', '', '', 0, 3, '2020-08-19 15:26:56', '2020-08-19 15:26:56', 0);
INSERT INTO `fund` VALUES (25, '中信证券股份有限公司-社保基金1106组合', '', '', 0, 3, '2020-08-19 15:27:30', '2020-08-19 15:27:30', 0);
INSERT INTO `fund` VALUES (26, '全国社保基金四一八组合', '', '', 0, 3, '2020-08-19 15:27:42', '2020-08-19 15:27:42', 0);
INSERT INTO `fund` VALUES (27, '全国社保基金一一四组合', '', '', 0, 3, '2020-08-19 15:27:53', '2020-08-19 15:27:53', 0);
INSERT INTO `fund` VALUES (28, '国泰基金管理有限公司-社保基金1102组合', '', '', 0, 3, '2020-08-19 15:34:50', '2020-08-19 15:34:50', 0);
INSERT INTO `fund` VALUES (29, '全国社保基金六零二组合', '', '', 0, 3, '2020-08-19 15:35:15', '2020-08-19 15:35:15', 0);
INSERT INTO `fund` VALUES (30, '全国社保基金四一三组合', '', '', 0, 3, '2020-08-19 15:40:51', '2020-08-19 15:40:51', 0);
INSERT INTO `fund` VALUES (31, '全国社保基金一一二组合', '', '', 0, 3, '2020-08-19 15:41:15', '2020-08-19 15:41:15', 0);
INSERT INTO `fund` VALUES (32, '汇添富基金管理股份有限公司-社保基金四二三组合', '', '', 0, 3, '2020-08-19 15:42:37', '2020-08-19 15:42:37', 0);
INSERT INTO `fund` VALUES (33, '全国社保基金一零二组合', '', '', 0, 3, '2020-08-19 15:43:12', '2020-08-19 15:43:12', 0);
INSERT INTO `fund` VALUES (34, '汇添富基金管理股份有限公司-社保基金1103组合', '', '', 0, 3, '2020-08-19 15:44:24', '2020-08-19 15:44:24', 0);
INSERT INTO `fund` VALUES (35, '全国社保基金一零四组合', '', '', 0, 3, '2020-08-19 15:45:03', '2020-08-19 15:45:03', 0);
INSERT INTO `fund` VALUES (36, '全国社保基金一零七组合', '', '', 0, 3, '2020-08-19 15:45:30', '2020-08-19 15:45:30', 0);
INSERT INTO `fund` VALUES (37, '全国社保基金一一八组合', '', '', 0, 3, '2020-08-19 15:48:01', '2020-08-19 15:48:01', 0);
INSERT INTO `fund` VALUES (38, '全国社保基金六零四组合', '', '', 0, 3, '2020-08-19 15:50:00', '2020-08-19 15:50:00', 0);
INSERT INTO `fund` VALUES (39, '全国社保基金四零三组合', '', '', 0, 3, '2020-08-19 15:50:36', '2020-08-19 15:50:36', 0);
INSERT INTO `fund` VALUES (40, '全国社保基金四一二组合', '', '', 0, 3, '2020-08-19 15:54:45', '2020-08-19 15:54:45', 0);
INSERT INTO `fund` VALUES (41, '大成基金管理有限公司-社保基金17011组合', '', '', 0, 3, '2020-08-19 15:56:41', '2020-08-19 15:56:41', 0);
INSERT INTO `fund` VALUES (42, '大成基金管理有限公司-社保基金1101组合', '', '', 0, 3, '2020-08-19 15:56:55', '2020-08-19 15:56:55', 0);
INSERT INTO `fund` VALUES (43, '全国社保基金四零一组合', '', '', 0, 3, '2020-08-19 16:19:23', '2020-08-19 16:19:23', 0);
INSERT INTO `fund` VALUES (44, '艾华集团', '', '', 0, 3, '2020-08-19 16:41:23', '2020-08-19 16:41:44', 1);
INSERT INTO `fund` VALUES (45, '全国社保基金四一六组合', '', '', 0, 3, '2020-08-25 17:50:31', '2020-08-25 17:50:31', 0);
INSERT INTO `fund` VALUES (46, '全国社保基金四零四组合', '', '', 0, 3, '2020-08-25 18:00:04', '2020-08-25 18:00:04', 0);
INSERT INTO `fund` VALUES (47, '华夏基金管理有限公司-社保基金16021组合', '', '', 0, 3, '2020-08-25 18:54:47', '2020-08-25 18:54:47', 0);
INSERT INTO `fund` VALUES (48, '银华基金管理股份有限公司-社保基金1105组合', '', '', 0, 3, '2020-08-25 23:44:30', '2020-08-25 23:44:30', 0);
INSERT INTO `fund` VALUES (49, '全国社保基金四一一组合', '', '', 0, 3, '2020-08-26 00:36:59', '2020-08-26 00:36:59', 0);

-- ----------------------------
-- Table structure for fund_stock
-- ----------------------------
DROP TABLE IF EXISTS `fund_stock`;
CREATE TABLE `fund_stock`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fund_id` int(10) NULL DEFAULT 0,
  `stock_id` int(10) NULL DEFAULT 0,
  `amount` decimal(20, 4) NULL DEFAULT 0.0000 COMMENT '持股市值',
  `position_cost` decimal(20, 4) NULL DEFAULT 0.0000 COMMENT '成本',
  `stock_num` decimal(20, 4) NULL DEFAULT 0.0000 COMMENT '持股总数',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '删除标记',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 205 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of fund_stock
-- ----------------------------
INSERT INTO `fund_stock` VALUES (1, 1, 1, 47700.0000, 17.6667, 2700.0000, '2020-08-23 13:28:44', '2020-08-23 13:28:44', 0);
INSERT INTO `fund_stock` VALUES (3, 2, 1, 105800.0000, 17.6760, 5985.5100, '2020-08-23 13:54:05', '2020-08-23 13:54:05', 0);
INSERT INTO `fund_stock` VALUES (4, 21, 1, 103200.0000, 17.6748, 5838.8100, '2020-08-23 13:55:03', '2020-08-23 13:55:03', 0);
INSERT INTO `fund_stock` VALUES (5, 3, 2, 78400.0000, 46.1190, 1699.9500, '2020-08-23 13:56:44', '2020-08-23 13:56:44', 0);
INSERT INTO `fund_stock` VALUES (6, 4, 2, 61100.0000, 46.1240, 1324.6900, '2020-08-23 13:57:56', '2020-08-23 13:57:56', 0);
INSERT INTO `fund_stock` VALUES (7, 5, 2, 41600.0000, 46.1197, 902.0000, '2020-08-23 13:58:27', '2020-08-23 13:58:27', 0);
INSERT INTO `fund_stock` VALUES (8, 6, 2, 63600.0000, 46.0870, 1380.0000, '2020-08-23 14:04:37', '2020-08-23 14:04:37', 0);
INSERT INTO `fund_stock` VALUES (9, 7, 2, 92300.0000, 46.1140, 2001.5600, '2020-08-23 14:05:21', '2020-08-23 14:06:25', 0);
INSERT INTO `fund_stock` VALUES (10, 8, 2, 73800.0000, 46.0648, 1602.0900, '2020-08-23 14:06:49', '2020-08-23 14:06:49', 0);
INSERT INTO `fund_stock` VALUES (11, 37, 51, 285200.0000, 435.3135, 655.1600, '2020-08-23 14:08:47', '2020-08-23 14:08:47', 0);
INSERT INTO `fund_stock` VALUES (12, 5, 51, 122700.0000, 435.1990, 281.9400, '2020-08-23 14:11:15', '2020-08-23 14:11:15', 0);
INSERT INTO `fund_stock` VALUES (13, 35, 51, 186200.0000, 435.2196, 427.8300, '2020-08-23 14:49:07', '2020-08-23 15:02:09', 0);
INSERT INTO `fund_stock` VALUES (14, 8, 51, 247600.0000, 435.2259, 568.9000, '2020-08-23 15:03:42', '2020-08-23 15:03:42', 0);
INSERT INTO `fund_stock` VALUES (15, 9, 3, 21400.0000, 18.5767, 1151.9800, '2020-08-24 13:08:15', '2020-08-24 13:08:15', 0);
INSERT INTO `fund_stock` VALUES (16, 10, 3, 12100.0000, 18.5685, 651.6400, '2020-08-24 13:10:29', '2020-08-24 13:10:29', 0);
INSERT INTO `fund_stock` VALUES (17, 11, 3, 19400.0000, 18.5321, 1046.8300, '2020-08-24 13:11:05', '2020-08-24 13:11:05', 0);
INSERT INTO `fund_stock` VALUES (18, 12, 3, 22300.0000, 18.5201, 1204.1000, '2020-08-24 13:13:08', '2020-08-24 13:13:08', 0);
INSERT INTO `fund_stock` VALUES (19, 13, 4, 30100.0000, 68.1937, 441.3900, '2020-08-24 18:11:05', '2020-08-24 18:11:05', 0);
INSERT INTO `fund_stock` VALUES (20, 3, 4, 25600.0000, 68.2685, 374.9900, '2020-08-24 18:11:37', '2020-08-24 18:11:37', 0);
INSERT INTO `fund_stock` VALUES (21, 14, 4, 42700.0000, 68.1836, 626.2500, '2020-08-24 18:17:17', '2020-08-24 18:18:06', 0);
INSERT INTO `fund_stock` VALUES (22, 15, 4, 36200.0000, 68.3186, 529.8700, '2020-08-24 18:17:48', '2020-08-24 18:17:48', 0);
INSERT INTO `fund_stock` VALUES (23, 16, 5, 7100.0000, 18.6822, 380.0400, '2020-08-25 13:32:28', '2020-08-25 13:32:28', 0);
INSERT INTO `fund_stock` VALUES (24, 17, 5, 6400.0000, 18.6943, 342.3500, '2020-08-25 13:33:57', '2020-08-25 13:33:57', 0);
INSERT INTO `fund_stock` VALUES (25, 3, 5, 16000.0000, 18.8235, 850.0000, '2020-08-25 13:34:25', '2020-08-25 13:34:25', 0);
INSERT INTO `fund_stock` VALUES (26, 9, 6, 15300.0000, 9.5626, 1599.9900, '2020-08-25 13:37:42', '2020-08-25 13:37:42', 0);
INSERT INTO `fund_stock` VALUES (27, 18, 6, 14000.0000, 9.5428, 1467.0700, '2020-08-25 13:38:14', '2020-08-25 13:38:14', 0);
INSERT INTO `fund_stock` VALUES (28, 19, 6, 33400.0000, 9.5430, 3499.9400, '2020-08-25 13:39:45', '2020-08-25 13:39:45', 0);
INSERT INTO `fund_stock` VALUES (29, 39, 52, 30200.0000, 8.8854, 3398.8500, '2020-08-25 17:36:13', '2020-08-25 17:36:13', 0);
INSERT INTO `fund_stock` VALUES (30, 5, 52, 35600.0000, 8.9075, 3996.6200, '2020-08-25 17:37:02', '2020-08-25 17:37:02', 0);
INSERT INTO `fund_stock` VALUES (31, 36, 52, 39000.0000, 8.8923, 4385.8400, '2020-08-25 17:37:45', '2020-08-25 17:37:45', 0);
INSERT INTO `fund_stock` VALUES (32, 10, 7, 6500.0000, 12.1723, 534.0000, '2020-08-25 17:41:21', '2020-08-25 17:41:21', 0);
INSERT INTO `fund_stock` VALUES (33, 20, 7, 7600.0000, 12.2435, 620.7400, '2020-08-25 17:41:56', '2020-08-25 17:41:56', 0);
INSERT INTO `fund_stock` VALUES (34, 12, 7, 12300.0000, 12.2095, 1007.4100, '2020-08-25 17:42:28', '2020-08-25 17:42:28', 0);
INSERT INTO `fund_stock` VALUES (35, 32, 53, 68600.0000, 21.1728, 3240.0000, '2020-08-25 17:49:13', '2020-08-25 17:49:13', 0);
INSERT INTO `fund_stock` VALUES (36, 26, 53, 80900.0000, 21.1769, 3820.2000, '2020-08-25 17:49:46', '2020-08-25 17:49:46', 0);
INSERT INTO `fund_stock` VALUES (37, 45, 53, 96600.0000, 21.1793, 4561.0500, '2020-08-25 17:51:24', '2020-08-25 17:51:24', 0);
INSERT INTO `fund_stock` VALUES (38, 9, 8, 17800.0000, 17.8002, 999.9900, '2020-08-25 17:55:02', '2020-08-25 17:55:02', 0);
INSERT INTO `fund_stock` VALUES (39, 22, 8, 17700.0000, 17.8705, 990.4600, '2020-08-25 17:55:38', '2020-08-25 17:55:38', 0);
INSERT INTO `fund_stock` VALUES (40, 8, 8, 9800.0000, 17.8156, 550.0800, '2020-08-25 17:56:10', '2020-08-25 17:56:10', 0);
INSERT INTO `fund_stock` VALUES (41, 30, 54, 17100.0000, 40.7424, 419.7100, '2020-08-25 17:59:31', '2020-08-25 17:59:31', 0);
INSERT INTO `fund_stock` VALUES (42, 46, 54, 15300.0000, 40.5857, 376.9800, '2020-08-25 18:00:41', '2020-08-25 18:00:41', 0);
INSERT INTO `fund_stock` VALUES (43, 19, 54, 12200.0000, 40.7577, 299.3300, '2020-08-25 18:01:13', '2020-08-25 18:01:13', 0);
INSERT INTO `fund_stock` VALUES (44, 7, 55, 134200.0000, 50.0045, 2683.7600, '2020-08-25 18:04:03', '2020-08-25 18:05:28', 0);
INSERT INTO `fund_stock` VALUES (45, 2, 55, 105200.0000, 50.0017, 2103.9300, '2020-08-25 18:04:47', '2020-08-25 18:04:47', 0);
INSERT INTO `fund_stock` VALUES (46, 21, 55, 83400.0000, 50.0180, 1667.4000, '2020-08-25 18:05:15', '2020-08-25 18:05:15', 0);
INSERT INTO `fund_stock` VALUES (47, 14, 56, 67500.0000, 62.2366, 1084.5700, '2020-08-25 18:09:05', '2020-08-25 18:09:05', 0);
INSERT INTO `fund_stock` VALUES (48, 6, 56, 55800.0000, 62.2074, 897.0000, '2020-08-25 18:09:32', '2020-08-25 18:09:32', 0);
INSERT INTO `fund_stock` VALUES (49, 31, 56, 109000.0000, 62.2750, 1750.3000, '2020-08-25 18:10:05', '2020-08-25 18:10:05', 0);
INSERT INTO `fund_stock` VALUES (50, 4, 57, 66200.0000, 56.9899, 1161.6100, '2020-08-25 18:11:45', '2020-08-25 18:11:45', 0);
INSERT INTO `fund_stock` VALUES (51, 14, 57, 25600.0000, 56.9104, 449.8300, '2020-08-25 18:12:23', '2020-08-25 18:12:23', 0);
INSERT INTO `fund_stock` VALUES (52, 11, 57, 27700.0000, 56.9408, 486.4700, '2020-08-25 18:13:00', '2020-08-25 18:13:00', 0);
INSERT INTO `fund_stock` VALUES (53, 10, 58, 5700.0000, 6.1057, 933.5500, '2020-08-25 18:23:07', '2020-08-25 18:23:07', 0);
INSERT INTO `fund_stock` VALUES (54, 37, 58, 7300.0000, 6.1164, 1193.5200, '2020-08-25 18:26:15', '2020-08-25 18:26:15', 0);
INSERT INTO `fund_stock` VALUES (55, 12, 58, 8400.0000, 6.1480, 1366.2900, '2020-08-25 18:26:51', '2020-08-25 18:26:51', 0);
INSERT INTO `fund_stock` VALUES (56, 23, 9, 23900.0000, 17.0714, 1400.0000, '2020-08-25 18:28:08', '2020-08-25 18:28:08', 0);
INSERT INTO `fund_stock` VALUES (57, 24, 9, 13300.0000, 17.0795, 778.7100, '2020-08-25 18:28:47', '2020-08-25 18:28:47', 0);
INSERT INTO `fund_stock` VALUES (58, 2, 9, 78600.0000, 17.0870, 4599.9900, '2020-08-25 18:29:19', '2020-08-25 18:29:19', 0);
INSERT INTO `fund_stock` VALUES (59, 25, 10, 21000.0000, 5.9341, 3538.8900, '2020-08-25 18:33:03', '2020-08-25 18:33:03', 0);
INSERT INTO `fund_stock` VALUES (60, 26, 10, 30200.0000, 5.9197, 5101.6100, '2020-08-25 18:33:47', '2020-08-25 18:33:47', 0);
INSERT INTO `fund_stock` VALUES (61, 27, 10, 17800.0000, 5.9298, 3001.8100, '2020-08-25 18:34:22', '2020-08-25 18:34:22', 0);
INSERT INTO `fund_stock` VALUES (62, 14, 59, 46000.0000, 58.5167, 786.1000, '2020-08-25 18:36:41', '2020-08-25 18:36:41', 0);
INSERT INTO `fund_stock` VALUES (63, 21, 59, 91400.0000, 58.5458, 1561.1700, '2020-08-25 18:37:14', '2020-08-25 18:37:14', 0);
INSERT INTO `fund_stock` VALUES (64, 8, 59, 106800.0000, 58.5222, 1824.9500, '2020-08-25 18:37:46', '2020-08-25 18:37:46', 0);
INSERT INTO `fund_stock` VALUES (65, 17, 11, 16200.0000, 28.9876, 558.8600, '2020-08-25 18:38:54', '2020-08-25 18:38:54', 0);
INSERT INTO `fund_stock` VALUES (66, 28, 11, 12800.0000, 28.9639, 441.9300, '2020-08-25 18:39:23', '2020-08-25 18:39:23', 0);
INSERT INTO `fund_stock` VALUES (67, 22, 11, 12600.0000, 28.9615, 435.0600, '2020-08-25 18:39:54', '2020-08-25 18:39:54', 0);
INSERT INTO `fund_stock` VALUES (68, 29, 12, 22900.0000, 77.7721, 294.4500, '2020-08-25 18:43:42', '2020-08-25 18:43:42', 0);
INSERT INTO `fund_stock` VALUES (69, 14, 12, 58400.0000, 77.7350, 751.2700, '2020-08-25 18:44:40', '2020-08-25 18:44:40', 0);
INSERT INTO `fund_stock` VALUES (70, 11, 12, 59200.0000, 77.7148, 761.7600, '2020-08-25 18:45:12', '2020-08-25 18:45:12', 0);
INSERT INTO `fund_stock` VALUES (71, 47, 60, 5500.0000, 9.4539, 581.7700, '2020-08-25 18:55:18', '2020-08-25 18:55:18', 0);
INSERT INTO `fund_stock` VALUES (72, 18, 60, 5300.0000, 9.4479, 560.9700, '2020-08-25 18:58:35', '2020-08-25 18:58:35', 0);
INSERT INTO `fund_stock` VALUES (73, 15, 60, 4700.0000, 9.3998, 500.0100, '2020-08-25 18:59:02', '2020-08-25 18:59:02', 0);
INSERT INTO `fund_stock` VALUES (74, 3, 13, 53900.0000, 62.6751, 859.9900, '2020-08-25 21:36:16', '2020-08-25 21:36:16', 0);
INSERT INTO `fund_stock` VALUES (75, 14, 13, 46299.0000, 62.8345, 736.8400, '2020-08-25 21:39:28', '2020-08-25 21:39:28', 0);
INSERT INTO `fund_stock` VALUES (76, 7, 13, 33000.0000, 62.7949, 525.5200, '2020-08-25 21:41:30', '2020-08-25 21:41:30', 0);
INSERT INTO `fund_stock` VALUES (77, 17, 61, 8500.0000, 75.3145, 112.8600, '2020-08-25 21:42:37', '2020-08-25 21:42:37', 0);
INSERT INTO `fund_stock` VALUES (78, 24, 61, 9700.0000, 75.2872, 128.8400, '2020-08-25 21:43:05', '2020-08-25 21:43:05', 0);
INSERT INTO `fund_stock` VALUES (79, 2, 61, 6000.0000, 75.0000, 80.0000, '2020-08-25 21:43:34', '2020-08-25 21:43:34', 0);
INSERT INTO `fund_stock` VALUES (80, 30, 14, 7400.0000, 13.1673, 562.0000, '2020-08-25 21:44:25', '2020-08-25 21:44:25', 0);
INSERT INTO `fund_stock` VALUES (81, 27, 14, 6600.0000, 13.2000, 500.0000, '2020-08-25 21:44:55', '2020-08-25 21:44:55', 0);
INSERT INTO `fund_stock` VALUES (82, 30, 62, 13000.0000, 20.6349, 630.0000, '2020-08-25 21:49:52', '2020-08-25 21:49:52', 0);
INSERT INTO `fund_stock` VALUES (83, 19, 62, 12400.0000, 20.6670, 599.9900, '2020-08-25 21:50:22', '2020-08-25 21:50:22', 0);
INSERT INTO `fund_stock` VALUES (84, 17, 63, 9700.0000, 31.3753, 309.1600, '2020-08-25 21:51:26', '2020-08-25 21:51:26', 0);
INSERT INTO `fund_stock` VALUES (85, 9, 63, 15600.0000, 31.2012, 499.9800, '2020-08-25 21:51:56', '2020-08-25 21:51:56', 0);
INSERT INTO `fund_stock` VALUES (86, 31, 15, 53400.0000, 17.2874, 3088.9500, '2020-08-25 21:57:01', '2020-08-25 21:57:01', 0);
INSERT INTO `fund_stock` VALUES (87, 2, 15, 59200.0000, 17.3099, 3420.0100, '2020-08-25 21:58:32', '2020-08-25 22:04:25', 0);
INSERT INTO `fund_stock` VALUES (88, 26, 16, 15800.0000, 11.2085, 1409.6400, '2020-08-25 21:59:38', '2020-08-25 21:59:38', 0);
INSERT INTO `fund_stock` VALUES (89, 8, 16, 41700.0000, 11.2114, 3719.4200, '2020-08-25 22:00:07', '2020-08-25 22:00:07', 0);
INSERT INTO `fund_stock` VALUES (90, 32, 17, 24700.0000, 49.3990, 500.0100, '2020-08-25 22:02:31', '2020-08-25 22:02:31', 0);
INSERT INTO `fund_stock` VALUES (91, 14, 17, 73400.0000, 49.4676, 1483.8000, '2020-08-25 22:03:00', '2020-08-25 22:03:00', 0);
INSERT INTO `fund_stock` VALUES (92, 10, 64, 5100.0000, 8.7768, 581.0800, '2020-08-25 22:05:32', '2020-08-25 22:05:32', 0);
INSERT INTO `fund_stock` VALUES (93, 20, 64, 2900.0000, 8.5857, 337.7700, '2020-08-25 22:06:02', '2020-08-25 22:06:02', 0);
INSERT INTO `fund_stock` VALUES (94, 21, 18, 6700.0000, 8.1653, 820.5500, '2020-08-25 22:06:45', '2020-08-25 22:06:45', 0);
INSERT INTO `fund_stock` VALUES (95, 33, 18, 9400.0000, 8.2024, 1146.0000, '2020-08-25 22:07:15', '2020-08-25 22:07:15', 0);
INSERT INTO `fund_stock` VALUES (96, 30, 65, 11800.0000, 14.8428, 795.0000, '2020-08-25 22:10:33', '2020-08-25 22:10:33', 0);
INSERT INTO `fund_stock` VALUES (97, 12, 18, 10400.0000, 14.8588, 699.9200, '2020-08-25 22:11:01', '2020-08-25 22:11:01', 0);
INSERT INTO `fund_stock` VALUES (98, 14, 19, 22900.0000, 22.2622, 1028.6500, '2020-08-25 22:11:54', '2020-08-25 22:11:54', 0);
INSERT INTO `fund_stock` VALUES (99, 8, 19, 22000.0000, 22.3289, 985.2700, '2020-08-25 22:12:22', '2020-08-25 22:12:22', 0);
INSERT INTO `fund_stock` VALUES (100, 34, 21, 82100.0000, 72.9778, 1125.0000, '2020-08-25 22:13:02', '2020-08-25 22:13:02', 0);
INSERT INTO `fund_stock` VALUES (101, 6, 21, 66900.0000, 73.0509, 915.8000, '2020-08-25 22:18:13', '2020-08-25 22:18:13', 0);
INSERT INTO `fund_stock` VALUES (102, 34, 66, 85600.0000, 17.4395, 4908.3900, '2020-08-25 22:19:51', '2020-08-25 22:19:51', 0);
INSERT INTO `fund_stock` VALUES (103, 39, 66, 30300.0000, 17.4163, 1739.7500, '2020-08-25 22:20:25', '2020-08-25 22:20:25', 0);
INSERT INTO `fund_stock` VALUES (104, 14, 22, 40800.0000, 29.2647, 1394.1700, '2020-08-25 22:22:38', '2020-08-25 22:22:38', 0);
INSERT INTO `fund_stock` VALUES (105, 35, 22, 33700.0000, 29.2931, 1150.4400, '2020-08-25 22:25:51', '2020-08-25 22:25:51', 0);
INSERT INTO `fund_stock` VALUES (106, 24, 23, 15700.0000, 10.6285, 1477.1600, '2020-08-25 22:27:43', '2020-08-25 22:27:43', 0);
INSERT INTO `fund_stock` VALUES (107, 36, 23, 12100.0000, 10.6615, 1134.9300, '2020-08-25 22:28:26', '2020-08-25 22:28:26', 0);
INSERT INTO `fund_stock` VALUES (108, 18, 24, 11100.0000, 10.5990, 1047.2700, '2020-08-25 22:32:42', '2020-08-25 22:32:42', 0);
INSERT INTO `fund_stock` VALUES (109, 37, 24, 6500.0000, 10.5519, 616.0000, '2020-08-25 22:39:16', '2020-08-25 22:39:16', 0);
INSERT INTO `fund_stock` VALUES (110, 40, 67, 1600.0000, 5.1187, 312.5800, '2020-08-25 22:42:06', '2020-08-25 22:42:06', 0);
INSERT INTO `fund_stock` VALUES (111, 8, 67, 3600.0000, 5.0834, 708.1900, '2020-08-25 22:42:40', '2020-08-25 22:42:40', 0);
INSERT INTO `fund_stock` VALUES (112, 17, 68, 26700.0000, 19.5693, 1364.3800, '2020-08-25 22:46:51', '2020-08-25 22:46:51', 0);
INSERT INTO `fund_stock` VALUES (113, 1, 68, 17600.0000, 19.5556, 900.0000, '2020-08-25 22:47:20', '2020-08-25 22:47:20', 0);
INSERT INTO `fund_stock` VALUES (114, 14, 69, 116300.0000, 80.1737, 1450.6000, '2020-08-25 22:48:43', '2020-08-25 22:48:43', 0);
INSERT INTO `fund_stock` VALUES (115, 8, 69, 47700.0000, 80.1331, 595.2600, '2020-08-25 22:49:20', '2020-08-25 22:49:20', 0);
INSERT INTO `fund_stock` VALUES (116, 26, 70, 22100.0000, 5.9896, 3689.7400, '2020-08-25 22:50:22', '2020-08-25 22:50:22', 0);
INSERT INTO `fund_stock` VALUES (117, 22, 70, 30700.0000, 5.9729, 5139.8500, '2020-08-25 22:50:57', '2020-08-25 22:50:57', 0);
INSERT INTO `fund_stock` VALUES (118, 12, 65, 10400.0000, 14.8588, 699.9200, '2020-08-25 23:07:04', '2020-08-25 23:07:04', 0);
INSERT INTO `fund_stock` VALUES (119, 11, 25, 29600.0000, 8.1206, 3645.0400, '2020-08-25 23:08:08', '2020-08-25 23:08:08', 0);
INSERT INTO `fund_stock` VALUES (120, 2, 25, 44700.0000, 8.1273, 5500.0100, '2020-08-25 23:08:36', '2020-08-25 23:08:36', 0);
INSERT INTO `fund_stock` VALUES (121, 14, 26, 63300.0000, 126.9529, 498.6100, '2020-08-25 23:09:23', '2020-08-25 23:09:23', 0);
INSERT INTO `fund_stock` VALUES (122, 21, 26, 39300.0000, 127.1392, 309.1100, '2020-08-25 23:09:53', '2020-08-25 23:09:53', 0);
INSERT INTO `fund_stock` VALUES (123, 38, 27, 7700.0000, 6.9739, 1104.1100, '2020-08-25 23:10:45', '2020-08-25 23:10:45', 0);
INSERT INTO `fund_stock` VALUES (124, 30, 27, 10400.0000, 7.0033, 1485.0100, '2020-08-25 23:11:21', '2020-08-25 23:11:21', 0);
INSERT INTO `fund_stock` VALUES (125, 4, 28, 19700.0000, 19.7000, 1000.0000, '2020-08-25 23:12:01', '2020-08-25 23:12:01', 0);
INSERT INTO `fund_stock` VALUES (126, 39, 28, 13500.0000, 19.7591, 683.2300, '2020-08-25 23:12:32', '2020-08-25 23:12:32', 0);
INSERT INTO `fund_stock` VALUES (127, 38, 71, 21700.0000, 6.8950, 3147.2000, '2020-08-25 23:15:20', '2020-08-25 23:15:20', 0);
INSERT INTO `fund_stock` VALUES (128, 43, 71, 15200.0000, 6.9091, 2200.0100, '2020-08-25 23:15:52', '2020-08-25 23:15:52', 0);
INSERT INTO `fund_stock` VALUES (129, 32, 72, 4800.0000, 5.3333, 900.0100, '2020-08-25 23:17:09', '2020-08-25 23:17:09', 0);
INSERT INTO `fund_stock` VALUES (130, 6, 72, 5300.0000, 5.3753, 986.0000, '2020-08-25 23:17:37', '2020-08-25 23:17:37', 0);
INSERT INTO `fund_stock` VALUES (131, 23, 73, 20600.0000, 6.1492, 3350.0100, '2020-08-25 23:19:39', '2020-08-25 23:19:39', 0);
INSERT INTO `fund_stock` VALUES (132, 2, 73, 26200.0000, 6.1375, 4268.8200, '2020-08-25 23:20:11', '2020-08-25 23:20:11', 0);
INSERT INTO `fund_stock` VALUES (133, 1, 29, 16800.0000, 54.1953, 309.9900, '2020-08-25 23:21:52', '2020-08-25 23:21:52', 0);
INSERT INTO `fund_stock` VALUES (134, 6, 29, 54200.0000, 54.2000, 1000.0000, '2020-08-25 23:22:20', '2020-08-25 23:22:20', 0);
INSERT INTO `fund_stock` VALUES (135, 30, 74, 18900.0000, 6.0577, 3120.0000, '2020-08-25 23:23:17', '2020-08-25 23:23:17', 0);
INSERT INTO `fund_stock` VALUES (136, 39, 74, 58600.0000, 6.0625, 9665.9100, '2020-08-25 23:23:53', '2020-08-25 23:23:53', 0);
INSERT INTO `fund_stock` VALUES (137, 31, 30, 58900.0000, 21.9096, 2688.3200, '2020-08-25 23:24:40', '2020-08-25 23:24:40', 0);
INSERT INTO `fund_stock` VALUES (138, 7, 30, 54000.0000, 21.9056, 2465.1200, '2020-08-25 23:25:11', '2020-08-25 23:25:11', 0);
INSERT INTO `fund_stock` VALUES (139, 14, 31, 91300.0000, 57.9701, 1574.9500, '2020-08-25 23:26:13', '2020-08-25 23:26:13', 0);
INSERT INTO `fund_stock` VALUES (140, 6, 31, 51300.0000, 57.9661, 885.0000, '2020-08-25 23:27:41', '2020-08-25 23:27:41', 0);
INSERT INTO `fund_stock` VALUES (141, 22, 32, 128700.0000, 20.2044, 6369.9000, '2020-08-25 23:28:25', '2020-08-25 23:28:25', 0);
INSERT INTO `fund_stock` VALUES (142, 31, 32, 102800.0000, 20.2029, 5088.3900, '2020-08-25 23:28:59', '2020-08-25 23:28:59', 0);
INSERT INTO `fund_stock` VALUES (143, 14, 75, 66300.0000, 19.7640, 3354.5900, '2020-08-25 23:31:29', '2020-08-25 23:31:29', 0);
INSERT INTO `fund_stock` VALUES (144, 6, 75, 55400.0000, 19.7575, 2804.0000, '2020-08-25 23:31:57', '2020-08-25 23:31:57', 0);
INSERT INTO `fund_stock` VALUES (145, 10, 76, 23400.0000, 34.8328, 671.7800, '2020-08-25 23:33:26', '2020-08-25 23:33:26', 0);
INSERT INTO `fund_stock` VALUES (146, 12, 76, 44900.0000, 34.9090, 1286.2000, '2020-08-25 23:33:59', '2020-08-25 23:33:59', 0);
INSERT INTO `fund_stock` VALUES (147, 22, 33, 33800.0000, 9.1589, 3690.3800, '2020-08-25 23:34:48', '2020-08-25 23:34:48', 0);
INSERT INTO `fund_stock` VALUES (148, 8, 33, 38200.0000, 9.1490, 4175.3000, '2020-08-25 23:35:22', '2020-08-25 23:35:22', 0);
INSERT INTO `fund_stock` VALUES (149, 4, 77, 16000.0000, 29.8541, 535.9400, '2020-08-25 23:36:20', '2020-08-25 23:36:20', 0);
INSERT INTO `fund_stock` VALUES (150, 40, 77, 10700.0000, 29.8933, 357.9400, '2020-08-25 23:36:50', '2020-08-25 23:36:50', 0);
INSERT INTO `fund_stock` VALUES (151, 38, 78, 22100.0000, 25.9963, 850.1200, '2020-08-25 23:39:02', '2020-08-25 23:39:02', 0);
INSERT INTO `fund_stock` VALUES (152, 33, 78, 68300.0000, 25.9496, 2632.0300, '2020-08-25 23:39:34', '2020-08-25 23:39:34', 0);
INSERT INTO `fund_stock` VALUES (153, 13, 34, 45000.0000, 28.0468, 1604.4600, '2020-08-25 23:40:27', '2020-08-25 23:40:27', 0);
INSERT INTO `fund_stock` VALUES (154, 27, 34, 36500.0000, 28.0769, 1300.0000, '2020-08-25 23:40:55', '2020-08-25 23:40:55', 0);
INSERT INTO `fund_stock` VALUES (155, 48, 79, 9900.0000, 37.3345, 265.1700, '2020-08-25 23:45:00', '2020-08-25 23:45:00', 0);
INSERT INTO `fund_stock` VALUES (156, 26, 79, 11400.0000, 37.2866, 305.7400, '2020-08-25 23:51:40', '2020-08-25 23:51:40', 0);
INSERT INTO `fund_stock` VALUES (157, 38, 80, 6400.0000, 14.6907, 435.6500, '2020-08-25 23:57:40', '2020-08-25 23:57:40', 0);
INSERT INTO `fund_stock` VALUES (158, 46, 80, 2900.0000, 14.3949, 201.4600, '2020-08-25 23:58:12', '2020-08-25 23:58:12', 0);
INSERT INTO `fund_stock` VALUES (159, 14, 81, 40400.0000, 32.8854, 1228.5100, '2020-08-25 23:59:28', '2020-08-25 23:59:28', 0);
INSERT INTO `fund_stock` VALUES (160, 22, 81, 28500.0000, 32.8496, 867.5900, '2020-08-25 23:59:53', '2020-08-25 23:59:53', 0);
INSERT INTO `fund_stock` VALUES (161, 32, 35, 9800.0000, 28.0000, 350.0000, '2020-08-26 00:01:03', '2020-08-26 00:01:03', 0);
INSERT INTO `fund_stock` VALUES (162, 30, 35, 12100.0000, 28.1317, 430.1200, '2020-08-26 00:04:43', '2020-08-26 00:04:43', 0);
INSERT INTO `fund_stock` VALUES (163, 9, 82, 37100.0000, 23.1839, 1600.2500, '2020-08-26 00:05:57', '2020-08-26 00:05:57', 0);
INSERT INTO `fund_stock` VALUES (164, 45, 82, 80700.0000, 23.1977, 3478.7900, '2020-08-26 00:06:24', '2020-08-26 00:06:24', 0);
INSERT INTO `fund_stock` VALUES (165, 14, 36, 30300.0000, 31.6284, 958.0000, '2020-08-26 00:11:19', '2020-08-26 00:11:19', 0);
INSERT INTO `fund_stock` VALUES (166, 1, 36, 23100.0000, 31.6101, 730.7800, '2020-08-26 00:11:48', '2020-08-26 00:11:48', 0);
INSERT INTO `fund_stock` VALUES (167, 26, 37, 18000.0000, 20.2109, 890.6100, '2020-08-26 00:13:46', '2020-08-26 00:13:46', 0);
INSERT INTO `fund_stock` VALUES (168, 8, 37, 21000.0000, 20.2400, 1037.5500, '2020-08-26 00:14:23', '2020-08-26 00:14:23', 0);
INSERT INTO `fund_stock` VALUES (169, 37, 83, 36500.0000, 7.5053, 4863.2500, '2020-08-26 00:15:25', '2020-08-26 00:15:25', 0);
INSERT INTO `fund_stock` VALUES (170, 11, 83, 55500.0000, 7.5048, 7395.2300, '2020-08-26 00:15:56', '2020-08-26 00:15:56', 0);
INSERT INTO `fund_stock` VALUES (171, 40, 38, 5600.0000, 23.5156, 238.1400, '2020-08-26 00:16:54', '2020-08-26 00:16:54', 0);
INSERT INTO `fund_stock` VALUES (172, 7, 38, 12600.0000, 23.4414, 537.5100, '2020-08-26 00:17:21', '2020-08-26 00:17:21', 0);
INSERT INTO `fund_stock` VALUES (173, 30, 39, 7800.0000, 10.9889, 709.8100, '2020-08-26 00:18:03', '2020-08-26 00:18:03', 0);
INSERT INTO `fund_stock` VALUES (174, 33, 39, 7300.0000, 10.9544, 666.4000, '2020-08-26 00:18:33', '2020-08-26 00:18:33', 0);
INSERT INTO `fund_stock` VALUES (175, 14, 84, 39300.0000, 117.9507, 333.1900, '2020-08-26 00:19:44', '2020-08-26 00:19:44', 0);
INSERT INTO `fund_stock` VALUES (176, 46, 84, 26900.0000, 117.8842, 228.1900, '2020-08-26 00:20:09', '2020-08-26 00:20:09', 0);
INSERT INTO `fund_stock` VALUES (177, 16, 85, 23800.0000, 108.2015, 219.9600, '2020-08-26 00:21:14', '2020-08-26 00:21:14', 0);
INSERT INTO `fund_stock` VALUES (178, 3, 85, 34600.0000, 108.1318, 319.9800, '2020-08-26 00:21:51', '2020-08-26 00:21:51', 0);
INSERT INTO `fund_stock` VALUES (179, 41, 40, 24400.0000, 21.9247, 1112.9000, '2020-08-26 00:22:42', '2020-08-26 00:22:42', 0);
INSERT INTO `fund_stock` VALUES (180, 42, 40, 18300.0000, 21.8638, 837.0000, '2020-08-26 00:23:08', '2020-08-26 00:23:08', 0);
INSERT INTO `fund_stock` VALUES (181, 16, 86, 24200.0000, 44.5377, 543.3600, '2020-08-26 00:24:21', '2020-08-26 00:24:21', 0);
INSERT INTO `fund_stock` VALUES (182, 39, 86, 10700.0000, 44.2972, 241.5500, '2020-08-26 00:24:46', '2020-08-26 00:24:46', 0);
INSERT INTO `fund_stock` VALUES (183, 9, 41, 25400.0000, 76.9767, 329.9700, '2020-08-26 00:25:37', '2020-08-26 00:25:37', 0);
INSERT INTO `fund_stock` VALUES (184, 14, 41, 36300.0000, 76.9214, 471.9100, '2020-08-26 00:26:07', '2020-08-26 00:26:07', 0);
INSERT INTO `fund_stock` VALUES (185, 29, 42, 34400.0000, 64.5585, 532.8500, '2020-08-26 00:26:55', '2020-08-26 00:26:55', 0);
INSERT INTO `fund_stock` VALUES (186, 15, 42, 40700.0000, 64.6011, 630.0200, '2020-08-26 00:27:29', '2020-08-26 00:27:29', 0);
INSERT INTO `fund_stock` VALUES (187, 14, 87, 37200.0000, 167.3490, 222.2900, '2020-08-26 00:29:26', '2020-08-26 00:29:26', 0);
INSERT INTO `fund_stock` VALUES (188, 37, 87, 28300.0000, 167.6838, 168.7700, '2020-08-26 00:30:01', '2020-08-26 00:30:01', 0);
INSERT INTO `fund_stock` VALUES (189, 39, 43, 32700.0000, 103.1285, 317.0800, '2020-08-26 00:31:06', '2020-08-26 00:31:06', 0);
INSERT INTO `fund_stock` VALUES (190, 36, 43, 31200.0000, 103.0247, 302.8400, '2020-08-26 00:31:34', '2020-08-26 00:31:34', 0);
INSERT INTO `fund_stock` VALUES (191, 43, 44, 28800.0000, 95.9968, 300.0100, '2020-08-26 00:32:25', '2020-08-26 00:32:25', 0);
INSERT INTO `fund_stock` VALUES (192, 33, 44, 28800.0000, 96.0000, 300.0000, '2020-08-26 00:32:53', '2020-08-26 00:32:53', 0);
INSERT INTO `fund_stock` VALUES (193, 16, 45, 8600.0000, 88.0336, 97.6900, '2020-08-26 00:33:39', '2020-08-26 00:33:39', 0);
INSERT INTO `fund_stock` VALUES (194, 38, 45, 21000.0000, 87.9360, 238.8100, '2020-08-26 00:34:06', '2020-08-26 00:34:06', 0);
INSERT INTO `fund_stock` VALUES (195, 18, 46, 14000.0000, 29.6215, 472.6300, '2020-08-26 00:34:51', '2020-08-26 00:34:51', 0);
INSERT INTO `fund_stock` VALUES (196, 22, 46, 10200.0000, 29.7307, 343.0800, '2020-08-26 00:35:19', '2020-08-26 00:35:19', 0);
INSERT INTO `fund_stock` VALUES (197, 49, 88, 10300.0000, 46.2298, 222.8000, '2020-08-26 00:37:28', '2020-08-26 00:37:28', 0);
INSERT INTO `fund_stock` VALUES (198, 22, 88, 8300.0000, 46.1111, 180.0000, '2020-08-26 00:37:56', '2020-08-26 00:37:56', 0);
INSERT INTO `fund_stock` VALUES (199, 20, 47, 3200.0000, 20.2276, 158.2000, '2020-08-26 00:38:42', '2020-08-26 00:38:42', 0);
INSERT INTO `fund_stock` VALUES (200, 8, 47, 2000.0000, 20.0000, 100.0000, '2020-08-26 00:39:10', '2020-08-26 00:39:10', 0);
INSERT INTO `fund_stock` VALUES (201, 26, 89, 6000.0000, 76.1808, 78.7600, '2020-08-26 00:40:04', '2020-08-26 00:40:04', 0);
INSERT INTO `fund_stock` VALUES (202, 7, 89, 38100.0000, 75.9176, 501.8600, '2020-08-26 00:40:39', '2020-08-26 00:40:39', 0);
INSERT INTO `fund_stock` VALUES (203, 4, 48, 17800.0000, 122.9706, 144.7500, '2020-08-26 00:41:25', '2020-08-26 00:41:25', 0);
INSERT INTO `fund_stock` VALUES (204, 2, 48, 33100.0000, 122.5971, 269.9900, '2020-08-26 00:42:02', '2020-08-26 00:42:02', 0);

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
) ENGINE = InnoDB AUTO_INCREMENT = 56 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (4, 2, 8, '角色管理', 'fa-user', '/admin/roles', 0, NULL, '2019-03-07 17:35:01');
INSERT INTO `menu` VALUES (5, 2, 9, '权限管理', 'fa-ban', '/admin/permissions', 0, NULL, '2019-03-07 17:35:01');
INSERT INTO `menu` VALUES (6, 2, 10, '菜单管理', 'fa-bars', '/admin/menu', 0, NULL, '2019-03-07 17:35:01');
INSERT INTO `menu` VALUES (10, 1, 2, '控制台', 'fa-tachometer', '/admin/console', 0, '2019-03-07 16:07:22', '2019-03-11 12:34:39');
INSERT INTO `menu` VALUES (11, 0, 26, 'System', 'fa-cog', '', 1, '2019-03-07 16:34:13', '2020-08-25 19:08:24');
INSERT INTO `menu` VALUES (14, 0, 2, '我的设置', 'fa-user', '', 0, '2019-03-07 16:40:13', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (15, 14, 3, '基本资料', 'fa-edit', '/admin/account', 4, '2019-03-07 16:41:35', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (16, 14, 4, '修改密码', 'fa-transgender-alt', '/admin/account/changePassword', 0, '2019-03-07 16:43:50', '2019-04-15 17:24:09');
INSERT INTO `menu` VALUES (17, 0, 20, 'User', 'fa-users', '', 1, '2019-03-07 16:44:30', '2020-08-25 19:19:39');
INSERT INTO `menu` VALUES (19, 17, 22, 'Admin', 'fa-user-secret', '/admin/admins', 1, '2019-03-07 16:46:07', '2020-08-25 19:12:23');
INSERT INTO `menu` VALUES (20, 11, 27, 'MenuManage', 'fa-align-justify', '/admin/menu', 0, '2019-03-07 17:40:35', '2020-08-25 19:12:03');
INSERT INTO `menu` VALUES (21, 11, 28, '角色管理', 'fa-venus-double', '/admin/roles', 0, '2019-03-07 17:41:31', '2020-08-18 17:28:37');
INSERT INTO `menu` VALUES (22, 11, 29, '权限管理', 'fa-ban', '/admin/permissions', 0, '2019-03-07 17:42:49', '2020-08-18 17:28:51');
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
INSERT INTO `menu` VALUES (49, 0, 999, 'Fund', 'fa-align-left', '', 1, '2020-08-18 17:33:31', '2020-08-25 19:08:37');
INSERT INTO `menu` VALUES (50, 0, 999, 'Stock', 'fa-bar-chart', '', 1, '2020-08-18 17:42:27', '2020-08-25 19:08:58');
INSERT INTO `menu` VALUES (51, 0, 999, 'Analysis', 'fa-bars', '', 1, '2020-08-18 17:52:20', '2020-08-25 19:09:25');
INSERT INTO `menu` VALUES (52, 49, 999, 'FundList', 'fa-bars', '/admin/fund', 1, '2020-08-18 18:01:18', '2020-08-25 19:12:40');
INSERT INTO `menu` VALUES (53, 50, 999, 'StockList', 'fa-bars', '/admin/stock', 1, '2020-08-18 18:29:53', '2020-08-25 19:12:55');
INSERT INTO `menu` VALUES (54, 51, 999, 'FounStock', 'fa-bars', '/admin/analysis', 1, '2020-08-19 15:00:06', '2020-08-25 19:13:46');
INSERT INTO `menu` VALUES (55, 51, 999, 'StockStatistics', 'fa-bars', '/admin/analysis/stock', 1, '2020-08-23 15:17:12', '2020-08-25 19:14:13');

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
INSERT INTO `role_menu` VALUES (1, 50, '2020-08-18 17:42:27', '2020-08-18 17:42:27');
INSERT INTO `role_menu` VALUES (1, 51, '2020-08-18 17:52:20', '2020-08-18 17:52:20');
INSERT INTO `role_menu` VALUES (1, 52, '2020-08-18 18:01:18', '2020-08-18 18:01:18');
INSERT INTO `role_menu` VALUES (1, 53, '2020-08-18 18:29:53', '2020-08-18 18:29:53');
INSERT INTO `role_menu` VALUES (1, 54, '2020-08-19 15:00:07', '2020-08-19 15:00:07');
INSERT INTO `role_menu` VALUES (1, 55, '2020-08-23 15:17:12', '2020-08-23 15:17:12');

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
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '删除标记',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 90 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of stock
-- ----------------------------
INSERT INTO `stock` VALUES (1, '华鲁恒升', '600426', '', '2020-08-19 15:02:48', '2020-08-19 15:02:48', 0);
INSERT INTO `stock` VALUES (2, '双汇发展', '000895', '', '2020-08-19 16:25:08', '2020-08-19 16:25:08', 0);
INSERT INTO `stock` VALUES (3, '恒顺醋业', '600305', '', '2020-08-19 16:25:32', '2020-08-19 16:25:32', 0);
INSERT INTO `stock` VALUES (4, '亿联网络', '300628', '', '2020-08-19 16:25:53', '2020-08-19 16:25:53', 0);
INSERT INTO `stock` VALUES (5, '晶澳科技', '002459', '', '2020-08-19 16:26:18', '2020-08-19 16:26:18', 0);
INSERT INTO `stock` VALUES (6, '天虹股份', '002419', '', '2020-08-19 16:30:10', '2020-08-19 16:30:10', 0);
INSERT INTO `stock` VALUES (7, '好想你', '002582', '', '2020-08-19 16:30:24', '2020-08-19 16:30:24', 0);
INSERT INTO `stock` VALUES (8, '利尔化学', '002258', '', '2020-08-19 16:31:07', '2020-08-19 16:31:07', 0);
INSERT INTO `stock` VALUES (9, '中信特钢', '000708', '', '2020-08-19 16:31:24', '2020-08-19 16:31:24', 0);
INSERT INTO `stock` VALUES (10, '旗滨集团', '601636', '', '2020-08-19 16:31:39', '2020-08-19 16:31:39', 0);
INSERT INTO `stock` VALUES (11, '艾华集团', '603989', '', '2020-08-19 16:42:05', '2020-08-19 16:42:05', 0);
INSERT INTO `stock` VALUES (12, '中科创达', '300496', '', '2020-08-19 16:42:20', '2020-08-19 16:42:20', 0);
INSERT INTO `stock` VALUES (13, '光威复材', '300699', '', '2020-08-19 16:43:35', '2020-08-19 16:43:35', 0);
INSERT INTO `stock` VALUES (14, '再升科技', '603601', '', '2020-08-19 16:43:52', '2020-08-19 16:43:52', 0);
INSERT INTO `stock` VALUES (15, '宋城演艺', '300144', '', '2020-08-19 16:44:05', '2020-08-25 21:56:23', 0);
INSERT INTO `stock` VALUES (16, '长信科技', '300088', '', '2020-08-19 16:50:28', '2020-08-19 16:50:28', 0);
INSERT INTO `stock` VALUES (17, '安琪酵母', '600298', '', '2020-08-19 16:53:01', '2020-08-19 16:53:01', 0);
INSERT INTO `stock` VALUES (18, '当代文体', '600136', '', '2020-08-19 16:53:16', '2020-08-19 16:53:16', 0);
INSERT INTO `stock` VALUES (19, '中顺洁柔', '002511', '', '2020-08-19 16:53:33', '2020-08-19 16:53:33', 0);
INSERT INTO `stock` VALUES (20, '晨鸣纸业', '000488', '', '2020-08-19 16:55:06', '2020-08-19 16:55:06', 0);
INSERT INTO `stock` VALUES (21, '重庆啤酒', '600132', '', '2020-08-19 16:55:24', '2020-08-19 16:55:24', 0);
INSERT INTO `stock` VALUES (22, '生益科技', '600183', '', '2020-08-19 16:56:58', '2020-08-19 16:56:58', 0);
INSERT INTO `stock` VALUES (23, '领益智造', '002600', '', '2020-08-19 16:57:10', '2020-08-19 16:57:10', 0);
INSERT INTO `stock` VALUES (24, '长海股份', '300196', '', '2020-08-19 16:59:11', '2020-08-19 16:59:11', 0);
INSERT INTO `stock` VALUES (25, '碧水源', '300070', '', '2020-08-19 16:59:24', '2020-08-19 16:59:24', 0);
INSERT INTO `stock` VALUES (26, '星宇股份', '601799', '', '2020-08-19 16:59:43', '2020-08-19 16:59:43', 0);
INSERT INTO `stock` VALUES (27, '承德露露', '000848', '', '2020-08-19 17:00:06', '2020-08-19 17:00:06', 0);
INSERT INTO `stock` VALUES (28, '汤臣倍健', '300146', '', '2020-08-19 17:00:22', '2020-08-19 17:00:22', 0);
INSERT INTO `stock` VALUES (29, '洽洽食品', '002557', '', '2020-08-19 17:03:57', '2020-08-19 17:03:57', 0);
INSERT INTO `stock` VALUES (30, '三花智控', '002050', '', '2020-08-19 17:04:10', '2020-08-19 17:04:10', 0);
INSERT INTO `stock` VALUES (31, '山东药玻', '600529', '', '2020-08-19 17:04:24', '2020-08-19 17:04:24', 0);
INSERT INTO `stock` VALUES (32, '东方财富', '300059', '', '2020-08-19 17:04:39', '2020-08-19 17:04:39', 0);
INSERT INTO `stock` VALUES (33, '中国巨石', '600176', '', '2020-08-19 17:04:54', '2020-08-19 17:04:54', 0);
INSERT INTO `stock` VALUES (34, '蓝思科技', '300433', '', '2020-08-19 17:05:49', '2020-08-19 17:05:49', 0);
INSERT INTO `stock` VALUES (35, '火炬电子', '603678', '', '2020-08-19 17:06:06', '2020-08-19 17:06:06', 0);
INSERT INTO `stock` VALUES (36, '千禾味业', '603027', '', '2020-08-19 17:06:24', '2020-08-19 17:06:24', 0);
INSERT INTO `stock` VALUES (37, '玲珑轮胎', '601966', '', '2020-08-19 17:08:01', '2020-08-19 17:08:01', 0);
INSERT INTO `stock` VALUES (38, '梦百合', '603313', '', '2020-08-19 17:08:18', '2020-08-19 17:08:18', 0);
INSERT INTO `stock` VALUES (39, '杭叉集团', '603298', '', '2020-08-19 17:08:31', '2020-08-19 17:08:31', 0);
INSERT INTO `stock` VALUES (40, '杰克股份', '603337', '', '2020-08-19 17:10:50', '2020-08-19 17:10:50', 0);
INSERT INTO `stock` VALUES (41, '艾德生物', '300685', '', '2020-08-19 17:11:06', '2020-08-19 17:11:06', 0);
INSERT INTO `stock` VALUES (42, '健友股份', '603707', '', '2020-08-19 17:11:19', '2020-08-19 17:11:19', 0);
INSERT INTO `stock` VALUES (43, '璞泰来', '603659', '', '2020-08-19 17:11:37', '2020-08-19 17:11:37', 0);
INSERT INTO `stock` VALUES (44, '精研科技', '300709', '', '2020-08-19 17:11:50', '2020-08-19 17:11:50', 0);
INSERT INTO `stock` VALUES (45, '亿嘉和', '603666', '', '2020-08-19 17:12:05', '2020-08-19 17:12:05', 0);
INSERT INTO `stock` VALUES (46, '宁水集团', '603700', '', '2020-08-19 17:12:16', '2020-08-19 17:12:16', 0);
INSERT INTO `stock` VALUES (47, '有友食品', '603697', '', '2020-08-19 17:12:29', '2020-08-19 17:12:29', 0);
INSERT INTO `stock` VALUES (48, '玉禾田', '300815', '', '2020-08-19 17:12:44', '2020-08-19 17:12:44', 0);
INSERT INTO `stock` VALUES (49, '冀东水泥', '000401', '', '2020-08-19 17:15:10', '2020-08-19 17:15:10', 0);
INSERT INTO `stock` VALUES (50, '塔牌集团', '002233', '', '2020-08-19 17:15:23', '2020-08-19 17:15:23', 0);
INSERT INTO `stock` VALUES (51, '长春高新', '000661', '', '2020-08-23 14:07:54', '2020-08-23 14:07:54', 0);
INSERT INTO `stock` VALUES (52, '中南建设', '000961', '', '2020-08-25 17:35:31', '2020-08-25 17:35:31', 0);
INSERT INTO `stock` VALUES (53, '南极电商', '002127', '', '2020-08-25 17:48:40', '2020-08-25 17:48:40', 0);
INSERT INTO `stock` VALUES (54, '国药股份', '600511', '', '2020-08-25 17:58:58', '2020-08-25 17:58:58', 0);
INSERT INTO `stock` VALUES (55, '万华化学', '600309', '', '2020-08-25 18:03:28', '2020-08-25 18:03:28', 0);
INSERT INTO `stock` VALUES (56, '我武生物', '300357', '', '2020-08-25 18:08:33', '2020-08-25 18:08:33', 0);
INSERT INTO `stock` VALUES (57, '顺鑫农业', '000860', '', '2020-08-25 18:10:58', '2020-08-25 18:10:58', 0);
INSERT INTO `stock` VALUES (58, '万邦达', '300055', '', '2020-08-25 18:22:36', '2020-08-25 18:22:36', 0);
INSERT INTO `stock` VALUES (59, '中炬高新', '600872', '', '2020-08-25 18:36:03', '2020-08-25 18:36:03', 0);
INSERT INTO `stock` VALUES (60, '苏垦农发', '601952', '', '2020-08-25 18:53:08', '2020-08-25 18:53:08', 0);
INSERT INTO `stock` VALUES (61, '科博达', '603786', '', '2020-08-25 21:42:01', '2020-08-25 21:42:01', 0);
INSERT INTO `stock` VALUES (62, '威孚高科', '000581', '', '2020-08-25 21:49:19', '2020-08-25 21:49:19', 0);
INSERT INTO `stock` VALUES (63, '重庆百货', '600729', '', '2020-08-25 21:50:49', '2020-08-25 21:50:49', 0);
INSERT INTO `stock` VALUES (64, '康力电梯', '002367', '', '2020-08-25 22:04:59', '2020-08-25 22:04:59', 0);
INSERT INTO `stock` VALUES (65, '光明乳业', '600597', '', '2020-08-25 22:10:05', '2020-08-25 22:10:05', 0);
INSERT INTO `stock` VALUES (66, '通化东宝', '600867', '', '2020-08-25 22:19:09', '2020-08-25 22:19:09', 0);
INSERT INTO `stock` VALUES (67, '长江传媒', '600757', '', '2020-08-25 22:41:22', '2020-08-25 22:41:22', 0);
INSERT INTO `stock` VALUES (68, '豆神教育', '300010', '', '2020-08-25 22:43:10', '2020-08-25 22:43:10', 0);
INSERT INTO `stock` VALUES (69, '恒立液压', '601100', '', '2020-08-25 22:47:48', '2020-08-25 22:47:48', 0);
INSERT INTO `stock` VALUES (70, '天顺风能', '002531', '', '2020-08-25 22:49:46', '2020-08-25 22:49:46', 0);
INSERT INTO `stock` VALUES (71, '云图控股', '002539', '', '2020-08-25 23:14:46', '2020-08-25 23:14:46', 0);
INSERT INTO `stock` VALUES (72, '伟星股份', '002003', '', '2020-08-25 23:16:42', '2020-08-25 23:16:42', 0);
INSERT INTO `stock` VALUES (73, '山东高速', '600350', '', '2020-08-25 23:19:05', '2020-08-25 23:19:05', 0);
INSERT INTO `stock` VALUES (74, '华侨城A', '000069', '', '2020-08-25 23:22:48', '2020-08-25 23:22:48', 0);
INSERT INTO `stock` VALUES (75, '华测检测', '300012', '', '2020-08-25 23:30:52', '2020-08-25 23:30:52', 0);
INSERT INTO `stock` VALUES (76, '宏大爆破', '002683', '', '2020-08-25 23:32:54', '2020-08-25 23:32:54', 0);
INSERT INTO `stock` VALUES (77, '贵研铂业', '600459', '', '2020-08-25 23:35:48', '2020-08-25 23:35:48', 0);
INSERT INTO `stock` VALUES (78, '金达威', '002626', '', '2020-08-25 23:38:32', '2020-08-25 23:38:32', 0);
INSERT INTO `stock` VALUES (79, '九阳股份', '002242', '', '2020-08-25 23:44:04', '2020-08-25 23:44:04', 0);
INSERT INTO `stock` VALUES (80, '天润乳业', '600419', '', '2020-08-25 23:57:12', '2020-08-25 23:57:12', 0);
INSERT INTO `stock` VALUES (81, '菲利华', '300395', '', '2020-08-25 23:58:41', '2020-08-25 23:58:41', 0);
INSERT INTO `stock` VALUES (82, '伟明环保', '603568', '', '2020-08-26 00:05:23', '2020-08-26 00:05:23', 0);
INSERT INTO `stock` VALUES (83, '常熟银行', '601128', '', '2020-08-26 00:14:50', '2020-08-26 00:14:50', 0);
INSERT INTO `stock` VALUES (84, '安井食品', '603345', '', '2020-08-26 00:19:04', '2020-08-26 00:19:04', 0);
INSERT INTO `stock` VALUES (85, '数据港', '603881', '', '2020-08-26 00:20:37', '2020-08-26 00:20:37', 0);
INSERT INTO `stock` VALUES (86, '科锐国际', '300662', '', '2020-08-26 00:23:46', '2020-08-26 00:23:46', 0);
INSERT INTO `stock` VALUES (87, '深南电路', '002916', '', '2020-08-26 00:28:57', '2020-08-26 00:28:57', 0);
INSERT INTO `stock` VALUES (88, '迪普科技', '300768', '', '2020-08-26 00:36:23', '2020-08-26 00:36:23', 0);
INSERT INTO `stock` VALUES (89, '三只松鼠', '300783', '', '2020-08-26 00:39:37', '2020-08-26 00:39:37', 0);

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
  `detail` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `curent_price` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '当前价格',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '删除标记',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 87 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of stock_statis
-- ----------------------------
INSERT INTO `stock_statis` VALUES (1, 1, 3, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e03\\u7ec4\\u5408 : \\u6682\\u65e0 : 17.6667\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 17.6760\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 17.6748\"]', 25.96, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (2, 2, 6, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 46.1190\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 46.1240\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 46.1197\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e94\\u7ec4\\u5408 : \\u6682\\u65e0 : 46.0870\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 46.1140\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 46.0648\"]', 58.01, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (3, 51, 4, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 435.3135\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 435.1990\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 435.2196\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 435.2259\"]', 447.79, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (4, 3, 4, 0, 0.00, '[\"\\u535a\\u65f6\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e5d\\u7ec4\\u5408 : \\u6682\\u65e0 : 18.5767\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 18.5685\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u96f6\\u7ec4\\u5408 : \\u6682\\u65e0 : 18.5321\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e94\\u7ec4\\u5408 : \\u6682\\u65e0 : 18.5201\"]', 24.84, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (5, 4, 4, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 68.1937\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 68.2685\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 68.1836\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e5d\\u7ec4\\u5408 : \\u6682\\u65e0 : 68.3186\"]', 57.69, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (6, 5, 3, 0, 0.00, '[\"\\u5e7f\\u53d1\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e8c\\u96f6\\u7ec4\\u5408 : \\u6682\\u65e0 : 18.6822\",\"\\u56fd\\u6cf0\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e8c\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 18.6943\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 18.8235\"]', 23.14, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (7, 6, 3, 0, 0.00, '[\"\\u535a\\u65f6\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e5d\\u7ec4\\u5408 : \\u6682\\u65e0 : 9.5626\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 9.5428\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 9.5430\"]', 10.84, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (8, 52, 3, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 8.8854\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 8.9075\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e03\\u7ec4\\u5408 : \\u6682\\u65e0 : 8.8923\"]', 9.99, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (9, 7, 3, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 12.1723\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 12.2435\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e94\\u7ec4\\u5408 : \\u6682\\u65e0 : 12.2095\"]', 16.60, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (10, 53, 3, 0, 0.00, '[\"\\u6c47\\u6dfb\\u5bcc\\u57fa\\u91d1\\u7ba1\\u7406\\u80a1\\u4efd\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e8c\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 21.1728\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 21.1769\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 21.1793\"]', 19.13, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (11, 8, 3, 0, 0.00, '[\"\\u535a\\u65f6\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e5d\\u7ec4\\u5408 : \\u6682\\u65e0 : 17.8002\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 17.8705\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 17.8156\"]', 23.54, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (12, 54, 3, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 40.7424\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 40.5857\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 40.7577\"]', 45.08, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (13, 55, 3, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 50.0045\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 50.0017\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 50.0180\"]', 69.56, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (14, 56, 3, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 62.2366\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e94\\u7ec4\\u5408 : \\u6682\\u65e0 : 62.2074\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 62.2750\"]', 59.59, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (15, 57, 3, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 56.9899\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 56.9104\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u96f6\\u7ec4\\u5408 : \\u6682\\u65e0 : 56.9408\"]', 69.25, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (16, 58, 3, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 6.1057\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 6.1164\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e94\\u7ec4\\u5408 : \\u6682\\u65e0 : 6.1480\"]', 7.38, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (17, 9, 3, 0, 0.00, '[\"\\u535a\\u65f6\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d116011\\u7ec4\\u5408 : \\u6682\\u65e0 : 17.0714\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 17.0795\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 17.0870\"]', 18.36, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (18, 10, 3, 0, 0.00, '[\"\\u4e2d\\u4fe1\\u8bc1\\u5238\\u80a1\\u4efd\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d11106\\u7ec4\\u5408 : \\u6682\\u65e0 : 5.9341\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 5.9197\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 5.9298\"]', 8.32, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (19, 59, 3, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 58.5167\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 58.5458\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 58.5222\"]', 79.15, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (20, 11, 3, 0, 0.00, '[\"\\u56fd\\u6cf0\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e8c\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 28.9876\",\"\\u56fd\\u6cf0\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d11102\\u7ec4\\u5408 : \\u6682\\u65e0 : 28.9639\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 28.9615\"]', 30.09, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (21, 12, 3, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 77.7721\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 77.7350\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u96f6\\u7ec4\\u5408 : \\u6682\\u65e0 : 77.7148\"]', 92.70, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (22, 60, 3, 0, 0.00, '[\"\\u534e\\u590f\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d116021\\u7ec4\\u5408 : \\u6682\\u65e0 : 9.4539\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 9.4479\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e5d\\u7ec4\\u5408 : \\u6682\\u65e0 : 9.3998\"]', 15.99, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (23, 13, 3, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 62.6751\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 62.8345\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 62.7949\"]', 78.95, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (24, 61, 3, 0, 0.00, '[\"\\u56fd\\u6cf0\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e8c\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 75.3145\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 75.2872\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 75.0000\"]', 76.50, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (25, 14, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 13.1673\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 13.2000\"]', 15.08, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (26, 62, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 20.6349\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 20.6670\"]', 24.12, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (27, 63, 2, 0, 0.00, '[\"\\u56fd\\u6cf0\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e8c\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 31.3753\",\"\\u535a\\u65f6\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e5d\\u7ec4\\u5408 : \\u6682\\u65e0 : 31.2012\"]', 34.23, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (28, 15, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 17.2874\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 17.3099\"]', 18.08, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (29, 16, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 11.2085\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 11.2114\"]', 12.03, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (30, 17, 2, 0, 0.00, '[\"\\u6c47\\u6dfb\\u5bcc\\u57fa\\u91d1\\u7ba1\\u7406\\u80a1\\u4efd\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e8c\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 49.3990\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 49.4676\"]', 69.17, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (31, 64, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 8.7768\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 8.5857\"]', 12.24, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (32, 18, 3, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 8.1653\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 8.2024\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e94\\u7ec4\\u5408 : \\u6682\\u65e0 : 14.8588\"]', 8.43, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (33, 65, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 14.8428\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e94\\u7ec4\\u5408 : \\u6682\\u65e0 : 14.8588\"]', 20.23, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (34, 19, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 22.2622\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 22.3289\"]', 26.18, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (35, 21, 2, 0, 0.00, '[\"\\u6c47\\u6dfb\\u5bcc\\u57fa\\u91d1\\u7ba1\\u7406\\u80a1\\u4efd\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d11103\\u7ec4\\u5408 : \\u6682\\u65e0 : 72.9778\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e94\\u7ec4\\u5408 : \\u6682\\u65e0 : 73.0509\"]', 85.60, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (36, 66, 2, 0, 0.00, '[\"\\u6c47\\u6dfb\\u5bcc\\u57fa\\u91d1\\u7ba1\\u7406\\u80a1\\u4efd\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d11103\\u7ec4\\u5408 : \\u6682\\u65e0 : 17.4395\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 17.4163\"]', 14.40, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (37, 22, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 29.2647\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 29.2931\"]', 25.60, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (38, 23, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 10.6285\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e03\\u7ec4\\u5408 : \\u6682\\u65e0 : 10.6615\"]', 11.95, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (39, 24, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 10.5990\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 10.5519\"]', 15.31, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (40, 67, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 5.1187\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 5.0834\"]', 5.58, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (41, 68, 2, 0, 0.00, '[\"\\u56fd\\u6cf0\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e8c\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 19.5693\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e03\\u7ec4\\u5408 : \\u6682\\u65e0 : 19.5556\"]', 19.40, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (42, 69, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 80.1737\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 80.1331\"]', 62.84, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (43, 70, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 5.9896\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 5.9729\"]', 0.00, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (44, 25, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u96f6\\u7ec4\\u5408 : \\u6682\\u65e0 : 8.1206\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 8.1273\"]', 9.49, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (45, 26, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 126.9529\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 127.1392\"]', 162.72, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (46, 27, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 6.9739\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 7.0033\"]', 8.42, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (47, 28, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 19.7000\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 19.7591\"]', 24.32, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (48, 71, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 6.8950\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 6.9091\"]', 7.93, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (49, 72, 2, 0, 0.00, '[\"\\u6c47\\u6dfb\\u5bcc\\u57fa\\u91d1\\u7ba1\\u7406\\u80a1\\u4efd\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e8c\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 5.3333\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e94\\u7ec4\\u5408 : \\u6682\\u65e0 : 5.3753\"]', 5.86, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (50, 73, 2, 0, 0.00, '[\"\\u535a\\u65f6\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d116011\\u7ec4\\u5408 : \\u6682\\u65e0 : 6.1492\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 6.1375\"]', 6.59, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (51, 29, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e03\\u7ec4\\u5408 : \\u6682\\u65e0 : 54.1953\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e94\\u7ec4\\u5408 : \\u6682\\u65e0 : 54.2000\"]', 68.00, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (52, 74, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 6.0577\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 6.0625\"]', 7.30, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (53, 30, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 21.9096\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 21.9056\"]', 22.08, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (54, 31, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 57.9701\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e94\\u7ec4\\u5408 : \\u6682\\u65e0 : 57.9661\"]', 52.30, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (55, 32, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 20.2044\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 20.2029\"]', 26.45, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (56, 75, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 19.7640\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e94\\u7ec4\\u5408 : \\u6682\\u65e0 : 19.7575\"]', 25.97, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (57, 76, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 34.8328\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e94\\u7ec4\\u5408 : \\u6682\\u65e0 : 34.9090\"]', 53.99, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (58, 33, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 9.1589\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 9.1490\"]', 15.15, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (59, 77, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 29.8541\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 29.8933\"]', 23.66, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (60, 78, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 25.9963\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 25.9496\"]', 42.58, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (61, 34, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 28.0468\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 28.0769\"]', 36.77, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (62, 79, 2, 0, 0.00, '[\"\\u94f6\\u534e\\u57fa\\u91d1\\u7ba1\\u7406\\u80a1\\u4efd\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d11105\\u7ec4\\u5408 : \\u6682\\u65e0 : 37.3345\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 37.2866\"]', 43.66, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (63, 80, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 14.6907\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 14.3949\"]', 19.14, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (64, 81, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 32.8854\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 32.8496\"]', 47.41, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (65, 35, 2, 0, 0.00, '[\"\\u6c47\\u6dfb\\u5bcc\\u57fa\\u91d1\\u7ba1\\u7406\\u80a1\\u4efd\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e8c\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 28.0000\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 28.1317\"]', 39.68, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (66, 82, 2, 0, 0.00, '[\"\\u535a\\u65f6\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e5d\\u7ec4\\u5408 : \\u6682\\u65e0 : 23.1839\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 23.1977\"]', 25.71, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (67, 36, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 31.6284\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e03\\u7ec4\\u5408 : \\u6682\\u65e0 : 31.6101\"]', 42.81, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (68, 37, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 20.2109\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 20.2400\"]', 24.00, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (69, 83, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 7.5053\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u96f6\\u7ec4\\u5408 : \\u6682\\u65e0 : 7.5048\"]', 7.95, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (70, 38, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 23.5156\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 23.4414\"]', 33.04, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (71, 39, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 10.9889\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 10.9544\"]', 16.03, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (72, 84, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 117.9507\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 117.8842\"]', 171.20, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (73, 85, 2, 0, 0.00, '[\"\\u5e7f\\u53d1\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e8c\\u96f6\\u7ec4\\u5408 : \\u6682\\u65e0 : 108.2015\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 108.1318\"]', 85.99, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (74, 40, 2, 0, 0.00, '[\"\\u5927\\u6210\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d117011\\u7ec4\\u5408 : \\u6682\\u65e0 : 21.9247\",\"\\u5927\\u6210\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d11101\\u7ec4\\u5408 : \\u6682\\u65e0 : 21.8638\"]', 26.68, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (75, 86, 2, 0, 0.00, '[\"\\u5e7f\\u53d1\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e8c\\u96f6\\u7ec4\\u5408 : \\u6682\\u65e0 : 44.5377\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 44.2972\"]', 59.50, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (76, 41, 2, 0, 0.00, '[\"\\u535a\\u65f6\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e5d\\u7ec4\\u5408 : \\u6682\\u65e0 : 76.9767\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 76.9214\"]', 82.43, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (77, 42, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 64.5585\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e5d\\u7ec4\\u5408 : \\u6682\\u65e0 : 64.6011\"]', 49.33, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (78, 87, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u516d\\u7ec4\\u5408 : \\u6682\\u65e0 : 167.3490\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 167.6838\"]', 144.10, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (79, 43, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 103.1285\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e03\\u7ec4\\u5408 : \\u6682\\u65e0 : 103.0247\"]', 97.13, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (80, 44, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 95.9968\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 96.0000\"]', 85.43, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (81, 45, 2, 0, 0.00, '[\"\\u5e7f\\u53d1\\u57fa\\u91d1\\u7ba1\\u7406\\u6709\\u9650\\u516c\\u53f8-\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e8c\\u96f6\\u7ec4\\u5408 : \\u6682\\u65e0 : 88.0336\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u516d\\u96f6\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 87.9360\"]', 95.96, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (82, 46, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u4e8c\\u7ec4\\u5408 : \\u6682\\u65e0 : 29.6215\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 29.7307\"]', 35.39, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (83, 88, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 46.2298\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e09\\u7ec4\\u5408 : \\u6682\\u65e0 : 46.1111\"]', 47.04, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (84, 47, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e94\\u96f6\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 20.2276\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 20.0000\"]', 28.86, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (85, 89, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 76.1808\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u4e00\\u4e00\\u7ec4\\u5408 : \\u6682\\u65e0 : 75.9176\"]', 67.21, NULL, NULL, 0);
INSERT INTO `stock_statis` VALUES (86, 48, 2, 0, 0.00, '[\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u56db\\u4e00\\u56db\\u7ec4\\u5408 : \\u6682\\u65e0 : 122.9706\",\"\\u5168\\u56fd\\u793e\\u4fdd\\u57fa\\u91d1\\u4e00\\u96f6\\u516b\\u7ec4\\u5408 : \\u6682\\u65e0 : 122.5971\"]', 135.10, NULL, NULL, 0);

SET FOREIGN_KEY_CHECKS = 1;
