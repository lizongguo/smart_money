DROP TABLE IF EXISTS `project`;
CREATE TABLE `project`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_no` int(5) NULL DEFAULT 0 COMMENT '项目编号',
  `company_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '公司名称',
  `company_name_eng` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '公司英文名称',
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '公司地址',
  `website` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '官网',
  `state` varchar(55) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '公司状态',
  `business_information` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '工商信息链接',
  `state_val` tinyint(1) NULL DEFAULT 0 COMMENT '0:未上市 1：上市 2：倒闭 3：退出',
  `listed_val` decimal(20, 2) NULL DEFAULT 0.00 COMMENT '上市市值',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '公司简介',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '项目信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of project
-- ----------------------------
INSERT INTO `project` VALUES (1, 1, '西安三角防务股份有限公司', '', '西安市航空基地蓝天二路8号', 'http://www.400mn.com', '上市（创业板）', '', 1, 14200000000.00, '项目简介111', '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (2, 2, '正益移动互联科技股份有限公司', '', '北京市海淀区苏州街29号维亚大厦12层014室', 'http://www.3g2win.com', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (3, 3, '上海赛傲生物技术有限公司', '', '上海市普陀区中江路879弄15号楼4层A座', 'http://www.icell.com.cn', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (4, 4, '上海国联干细胞技术有限公司', '', '上海市普陀区中江路879弄2号楼307室', 'tangwenjuan@icell.com.cn ', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (5, 5, '北京天坦呵呵软件有限责任公司', '', '北京市海淀区上地佳园23号楼6层601', 'www.tiantanhehe.com', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (6, 6, '武汉璞华大数据技术有限公司', '', '武汉市东湖新技术开发区高新大道999号', 'www.purvardata.com', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (7, 7, '武汉璞睿互联技术有限公司', '', '武汉市东湖新技术开发区东信路光谷创业街10栋1单元2层01号(2155)', '', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (8, 8, '璞华教育(武汉)有限公司', '', '武汉市东湖高新技术开发区花城大道武汉软件新城1.1期A区3号(B2)楼', 'www.purvaredu.com', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (9, 9, '苏州璞华爱体育管理有限公司', '', '苏州工业园区集贤街88号1#楼的第8层810', '', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (10, 10, '乐屋（上海）电子商务有限公司', '', '中国(上海)自由贸易试验区郭守敬路498号8幢19号楼3层', 'http://julewu.com', '倒闭', '', 2, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (11, 11, '上海华新生物高技术有限公司', '', '中国(上海)自由贸易试验区桂桥路1150号', 'http://www.huaxin-bio.com', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (12, 12, '苏州千机智能技术有限公司', '', '苏州工业园区双马街2号星华产业园4号楼南', 'www.qianjizn.com', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (13, 13, '北京杰思安全科技有限公司', '', '北京市朝阳区安华里五区21号楼6层2605', 'http://www.majorsec.com', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (14, 14, '北京中交兴路信源科技有限公司', '', '北京市北京经济技术开发区文化园西路8号院29号楼22层2606', 'http://www.utrailer.cn', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (15, 15, '布比(北京)网络技术有限公司', '', '北京市海淀区中关村东路66号1号楼8层906 ', 'http://www.bubi.cn', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (16, 16, '致保科技（上海）有限公司', '', '中国(上海)自由贸易试验区茂兴路88号4楼434室', '', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (17, 17, '上海安逸网络科技有限公司', '', '中国(上海)自由贸易试验区亮秀路112号B座701A室', 'http://www.anyi-tech.com', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (18, 18, '成都链安科技有限公司', '', '中国(四川)自由贸易试验区成都高新区世纪城南路599号7栋5层505A、505B号', '', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (19, 19, '北京品友互动信息技术股份公司', '', '北京市朝阳区东三环中路20号楼9层01单元', 'http://www.ipinyou.com', '退出（2016年2月）', '', 3, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (20, 20, '北京寺库商贸有限公司', '', '北京市西城区阜成门外大街31号4层428', 'http://www.secoo.com', '上市', '', 1, 907000000.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (21, 21, '索福德(上海)体育发展有限公司', '', '中国(上海)自由贸易试验区峨山路111号4幢130室', 'http://www.soccerworld.cc', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (22, 22, '上海汇翼信息科技有限公司', '', '中国(上海)自由贸易试验区张江路665号3层', 'http://www.teambition.com', '退出（2019年5月）', '', 3, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (23, 23, '摩贝(上海)生物科技有限公司', '', '中国(上海)自由贸易试验区富特北路399号1幢楼6层6020室', 'http://www.molbase.com', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (24, 24, '北京极客易品科技有限公司', '', '北京市朝阳区十里堡54楼东侧1幢109', 'http://www.mj100.com', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (25, 25, '搜当网(北京)信息服务有限公司', '', '北京市朝阳区北辰东路8号16号楼1301(A1304)室', 'http://www.soudang.cn', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (26, 26, '上海蓝游文化传播有限公司', '', '上海市崇明县新海镇跃进南路495号4幢3274室(光明米业经济园区)', 'www.chuangleague.cn', '倒闭（准备跟Newbee置换股份）', '', 2, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);
INSERT INTO `project` VALUES (27, 27, '况客科技(北京)有限公司', '', '北京市朝阳区将台路5号院5号楼2层2028室', 'http://qutke.com', '未上市', '', 0, 0.00, NULL, '2020-04-20 14:14:26', NULL, 0);

DROP TABLE IF EXISTS `found`;
CREATE TABLE `found`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `found_no` int(5) NULL DEFAULT 0 COMMENT '基金编号',
  `current_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '基金当前名称',
  `ever_name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '基金曾用名（json）',
  `current_gp` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '当前gp',
  `ever_gp` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '历史gp',
  `total_value_cn` decimal(20, 2) NULL DEFAULT 0.00 COMMENT '基金募集总额(人民币)',
  `total_value_us` decimal(20, 2) NULL DEFAULT 0.00 COMMENT '基金募集总额(美元)',
  `introduce` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '基金公司介绍',
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '图片',
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '基金类型',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '基金信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of found
-- ----------------------------
INSERT INTO `found` VALUES (1, 1, '上海毅睿企业管理中心（有限合伙）', '北京盘古创富科技投资中心（有限合伙）', '宁波保税区盘古创富投资管理合伙企业（有限合伙）', NULL, 36959331.00, NULL, '基金简介1', '/images/fund_logo.png', '科技基金', NULL, NULL, 0);
INSERT INTO `found` VALUES (2, 2, '宁波盘古创富和盛股权投资合伙企业（有限合伙）', '北京盘古创富成长投资中心（有限合伙）', '北京盘古创富卓越投资中心（有限合伙）', NULL, 90999356.41, NULL, '基金简介2', '/images/fund_logo.png', '科技基金', NULL, NULL, 0);
INSERT INTO `found` VALUES (3, 3, '宁波盘古创富和宏投资合伙企业（有限合伙）', '北京盘古创富成长二号投资中心（有限合伙）', '北京盘古创富卓越投资中心（有限合伙）', NULL, 55000000.00, NULL, '基金简介3', '/images/fund_logo.png', '科技基金', NULL, NULL, 0);
INSERT INTO `found` VALUES (4, 4, '宁波盘古创富和富股权投资合伙企业（有限合伙）', '北京盘古创富成长三号投资中心（有限合伙）', '北京盘古创富卓越投资中心（有限合伙）', NULL, 65100000.00, NULL, '基金简介4', '/images/fund_logo.png', '科技基金', NULL, NULL, 0);
INSERT INTO `found` VALUES (5, 5, '盘古中国成长基金二号', '盘古中国成长基金二号', 'Vangoo Investment Partners', NULL, 32457211.61, NULL, '基金简介5', '/images/fund_logo.png', '科技基金', NULL, NULL, 0);
INSERT INTO `found` VALUES (6, 6, '目标基金二号Vangoo Target Fund II.LP', '', '', NULL, 0.00, NULL, '基金简介6', '/images/fund_logo.png', '科技基金', NULL, NULL, 0);
INSERT INTO `found` VALUES (7, 7, '亚洲投资基金Vangoo Asia Investment Fund.LP', '', '', NULL, 0.00, NULL, '基金简介7', '/images/fund_logo.png', '科技基金', NULL, NULL, 0);
INSERT INTO `found` VALUES (8, 8, '美元一号基金VANGOO CHINA GROWTH FUND I L.P', '', '', NULL, 0.00, NULL, '基金简介8', '/images/fund_logo.png', '科技基金', NULL, NULL, 0);
INSERT INTO `found` VALUES (9, 9, '医疗基金中信', '', '', NULL, 0.00, NULL, '基金简介9', '/images/fund_logo.png', '科技基金', NULL, NULL, 0);


CREATE TABLE `found_audit`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fund_id` int(10) NOT NULL DEFAULT 0 COMMENT 'tb_fund.id',
  `name` varchar(55) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '报表名称',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '路径',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '基金-审计报告' ROW_FORMAT = Dynamic;

CREATE TABLE `found_capital`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fund_id` int(10) NOT NULL DEFAULT 0 COMMENT 'tb_fund.id',
  `name` varchar(55) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '出资人',
  `type` tinyint(3) NULL DEFAULT 0 COMMENT '类型(1:个人 2：团体)',
  `amount_cn` decimal(20, 2) NULL DEFAULT 0.00 COMMENT '认缴金额(人民币)',
  `amount_us` decimal(20, 2) NULL DEFAULT 0.00 COMMENT '认缴金额(美元)',
  `paid_date` date NULL DEFAULT NULL COMMENT '认缴时间',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '基金-出资明细' ROW_FORMAT = Dynamic;

CREATE TABLE `found_diagram`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fund_id` int(10) NOT NULL DEFAULT 0 COMMENT 'tb_fund.id',
  `image` varchar(55) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '关系图',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '基金-关系图' ROW_FORMAT = Dynamic;

CREATE TABLE `found_financial`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fund_id` int(10) NOT NULL DEFAULT 0 COMMENT 'tb_fund.id',
  `name` varchar(55) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '报表名称',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '路径',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '基金-财务报表' ROW_FORMAT = Dynamic;

CREATE TABLE `found_other`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fund_id` int(10) NOT NULL DEFAULT 0 COMMENT 'tb_fund.id',
  `name` varchar(55) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '标题',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '提示内容',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '基金-其它提示' ROW_FORMAT = Dynamic;

CREATE TABLE `found_risk`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fund_id` int(10) NOT NULL DEFAULT 0 COMMENT 'tb_fund.id',
  `name` varchar(55) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '标题',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '风险内容',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '基金-风险提示' ROW_FORMAT = Dynamic;