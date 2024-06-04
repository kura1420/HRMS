-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `trn_offertime`;
-- drop table if exists `trn_offertimedt`;


CREATE TABLE IF NOT EXISTS `trn_offertime` (
	`offertime_id` varchar(36) NOT NULL , 
	`offertime_code` varchar(50) NOT NULL , 
	`empl_id` varchar(36) NOT NULL , 
	`docapprv_id` varchar(36) NOT NULL , 
	`offertime_rejectnotes` varchar(255)  , 
	`offertime_isrequest` tinyint(1) NOT NULL DEFAULT 0, 
	`offertime_requestby` varchar(14)  , 
	`offertime_requestdate` datetime  , 
	`offertime_isapproved` tinyint(1) NOT NULL DEFAULT 0, 
	`offertime_approveby` varchar(14)  , 
	`offertime_approvedate` datetime  , 
	`offertime_isdeclined` tinyint(1) NOT NULL DEFAULT 0, 
	`offertime_declineby` varchar(14)  , 
	`offertime_declinedate` datetime  , 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `offertime_code` (`offertime_code`),
	PRIMARY KEY (`offertime_id`)
) 
ENGINE=InnoDB
COMMENT='Offertime';


ALTER TABLE `trn_offertime` ADD COLUMN IF NOT EXISTS  `offertime_code` varchar(50) NOT NULL  AFTER `offertime_id`;
ALTER TABLE `trn_offertime` ADD COLUMN IF NOT EXISTS  `empl_id` varchar(36) NOT NULL  AFTER `offertime_code`;
ALTER TABLE `trn_offertime` ADD COLUMN IF NOT EXISTS  `docapprv_id` varchar(36) NOT NULL  AFTER `empl_id`;
ALTER TABLE `trn_offertime` ADD COLUMN IF NOT EXISTS  `offertime_rejectnotes` varchar(255)   AFTER `docapprv_id`;
ALTER TABLE `trn_offertime` ADD COLUMN IF NOT EXISTS  `offertime_isrequest` tinyint(1) NOT NULL DEFAULT 0 AFTER `offertime_rejectnotes`;
ALTER TABLE `trn_offertime` ADD COLUMN IF NOT EXISTS  `offertime_requestby` varchar(14)   AFTER `offertime_isrequest`;
ALTER TABLE `trn_offertime` ADD COLUMN IF NOT EXISTS  `offertime_requestdate` datetime   AFTER `offertime_requestby`;
ALTER TABLE `trn_offertime` ADD COLUMN IF NOT EXISTS  `offertime_isapproved` tinyint(1) NOT NULL DEFAULT 0 AFTER `offertime_requestdate`;
ALTER TABLE `trn_offertime` ADD COLUMN IF NOT EXISTS  `offertime_approveby` varchar(14)   AFTER `offertime_isapproved`;
ALTER TABLE `trn_offertime` ADD COLUMN IF NOT EXISTS  `offertime_approvedate` datetime   AFTER `offertime_approveby`;
ALTER TABLE `trn_offertime` ADD COLUMN IF NOT EXISTS  `offertime_isdeclined` tinyint(1) NOT NULL DEFAULT 0 AFTER `offertime_approvedate`;
ALTER TABLE `trn_offertime` ADD COLUMN IF NOT EXISTS  `offertime_declineby` varchar(14)   AFTER `offertime_isdeclined`;
ALTER TABLE `trn_offertime` ADD COLUMN IF NOT EXISTS  `offertime_declinedate` datetime   AFTER `offertime_declineby`;


ALTER TABLE `trn_offertime` MODIFY COLUMN IF EXISTS  `offertime_code` varchar(50) NOT NULL   AFTER `offertime_id`;
ALTER TABLE `trn_offertime` MODIFY COLUMN IF EXISTS  `empl_id` varchar(36) NOT NULL   AFTER `offertime_code`;
ALTER TABLE `trn_offertime` MODIFY COLUMN IF EXISTS  `docapprv_id` varchar(36) NOT NULL   AFTER `empl_id`;
ALTER TABLE `trn_offertime` MODIFY COLUMN IF EXISTS  `offertime_rejectnotes` varchar(255)    AFTER `docapprv_id`;
ALTER TABLE `trn_offertime` MODIFY COLUMN IF EXISTS  `offertime_isrequest` tinyint(1) NOT NULL DEFAULT 0  AFTER `offertime_rejectnotes`;
ALTER TABLE `trn_offertime` MODIFY COLUMN IF EXISTS  `offertime_requestby` varchar(14)    AFTER `offertime_isrequest`;
ALTER TABLE `trn_offertime` MODIFY COLUMN IF EXISTS  `offertime_requestdate` datetime    AFTER `offertime_requestby`;
ALTER TABLE `trn_offertime` MODIFY COLUMN IF EXISTS  `offertime_isapproved` tinyint(1) NOT NULL DEFAULT 0  AFTER `offertime_requestdate`;
ALTER TABLE `trn_offertime` MODIFY COLUMN IF EXISTS  `offertime_approveby` varchar(14)    AFTER `offertime_isapproved`;
ALTER TABLE `trn_offertime` MODIFY COLUMN IF EXISTS  `offertime_approvedate` datetime    AFTER `offertime_approveby`;
ALTER TABLE `trn_offertime` MODIFY COLUMN IF EXISTS  `offertime_isdeclined` tinyint(1) NOT NULL DEFAULT 0  AFTER `offertime_approvedate`;
ALTER TABLE `trn_offertime` MODIFY COLUMN IF EXISTS  `offertime_declineby` varchar(14)    AFTER `offertime_isdeclined`;
ALTER TABLE `trn_offertime` MODIFY COLUMN IF EXISTS  `offertime_declinedate` datetime    AFTER `offertime_declineby`;


