<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}


require_once __ROOT_DIR.'/core/sqlutil.php';
require_once __ROOT_DIR.'/core/userauth.php';
require_once __DIR__ . '/xapi.base.php';


use \FGTA4\exceptions\WebException;


$API = new class extends WebAPI {

	protected $tablename = 'trn_offertime';
	protected $primarykey = 'offertime_id';
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

			switch ($data->event) {
				case 'request':
					$this->requestAction($obj, $key, $userdata);

					$options->message = 'Request Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'unrequest':
					$this->unrequestAction($obj, $key, $userdata);

					$options->message = 'UnRequest Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'approve':
					$this->approveAction($obj, $key, $userdata);

					$options->message = 'Approval Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'decline':
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
			"offertime_id" => $obj->offertime_id
		];

		$criteriaValues = [
			"offertime_id" => " offertime_id = :offertime_id "
		];

		$where = \FGTA4\utils\SqlUtility::BuildCriteria($options->criteria, $criteriaValues);
		$result = new \stdClass;

		$sqlFieldList = [
			'offertime_id' => 'A.`offertime_id`', 'offertime_code' => 'A.`offertime_code`', 'empl_id' => 'A.`empl_id`', 'docapprv_id' => 'A.`docapprv_id`',
			'offertime_rejectnotes' => 'A.`offertime_rejectnotes`', 'offertime_isrequest' => 'A.`offertime_isrequest`', 'offertime_requestby' => 'A.`offertime_requestby`', 'offertime_requestdate' => 'A.`offertime_requestdate`',
			'offertime_isapproved' => 'A.`offertime_isapproved`', 'offertime_approveby' => 'A.`offertime_approveby`', 'offertime_approvedate' => 'A.`offertime_approvedate`', 'offertime_isdeclined' => 'A.`offertime_isdeclined`',
			'offertime_declineby' => 'A.`offertime_declineby`', 'offertime_declinedate' => 'A.`offertime_declinedate`', '_createby' => 'A.`_createby`', '_createdate' => 'A.`_createdate`',
			'_createby' => 'A.`_createby`', '_createdate' => 'A.`_createdate`', '_modifyby' => 'A.`_modifyby`', '_modifydate' => 'A.`_modifydate`'
		];
		$sqlFromTable = "trn_offertime A";
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

		$dataresponse = array_merge($record, [
			//  untuk lookup atau modify response ditaruh disini
			'empl_fullname' => \FGTA4\utils\SqlUtility::Lookup($record['empl_id'], $this->db, 'mst_empl', 'empl_id', 'empl_fullname'),
			'docapprv_name' => \FGTA4\utils\SqlUtility::Lookup($record['docapprv_id'], $this->db, 'mst_docapprv', 'docapprv_id', 'docapprv_name'),
			'offertime_requestby' => \FGTA4\utils\SqlUtility::Lookup($record['offertime_requestby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
			'offertime_approveby' => \FGTA4\utils\SqlUtility::Lookup($record['offertime_approveby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
			'offertime_declineby' => \FGTA4\utils\SqlUtility::Lookup($record['offertime_declineby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),

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
			$obj->offertime_isrequest = 1;
			$obj->offertime_requestby = $userdata->username;
			$obj->offertime_requestdate = date("Y-m-d H:i:s");
			
			$obj->_modifyby = $userdata->username;
			$obj->_modifydate = date("Y-m-d H:i:s");

			$sql = "
				update $this->tablename
				set
				offertime_isrequest = :offertime_isrequest,
				offertime_requestby = :offertime_requestby,
				offertime_requestdate = :offertime_requestdate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				offertime_id = :offertime_id
				and
				offertime_isrequest = 0
				and
				offertime_isapproved = 0
				and
				offertime_isdeclined = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':offertime_isrequest' => $obj->offertime_isrequest,
				':offertime_requestby' => $obj->offertime_requestby,
				':offertime_requestdate' => $obj->offertime_requestdate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,
				':offertime_id' => $obj->{$this->primarykey}
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
			$obj->offertime_isrequest = 0;
			$obj->offertime_requestby = $userdata->username;
			$obj->offertime_requestdate = date("Y-m-d H:i:s");
			
			$obj->_modifyby = $userdata->username;
			$obj->_modifydate = date("Y-m-d H:i:s");

			$sql = "
				update $this->tablename
				set
				offertime_isrequest = :offertime_isrequest,
				offertime_requestby = :offertime_requestby,
				offertime_requestdate = :offertime_requestdate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				offertime_id = :offertime_id
				and
				offertime_isrequest = 1
				and
				offertime_isapproved = 0
				and
				offertime_isdeclined = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':offertime_isrequest' => $obj->offertime_isrequest,
				':offertime_requestby' => NULL,
				':offertime_requestdate' => NULL,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,
				':offertime_id' => $obj->{$this->primarykey}
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
			$userAuth = new \FGTA4\utils\UserAuth();

			$userAuth->docAuth($userdata, $obj);

			$sql = "SELECT md.empl_id 
			FROM mst_docapprvlevl md 
			INNER JOIN mst_empl me ON md.empl_id = me.empl_id 
			WHERE me.user_id = :user_id AND md.docapprv_id = :docapprv_id AND md.docapprvlevl_isdisabled = 0";

			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':user_id' => $userdata->username,
				':docapprv_id' => $obj->docapprv_id,
			]);
			$docapprvlevl = $stmt->rowCount();

			if ($docapprvlevl == 0) {
				throw new \Exception("Anda tidak memiliki akses untuk approved dokumen ini");
			}

			$obj->offertime_isapproved = 1;
			$obj->offertime_approveby = $userdata->username;
			$obj->offertime_approvedate = date("Y-m-d H:i:s");
			
			$obj->_modifyby = $userdata->username;
			$obj->_modifydate = date("Y-m-d H:i:s");

			$sql = "
				update $this->tablename
				set
				offertime_isapproved = :offertime_isapproved,
				offertime_approveby = :offertime_approveby,
				offertime_approvedate = :offertime_approvedate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				offertime_id = :offertime_id
				and
				offertime_isrequest = 1
				and
				offertime_isapproved = 0
				and
				offertime_isdeclined = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':offertime_isapproved' => $obj->offertime_isapproved,
				':offertime_approveby' => $obj->offertime_approveby,
				':offertime_approvedate' => $obj->offertime_approvedate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,
				':offertime_id' => $obj->{$this->primarykey}
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
			$userAuth = new \FGTA4\utils\UserAuth();

			$userAuth->docAuth($userdata, $obj);

			$obj->offertime_isdeclined = 1;
			$obj->offertime_declineby = $userdata->username;
			$obj->offertime_declinedate = date("Y-m-d H:i:s");
			
			$obj->_modifyby = $userdata->username;
			$obj->_modifydate = date("Y-m-d H:i:s");

			$sql = "
				update $this->tablename
				set
				offertime_rejectnotes = :offertime_rejectnotes,
				offertime_isdeclined = :offertime_isdeclined,
				offertime_declineby = :offertime_declineby,
				offertime_declinedate = :offertime_declinedate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				offertime_id = :offertime_id
				and
				offertime_isrequest = 1
				and
				offertime_isdeclined = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':offertime_rejectnotes' => $obj->offertime_rejectnotes,
				':offertime_isdeclined' => $obj->offertime_isdeclined,
				':offertime_declineby' => $obj->offertime_declineby,
				':offertime_declinedate' => $obj->offertime_declinedate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,
				':offertime_id' => $obj->{$this->primarykey}
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




