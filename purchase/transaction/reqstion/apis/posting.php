<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}


require_once __ROOT_DIR.'/core/sqlutil.php';
require_once __ROOT_DIR.'/rootdir/helper/userauth.php';
require_once __DIR__ . '/xapi.base.php';


use \FGTA4\exceptions\WebException;
use UserAuth;

$API = new class extends WebAPI {

	protected $tablename = 'trn_reqstion';
	protected $primarykey = 'reqstion_id';
	protected $action = 'MODIFY';

	function __construct() {
		$this->debugoutput = true;
		$DB_CONFIG = DB_CONFIG[$GLOBALS['MAINDB']];
		$DB_CONFIG['param'] = DB_CONFIG_PARAM[$GLOBALS['MAINDBTYPE']];
		$this->db = new \PDO(
					$DB_CONFIG['DSN'], 
					$DB_CONFIG['user'], 
					$DB_CONFIG['pass'], 
					$DB_CONFIG['param']
		);	
	}
	
	public function execute($data, $options) {
		try {
			$userdata = $this->auth->session_get_user(); 
			
			$key = new \stdClass;
			$obj = new \stdClass;
			foreach ($data as $fieldname => $value) {
				if ($fieldname=='event') { continue; }
				if ($fieldname==$this->primarykey) {
					$key->{$fieldname} = $value;
				}
				$obj->{$fieldname} = $value;
			}

			$obj->_modifyby = $userdata->username;
			$obj->_modifydate = date("Y-m-d H:i:s");

			switch ($data->event) {
				case 'request':
					$obj->reqstion_isrequest = 1;
					$obj->reqstion_requestby = $userdata->username;
					$obj->reqstion_requestdate = date("Y-m-d H:i:s");

					$this->requestAction($obj, $key, $userdata);

					$options->message = 'Request Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'unrequest':
					$obj->reqstion_isrequest = 0;
					$obj->reqstion_requestby = NULL;
					$obj->reqstion_requestdate = NULL;

					$this->unrequestAction($obj, $key, $userdata);

					$options->message = 'UnRequest Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'approve':
					$obj->reqstion_isapproved = 1;
					$obj->reqstion_approveby = $userdata->username;
					$obj->reqstion_approvedate = date("Y-m-d H:i:s");

					$obj->reqstion_executeby = $userdata->username;
					$obj->reqstion_executedate = date("Y-m-d H:i:s");

					$this->approveAction($obj, $key, $userdata);

					$options->message = 'Approval Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'decline':
					$obj->reqstion_isdecline = 1;
					$obj->reqstion_declineby = $userdata->username;
					$obj->reqstion_declinedate = date("Y-m-d H:i:s");

					$obj->reqstion_executeby = $userdata->username;
					$obj->reqstion_executedate = date("Y-m-d H:i:s");

					$this->declineAction($obj, $key, $userdata);

					$options->message = 'Decline Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;
				
				default:
					throw new \Exception('invalid request type: '.$data->event);
					break;
			}
		} catch (\Exception $ex) {
			throw $ex;
		}
	}

	protected function getData($obj, $options, $userdata): object
	{
		$options->criteria = [
			"reqstion_id" => $obj->reqstion_id
		];

		$criteriaValues = [
			"reqstion_id" => " reqstion_id = :reqstion_id "
		];

		$where = \FGTA4\utils\SqlUtility::BuildCriteria($options->criteria, $criteriaValues);
		$result = new \stdClass;

		$sqlFieldList = [
			'reqstion_id' => 'A.`reqstion_id`', 'reqstion_code' => 'A.`reqstion_code`', 'empl_id' => 'A.`empl_id`', 'reqstion_subject' => 'A.`reqstion_subject`',
			'reqstion_dt' => 'A.`reqstion_dt`', 'reqstion_total' => 'A.`reqstion_total`', 'reqstion_descr' => 'A.`reqstion_descr`', 'docapprv_id' => 'A.`docapprv_id`',
			'reqstion_rejectnotes' => 'A.`reqstion_rejectnotes`', 'reqstion_isrequest' => 'A.`reqstion_isrequest`', 'reqstion_requestby' => 'A.`reqstion_requestby`', 'reqstion_requestdate' => 'A.`reqstion_requestdate`',
			'reqstion_isapproved' => 'A.`reqstion_isapproved`', 'reqstion_approveby' => 'A.`reqstion_approveby`', 'reqstion_approvedate' => 'A.`reqstion_approvedate`', 'reqstion_isapprovalprogress' => 'A.`reqstion_isapprovalprogress`',
			'reqstion_isdecline' => 'A.`reqstion_isdecline`', 'reqstion_declineby' => 'A.`reqstion_declineby`', 'reqstion_declinedate' => 'A.`reqstion_declinedate`', 'reqstion_ispayment' => 'A.`reqstion_ispayment`',
			'reqstion_executeby' => 'A.`reqstion_executeby`', 'reqstion_executedate' => 'A.`reqstion_executedate`', '_createby' => 'A.`_createby`', '_createdate' => 'A.`_createdate`',
			'_createby' => 'A.`_createby`', '_createdate' => 'A.`_createdate`', '_modifyby' => 'A.`_modifyby`', '_modifydate' => 'A.`_modifydate`'
		];
		$sqlFromTable = "trn_reqstion A";
		$sqlWhere = $where->sql;

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

		$SQL = "SELECT * FROM mst_docapprvlevl WHERE docapprv_id = :docapprv_id ORDER BY docapprvlevl_sortorder DESC LIMIT 1";
		$stmt = $this->db->prepare($SQL);
		$stmt->execute(['docapprv_id' => $obj->docapprv_id]);
		$docapprv = $stmt->fetch(\PDO::FETCH_OBJ);

		$dataresponse = array_merge($record, [
			//  untuk lookup atau modify response ditaruh disini
			'empl_fullname' => \FGTA4\utils\SqlUtility::Lookup($record['empl_id'], $this->db, 'mst_empl', 'empl_id', 'empl_fullname'),
			'docapprv_name' => \FGTA4\utils\SqlUtility::Lookup($record['docapprv_id'], $this->db, 'mst_docapprv', 'docapprv_id', 'docapprv_name'),
			'reqstion_requestby' => \FGTA4\utils\SqlUtility::Lookup($record['reqstion_requestby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
			'reqstion_approveby' => \FGTA4\utils\SqlUtility::Lookup($record['reqstion_approveby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
			'reqstion_declineby' => \FGTA4\utils\SqlUtility::Lookup($record['reqstion_declineby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),

			'docapprvlevl_sortorder' => $docapprv->docapprvlevl_sortorder,

			'_createby' => \FGTA4\utils\SqlUtility::Lookup($record['_createby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
			'_modifyby' => \FGTA4\utils\SqlUtility::Lookup($record['_modifyby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
		]);

		$result->username = $userdata->username;
		$result->dataresponse = (object) $dataresponse;
		$result->message = $options->message;

		return $result;
	}

	protected function requestAction($obj, $key, $userdata): void
	{
		try {
			$sql = "
				update $this->tablename
				set
				reqstion_isrequest = :reqstion_isrequest,
				reqstion_requestby = :reqstion_requestby,
				reqstion_requestdate = :reqstion_requestdate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				reqstion_id = :reqstion_id
				and
				reqstion_isrequest = 0
				and
				reqstion_isapproved = 0
				and
				reqstion_isdecline = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':reqstion_isrequest' => $obj->reqstion_isrequest,
				':reqstion_requestby' => $obj->reqstion_requestby,
				':reqstion_requestdate' => $obj->reqstion_requestdate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,

				':reqstion_id' => $obj->{$this->primarykey}
			]);

			$rowsAffected = $stmt->rowCount();
			if ($rowsAffected==0) {
				throw new \Exception("Data not found");
			}

			\FGTA4\utils\SqlUtility::WriteLog($this->db, $this->reqinfo->modulefullname, $this->tablename, $obj->{$this->primarykey}, $this->action, $userdata->username, (object)[]);
		} catch (\Exception $ex) {
			throw $ex;
		}
	}

	protected function unrequestAction($obj, $key, $userdata): void
	{
		try {
			$sql = "
				update $this->tablename
				set
				reqstion_isrequest = :reqstion_isrequest,
				reqstion_requestby = :reqstion_requestby,
				reqstion_requestdate = :reqstion_requestdate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				reqstion_id = :reqstion_id
				and
				reqstion_isrequest = 1
				and
				reqstion_isapproved = 0
				and
				reqstion_isdecline = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':reqstion_isrequest' => $obj->reqstion_isrequest,
				':reqstion_requestby' => $obj->reqstion_requestby,
				':reqstion_requestdate' => $obj->reqstion_requestdate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,

				':reqstion_id' => $obj->{$this->primarykey}
			]);

			$rowsAffected = $stmt->rowCount();
			if ($rowsAffected==0) {
				throw new \Exception("Data not found");
			}

			\FGTA4\utils\SqlUtility::WriteLog($this->db, $this->reqinfo->modulefullname, $this->tablename, $obj->{$this->primarykey}, $this->action, $userdata->username, (object)[]);
		} catch (\Exception $ex) {
			throw $ex;
		}
	}

	protected function approveAction($obj, $key, $userdata): void
	{
		try {
			$userAuth = new UserAuth();

			$emplRequest = $userAuth->employeeProfile($obj->empl_id);

			$docAuth = $userAuth->docAuth($userdata, $emplRequest, $obj);

			$obj->reqstion_isapprovalprogress = $docAuth->docapprvlevl_sortorder;

			$sql = "
				update $this->tablename
				set
				reqstion_isapproved = :reqstion_isapproved,
				reqstion_approveby = :reqstion_approveby,
				reqstion_approvedate = :reqstion_approvedate,
				reqstion_isapprovalprogress = :reqstion_isapprovalprogress,
				reqstion_executeby = :reqstion_executeby,
				reqstion_executedate = :reqstion_executedate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				reqstion_id = :reqstion_id
				and
				reqstion_isrequest = 1
				and
				reqstion_isdecline = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':reqstion_isapproved' => $obj->reqstion_isapproved,
				':reqstion_approveby' => $obj->reqstion_approveby,
				':reqstion_approvedate' => $obj->reqstion_approvedate,
				':reqstion_isapprovalprogress' => $obj->reqstion_isapprovalprogress,
				':reqstion_executeby' => $obj->reqstion_executeby,
				':reqstion_executedate' => $obj->reqstion_executedate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,

				':reqstion_id' => $obj->{$this->primarykey}
			]);

			$rowsAffected = $stmt->rowCount();
			if ($rowsAffected==0) {
				throw new \Exception("Data not found");
			}			

			\FGTA4\utils\SqlUtility::WriteLog($this->db, $this->reqinfo->modulefullname, $this->tablename, $obj->{$this->primarykey}, $this->action, $userdata->username, (object)[]);
		} catch (\Exception $ex) {
			throw $ex;
		}
	}

	protected function declineAction($obj, $key, $userdata): void
	{
		try {
			$userAuth = new UserAuth();

			$emplRequest = $userAuth->employeeProfile($obj->empl_id);

			$userAuth->docAuth($userdata, $emplRequest, $obj);

			$sql = "
				update $this->tablename
				set
				reqstion_rejectnotes = :reqstion_rejectnotes,
				reqstion_isdecline = :reqstion_isdecline,
				reqstion_declineby = :reqstion_declineby,
				reqstion_declinedate = :reqstion_declinedate,
				reqstion_executeby = :reqstion_executeby,
				reqstion_executedate = :reqstion_executedate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				reqstion_id = :reqstion_id
				and
				reqstion_isrequest = 1
				and
				reqstion_isdecline = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':reqstion_rejectnotes' => $obj->reqstion_rejectnotes,
				':reqstion_isdecline' => $obj->reqstion_isdecline,
				':reqstion_declineby' => $obj->reqstion_declineby,
				':reqstion_declinedate' => $obj->reqstion_declinedate,
				':reqstion_executeby' => $obj->reqstion_executeby,
				':reqstion_executedate' => $obj->reqstion_executedate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,

				':reqstion_id' => $obj->{$this->primarykey}
			]);

			$rowsAffected = $stmt->rowCount();
			if ($rowsAffected==0) {
				throw new \Exception("Data not found");
			}

			\FGTA4\utils\SqlUtility::WriteLog($this->db, $this->reqinfo->modulefullname, $this->tablename, $obj->{$this->primarykey}, $this->action, $userdata->username, (object)[]);
		} catch (\Exception $ex) {
			throw $ex;
		}
	}

};




