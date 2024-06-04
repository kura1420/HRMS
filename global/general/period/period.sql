SET FOREIGN_KEY_CHECKS=0;

drop table if exists `mst_period`;


CREATE TABLE IF NOT EXISTS `mst_period` (
	`period_id` varchar(36) NOT NULL , 
	`period_name` varchar(255) NOT NULL , 
	`period_descr` varchar(1000)  , 
	`period_isdisabled` tinyint(1) NOT NULL DEFAULT 0, 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `period_name` (`period_name`),
	PRIMARY KEY (`period_id`)
) 
ENGINE=InnoDB
COMMENT='Master Period';


ALTER TABLE `mst_period` ADD COLUMN IF NOT EXISTS  `period_name` varchar(255) NOT NULL  AFTER `period_id`;
ALTER TABLE `mst_period` ADD COLUMN IF NOT EXISTS  `period_descr` varchar(1000)   AFTER `period_name`;
ALTER TABLE `mst_period` ADD COLUMN IF NOT EXISTS  `period_isdisabled` tinyint(1) NOT NULL DEFAULT 0 AFTER `period_descr`;


ALTER TABLE `mst_period` MODIFY COLUMN IF EXISTS  `period_name` varchar(255) NOT NULL   AFTER `period_id`;
ALTER TABLE `mst_period` MODIFY COLUMN IF EXISTS  `period_descr` varchar(1000)    AFTER `period_name`;
ALTER TABLE `mst_period` MODIFY COLUMN IF EXISTS  `period_isdisabled` tinyint(1) NOT NULL DEFAULT 0  AFTER `period_descr`;


ALTER TABLE `mst_period` ADD CONSTRAINT `period_name` UNIQUE IF NOT EXISTS  (`period_name`);







