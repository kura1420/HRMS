<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}


require_once __ROOT_DIR.'/core/sqlutil.php';
require_once __ROOT_DIR.'/core/userauth.php';
require_once __DIR__ . '/xapi.base.php';


use \FGTA4\exceptions\WebException;


$API = new class extends WebAPI {

	protected $tablename = 'trn_claim';
	protected $primarykey = 'claim_id';
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
			"claim_id" => $obj->claim_id
		];

		$criteriaValues = [
			"claim_id" => " claim_id = :claim_id "
		];

		$where = \FGTA4\utils\SqlUtility::BuildCriteria($options->criteria, $criteriaValues);
		$result = new \stdClass;

		$sqlFieldList = [
			'claim_id' => 'A.`claim_id`', 'claim_code' => 'A.`claim_code`', 'empl_id' => 'A.`empl_id`', 'docapprv_id' => 'A.`docapprv_id`',
			'claim_rejectnotes' => 'A.`claim_rejectnotes`', 'claim_isrequest' => 'A.`claim_isrequest`', 'claim_requestby' => 'A.`claim_requestby`', 'claim_requestdate' => 'A.`claim_requestdate`',
			'claim_isapproved' => 'A.`claim_isapproved`', 'claim_approveby' => 'A.`claim_approveby`', 'claim_approvedate' => 'A.`claim_approvedate`', 'claim_isdeclined' => 'A.`claim_isdeclined`',
			'claim_declineby' => 'A.`claim_declineby`', 'claim_declinedate' => 'A.`claim_declinedate`', '_createby' => 'A.`_createby`', '_createdate' => 'A.`_createdate`',
			'_createby' => 'A.`_createby`', '_createdate' => 'A.`_createdate`', '_modifyby' => 'A.`_modifyby`', '_modifydate' => 'A.`_modifydate`'
		];
		$sqlFromTable = "trn_claim A";
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
			'claim_requestby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_requestby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
			'claim_approveby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_approveby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
			'claim_declineby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_declineby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),

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
			$obj->claim_isrequest = 1;
			$obj->claim_requestby = $userdata->username;
			$obj->claim_requestdate = date("Y-m-d H:i:s");
			
			$obj->_modifyby = $userdata->username;
			$obj->_modifydate = date("Y-m-d H:i:s");

			$sql = "
				update $this->tablename
				set
				claim_isrequest = :claim_isrequest,
				claim_requestby = :claim_requestby,
				claim_requestdate = :claim_requestdate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				claim_id = :claim_id
				and
				claim_isrequest = 0
				and
				claim_isapproved = 0
				and
				claim_isdeclined = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':claim_isrequest' => $obj->claim_isrequest,
				':claim_requestby' => $obj->claim_requestby,
				':claim_requestdate' => $obj->claim_requestdate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,
				':claim_id' => $obj->{$this->primarykey}
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
			$obj->claim_isrequest = 0;
			$obj->claim_requestby = $userdata->username;
			$obj->claim_requestdate = date("Y-m-d H:i:s");
			
			$obj->_modifyby = $userdata->username;
			$obj->_modifydate = date("Y-m-d H:i:s");

			$sql = "
				update $this->tablename
				set
				claim_isrequest = :claim_isrequest,
				claim_requestby = :claim_requestby,
				claim_requestdate = :claim_requestdate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				claim_id = :claim_id
				and
				claim_isrequest = 1
				and
				claim_isapproved = 0
				and
				claim_isdeclined = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':claim_isrequest' => $obj->claim_isrequest,
				':claim_requestby' => $obj->claim_requestby,
				':claim_requestdate' => $obj->claim_requestdate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,
				':claim_id' => $obj->{$this->primarykey}
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

			$obj->claim_isapproved = 1;
			$obj->claim_approveby = $userdata->username;
			$obj->claim_approvedate = date("Y-m-d H:i:s");
			
			$obj->_modifyby = $userdata->username;
			$obj->_modifydate = date("Y-m-d H:i:s");

			$sql = "
				update $this->tablename
				set
				claim_isapproved = :claim_isapproved,
				claim_approveby = :claim_approveby,
				claim_approvedate = :claim_approvedate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				claim_id = :claim_id
				and
				claim_isrequest = 1
				and
				claim_isapproved = 0
				and
				claim_isdeclined = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':claim_isapproved' => $obj->claim_isapproved,
				':claim_approveby' => $obj->claim_approveby,
				':claim_approvedate' => $obj->claim_approvedate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,
				':claim_id' => $obj->{$this->primarykey}
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

			$obj->claim_isdeclined = 1;
			$obj->claim_declineby = $userdata->username;
			$obj->claim_declinedate = date("Y-m-d H:i:s");
			
			$obj->_modifyby = $userdata->username;
			$obj->_modifydate = date("Y-m-d H:i:s");

			$sql = "
				update $this->tablename
				set
				claim_rejectnotes = :claim_rejectnotes,
				claim_isdeclined = :claim_isdeclined,
				claim_declineby = :claim_declineby,
				claim_declinedate = :claim_declinedate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				claim_id = :claim_id
				and
				claim_isrequest = 1
				and
				claim_isdeclined = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':claim_rejectnotes' => $obj->claim_rejectnotes,
				':claim_isdeclined' => $obj->claim_isdeclined,
				':claim_declineby' => $obj->claim_declineby,
				':claim_declinedate' => $obj->claim_declinedate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,
				':claim_id' => $obj->{$this->primarykey}
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