ALTER TABLE `trn_offertime` ADD CONSTRAINT `offertime_code` UNIQUE IF NOT EXISTS  (`offertime_code`);

ALTER TABLE `trn_offertime` ADD KEY IF NOT EXISTS `empl_id` (`empl_id`);
ALTER TABLE `trn_offertime` ADD KEY IF NOT EXISTS `docapprv_id` (`docapprv_id`);

ALTER TABLE `trn_offertime` ADD CONSTRAINT `fk_trn_offertime_mst_empl` FOREIGN KEY IF NOT EXISTS  (`empl_id`) REFERENCES `mst_empl` (`empl_id`);
ALTER TABLE `trn_offertime` ADD CONSTRAINT `fk_trn_offertime_mst_docapprv` FOREIGN KEY IF NOT EXISTS  (`docapprv_id`) REFERENCES `mst_docapprv` (`docapprv_id`);





CREATE TABLE IF NOT EXISTS `trn_offertimedt` (
	`offertimedt_id` varchar(36) NOT NULL , 
	`offertime_id` varchar(36) NOT NULL , 
	`offertimedt_dt` date NOT NULL , 
	`offertime_id_type` varchar(36)  , 
	`offertimedt_descr` varchar(255)  , 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	PRIMARY KEY (`offertimedt_id`)
) 
ENGINE=InnoDB
COMMENT='Offertime Date';


ALTER TABLE `trn_offertimedt` ADD COLUMN IF NOT EXISTS  `offertime_id` varchar(36) NOT NULL  AFTER `offertimedt_id`;
ALTER TABLE `trn_offertimedt` ADD COLUMN IF NOT EXISTS  `offertimedt_dt` date NOT NULL  AFTER `offertime_id`;
ALTER TABLE `trn_offertimedt` ADD COLUMN IF NOT EXISTS  `offertime_id_type` varchar(36)   AFTER `offertimedt_dt`;
ALTER TABLE `trn_offertimedt` ADD COLUMN IF NOT EXISTS  `offertimedt_descr` varchar(255)   AFTER `offertime_id_type`;


ALTER TABLE `trn_offertimedt` MODIFY COLUMN IF EXISTS  `offertime_id` varchar(36) NOT NULL   AFTER `offertimedt_id`;
ALTER TABLE `trn_offertimedt` MODIFY COLUMN IF EXISTS  `offertimedt_dt` date NOT NULL   AFTER `offertime_id`;
ALTER TABLE `trn_offertimedt` MODIFY COLUMN IF EXISTS  `offertime_id_type` varchar(36)    AFTER `offertimedt_dt`;
ALTER TABLE `trn_offertimedt` MODIFY COLUMN IF EXISTS  `offertimedt_descr` varchar(255)    AFTER `offertime_id_type`;



ALTER TABLE `trn_offertimedt` ADD KEY IF NOT EXISTS `offertime_id` (`offertime_id`);
ALTER TABLE `trn_offertimedt` ADD KEY IF NOT EXISTS `offertime_id_type` (`offertime_id_type`);

ALTER TABLE `trn_offertimedt` ADD CONSTRAINT `fk_trn_offertimedt_trn_offertime` FOREIGN KEY IF NOT EXISTS (`offertime_id`) REFERENCES `trn_offertime` (`offertime_id`);
ALTER TABLE `trn_offertimedt` ADD CONSTRAINT `fk_trn_offertimedt_mst_offertime` FOREIGN KEY IF NOT EXISTS  (`offertime_id_type`) REFERENCES `mst_offertime` (`offertime_id`);





