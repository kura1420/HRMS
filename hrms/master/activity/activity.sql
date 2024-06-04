-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `mst_activity`;


CREATE TABLE IF NOT EXISTS `mst_activity` (
	`activity_id` varchar(36) NOT NULL , 
	`activity_name` varchar(255) NOT NULL , 
	`activity_descr` varchar(1000)  , 
	`activity_isdisabled` tinyint(1) NOT NULL DEFAULT 0, 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `activity_name` (`activity_name`),
	PRIMARY KEY (`activity_id`)
) 
ENGINE=InnoDB
COMMENT='Master Activity';


ALTER TABLE `mst_activity` ADD COLUMN IF NOT EXISTS  `activity_name` varchar(255) NOT NULL  AFTER `activity_id`;
ALTER TABLE `mst_activity` ADD COLUMN IF NOT EXISTS  `activity_descr` varchar(1000)   AFTER `activity_name`;
ALTER TABLE `mst_activity` ADD COLUMN IF NOT EXISTS  `activity_isdisabled` tinyint(1) NOT NULL DEFAULT 0 AFTER `activity_descr`;


ALTER TABLE `mst_activity` MODIFY COLUMN IF EXISTS  `activity_name` varchar(255) NOT NULL   AFTER `activity_id`;
ALTER TABLE `mst_activity` MODIFY COLUMN IF EXISTS  `activity_descr` varchar(1000)    AFTER `activity_name`;
ALTER TABLE `mst_activity` MODIFY COLUMN IF EXISTS  `activity_isdisabled` tinyint(1) NOT NULL DEFAULT 0  AFTER `activity_descr`;


ALTER TABLE `mst_activity` ADD CONSTRAINT `activity_name` UNIQUE IF NOT EXISTS  (`activity_name`);







