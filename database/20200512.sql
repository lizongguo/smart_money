ALTER TABLE `found_other` 
ADD COLUMN `content_type` tinyint(1) NULL DEFAULT 1 COMMENT '1：文件 2：文本' AFTER `path`;

ALTER TABLE `found_risk` 
ADD COLUMN `content_type` tinyint(1) NULL DEFAULT 1 COMMENT '1：文件 2：文本' AFTER `path`;

ALTER TABLE `found_audit` 
ADD COLUMN `content_type` tinyint(1) NULL DEFAULT 1 COMMENT '1：文件 2：文本' AFTER `path`,
ADD COLUMN `content` text  NULL COMMENT '提示内容' AFTER `content_type`;

ALTER TABLE `found_financial` 
ADD COLUMN `content_type` tinyint(1) NULL DEFAULT 1 COMMENT '1：文件 2：文本' AFTER `path`,
ADD COLUMN `content` text  NULL COMMENT '提示内容' AFTER `content_type`;

ALTER TABLE `project_risk` 
ADD COLUMN `content_type` tinyint(1) NULL DEFAULT 1 COMMENT '1：文件 2：文本' AFTER `path`;