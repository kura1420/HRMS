-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `mst_empl`;


CREATE TABLE IF NOT EXISTS `mst_empl` (
	`empl_id` varchar(36) NOT NULL , 
	`empl_fullname` varchar(255) NOT NULL , 
	`empl_isexit` tinyint(1) NOT NULL DEFAULT 0, 
	`empl_dtjoin` date  , 
	`dept_id` varchar(36) NOT NULL , 
	`division_id` varchar(36)  , 
	`user_id` varchar(14) NOT NULL , 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `user_id` (`user_id`),
	PRIMARY KEY (`empl_id`)
) 
ENGINE=InnoDB
COMMENT='Master Employee';


ALTER TABLE `mst_empl` ADD COLUMN IF NOT EXISTS  `empl_fullname` varchar(255) NOT NULL  AFTER `empl_id`;
ALTER TABLE `mst_empl` ADD COLUMN IF NOT EXISTS  `empl_isexit` tinyint(1) NOT NULL DEFAULT 0 AFTER `empl_fullname`;
ALTER TABLE `mst_empl` ADD COLUMN IF NOT EXISTS  `empl_dtjoin` date   AFTER `empl_isexit`;
ALTER TABLE `mst_empl` ADD COLUMN IF NOT EXISTS  `dept_id` varchar(36) NOT NULL  AFTER `empl_dtjoin`;
ALTER TABLE `mst_empl` ADD COLUMN IF NOT EXISTS  `division_id` varchar(36)   AFTER `dept_id`;
ALTER TABLE `mst_empl` ADD COLUMN IF NOT EXISTS  `user_id` varchar(14) NOT NULL  AFTER `division_id`;


ALTER TABLE `mst_empl` MODIFY COLUMN IF EXISTS  `empl_fullname` varchar(255) NOT NULL   AFTER `empl_id`;
ALTER TABLE `mst_empl` MODIFY COLUMN IF EXISTS  `empl_isexit` tinyint(1) NOT NULL DEFAULT 0  AFTER `empl_fullname`;
ALTER TABLE `mst_empl` MODIFY COLUMN IF EXISTS  `empl_dtjoin` date    AFTER `empl_isexit`;
ALTER TABLE `mst_empl` MODIFY COLUMN IF EXISTS  `dept_id` varchar(36) NOT NULL   AFTER `empl_dtjoin`;
ALTER TABLE `mst_empl` MODIFY COLUMN IF EXISTS  `division_id` varchar(36)    AFTER `dept_id`;
ALTER TABLE `mst_empl` MODIFY COLUMN IF EXISTS  `user_id` varchar(14) NOT NULL   AFTER `division_id`;


ALTER TABLE `mst_empl` ADD CONSTRAINT `user_id` UNIQUE IF NOT EXISTS  (`user_id`);

ALTER TABLE `mst_empl` ADD KEY IF NOT EXISTS `dept_id` (`dept_id`);
ALTER TABLE `mst_empl` ADD KEY IF NOT EXISTS `division_id` (`division_id`);
ALTER TABLE `mst_empl` ADD KEY IF NOT EXISTS `user_id` (`user_id`);

ALTER TABLE `mst_empl` ADD CONSTRAINT `fk_mst_empl_mst_dept` FOREIGN KEY IF NOT EXISTS  (`dept_id`) REFERENCES `mst_dept` (`dept_id`);
ALTER TABLE `mst_empl` ADD CONSTRAINT `fk_mst_empl_mst_division` FOREIGN KEY IF NOT EXISTS  (`division_id`) REFERENCES `mst_division` (`division_id`);
ALTER TABLE `mst_empl` ADD CONSTRAINT `fk_mst_empl_fgt_user` FOREIGN KEY IF NOT EXISTS  (`user_id`) REFERENCES `fgt_user` (`user_id`);





