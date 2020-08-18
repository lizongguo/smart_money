ALTER TABLE `found`.`users` 
ADD COLUMN `user_id` int(10) NULL DEFAULT 0 COMMENT '投资人id' AFTER `last_login_ip`;

INSERT INTO `menu` VALUES (52, 17, 6, '投资人信息', 'fa-bars', '/admin/found_user', 1, '2020-04-21 11:15:27', '2020-04-21 11:15:43');