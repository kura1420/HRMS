-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `trn_ovrtime`;
-- drop table if exists `trn_ovrtimedt`;


CREATE TABLE IF NOT EXISTS `trn_ovrtime` (
	`ovrtime_id` varchar(36) NOT NULL , 
	`ovrtime_code` varchar(50) NOT NULL , 
	`empl_id` varchar(36) NOT NULL , 
	`docapprv_id` varchar(36) NOT NULL , 
	`ovrtime_rejectnotes` varchar(255)  , 
	`ovrtime_isrequest` tinyint(1) NOT NULL DEFAULT 0, 
	`ovrtime_requestby` varchar(14)  , 
	`ovrtime_requestdate` datetime  , 
	`ovrtime_isapproved` tinyint(1) NOT NULL DEFAULT 0, 
	`ovrtime_approveby` varchar(14)  , 
	`ovrtime_approvedate` datetime  , 
	`ovrtime_isapprovalprogress` tinyint(1) NOT NULL DEFAULT 0, 
	`ovrtime_isdecline` tinyint(1) NOT NULL DEFAULT 0, 
	`ovrtime_declineby` varchar(14)  , 
	`ovrtime_declinedate` datetime  , 
	`ovrtime_ispayment` tinyint(1) NOT NULL DEFAULT 0, 
	`ovrtime_paymentby` varchar(14)  , 
	`ovrtime_paymentdate` datetime  , 
	`ovrtime_executeby` varchar(14)  , 
	`ovrtime_executedate` datetime  , 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `ovrtime_code` (`ovrtime_code`),
	PRIMARY KEY (`ovrtime_id`)
) 
ENGINE=InnoDB
COMMENT='Overtime';


ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_code` varchar(50) NOT NULL  AFTER `ovrtime_id`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `empl_id` varchar(36) NOT NULL  AFTER `ovrtime_code`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `docapprv_id` varchar(36) NOT NULL  AFTER `empl_id`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_rejectnotes` varchar(255)   AFTER `docapprv_id`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_isrequest` tinyint(1) NOT NULL DEFAULT 0 AFTER `ovrtime_rejectnotes`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_requestby` varchar(14)   AFTER `ovrtime_isrequest`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_requestdate` datetime   AFTER `ovrtime_requestby`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_isapproved` tinyint(1) NOT NULL DEFAULT 0 AFTER `ovrtime_requestdate`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_approveby` varchar(14)   AFTER `ovrtime_isapproved`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_approvedate` datetime   AFTER `ovrtime_approveby`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_isapprovalprogress` tinyint(1) NOT NULL DEFAULT 0 AFTER `ovrtime_approvedate`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_isdecline` tinyint(1) NOT NULL DEFAULT 0 AFTER `ovrtime_isapprovalprogress`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_declineby` varchar(14)   AFTER `ovrtime_isdecline`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_declinedate` datetime   AFTER `ovrtime_declineby`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_ispayment` tinyint(1) NOT NULL DEFAULT 0 AFTER `ovrtime_declinedate`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_paymentby` varchar(14)   AFTER `ovrtime_ispayment`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_paymentdate` datetime   AFTER `ovrtime_paymentby`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_executeby` varchar(14)   AFTER `ovrtime_paymentdate`;
ALTER TABLE `trn_ovrtime` ADD COLUMN IF NOT EXISTS  `ovrtime_executedate` datetime   AFTER `ovrtime_executeby`;


ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_code` varchar(50) NOT NULL   AFTER `ovrtime_id`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `empl_id` varchar(36) NOT NULL   AFTER `ovrtime_code`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `docapprv_id` varchar(36) NOT NULL   AFTER `empl_id`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_rejectnotes` varchar(255)    AFTER `docapprv_id`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_isrequest` tinyint(1) NOT NULL DEFAULT 0  AFTER `ovrtime_rejectnotes`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_requestby` varchar(14)    AFTER `ovrtime_isrequest`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_requestdate` datetime    AFTER `ovrtime_requestby`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_isapproved` tinyint(1) NOT NULL DEFAULT 0  AFTER `ovrtime_requestdate`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_approveby` varchar(14)    AFTER `ovrtime_isapproved`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_approvedate` datetime    AFTER `ovrtime_approveby`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_isapprovalprogress` tinyint(1) NOT NULL DEFAULT 0  AFTER `ovrtime_approvedate`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_isdecline` tinyint(1) NOT NULL DEFAULT 0  AFTER `ovrtime_isapprovalprogress`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_declineby` varchar(14)    AFTER `ovrtime_isdecline`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_declinedate` datetime    AFTER `ovrtime_declineby`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_ispayment` tinyint(1) NOT NULL DEFAULT 0  AFTER `ovrtime_declinedate`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_paymentby` varchar(14)    AFTER `ovrtime_ispayment`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_paymentdate` datetime    AFTER `ovrtime_paymentby`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_executeby` varchar(14)    AFTER `ovrtime_paymentdate`;
ALTER TABLE `trn_ovrtime` MODIFY COLUMN IF EXISTS  `ovrtime_executedate` datetime    AFTER `ovrtime_executeby`;


