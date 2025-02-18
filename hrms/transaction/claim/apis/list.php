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
 * hrms/transaction/claim/apis/list.php
 *
 * ========
 * DataList
 * ========
 * Menampilkan data-data pada tabel header claim (trn_claim)
 * sesuai dengan parameter yang dikirimkan melalui variable $option->criteria
 *
 * Agung Nugroho <agung@fgta.net> http://www.fgta.net
 * Tangerang, 26 Maret 2021
 *
 * digenerate dengan FGTA4 generator
 * tanggal 07/06/2024
 */
$API = new class extends claimBase {

	public function execute($options) {

		$userdata = $this->auth->session_get_user();

		$handlerclassname = "\\FGTA4\\apis\\claim_headerHandler";
		if (class_exists($handlerclassname)) {
			$hnd = new claim_headerHandler($options);
			$hnd->caller = &$this;
			$hnd->db = $this->db;
			$hnd->auth = $this->auth;
			$hnd->reqinfo = $this->reqinfo;
		} else {
			$hnd = new \stdClass;
		}


		try {
		
			// cek apakah user boleh mengeksekusi API ini
			if (!$this->RequestIsAllowedFor($this->reqinfo, "list", $userdata->groups)) {
				throw new \Exception('your group authority is not allowed to do this action.');
			}

			if (method_exists(get_class($hnd), 'init')) {
				// init(object &$options) : void
				$hnd->init($options);
			}

			$criteriaValues = [
				"search" => " A.claim_code LIKE CONCAT('%', :search, '%') "
			];

			if (method_exists(get_class($hnd), 'buildListCriteriaValues')) {
				// ** buildListCriteriaValues(object &$options, array &$criteriaValues) : void
				//    apabila akan modifikasi parameter2 untuk query
				//    $criteriaValues['fieldname'] = " A.fieldname = :fieldname";  <-- menambahkan field pada where dan memberi parameter value
				//    $criteriaValues['fieldname'] = "--";                         <-- memberi parameter value tanpa menambahkan pada where
				//    $criteriaValues['fieldname'] = null                          <-- tidak memberi efek pada query secara langsung, parameter digunakan untuk keperluan lain 
				//
				//    untuk memberikan nilai default apabila paramter tidak dikirim
				//    // \FGTA4\utils\SqlUtility::setDefaultCriteria($options->criteria, '--fieldscriteria--', '--value--');
				$hnd->buildListCriteriaValues($options, $criteriaValues);
			}

			$where = \FGTA4\utils\SqlUtility::BuildCriteria($options->criteria, $criteriaValues);
			
			$maxrow = 30;
			$offset = (property_exists($options, 'offset')) ? $options->offset : 0;

			/* prepare DbLayer Temporay Data Helper if needed */
			if (method_exists(get_class($hnd), 'prepareListData')) {
				// ** prepareListData(object $options, array $criteriaValues) : void
				//    misalnya perlu mebuat temporary table,
				//    untuk membuat query komplex dapat dibuat disini	
				$hnd->prepareListData($options, $criteriaValues);
			}


			/* Data Query Configuration */
			$sqlFieldList = [
				'claim_id' => 'A.`claim_id`', 'claim_code' => 'A.`claim_code`', 'activity_id' => 'A.`activity_id`', 'claim_descr' => 'A.`claim_descr`',
				'empl_id' => 'A.`empl_id`', 'claim_total' => 'A.`claim_total`', 'month_id' => 'A.`month_id`', 'docapprv_id' => 'A.`docapprv_id`',
				'claim_rejectnotes' => 'A.`claim_rejectnotes`', 'claim_isrequest' => 'A.`claim_isrequest`', 'claim_requestby' => 'A.`claim_requestby`', 'claim_requestdate' => 'A.`claim_requestdate`',
				'claim_isapproved' => 'A.`claim_isapproved`', 'claim_approveby' => 'A.`claim_approveby`', 'claim_approvedate' => 'A.`claim_approvedate`', 'claim_isapprovalprogress' => 'A.`claim_isapprovalprogress`',
				'claim_isdecline' => 'A.`claim_isdecline`', 'claim_declineby' => 'A.`claim_declineby`', 'claim_declinedate' => 'A.`claim_declinedate`', 'claim_ispayment' => 'A.`claim_ispayment`',
				'claim_paymentby' => 'A.`claim_paymentby`', 'claim_paymentdate' => 'A.`claim_paymentdate`', 'claim_executeby' => 'A.`claim_executeby`', 'claim_executedate' => 'A.`claim_executedate`',
				'_createby' => 'A.`_createby`', '_createdate' => 'A.`_createdate`', '_modifyby' => 'A.`_modifyby`', '_modifydate' => 'A.`_modifydate`'
			];
			$sqlFromTable = "trn_claim A";
			$sqlWhere = $where->sql;
			$sqlLimit = "LIMIT $maxrow OFFSET $offset";

			if (method_exists(get_class($hnd), 'SqlQueryListBuilder')) {
				// ** SqlQueryListBuilder(array &$sqlFieldList, string &$sqlFromTable, string &$sqlWhere, array &$params) : void
				//    menambah atau memodifikasi field-field yang akan ditampilkan
				//    apabila akan memodifikasi join table
				//    apabila akan memodifikasi nilai parameter
				$hnd->SqlQueryListBuilder($sqlFieldList, $sqlFromTable, $sqlWhere, $where->params);
			}
			
			// filter select columns
			if (!property_exists($options, 'selectFields')) {
				$options->selectFields = [];
			}
			$columsSelected = $this->SelectColumns($sqlFieldList, $options->selectFields);
			$sqlFields = \FGTA4\utils\SqlUtility::generateSqlSelectFieldList($columsSelected);


			/* Sort Configuration */
			if (!property_exists($options, 'sortData')) {
				$options->sortData = [];
			}
			if (!is_array($options->sortData)) {
				if (is_object($options->sortData)) {
					$options->sortData = (array)$options->sortData;
				} else {
					$options->sortData = [];
				}
			}

		


			if (method_exists(get_class($hnd), 'sortListOrder')) {
				// ** sortListOrder(array &$sortData) : void
				//    jika ada keperluan mengurutkan data
				//    $sortData['fieldname'] = 'ASC/DESC';
				$hnd->sortListOrder($options->sortData);
			}
			$sqlOrders = \FGTA4\utils\SqlUtility::generateSqlSelectSort($options->sortData);


			/* Compose SQL Query */
			$sqlCount = "select count(*) as n from $sqlFromTable $sqlWhere";
			$sqlData = "
				select 
				$sqlFields 
				from 
				$sqlFromTable 
				$sqlWhere 
				$sqlOrders 
				$sqlLimit
			";

			/* Execute Query: Count */
			$stmt = $this->db->prepare($sqlCount );
			$stmt->execute($where->params);
			$row  = $stmt->fetch(\PDO::FETCH_ASSOC);
			$total = (float) $row['n'];

			/* Execute Query: Retrieve Data */
			$stmt = $this->db->prepare($sqlData);
			$stmt->execute($where->params);
			$rows  = $stmt->fetchall(\PDO::FETCH_ASSOC);


			$handleloop = false;
			if (method_exists(get_class($hnd), 'DataListLooping')) {
				$handleloop = true;
			}

			/* Proces result */
			$records = [];
			foreach ($rows as $row) {
				$record = [];
				foreach ($row as $key => $value) {
					$record[$key] = $value;
				}


				/*
				$record = array_merge($record, [
					// // jikalau ingin menambah atau edit field di result record, dapat dilakukan sesuai contoh sbb: 
					//'tanggal' => date("d/m/y", strtotime($record['tanggal'])),
				 	//'tambahan' => 'dta'
					'activity_name' => \FGTA4\utils\SqlUtility::Lookup($record['activity_id'], $this->db, 'mst_activity', 'activity_id', 'activity_name'),
					'empl_fullname' => \FGTA4\utils\SqlUtility::Lookup($record['empl_id'], $this->db, 'mst_empl', 'empl_id', 'empl_fullname'),
					'month_name' => \FGTA4\utils\SqlUtility::Lookup($record['month_id'], $this->db, 'mst_month', 'month_id', 'month_name'),
					'docapprv_name' => \FGTA4\utils\SqlUtility::Lookup($record['docapprv_id'], $this->db, 'mst_docapprv', 'docapprv_id', 'docapprv_name'),
					'claim_requestby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_requestby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
					'claim_approveby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_approveby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
					'claim_declineby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_declineby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
					'claim_paymentby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_paymentby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
					'claim_executeby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_executeby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
					 
				]);
				*/


				// lookup data id yang refer ke table lain
				$this->addFields('activity_name', 'activity_id', $record, 'mst_activity', 'activity_name', 'activity_id');
				$this->addFields('empl_fullname', 'empl_id', $record, 'mst_empl', 'empl_fullname', 'empl_id');
				$this->addFields('month_name', 'month_id', $record, 'mst_month', 'month_name', 'month_id');
				$this->addFields('docapprv_name', 'docapprv_id', $record, 'mst_docapprv', 'docapprv_name', 'docapprv_id');
				$this->addFields('claim_requestby', 'claim_requestby', $record, $GLOBALS['MAIN_USERTABLE'], 'user_fullname', 'user_id');
				$this->addFields('claim_approveby', 'claim_approveby', $record, $GLOBALS['MAIN_USERTABLE'], 'user_fullname', 'user_id');
				$this->addFields('claim_declineby', 'claim_declineby', $record, $GLOBALS['MAIN_USERTABLE'], 'user_fullname', 'user_id');
				$this->addFields('claim_paymentby', 'claim_paymentby', $record, $GLOBALS['MAIN_USERTABLE'], 'user_fullname', 'user_id');
				$this->addFields('claim_executeby', 'claim_executeby', $record, $GLOBALS['MAIN_USERTABLE'], 'user_fullname', 'user_id');
					 


				if ($handleloop) {
					// ** DataListLooping(array &$record) : void
					//    apabila akan menambahkan field di record
					$hnd->DataListLooping($record);
				}

				array_push($records, $record);
			}

			/* modify and finalize records */
			if (method_exists(get_class($hnd), 'DataListFinal')) {
				// ** DataListFinal(array &$records) : void
				//    finalisasi data list
				$hnd->DataListFinal($records);
			}

			// kembalikan hasilnya
			$result = new \stdClass; 
			$result->total = $total;
			$result->offset = $offset + $maxrow;
			$result->maxrow = $maxrow;
			$result->records = $records;
			return $result;
		} catch (\Exception $ex) {
			throw $ex;
		}
	}

};