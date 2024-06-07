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
 * hrms/transaction/ovrtime/apis/open.php
 *
 * ====
 * Open
 * ====
 * Menampilkan satu baris data/record sesuai PrimaryKey,
 * dari tabel header ovrtime (trn_ovrtime)
 *
 * Agung Nugroho <agung@fgta.net> http://www.fgta.net
 * Tangerang, 26 Maret 2021
 *
 * digenerate dengan FGTA4 generator
 * tanggal 07/06/2024
 */
$API = new class extends ovrtimeBase {
	
	public function execute($options) {
		$event = 'on-open';
		$tablename = 'trn_ovrtime';
		$primarykey = 'ovrtime_id';
		$userdata = $this->auth->session_get_user();

		$handlerclassname = "\\FGTA4\\apis\\ovrtime_headerHandler";
		$hnd = null;
		if (class_exists($handlerclassname)) {
			$hnd = new ovrtime_headerHandler($options);
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
				"ovrtime_id" => " ovrtime_id = :ovrtime_id "
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
				'ovrtime_id' => 'A.`ovrtime_id`', 'ovrtime_code' => 'A.`ovrtime_code`', 'empl_id' => 'A.`empl_id`', 'docapprv_id' => 'A.`docapprv_id`',
				'ovrtime_rejectnotes' => 'A.`ovrtime_rejectnotes`', 'ovrtime_isrequest' => 'A.`ovrtime_isrequest`', 'ovrtime_requestby' => 'A.`ovrtime_requestby`', 'ovrtime_requestdate' => 'A.`ovrtime_requestdate`',
				'ovrtime_isapproved' => 'A.`ovrtime_isapproved`', 'ovrtime_approveby' => 'A.`ovrtime_approveby`', 'ovrtime_approvedate' => 'A.`ovrtime_approvedate`', 'ovrtime_isapprovalprogress' => 'A.`ovrtime_isapprovalprogress`',
				'ovrtime_isdecline' => 'A.`ovrtime_isdecline`', 'ovrtime_declineby' => 'A.`ovrtime_declineby`', 'ovrtime_declinedate' => 'A.`ovrtime_declinedate`', 'ovrtime_ispayment' => 'A.`ovrtime_ispayment`',
				'ovrtime_paymentby' => 'A.`ovrtime_paymentby`', 'ovrtime_paymentdate' => 'A.`ovrtime_paymentdate`', 'ovrtime_executeby' => 'A.`ovrtime_executeby`', 'ovrtime_executedate' => 'A.`ovrtime_executedate`',
				'_createby' => 'A.`_createby`', '_createdate' => 'A.`_createdate`', '_modifyby' => 'A.`_modifyby`', '_modifydate' => 'A.`_modifydate`'
			];
			$sqlFromTable = "trn_ovrtime A";
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
				
				'empl_fullname' => \FGTA4\utils\SqlUtility::Lookup($record['empl_id'], $this->db, 'mst_empl', 'empl_id', 'empl_fullname'),
				'docapprv_name' => \FGTA4\utils\SqlUtility::Lookup($record['docapprv_id'], $this->db, 'mst_docapprv', 'docapprv_id', 'docapprv_name'),
				'ovrtime_requestby' => \FGTA4\utils\SqlUtility::Lookup($record['ovrtime_requestby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
				'ovrtime_approveby' => \FGTA4\utils\SqlUtility::Lookup($record['ovrtime_approveby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
				'ovrtime_declineby' => \FGTA4\utils\SqlUtility::Lookup($record['ovrtime_declineby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
				'ovrtime_paymentby' => \FGTA4\utils\SqlUtility::Lookup($record['ovrtime_paymentby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
				'ovrtime_executeby' => \FGTA4\utils\SqlUtility::Lookup($record['ovrtime_executeby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),


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