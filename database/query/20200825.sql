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
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;
