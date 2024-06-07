<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}


require_once __ROOT_DIR.'/core/sqlutil.php';
require_once __ROOT_DIR.'/core/userauth.php';
require_once __DIR__ . '/xapi.base.php';


use \FGTA4\exceptions\WebException;


$API = new class extends WebAPI {

	protected $tablename = 'trn_ovrtime';
	protected $primarykey = 'ovrtime_id';
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
					$obj->ovrtime_isrequest = 1;
					$obj->ovrtime_requestby = $userdata->username;
					$obj->ovrtime_requestdate = date("Y-m-d H:i:s");

					$this->requestAction($obj, $key, $userdata);

					$options->message = 'Request Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'unrequest':
					$obj->ovrtime_isrequest = 0;
					$obj->ovrtime_requestby = NULL;
					$obj->ovrtime_requestdate = NULL;

					$this->unrequestAction($obj, $key, $userdata);

					$options->message = 'Unrequest Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'approve':
					$obj->ovrtime_isapproved = 1;
					$obj->ovrtime_approveby = $userdata->username;
					$obj->ovrtime_approvedate = date("Y-m-d H:i:s");

					$obj->ovrtime_executeby = $userdata->username;
					$obj->ovrtime_executedate = date("Y-m-d H:i:s");

					$this->approveAction($obj, $key, $userdata);

					$options->message = 'Approval Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'decline':
					$obj->ovrtime_isdecline = 1;
					$obj->ovrtime_declineby = $userdata->username;
					$obj->ovrtime_declinedate = date("Y-m-d H:i:s");

					$obj->ovrtime_executeby = $userdata->username;
					$obj->ovrtime_executedate = date("Y-m-d H:i:s");

					$this->declineAction($obj, $key, $userdata);

					$options->message = 'Decline Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'payment':
					$obj->ovrtime_ispayment = 1;
					$obj->ovrtime_paymentby = $userdata->username;
					$obj->ovrtime_paymentdate = date("Y-m-d H:i:s");

					$obj->ovrtime_executeby = $userdata->username;
					$obj->ovrtime_executedate = date("Y-m-d H:i:s");

					$this->paymentAction($obj, $key, $userdata);

					$options->message = 'Info Payment Success';

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
			"ovrtime_id" => $obj->ovrtime_id
		];

		$criteriaValues = [
			"ovrtime_id" => " ovrtime_id = :ovrtime_id "
		];

		$where = \FGTA4\utils\SqlUtility::BuildCriteria($options->criteria, $criteriaValues);
		$result = new \stdClass;

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
			'ovrtime_requestby' => \FGTA4\utils\SqlUtility::Lookup($record['ovrtime_requestby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
			'ovrtime_approveby' => \FGTA4\utils\SqlUtility::Lookup($record['ovrtime_approveby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
			'ovrtime_declineby' => \FGTA4\utils\SqlUtility::Lookup($record['ovrtime_declineby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),

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
				ovrtime_isrequest = :ovrtime_isrequest,
				ovrtime_requestby = :ovrtime_requestby,
				ovrtime_requestdate = :ovrtime_requestdate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				ovrtime_id = :ovrtime_id
				and
				ovrtime_isrequest = 0
				and
				ovrtime_isapproved = 0
				and
				ovrtime_isdecline = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				// update
				':ovrtime_isrequest' => $obj->ovrtime_isrequest,
				':ovrtime_requestby' => $obj->ovrtime_requestby,
				':ovrtime_requestdate' => $obj->ovrtime_requestdate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,

				// where
				':ovrtime_id' => $obj->{$this->primarykey}
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
				ovrtime_isrequest = :ovrtime_isrequest,
				ovrtime_requestby = :ovrtime_requestby,
				ovrtime_requestdate = :ovrtime_requestdate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				ovrtime_id = :ovrtime_id
				and
				ovrtime_isrequest = 1
				and
				ovrtime_isapproved = 0
				and
				ovrtime_isdecline = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				// update
				':ovrtime_isrequest' => $obj->ovrtime_isrequest,
				':ovrtime_requestby' => NULL,
				':ovrtime_requestdate' => NULL,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,

				// where
				':ovrtime_id' => $obj->{$this->primarykey}
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

			$emplRequest = $userAuth->employeeProfile($obj->empl_id);

			$docAuth = $userAuth->docAuth($userdata, $emplRequest, $obj);

			$obj->ovrtime_isapprovalprogress = $docAuth->docapprvlevl_sortorder;

			$sql = "
				update $this->tablename
				set
				ovrtime_isapproved = :ovrtime_isapproved,
				ovrtime_approveby = :ovrtime_approveby,
				ovrtime_approvedate = :ovrtime_approvedate,
				ovrtime_isapprovalprogress = :ovrtime_isapprovalprogress,
				ovrtime_executeby = :ovrtime_executeby,
				ovrtime_executedate = :ovrtime_executedate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				ovrtime_id = :ovrtime_id
				and
				ovrtime_isrequest = 1
				and
				ovrtime_isdecline = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				// update
				':ovrtime_isapproved' => $obj->ovrtime_isapproved,
				':ovrtime_approveby' => $obj->ovrtime_approveby,
				':ovrtime_approvedate' => $obj->ovrtime_approvedate,
				':ovrtime_isapprovalprogress' => $obj->ovrtime_isapprovalprogress,
				':ovrtime_executeby' => $obj->ovrtime_executeby,
				':ovrtime_executedate' => $obj->ovrtime_executedate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,

				// where
				':ovrtime_id' => $obj->{$this->primarykey}
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

			$emplRequest = $userAuth->employeeProfile($obj->empl_id);

			$userAuth->docAuth($userdata, $emplRequest, $obj);

			$sql = "
				update $this->tablename
				set
				ovrtime_rejectnotes = :ovrtime_rejectnotes,
				ovrtime_isdecline = :ovrtime_isdecline,
				ovrtime_declineby = :ovrtime_declineby,
				ovrtime_declinedate = :ovrtime_declinedate,
				ovrtime_executeby = :ovrtime_executeby,
				ovrtime_executedate = :ovrtime_executedate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				ovrtime_id = :ovrtime_id
				and
				ovrtime_isrequest = 1
				and
				ovrtime_isdecline = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				// update
				':ovrtime_rejectnotes' => $obj->ovrtime_rejectnotes,
				':ovrtime_isdecline' => $obj->ovrtime_isdecline,
				':ovrtime_declineby' => $obj->ovrtime_declineby,
				':ovrtime_declinedate' => $obj->ovrtime_declinedate,
				':ovrtime_executeby' => $obj->ovrtime_executeby,
				':ovrtime_executedate' => $obj->ovrtime_executedate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,

				// where
				':ovrtime_id' => $obj->{$this->primarykey}
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

	protected function paymentAction($obj, $key, $userdata): void
	{
		try {
			$sql = "
				update $this->tablename
				set
				ovrtime_ispayment = :ovrtime_ispayment,
				ovrtime_paymentby = :ovrtime_paymentby,
				ovrtime_paymentdate = :ovrtime_paymentdate,
				ovrtime_executeby = :ovrtime_executeby,
				ovrtime_executedate = :ovrtime_executedate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				ovrtime_id = :ovrtime_id
				and
				ovrtime_isrequest = 1
				and
				ovrtime_isapproved = 1
				and
				ovrtime_isdecline = 0
				and
				ovrtime_ispayment = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				// update
				':ovrtime_ispayment' => $obj->ovrtime_ispayment,
				':ovrtime_paymentby' => $obj->ovrtime_paymentby,
				':ovrtime_paymentdate' => $obj->ovrtime_paymentdate,
				':ovrtime_executeby' => $obj->ovrtime_executeby,
				':ovrtime_executedate' => $obj->ovrtime_executedate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,

				// where
				':ovrtime_id' => $obj->{$this->primarykey}
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




