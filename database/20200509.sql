ALTER TABLE `found_risk` 
ADD COLUMN `path` varchar(255) NULL DEFAULT '' COMMENT '路径' AFTER `content`;

ALTER TABLE `found_other` 
ADD COLUMN `path` varchar(255) NULL DEFAULT '' COMMENT '路径' AFTER `content`;


ALTER TABLE `found_capital` 
ADD COLUMN `user_id` int(10) NULL DEFAULT 0 COMMENT '用户id' AFTER `paid_date`;

ALTER TABLE `project` 
ADD COLUMN `shares_url` varchar(255) NULL DEFAULT '' COMMENT '股价查询地址' AFTER `content`;