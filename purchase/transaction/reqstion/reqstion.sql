-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `trn_reqstion`;
-- drop table if exists `trn_reqstionitm`;


CREATE TABLE IF NOT EXISTS `trn_reqstion` (
	`reqstion_id` varchar(36) NOT NULL , 
	`reqstion_code` varchar(50) NOT NULL , 
	`empl_id` varchar(36) NOT NULL , 
	`reqstion_subject` varchar(255) NOT NULL , 
	`reqstion_dt` date NOT NULL , 
	`reqstion_total` int(11) NOT NULL DEFAULT 0, 
	`reqstion_descr` varchar(255) NOT NULL , 
	`docapprv_id` varchar(36) NOT NULL , 
	`reqstion_rejectnotes` varchar(255)  , 
	`reqstion_isrequest` tinyint(1) NOT NULL DEFAULT 0, 
	`reqstion_requestby` varchar(14)  , 
	`reqstion_requestdate` datetime  , 
	`reqstion_isapproved` tinyint(1) NOT NULL DEFAULT 0, 
	`reqstion_approveby` varchar(14)  , 
	`reqstion_approvedate` datetime  , 
	`reqstion_isapprovalprogress` tinyint(1) NOT NULL DEFAULT 0, 
	`reqstion_isdecline` tinyint(1) NOT NULL DEFAULT 0, 
	`reqstion_declineby` varchar(14)  , 
	`reqstion_declinedate` datetime  , 
	`reqstion_ispayment` tinyint(1) NOT NULL DEFAULT 0, 
	`reqstion_executeby` varchar(14)  , 
	`reqstion_executedate` datetime  , 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `reqstion_code` (`reqstion_code`),
	PRIMARY KEY (`reqstion_id`)
) 
ENGINE=InnoDB
COMMENT='Transaction Purchase Requisition';


ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_code` varchar(50) NOT NULL  AFTER `reqstion_id`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `empl_id` varchar(36) NOT NULL  AFTER `reqstion_code`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_subject` varchar(255) NOT NULL  AFTER `empl_id`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_dt` date NOT NULL  AFTER `reqstion_subject`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_total` int(11) NOT NULL DEFAULT 0 AFTER `reqstion_dt`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_descr` varchar(255) NOT NULL  AFTER `reqstion_total`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `docapprv_id` varchar(36) NOT NULL  AFTER `reqstion_descr`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_rejectnotes` varchar(255)   AFTER `docapprv_id`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_isrequest` tinyint(1) NOT NULL DEFAULT 0 AFTER `reqstion_rejectnotes`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_requestby` varchar(14)   AFTER `reqstion_isrequest`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_requestdate` datetime   AFTER `reqstion_requestby`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_isapproved` tinyint(1) NOT NULL DEFAULT 0 AFTER `reqstion_requestdate`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_approveby` varchar(14)   AFTER `reqstion_isapproved`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_approvedate` datetime   AFTER `reqstion_approveby`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_isapprovalprogress` tinyint(1) NOT NULL DEFAULT 0 AFTER `reqstion_approvedate`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_isdecline` tinyint(1) NOT NULL DEFAULT 0 AFTER `reqstion_isapprovalprogress`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_declineby` varchar(14)   AFTER `reqstion_isdecline`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_declinedate` datetime   AFTER `reqstion_declineby`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_ispayment` tinyint(1) NOT NULL DEFAULT 0 AFTER `reqstion_declinedate`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_executeby` varchar(14)   AFTER `reqstion_ispayment`;
ALTER TABLE `trn_reqstion` ADD COLUMN IF NOT EXISTS  `reqstion_executedate` datetime   AFTER `reqstion_executeby`;


ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_code` varchar(50) NOT NULL   AFTER `reqstion_id`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `empl_id` varchar(36) NOT NULL   AFTER `reqstion_code`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_subject` varchar(255) NOT NULL   AFTER `empl_id`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_dt` date NOT NULL   AFTER `reqstion_subject`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_total` int(11) NOT NULL DEFAULT 0  AFTER `reqstion_dt`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_descr` varchar(255) NOT NULL   AFTER `reqstion_total`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `docapprv_id` varchar(36) NOT NULL   AFTER `reqstion_descr`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_rejectnotes` varchar(255)    AFTER `docapprv_id`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_isrequest` tinyint(1) NOT NULL DEFAULT 0  AFTER `reqstion_rejectnotes`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_requestby` varchar(14)    AFTER `reqstion_isrequest`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_requestdate` datetime    AFTER `reqstion_requestby`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_isapproved` tinyint(1) NOT NULL DEFAULT 0  AFTER `reqstion_requestdate`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_approveby` varchar(14)    AFTER `reqstion_isapproved`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_approvedate` datetime    AFTER `reqstion_approveby`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_isapprovalprogress` tinyint(1) NOT NULL DEFAULT 0  AFTER `reqstion_approvedate`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_isdecline` tinyint(1) NOT NULL DEFAULT 0  AFTER `reqstion_isapprovalprogress`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_declineby` varchar(14)    AFTER `reqstion_isdecline`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_declinedate` datetime    AFTER `reqstion_declineby`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_ispayment` tinyint(1) NOT NULL DEFAULT 0  AFTER `reqstion_declinedate`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_executeby` varchar(14)    AFTER `reqstion_ispayment`;
ALTER TABLE `trn_reqstion` MODIFY COLUMN IF EXISTS  `reqstion_executedate` datetime    AFTER `reqstion_executeby`;


