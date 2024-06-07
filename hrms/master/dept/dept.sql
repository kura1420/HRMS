-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `mst_dept`;


CREATE TABLE IF NOT EXISTS `mst_dept` (
	`dept_id` varchar(36) NOT NULL , 
	`dept_name` varchar(255) NOT NULL , 
	`dept_descr` varchar(10000)  , 
	`dept_isdisabled` tinyint(1) NOT NULL DEFAULT 0, 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `dept_name` (`dept_name`),
	PRIMARY KEY (`dept_id`)
) 
ENGINE=InnoDB
COMMENT='Daftar Departement';


ALTER TABLE `mst_dept` ADD COLUMN IF NOT EXISTS  `dept_name` varchar(255) NOT NULL  AFTER `dept_id`;
ALTER TABLE `mst_dept` ADD COLUMN IF NOT EXISTS  `dept_descr` varchar(10000)   AFTER `dept_name`;
ALTER TABLE `mst_dept` ADD COLUMN IF NOT EXISTS  `dept_isdisabled` tinyint(1) NOT NULL DEFAULT 0 AFTER `dept_descr`;


ALTER TABLE `mst_dept` MODIFY COLUMN IF EXISTS  `dept_name` varchar(255) NOT NULL   AFTER `dept_id`;
ALTER TABLE `mst_dept` MODIFY COLUMN IF EXISTS  `dept_descr` varchar(10000)    AFTER `dept_name`;
ALTER TABLE `mst_dept` MODIFY COLUMN IF EXISTS  `dept_isdisabled` tinyint(1) NOT NULL DEFAULT 0  AFTER `dept_descr`;


ALTER TABLE `mst_dept` ADD CONSTRAINT `dept_name` UNIQUE IF NOT EXISTS  (`dept_name`);







