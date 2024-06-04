-- SET FOREIGN_KEY_CHECKS=0;

-- drop table if exists `trn_claim`;
-- drop table if exists `trn_claimdt`;


CREATE TABLE IF NOT EXISTS `trn_claim` (
	`claim_id` varchar(36) NOT NULL , 
	`claim_code` varchar(50) NOT NULL , 
	`activity_id` varchar(36) NOT NULL , 
	`claim_descr` varchar(255)  , 
	`empl_id` varchar(36) NOT NULL , 
	`docapprv_id` varchar(36) NOT NULL , 
	`claim_rejectnotes` varchar(255)  , 
	`claim_isrequest` tinyint(1) NOT NULL DEFAULT 0, 
	`claim_requestby` varchar(14)  , 
	`claim_requestdate` datetime  , 
	`claim_isapproved` tinyint(1) NOT NULL DEFAULT 0, 
	`claim_approveby` varchar(14)  , 
	`claim_approvedate` datetime  , 
	`claim_isdeclined` tinyint(1) NOT NULL DEFAULT 0, 
	`claim_declineby` varchar(14)  , 
	`claim_declinedate` datetime  , 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	UNIQUE KEY `claim_code` (`claim_code`),
	PRIMARY KEY (`claim_id`)
) 
ENGINE=InnoDB
COMMENT='Claim';


ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `claim_code` varchar(50) NOT NULL  AFTER `claim_id`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `activity_id` varchar(36) NOT NULL  AFTER `claim_code`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `claim_descr` varchar(255)   AFTER `activity_id`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `empl_id` varchar(36) NOT NULL  AFTER `claim_descr`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `docapprv_id` varchar(36) NOT NULL  AFTER `empl_id`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `claim_rejectnotes` varchar(255)   AFTER `docapprv_id`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `claim_isrequest` tinyint(1) NOT NULL DEFAULT 0 AFTER `claim_rejectnotes`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `claim_requestby` varchar(14)   AFTER `claim_isrequest`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `claim_requestdate` datetime   AFTER `claim_requestby`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `claim_isapproved` tinyint(1) NOT NULL DEFAULT 0 AFTER `claim_requestdate`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `claim_approveby` varchar(14)   AFTER `claim_isapproved`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `claim_approvedate` datetime   AFTER `claim_approveby`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `claim_isdeclined` tinyint(1) NOT NULL DEFAULT 0 AFTER `claim_approvedate`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `claim_declineby` varchar(14)   AFTER `claim_isdeclined`;
ALTER TABLE `trn_claim` ADD COLUMN IF NOT EXISTS  `claim_declinedate` datetime   AFTER `claim_declineby`;


ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `claim_code` varchar(50) NOT NULL   AFTER `claim_id`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `activity_id` varchar(36) NOT NULL   AFTER `claim_code`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `claim_descr` varchar(255)    AFTER `activity_id`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `empl_id` varchar(36) NOT NULL   AFTER `claim_descr`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `docapprv_id` varchar(36) NOT NULL   AFTER `empl_id`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `claim_rejectnotes` varchar(255)    AFTER `docapprv_id`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `claim_isrequest` tinyint(1) NOT NULL DEFAULT 0  AFTER `claim_rejectnotes`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `claim_requestby` varchar(14)    AFTER `claim_isrequest`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `claim_requestdate` datetime    AFTER `claim_requestby`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `claim_isapproved` tinyint(1) NOT NULL DEFAULT 0  AFTER `claim_requestdate`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `claim_approveby` varchar(14)    AFTER `claim_isapproved`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `claim_approvedate` datetime    AFTER `claim_approveby`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `claim_isdeclined` tinyint(1) NOT NULL DEFAULT 0  AFTER `claim_approvedate`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `claim_declineby` varchar(14)    AFTER `claim_isdeclined`;
ALTER TABLE `trn_claim` MODIFY COLUMN IF EXISTS  `claim_declinedate` datetime    AFTER `claim_declineby`;


ALTER TABLE `trn_claim` ADD CONSTRAINT `claim_code` UNIQUE IF NOT EXISTS  (`claim_code`);

ALTER TABLE `trn_claim` ADD KEY IF NOT EXISTS `activity_id` (`activity_id`);
ALTER TABLE `trn_claim` ADD KEY IF NOT EXISTS `empl_id` (`empl_id`);
ALTER TABLE `trn_claim` ADD KEY IF NOT EXISTS `docapprv_id` (`docapprv_id`);

ALTER TABLE `trn_claim` ADD CONSTRAINT `fk_trn_claim_mst_activity` FOREIGN KEY IF NOT EXISTS  (`activity_id`) REFERENCES `mst_activity` (`activity_id`);
ALTER TABLE `trn_claim` ADD CONSTRAINT `fk_trn_claim_mst_empl` FOREIGN KEY IF NOT EXISTS  (`empl_id`) REFERENCES `mst_empl` (`empl_id`);
ALTER TABLE `trn_claim` ADD CONSTRAINT `fk_trn_claim_mst_docapprv` FOREIGN KEY IF NOT EXISTS  (`docapprv_id`) REFERENCES `mst_docapprv` (`docapprv_id`);





CREATE TABLE IF NOT EXISTS `trn_claimdt` (
	`claimdt_id` varchar(36) NOT NULL , 
	`claim_id` varchar(36) NOT NULL , 
	`claimdt_dt` date NOT NULL , 
	`claimdt_file` varchar(255) NOT NULL , 
	`claimdt_descr` varchar(255)  , 
	`_createby` varchar(14) NOT NULL , 
	`_createdate` datetime NOT NULL DEFAULT current_timestamp(), 
	`_modifyby` varchar(14)  , 
	`_modifydate` datetime  , 
	PRIMARY KEY (`claimdt_id`)
) 
ENGINE=InnoDB
COMMENT='Claim Date';


ALTER TABLE `trn_claimdt` ADD COLUMN IF NOT EXISTS  `claim_id` varchar(36) NOT NULL  AFTER `claimdt_id`;
ALTER TABLE `trn_claimdt` ADD COLUMN IF NOT EXISTS  `claimdt_dt` date NOT NULL  AFTER `claim_id`;
ALTER TABLE `trn_claimdt` ADD COLUMN IF NOT EXISTS  `claimdt_file` varchar(255) NOT NULL  AFTER `claimdt_dt`;
ALTER TABLE `trn_claimdt` ADD COLUMN IF NOT EXISTS  `claimdt_descr` varchar(255)   AFTER `claimdt_file`;


ALTER TABLE `trn_claimdt` MODIFY COLUMN IF EXISTS  `claim_id` varchar(36) NOT NULL   AFTER `claimdt_id`;
ALTER TABLE `trn_claimdt` MODIFY COLUMN IF EXISTS  `claimdt_dt` date NOT NULL   AFTER `claim_id`;
ALTER TABLE `trn_claimdt` MODIFY COLUMN IF EXISTS  `claimdt_file` varchar(255) NOT NULL   AFTER `claimdt_dt`;
ALTER TABLE `trn_claimdt` MODIFY COLUMN IF EXISTS  `claimdt_descr` varchar(255)    AFTER `claimdt_file`;



ALTER TABLE `trn_claimdt` ADD KEY IF NOT EXISTS `claim_id` (`claim_id`);

ALTER TABLE `trn_claimdt` ADD CONSTRAINT `fk_trn_claimdt_trn_claim` FOREIGN KEY IF NOT EXISTS (`claim_id`) REFERENCES `trn_claim` (`claim_id`);





