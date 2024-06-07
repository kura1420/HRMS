<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}

require_once __ROOT_DIR.'/core/sqlutil.php';
// require_once __ROOT_DIR . "/core/sequencer.php";
require_once __DIR__ . '/xapi.base.php';

if (is_file(__DIR__ .'/data-header-handler.php')) {
	require_once __DIR__ .'/data-header-handler.php';
}


use \FGTA4\exceptions\WebException;
// use \FGTA4\utils\Sequencer;



/**
 * hrms/transaction/ovrtime/apis/save.php
 *
 * ====
 * Save
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
	
	public function execute($data, $options) {
		$event = 'on-save';
		$tablename = 'trn_ovrtime';
		$primarykey = 'ovrtime_id';
		$autoid = $options->autoid;
		$datastate = $data->_state;
		$userdata = $this->auth->session_get_user();

		$handlerclassname = "\\FGTA4\\apis\\ovrtime_headerHandler";
		$hnd = null;
		if (class_exists($handlerclassname)) {
			$hnd = new ovrtime_headerHandler($options);
			$hnd->caller = &$this;
			$hnd->db = &$this->db;
			$hnd->auth = $this->auth;
			$hnd->reqinfo = $this->reqinfo;
			$hnd->event = $event;
		} else {
			$hnd = new \stdClass;
		}

		try {

			// cek apakah user boleh mengeksekusi API ini
			if (!$this->RequestIsAllowedFor($this->reqinfo, "save", $userdata->groups)) {
				throw new \Exception('your group authority is not allowed to do this action.');
			}

			if (method_exists(get_class($hnd), 'init')) {
				// init(object &$options) : void
				$hnd->init($options);
			}

			$result = new \stdClass; 
			
			$key = new \stdClass;
			$obj = new \stdClass;
			foreach ($data as $fieldname => $value) {
				if ($fieldname=='_state') { continue; }
				if ($fieldname==$primarykey) {
					$key->{$fieldname} = $value;
				}
				$obj->{$fieldname} = $value;
			}

			// apabila ada tanggal, ubah ke format sql sbb:
			// $obj->tanggal = (\DateTime::createFromFormat('d/m/Y',$obj->tanggal))->format('Y-m-d');

			$obj->ovrtime_code = strtoupper($obj->ovrtime_code);


			if ($obj->ovrtime_rejectnotes=='') { $obj->ovrtime_rejectnotes = '--NULL--'; }


			unset($obj->ovrtime_rejectnotes);
			unset($obj->ovrtime_isrequest);
			unset($obj->ovrtime_requestby);
			unset($obj->ovrtime_requestdate);
			unset($obj->ovrtime_isapproved);
			unset($obj->ovrtime_approveby);
			unset($obj->ovrtime_approvedate);
			unset($obj->ovrtime_isapprovalprogress);
			unset($obj->ovrtime_isdecline);
			unset($obj->ovrtime_declineby);
			unset($obj->ovrtime_declinedate);
			unset($obj->ovrtime_ispayment);
			unset($obj->ovrtime_paymentby);
			unset($obj->ovrtime_paymentdate);
			unset($obj->ovrtime_executeby);
			unset($obj->ovrtime_executedate);


			// current user & timestamp	
			if ($datastate=='NEW') {
				$obj->_createby = $userdata->username;
				$obj->_createdate = date("Y-m-d H:i:s");

				if (method_exists(get_class($hnd), 'PreCheckInsert')) {
					// PreCheckInsert($data, &$obj, &$options)
					$hnd->PreCheckInsert($data, $obj, $options);
				}
			} else {
				$obj->_modifyby = $userdata->username;
				$obj->_modifydate = date("Y-m-d H:i:s");	
		
				if (method_exists(get_class($hnd), 'PreCheckUpdate')) {
					// PreCheckUpdate($data, &$obj, &$key, &$options)
					$hnd->PreCheckUpdate($data, $obj, $key, $options);
				}
			}

			//handle data sebelum sebelum save
			if (method_exists(get_class($hnd), 'DataSaving')) {
				// ** DataSaving(object &$obj, object &$key)
				$hnd->DataSaving($obj, $key);
			}

			$this->db->setAttribute(\PDO::ATTR_AUTOCOMMIT,0);
			$this->db->beginTransaction();

			try {

				$action = '';
				if ($datastate=='NEW') {
					$action = 'NEW';
					if ($autoid) {
						$obj->{$primarykey} = $this->NewId($hnd, $obj);
					}
					
					// handle data sebelum pada saat pembuatan SQL Insert
					if (method_exists(get_class($hnd), 'RowInserting')) {
						// ** RowInserting(object &$obj)
						$hnd->RowInserting($obj);
					}
					$cmd = \FGTA4\utils\SqlUtility::CreateSQLInsert($tablename, $obj);
				} else {
					$action = 'MODIFY';

					// handle data sebelum pada saat pembuatan SQL Update
					if (method_exists(get_class($hnd), 'RowUpdating')) {
						// ** RowUpdating(object &$obj, object &$key))
						$hnd->RowUpdating($obj, $key);
					}
					$cmd = \FGTA4\utils\SqlUtility::CreateSQLUpdate($tablename, $obj, $key);
				}
	
				$stmt = $this->db->prepare($cmd->sql);
				$stmt->execute($cmd->params);

				\FGTA4\utils\SqlUtility::WriteLog($this->db, $this->reqinfo->modulefullname, $tablename, $obj->{$primarykey}, $action, $userdata->username, (object)[]);




				// result
				$options->criteria = [
					"ovrtime_id" => $obj->ovrtime_id
				];

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

				$dataresponse = array_merge($record, [
					//  untuk lookup atau modify response ditaruh disini
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
					$hnd->DataOpen($dataresponse);
				}

				$result->username = $userdata->username;
				$result->dataresponse = (object) $dataresponse;
				if (method_exists(get_class($hnd), 'DataSavedSuccess')) {
					// DataSavedSuccess(object &$result) : void
					$hnd->DataSavedSuccess($result);
				}

				$this->db->commit();
				return $result;

			} catch (\Exception $ex) {
				$this->db->rollBack();
				throw $ex;
			} finally {
				$this->db->setAttribute(\PDO::ATTR_AUTOCOMMIT,1);
			}

		} catch (\Exception $ex) {
			throw $ex;
		}
	}

	public function NewId(object $hnd, object $obj) : string {
		// dipanggil hanya saat $autoid == true;

		$id = null;
		$handled = false;
		if (method_exists(get_class($hnd), 'CreateNewId')) {
			// CreateNewId(object $obj) : string 
			$id = $hnd->CreateNewId($obj);
			$handled = true;
		}

		if (!$handled) {
			$id = uniqid();
		}

		return $id;
	}

};