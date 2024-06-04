-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `mst_offertime`;


CREATE TABLE IF NOT EXISTS `mst_offertime` (
	`offertime_id` varchar(36) NOT NULL , 
	`offertime_name` varchar(255) NOT NULL , 
	`offertime_descr` varchar(1000)  , 
	`offertime_isdisabled` tinyint(1) NOT NULL DEFAULT 0, 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `offertime_name` (`offertime_name`),
	PRIMARY KEY (`offertime_id`)
) 
ENGINE=InnoDB
COMMENT='Master Overtime';


ALTER TABLE `mst_offertime` ADD COLUMN IF NOT EXISTS  `offertime_name` varchar(255) NOT NULL  AFTER `offertime_id`;
ALTER TABLE `mst_offertime` ADD COLUMN IF NOT EXISTS  `offertime_descr` varchar(1000)   AFTER `offertime_name`;
ALTER TABLE `mst_offertime` ADD COLUMN IF NOT EXISTS  `offertime_isdisabled` tinyint(1) NOT NULL DEFAULT 0 AFTER `offertime_descr`;


ALTER TABLE `mst_offertime` MODIFY COLUMN IF EXISTS  `offertime_name` varchar(255) NOT NULL   AFTER `offertime_id`;
ALTER TABLE `mst_offertime` MODIFY COLUMN IF EXISTS  `offertime_descr` varchar(1000)    AFTER `offertime_name`;
ALTER TABLE `mst_offertime` MODIFY COLUMN IF EXISTS  `offertime_isdisabled` tinyint(1) NOT NULL DEFAULT 0  AFTER `offertime_descr`;


ALTER TABLE `mst_offertime` ADD CONSTRAINT `offertime_name` UNIQUE IF NOT EXISTS  (`offertime_name`);