ALTER TABLE `trn_reqstion` ADD CONSTRAINT `reqstion_code` UNIQUE IF NOT EXISTS  (`reqstion_code`);

ALTER TABLE `trn_reqstion` ADD KEY IF NOT EXISTS `empl_id` (`empl_id`);
ALTER TABLE `trn_reqstion` ADD KEY IF NOT EXISTS `docapprv_id` (`docapprv_id`);

ALTER TABLE `trn_reqstion` ADD CONSTRAINT `fk_trn_reqstion_mst_empl` FOREIGN KEY IF NOT EXISTS  (`empl_id`) REFERENCES `mst_empl` (`empl_id`);
ALTER TABLE `trn_reqstion` ADD CONSTRAINT `fk_trn_reqstion_mst_docapprv` FOREIGN KEY IF NOT EXISTS  (`docapprv_id`) REFERENCES `mst_docapprv` (`docapprv_id`);





CREATE TABLE IF NOT EXISTS `trn_reqstionitm` (
	`reqstionitm_id` varchar(36) NOT NULL , 
	`typereq_id` varchar(36) NOT NULL , 
	`reqstionitm_name` varchar(255) NOT NULL , 
	`reqstionitm_val` int(11) NOT NULL , 
	`reqstionitm_file` varchar(255)  , 
	`reqstionitm_descr` varchar(255)  , 
	`reqstion_id` varchar(36) NOT NULL , 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	PRIMARY KEY (`reqstionitm_id`)
) 
ENGINE=InnoDB
COMMENT='Purchase Requisition Item';


ALTER TABLE `trn_reqstionitm` ADD COLUMN IF NOT EXISTS  `typereq_id` varchar(36) NOT NULL  AFTER `reqstionitm_id`;
ALTER TABLE `trn_reqstionitm` ADD COLUMN IF NOT EXISTS  `reqstionitm_name` varchar(255) NOT NULL  AFTER `typereq_id`;
ALTER TABLE `trn_reqstionitm` ADD COLUMN IF NOT EXISTS  `reqstionitm_val` int(11) NOT NULL  AFTER `reqstionitm_name`;
ALTER TABLE `trn_reqstionitm` ADD COLUMN IF NOT EXISTS  `reqstionitm_file` varchar(255)   AFTER `reqstionitm_val`;
ALTER TABLE `trn_reqstionitm` ADD COLUMN IF NOT EXISTS  `reqstionitm_descr` varchar(255)   AFTER `reqstionitm_file`;
ALTER TABLE `trn_reqstionitm` ADD COLUMN IF NOT EXISTS  `reqstion_id` varchar(36) NOT NULL  AFTER `reqstionitm_descr`;


ALTER TABLE `trn_reqstionitm` MODIFY COLUMN IF EXISTS  `typereq_id` varchar(36) NOT NULL   AFTER `reqstionitm_id`;
ALTER TABLE `trn_reqstionitm` MODIFY COLUMN IF EXISTS  `reqstionitm_name` varchar(255) NOT NULL   AFTER `typereq_id`;
ALTER TABLE `trn_reqstionitm` MODIFY COLUMN IF EXISTS  `reqstionitm_val` int(11) NOT NULL   AFTER `reqstionitm_name`;
ALTER TABLE `trn_reqstionitm` MODIFY COLUMN IF EXISTS  `reqstionitm_file` varchar(255)    AFTER `reqstionitm_val`;
ALTER TABLE `trn_reqstionitm` MODIFY COLUMN IF EXISTS  `reqstionitm_descr` varchar(255)    AFTER `reqstionitm_file`;
ALTER TABLE `trn_reqstionitm` MODIFY COLUMN IF EXISTS  `reqstion_id` varchar(36) NOT NULL   AFTER `reqstionitm_descr`;



ALTER TABLE `trn_reqstionitm` ADD KEY IF NOT EXISTS `typereq_id` (`typereq_id`);
ALTER TABLE `trn_reqstionitm` ADD KEY IF NOT EXISTS `reqstion_id` (`reqstion_id`);

ALTER TABLE `trn_reqstionitm` ADD CONSTRAINT `fk_trn_reqstionitm_mst_typereq` FOREIGN KEY IF NOT EXISTS  (`typereq_id`) REFERENCES `mst_typereq` (`typereq_id`);
ALTER TABLE `trn_reqstionitm` ADD CONSTRAINT `fk_trn_reqstionitm_trn_reqstion` FOREIGN KEY IF NOT EXISTS (`reqstion_id`) REFERENCES `trn_reqstion` (`reqstion_id`);





