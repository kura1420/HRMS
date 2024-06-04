-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `mst_docapprv`;
-- drop table if exists `mst_docapprvlevl`;


CREATE TABLE IF NOT EXISTS `mst_docapprv` (
	`docapprv_id` varchar(36) NOT NULL , 
	`docapprv_name` varchar(255) NOT NULL , 
	`docapprv_descr` varchar(1000)  , 
	`docapprv_isdisabled` tinyint(1) NOT NULL DEFAULT 0, 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `docapprv_name` (`docapprv_name`),
	PRIMARY KEY (`docapprv_id`)
) 
ENGINE=InnoDB
COMMENT='Master Activity';


ALTER TABLE `mst_docapprv` ADD COLUMN IF NOT EXISTS  `docapprv_name` varchar(255) NOT NULL  AFTER `docapprv_id`;
ALTER TABLE `mst_docapprv` ADD COLUMN IF NOT EXISTS  `docapprv_descr` varchar(1000)   AFTER `docapprv_name`;
ALTER TABLE `mst_docapprv` ADD COLUMN IF NOT EXISTS  `docapprv_isdisabled` tinyint(1) NOT NULL DEFAULT 0 AFTER `docapprv_descr`;


ALTER TABLE `mst_docapprv` MODIFY COLUMN IF EXISTS  `docapprv_name` varchar(255) NOT NULL   AFTER `docapprv_id`;
ALTER TABLE `mst_docapprv` MODIFY COLUMN IF EXISTS  `docapprv_descr` varchar(1000)    AFTER `docapprv_name`;
ALTER TABLE `mst_docapprv` MODIFY COLUMN IF EXISTS  `docapprv_isdisabled` tinyint(1) NOT NULL DEFAULT 0  AFTER `docapprv_descr`;


ALTER TABLE `mst_docapprv` ADD CONSTRAINT `docapprv_name` UNIQUE IF NOT EXISTS  (`docapprv_name`);







CREATE TABLE IF NOT EXISTS `mst_docapprvlevl` (
	`docapprvlevl_id` varchar(36) NOT NULL , 
	`docapprvlevl_sortorder` int(4) NOT NULL DEFAULT 0, 
	`docapprvlevl_isdisabled` tinyint(1) NOT NULL DEFAULT 0, 
	`empl_id` varchar(36) NOT NULL , 
	`docapprv_id` varchar(36) NOT NULL , 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	PRIMARY KEY (`docapprvlevl_id`)
) 
ENGINE=InnoDB
COMMENT='';


ALTER TABLE `mst_docapprvlevl` ADD COLUMN IF NOT EXISTS  `docapprvlevl_sortorder` int(4) NOT NULL DEFAULT 0 AFTER `docapprvlevl_id`;
ALTER TABLE `mst_docapprvlevl` ADD COLUMN IF NOT EXISTS  `docapprvlevl_isdisabled` tinyint(1) NOT NULL DEFAULT 0 AFTER `docapprvlevl_sortorder`;
ALTER TABLE `mst_docapprvlevl` ADD COLUMN IF NOT EXISTS  `empl_id` varchar(36) NOT NULL  AFTER `docapprvlevl_isdisabled`;
ALTER TABLE `mst_docapprvlevl` ADD COLUMN IF NOT EXISTS  `docapprv_id` varchar(36) NOT NULL  AFTER `empl_id`;


ALTER TABLE `mst_docapprvlevl` MODIFY COLUMN IF EXISTS  `docapprvlevl_sortorder` int(4) NOT NULL DEFAULT 0  AFTER `docapprvlevl_id`;
ALTER TABLE `mst_docapprvlevl` MODIFY COLUMN IF EXISTS  `docapprvlevl_isdisabled` tinyint(1) NOT NULL DEFAULT 0  AFTER `docapprvlevl_sortorder`;
ALTER TABLE `mst_docapprvlevl` MODIFY COLUMN IF EXISTS  `empl_id` varchar(36) NOT NULL   AFTER `docapprvlevl_isdisabled`;
ALTER TABLE `mst_docapprvlevl` MODIFY COLUMN IF EXISTS  `docapprv_id` varchar(36) NOT NULL   AFTER `empl_id`;



ALTER TABLE `mst_docapprvlevl` ADD KEY IF NOT EXISTS `empl_id` (`empl_id`);
ALTER TABLE `mst_docapprvlevl` ADD KEY IF NOT EXISTS `docapprv_id` (`docapprv_id`);

ALTER TABLE `mst_docapprvlevl` ADD CONSTRAINT `fk_mst_docapprvlevl_mst_empl` FOREIGN KEY IF NOT EXISTS  (`empl_id`) REFERENCES `mst_empl` (`empl_id`);
ALTER TABLE `mst_docapprvlevl` ADD CONSTRAINT `fk_mst_docapprvlevl_mst_docapprv` FOREIGN KEY IF NOT EXISTS (`docapprv_id`) REFERENCES `mst_docapprv` (`docapprv_id`);





