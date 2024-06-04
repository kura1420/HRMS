-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `mst_division`;


CREATE TABLE IF NOT EXISTS `mst_division` (
	`division_id` varchar(36) NOT NULL , 
	`division_code` varchar(50) NOT NULL , 
	`division_name` varchar(255) NOT NULL , 
	`division_descr` varchar(1000)  , 
	`division_isdisabled` tinyint(1) NOT NULL DEFAULT 0, 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `division_code` (`division_code`),
	PRIMARY KEY (`division_id`)
) 
ENGINE=InnoDB
COMMENT='Master Division';


ALTER TABLE `mst_division` ADD COLUMN IF NOT EXISTS  `division_code` varchar(50) NOT NULL  AFTER `division_id`;
ALTER TABLE `mst_division` ADD COLUMN IF NOT EXISTS  `division_name` varchar(255) NOT NULL  AFTER `division_code`;
ALTER TABLE `mst_division` ADD COLUMN IF NOT EXISTS  `division_descr` varchar(1000)   AFTER `division_name`;
ALTER TABLE `mst_division` ADD COLUMN IF NOT EXISTS  `division_isdisabled` tinyint(1) NOT NULL DEFAULT 0 AFTER `division_descr`;


ALTER TABLE `mst_division` MODIFY COLUMN IF EXISTS  `division_code` varchar(50) NOT NULL   AFTER `division_id`;
ALTER TABLE `mst_division` MODIFY COLUMN IF EXISTS  `division_name` varchar(255) NOT NULL   AFTER `division_code`;
ALTER TABLE `mst_division` MODIFY COLUMN IF EXISTS  `division_descr` varchar(1000)    AFTER `division_name`;
ALTER TABLE `mst_division` MODIFY COLUMN IF EXISTS  `division_isdisabled` tinyint(1) NOT NULL DEFAULT 0  AFTER `division_descr`;


ALTER TABLE `mst_division` ADD CONSTRAINT `division_code` UNIQUE IF NOT EXISTS  (`division_code`);







