-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `mst_ovrtime`;


CREATE TABLE IF NOT EXISTS `mst_ovrtime` (
	`ovrtime_id` varchar(36) NOT NULL , 
	`ovrtime_name` varchar(255) NOT NULL , 
	`ovrtime_descr` varchar(1000)  , 
	`ovrtime_isdisabled` tinyint(1) NOT NULL DEFAULT 0, 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `ovrtime_name` (`ovrtime_name`),
	PRIMARY KEY (`ovrtime_id`)
) 
ENGINE=InnoDB
COMMENT='Master Overtime';


ALTER TABLE `mst_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_name` varchar(255) NOT NULL  AFTER `ovrtime_id`;
ALTER TABLE `mst_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_descr` varchar(1000)   AFTER `ovrtime_name`;
ALTER TABLE `mst_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_isdisabled` tinyint(1) NOT NULL DEFAULT 0 AFTER `ovrtime_descr`;


ALTER TABLE `mst_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_name` varchar(255) NOT NULL   AFTER `ovrtime_id`;
ALTER TABLE `mst_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_descr` varchar(1000)    AFTER `ovrtime_name`;
ALTER TABLE `mst_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_isdisabled` tinyint(1) NOT NULL DEFAULT 0  AFTER `ovrtime_descr`;


ALTER TABLE `mst_ovrtime` ADD CONSTRAINT `ovrtime_name` UNIQUE IF NOT EXISTS  (`ovrtime_name`);







