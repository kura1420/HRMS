-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `mst_month`;


CREATE TABLE IF NOT EXISTS `mst_month` (
	`month_id` varchar(36) NOT NULL , 
	`month_name` varchar(255) NOT NULL , 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `month_name` (`month_name`),
	PRIMARY KEY (`month_id`)
) 
ENGINE=InnoDB
COMMENT='Master Month';


ALTER TABLE `mst_month` ADD COLUMN IF NOT EXISTS  `month_name` varchar(255) NOT NULL  AFTER `month_id`;


ALTER TABLE `mst_month` MODIFY COLUMN IF EXISTS  `month_name` varchar(255) NOT NULL   AFTER `month_id`;


ALTER TABLE `mst_month` ADD CONSTRAINT `month_name` UNIQUE IF NOT EXISTS  (`month_name`);







