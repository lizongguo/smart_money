ALTER TABLE `users` 
ADD COLUMN `permissions` varchar(55) NULL DEFAULT '' COMMENT '权限' AFTER `note`;