ALTER TABLE `trn_ovrtime` ADD CONSTRAINT `ovrtime_code` UNIQUE IF NOT EXISTS  (`ovrtime_code`);

ALTER TABLE `trn_ovrtime` ADD KEY IF NOT EXISTS `empl_id` (`empl_id`);
ALTER TABLE `trn_ovrtime` ADD KEY IF NOT EXISTS `docapprv_id` (`docapprv_id`);

ALTER TABLE `trn_ovrtime` ADD CONSTRAINT `fk_trn_ovrtime_mst_empl` FOREIGN KEY IF NOT EXISTS  (`empl_id`) REFERENCES `mst_empl` (`empl_id`);
ALTER TABLE `trn_ovrtime` ADD CONSTRAINT `fk_trn_ovrtime_mst_docapprv` FOREIGN KEY IF NOT EXISTS  (`docapprv_id`) REFERENCES `mst_docapprv` (`docapprv_id`);





CREATE TABLE IF NOT EXISTS `trn_ovrtimedt` (
	`ovrtimedt_id` varchar(36) NOT NULL , 
	`ovrtime_id` varchar(36) NOT NULL , 
	`ovrtimedt_dt` date NOT NULL , 
	`ovrtime_id_type` varchar(36)  , 
	`ovrtimedt_descr` varchar(255)  , 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	PRIMARY KEY (`ovrtimedt_id`)
) 
ENGINE=InnoDB
COMMENT='Overtime Date';


ALTER TABLE `trn_ovrtimedt` ADD COLUMN IF NOT EXISTS  `ovrtime_id` varchar(36) NOT NULL  AFTER `ovrtimedt_id`;
ALTER TABLE `trn_ovrtimedt` ADD COLUMN IF NOT EXISTS  `ovrtimedt_dt` date NOT NULL  AFTER `ovrtime_id`;
ALTER TABLE `trn_ovrtimedt` ADD COLUMN IF NOT EXISTS  `ovrtime_id_type` varchar(36)   AFTER `ovrtimedt_dt`;
ALTER TABLE `trn_ovrtimedt` ADD COLUMN IF NOT EXISTS  `ovrtimedt_descr` varchar(255)   AFTER `ovrtime_id_type`;


ALTER TABLE `trn_ovrtimedt` MODIFY COLUMN IF EXISTS  `ovrtime_id` varchar(36) NOT NULL   AFTER `ovrtimedt_id`;
ALTER TABLE `trn_ovrtimedt` MODIFY COLUMN IF EXISTS  `ovrtimedt_dt` date NOT NULL   AFTER `ovrtime_id`;
ALTER TABLE `trn_ovrtimedt` MODIFY COLUMN IF EXISTS  `ovrtime_id_type` varchar(36)    AFTER `ovrtimedt_dt`;
ALTER TABLE `trn_ovrtimedt` MODIFY COLUMN IF EXISTS  `ovrtimedt_descr` varchar(255)    AFTER `ovrtime_id_type`;



ALTER TABLE `trn_ovrtimedt` ADD KEY IF NOT EXISTS `ovrtime_id` (`ovrtime_id`);
ALTER TABLE `trn_ovrtimedt` ADD KEY IF NOT EXISTS `ovrtime_id_type` (`ovrtime_id_type`);

ALTER TABLE `trn_ovrtimedt` ADD CONSTRAINT `fk_trn_ovrtimedt_trn_ovrtime` FOREIGN KEY IF NOT EXISTS (`ovrtime_id`) REFERENCES `trn_ovrtime` (`ovrtime_id`);
ALTER TABLE `trn_ovrtimedt` ADD CONSTRAINT `fk_trn_ovrtimedt_mst_ovrtime` FOREIGN KEY IF NOT EXISTS  (`ovrtime_id_type`) REFERENCES `mst_ovrtime` (`ovrtime_id`);





