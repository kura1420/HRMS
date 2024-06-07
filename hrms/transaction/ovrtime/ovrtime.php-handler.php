<?php namespace FGTA4\module; if (!defined('FGTA4')) { die('Forbiden'); } 

require_once __ROOT_DIR.'/core/userauth.php';

class ovrtime_pageHandler extends WebModule {

	function __construct() {

		// $logfilepath = __LOCALDB_DIR . "/output//*offertime*/.txt";
		// debug::disable();
		// debug::start($logfilepath, "w");

		$DB_CONFIG = DB_CONFIG[$GLOBALS['MAINDB']];
		$DB_CONFIG['param'] = DB_CONFIG_PARAM[$GLOBALS['MAINDBTYPE']];		
		$this->db = new \PDO(
					$DB_CONFIG['DSN'], 
					$DB_CONFIG['user'], 
					$DB_CONFIG['pass'], 
					$DB_CONFIG['param']
		);

		
	}

	public function LoadPage()
	{
		$userdata = $this->auth->session_get_user();

		$userAuth = new \FGTA4\utils\UserAuth();

		$permissions = $userAuth->permissions($userdata->username);

		$isRequestMySelf = FALSE;
		if (in_array('REQSELF', $permissions)) {
			$isRequestMySelf = TRUE;
		}

		$canApprove = FALSE;
		if (in_array('DOCAPPRV', $permissions)) {
			$canApprove = TRUE;
		}

		$canPayment = FALSE;
		if (in_array('PAYMENT', $permissions)) {
			$canPayment = TRUE;
		}

		$this->setup->variancedata = [
			'employee_profile' => $userAuth->employeeProfile($userdata->username),
			'is_request_myself' => $isRequestMySelf,
			'can_approve' => $canApprove,
			'can_payment' => $canPayment,
		];
	}

}