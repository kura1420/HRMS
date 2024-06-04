-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `mst_paidleave`;


CREATE TABLE IF NOT EXISTS `mst_paidleave` (
	`paidleave_id` varchar(36) NOT NULL , 
	`paidleave_code` varchar(50) NOT NULL , 
	`paidleave_name` varchar(255) NOT NULL , 
	`paidleave_isdisabled` tinyint(1) NOT NULL DEFAULT 0, 
	`paidleave_iscutting` tinyint(1) NOT NULL DEFAULT 0, 
	`paidleave_descr` varchar(1000)  , 
	`paidleave_expenable` tinyint(1) NOT NULL DEFAULT 0, 
	`paidleave_qty` int(11) NOT NULL DEFAULT 0, 
	`period_id` varchar(36)  , 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `paidleave_code` (`paidleave_code`),
	UNIQUE KEY `paidleave_name` (`paidleave_name`),
	PRIMARY KEY (`paidleave_id`)
) 
ENGINE=InnoDB
COMMENT='Master Paid Leave",';


ALTER TABLE `mst_paidleave` ADD COLUMN IF NOT EXISTS  `paidleave_code` varchar(50) NOT NULL  AFTER `paidleave_id`;
ALTER TABLE `mst_paidleave` ADD COLUMN IF NOT EXISTS  `paidleave_name` varchar(255) NOT NULL  AFTER `paidleave_code`;
ALTER TABLE `mst_paidleave` ADD COLUMN IF NOT EXISTS  `paidleave_isdisabled` tinyint(1) NOT NULL DEFAULT 0 AFTER `paidleave_name`;
ALTER TABLE `mst_paidleave` ADD COLUMN IF NOT EXISTS  `paidleave_iscutting` tinyint(1) NOT NULL DEFAULT 0 AFTER `paidleave_isdisabled`;
ALTER TABLE `mst_paidleave` ADD COLUMN IF NOT EXISTS  `paidleave_descr` varchar(1000)   AFTER `paidleave_iscutting`;
ALTER TABLE `mst_paidleave` ADD COLUMN IF NOT EXISTS  `paidleave_expenable` tinyint(1) NOT NULL DEFAULT 0 AFTER `paidleave_descr`;
ALTER TABLE `mst_paidleave` ADD COLUMN IF NOT EXISTS  `paidleave_qty` int(11) NOT NULL DEFAULT 0 AFTER `paidleave_expenable`;
ALTER TABLE `mst_paidleave` ADD COLUMN IF NOT EXISTS  `period_id` varchar(36)   AFTER `paidleave_qty`;


ALTER TABLE `mst_paidleave` MODIFY COLUMN IF EXISTS  `paidleave_code` varchar(50) NOT NULL   AFTER `paidleave_id`;
ALTER TABLE `mst_paidleave` MODIFY COLUMN IF EXISTS  `paidleave_name` varchar(255) NOT NULL   AFTER `paidleave_code`;
ALTER TABLE `mst_paidleave` MODIFY COLUMN IF EXISTS  `paidleave_isdisabled` tinyint(1) NOT NULL DEFAULT 0  AFTER `paidleave_name`;
ALTER TABLE `mst_paidleave` MODIFY COLUMN IF EXISTS  `paidleave_iscutting` tinyint(1) NOT NULL DEFAULT 0  AFTER `paidleave_isdisabled`;
ALTER TABLE `mst_paidleave` MODIFY COLUMN IF EXISTS  `paidleave_descr` varchar(1000)    AFTER `paidleave_iscutting`;
ALTER TABLE `mst_paidleave` MODIFY COLUMN IF EXISTS  `paidleave_expenable` tinyint(1) NOT NULL DEFAULT 0  AFTER `paidleave_descr`;
ALTER TABLE `mst_paidleave` MODIFY COLUMN IF EXISTS  `paidleave_qty` int(11) NOT NULL DEFAULT 0  AFTER `paidleave_expenable`;
ALTER TABLE `mst_paidleave` MODIFY COLUMN IF EXISTS  `period_id` varchar(36)    AFTER `paidleave_qty`;


ALTER TABLE `mst_paidleave` ADD CONSTRAINT `paidleave_code` UNIQUE IF NOT EXISTS  (`paidleave_code`);
ALTER TABLE `mst_paidleave` ADD CONSTRAINT `paidleave_name` UNIQUE IF NOT EXISTS  (`paidleave_name`);

ALTER TABLE `mst_paidleave` ADD KEY IF NOT EXISTS `period_id` (`period_id`);

ALTER TABLE `mst_paidleave` ADD CONSTRAINT `fk_mst_paidleave_mst_period` FOREIGN KEY IF NOT EXISTS  (`period_id`) REFERENCES `mst_period` (`period_id`);





