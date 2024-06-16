-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `mst_typereq`;


CREATE TABLE IF NOT EXISTS `mst_typereq` (
	`typereq_id` varchar(36) NOT NULL , 
	`typereq_name` varchar(255) NOT NULL , 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `typereq_name` (`typereq_name`),
	PRIMARY KEY (`typereq_id`)
) 
ENGINE=InnoDB
COMMENT='Master Purchase Type Requisition';


ALTER TABLE `mst_typereq` ADD COLUMN IF NOT EXISTS  `typereq_name` varchar(255) NOT NULL  AFTER `typereq_id`;


ALTER TABLE `mst_typereq` MODIFY COLUMN IF EXISTS  `typereq_name` varchar(255) NOT NULL   AFTER `typereq_id`;


ALTER TABLE `mst_typereq` ADD CONSTRAINT `typereq_name` UNIQUE IF NOT EXISTS  (`typereq_name`);







