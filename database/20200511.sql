CREATE TABLE `project_risk`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fund_id` int(10) NOT NULL DEFAULT 0 COMMENT 'tb_fund.id',
  `name` varchar(55) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '标题',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '风险内容',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '路径',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '项目-风险提示' ROW_FORMAT = Dynamic;

ALTER TABLE `project` 
ADD COLUMN `file_path` varchar(255) NULL DEFAULT '' COMMENT '项目文档' AFTER `shares_url`;

ALTER TABLE `found` 
ADD COLUMN `currency` tinyint(1) NULL DEFAULT 1 COMMENT '币种(1:人民币 2：美元)' AFTER `type`;