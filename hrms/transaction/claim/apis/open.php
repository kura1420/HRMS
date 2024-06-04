<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}

require_once __ROOT_DIR.'/core/sqlutil.php';
require_once __DIR__ . '/xapi.base.php';

if (is_file(__DIR__ .'/data-header-handler.php')) {
	require_once __DIR__ .'/data-header-handler.php';
}


use \FGTA4\exceptions\WebException;


/**
 * hrms/transaction/claim/apis/open.php
 *
 * ====
 * Open
 * ====
 * Menampilkan satu baris data/record sesuai PrimaryKey,
 * dari tabel header claim (trn_claim)
 *
 * Agung Nugroho <agung@fgta.net> http://www.fgta.net
 * Tangerang, 26 Maret 2021
 *
 * digenerate dengan FGTA4 generator
 * tanggal 04/06/2024
 */
$API = new class extends claimBase {
	
	public function execute($options) {
		$event = 'on-open';
		$tablename = 'trn_claim';
		$primarykey = 'claim_id';
		$userdata = $this->auth->session_get_user();

		$handlerclassname = "\\FGTA4\\apis\\claim_headerHandler";
		$hnd = null;
		if (class_exists($handlerclassname)) {
			$hnd = new claim_headerHandler($options);
			$hnd->caller = &$this;
			$hnd->db = $this->db;
			$hnd->auth = $this->auth;
			$hnd->reqinfo = $this->reqinfo;
			$hnd->event = $event;
		} else {
			$hnd = new \stdClass;
		}

		try {

			// cek apakah user boleh mengeksekusi API ini
			if (!$this->RequestIsAllowedFor($this->reqinfo, "open", $userdata->groups)) {
				throw new \Exception('your group authority is not allowed to do this action.');
			}

			if (method_exists(get_class($hnd), 'init')) {
				// init(object &$options) : void
				$hnd->init($options);
			}

			if (method_exists(get_class($hnd), 'PreCheckOpen')) {
				// PreCheckOpen($data, &$key, &$options)
				$hnd->PreCheckOpen($data, $key, $options);
			}

			$criteriaValues = [
				"claim_id" => " claim_id = :claim_id "
			];
			if (method_exists(get_class($hnd), 'buildOpenCriteriaValues')) {
				// buildOpenCriteriaValues(object $options, array &$criteriaValues) : void
				$hnd->buildOpenCriteriaValues($options, $criteriaValues);
			}
			$where = \FGTA4\utils\SqlUtility::BuildCriteria($options->criteria, $criteriaValues);
			$result = new \stdClass; 

			if (method_exists(get_class($hnd), 'prepareOpenData')) {
				// prepareOpenData(object $options, $criteriaValues) : void
				$hnd->prepareOpenData($options, $criteriaValues);
			}
			

			if (method_exists(get_class($hnd), 'prepareOpenData')) {
				// prepareOpenData(object $options, $criteriaValues) : void
				$hnd->prepareOpenData($options, $criteriaValues);
			}


			$sqlFieldList = [
				'claim_id' => 'A.`claim_id`', 'claim_code' => 'A.`claim_code`', 'activity_id' => 'A.`activity_id`', 'claim_descr' => 'A.`claim_descr`',
				'empl_id' => 'A.`empl_id`', 'claim_total' => 'A.`claim_total`', 'docapprv_id' => 'A.`docapprv_id`', 'claim_rejectnotes' => 'A.`claim_rejectnotes`',
				'claim_isrequest' => 'A.`claim_isrequest`', 'claim_requestby' => 'A.`claim_requestby`', 'claim_requestdate' => 'A.`claim_requestdate`', 'claim_isapproved' => 'A.`claim_isapproved`',
				'claim_approveby' => 'A.`claim_approveby`', 'claim_approvedate' => 'A.`claim_approvedate`', 'claim_isdeclined' => 'A.`claim_isdeclined`', 'claim_declineby' => 'A.`claim_declineby`',
				'claim_declinedate' => 'A.`claim_declinedate`', '_createby' => 'A.`_createby`', '_createdate' => 'A.`_createdate`', '_modifyby' => 'A.`_modifyby`',
				'_createby' => 'A.`_createby`', '_createdate' => 'A.`_createdate`', '_modifyby' => 'A.`_modifyby`', '_modifydate' => 'A.`_modifydate`'
			];
			$sqlFromTable = "trn_claim A";
			$sqlWhere = $where->sql;

			if (method_exists(get_class($hnd), 'SqlQueryOpenBuilder')) {
				// SqlQueryOpenBuilder(array &$sqlFieldList, string &$sqlFromTable, string &$sqlWhere, array &$params) : void
				$hnd->SqlQueryOpenBuilder($sqlFieldList, $sqlFromTable, $sqlWhere, $where->params);
			}
			$sqlFields = \FGTA4\utils\SqlUtility::generateSqlSelectFieldList($sqlFieldList);

			
			$sqlData = "
				select 
				$sqlFields 
				from 
				$sqlFromTable 
				$sqlWhere 
			";

			$stmt = $this->db->prepare($sqlData);
			$stmt->execute($where->params);
			$row  = $stmt->fetch(\PDO::FETCH_ASSOC);

			$record = [];
			foreach ($row as $key => $value) {
				$record[$key] = $value;
			}



			$result->record = array_merge($record, [
				
				// // jikalau ingin menambah atau edit field di result record, dapat dilakukan sesuai contoh sbb: 
				// 'tambahan' => 'dta',
				//'tanggal' => date("d/m/Y", strtotime($record['tanggal'])),
				//'gendername' => $record['gender']
				
				'activity_name' => \FGTA4\utils\SqlUtility::Lookup($record['activity_id'], $this->db, 'mst_activity', 'activity_id', 'activity_name'),
				'empl_fullname' => \FGTA4\utils\SqlUtility::Lookup($record['empl_id'], $this->db, 'mst_empl', 'empl_id', 'empl_fullname'),
				'docapprv_name' => \FGTA4\utils\SqlUtility::Lookup($record['docapprv_id'], $this->db, 'mst_docapprv', 'docapprv_id', 'docapprv_name'),
				'claim_requestby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_requestby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
				'claim_approveby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_approveby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
				'claim_declineby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_declineby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),


				'_createby' => \FGTA4\utils\SqlUtility::Lookup($record['_createby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
				'_modifyby' => \FGTA4\utils\SqlUtility::Lookup($record['_modifyby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),

			]);


			

			if (method_exists(get_class($hnd), 'DataOpen')) {
				//  DataOpen(array &$record) : void 
				$hnd->DataOpen($result->record);
			}

			return $result;
		} catch (\Exception $ex) {
			throw $ex;
		}
	}

};