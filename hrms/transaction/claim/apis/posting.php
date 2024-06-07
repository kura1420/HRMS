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

			$obj->_modifyby = $userdata->username;
			$obj->_modifydate = date("Y-m-d H:i:s");

			switch ($data->event) {
				case 'request':
					$obj->claim_isrequest = 1;
					$obj->claim_requestby = $userdata->username;
					$obj->claim_requestdate = date("Y-m-d H:i:s");

					$this->requestAction($obj, $key, $userdata);

					$options->message = 'Request Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'unrequest':
					$obj->claim_isrequest = 0;
					$obj->claim_requestby = NULL;
					$obj->claim_requestdate = NULL;

					$this->unrequestAction($obj, $key, $userdata);

					$options->message = 'UnRequest Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'approve':
					$obj->claim_isapproved = 1;
					$obj->claim_approveby = $userdata->username;
					$obj->claim_approvedate = date("Y-m-d H:i:s");

					$obj->claim_executeby = $userdata->username;
					$obj->claim_executedate = date("Y-m-d H:i:s");

					$this->approveAction($obj, $key, $userdata);

					$options->message = 'Approval Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'decline':
					$obj->claim_isdecline = 1;
					$obj->claim_declineby = $userdata->username;
					$obj->claim_declinedate = date("Y-m-d H:i:s");

					$obj->claim_executeby = $userdata->username;
					$obj->claim_executedate = date("Y-m-d H:i:s");

					$this->declineAction($obj, $key, $userdata);

					$options->message = 'Decline Success';

					$return = $this->getData($obj, $options, $userdata);
					
					return $return;
					break;

				case 'payment':
					$obj->claim_ispayment = 1;
					$obj->claim_paymentby = $userdata->username;
					$obj->claim_paymentdate = date("Y-m-d H:i:s");

					$obj->claim_executeby = $userdata->username;
					$obj->claim_executedate = date("Y-m-d H:i:s");

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
			"claim_id" => $obj->claim_id
		];

		$criteriaValues = [
			"claim_id" => " claim_id = :claim_id "
		];

		$where = \FGTA4\utils\SqlUtility::BuildCriteria($options->criteria, $criteriaValues);
		$result = new \stdClass;

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
			'claim_requestby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_requestby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
			'claim_approveby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_approveby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),
			'claim_declineby' => \FGTA4\utils\SqlUtility::Lookup($record['claim_declineby'], $this->db, $GLOBALS['MAIN_USERTABLE'], 'user_id', 'user_fullname'),

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
				claim_isdecline = 0
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
				claim_isdecline = 0
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

			$emplRequest = $userAuth->employeeProfile($obj->empl_id);

			$docAuth = $userAuth->docAuth($userdata, $emplRequest, $obj);

			$obj->claim_isapprovalprogress = $docAuth->docapprvlevl_sortorder;

			$sql = "
				update $this->tablename
				set
				claim_isapproved = :claim_isapproved,
				claim_approveby = :claim_approveby,
				claim_approvedate = :claim_approvedate,
				claim_isapprovalprogress = :claim_isapprovalprogress,
				claim_executeby = :claim_executeby,
				claim_executedate = :claim_executedate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				claim_id = :claim_id
				and
				claim_isrequest = 1
				and
				claim_isdecline = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':claim_isapproved' => $obj->claim_isapproved,
				':claim_approveby' => $obj->claim_approveby,
				':claim_approvedate' => $obj->claim_approvedate,
				':claim_isapprovalprogress' => $obj->claim_isapprovalprogress,
				':claim_executeby' => $obj->claim_executeby,
				':claim_executedate' => $obj->claim_executedate,
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

			$emplRequest = $userAuth->employeeProfile($obj->empl_id);

			$userAuth->docAuth($userdata, $emplRequest, $obj);

			$sql = "
				update $this->tablename
				set
				claim_rejectnotes = :claim_rejectnotes,
				claim_isdecline = :claim_isdecline,
				claim_declineby = :claim_declineby,
				claim_declinedate = :claim_declinedate,
				claim_executeby = :claim_executeby,
				claim_executedate = :claim_executedate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				claim_id = :claim_id
				and
				claim_isrequest = 1
				and
				claim_isdecline = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':claim_rejectnotes' => $obj->claim_rejectnotes,
				':claim_isdecline' => $obj->claim_isdecline,
				':claim_declineby' => $obj->claim_declineby,
				':claim_declinedate' => $obj->claim_declinedate,
				':claim_executeby' => $obj->claim_executeby,
				':claim_executedate' => $obj->claim_executedate,
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

	protected function paymentAction($obj, $key, $userdata): void
	{
		try {
			$sql = "
				update $this->tablename
				set
				claim_ispayment = :claim_ispayment,
				claim_paymentby = :claim_paymentby,
				claim_paymentdate = :claim_paymentdate,
				claim_executeby = :claim_executeby,
				claim_executedate = :claim_executedate,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				claim_id = :claim_id
				and
				claim_isrequest = 1
				and
				claim_isapproved = 1
				and
				claim_isdecline = 0
				and
				claim_ispayment = 0
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				// update
				':claim_ispayment' => $obj->claim_ispayment,
				':claim_paymentby' => $obj->claim_paymentby,
				':claim_paymentdate' => $obj->claim_paymentdate,
				':claim_executeby' => $obj->claim_executeby,
				':claim_executedate' => $obj->claim_executedate,
				':_modifyby' => $obj->_modifyby,
				':_modifydate' => $obj->_modifydate,

				// where
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




