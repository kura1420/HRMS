<?php namespace FGTA4\utils;

if (!defined('FGTA4')) {
	die('Forbiden');
}

class UserAuth
{

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
	
	public function employeeProfile($user_id)
	{
		$SQL = "SELECT *
		FROM mst_empl me 
		WHERE me.user_id = '{$user_id}'";

		$stmt = $this->db->prepare($SQL);
		$stmt->execute();

		$rows = $stmt->fetch(\PDO::FETCH_OBJ);

		return $rows;
	}

	protected function rolePermissions($user_id)
	{
		$SQL = "SELECT fr.role_id, fr.permission_id  
		FROM fgt_user fu 
		INNER JOIN fgt_grouprole fg ON fu.group_id = fg.group_id 
		INNER JOIN fgt_rolepermission fr ON fg.role_id = fr.role_id 
		WHERE fu.user_id = '{$user_id}'";

		$stmt = $this->db->prepare($SQL);
		$stmt->execute();
	
		$rolePermissions = $stmt->fetchAll(\PDO::FETCH_OBJ);

		return $rolePermissions;
	}

	public function permissions($user_id): array
	{
		$rolePermissions = $this->rolePermissions($user_id);
		$permissions = [];
		foreach ($rolePermissions as $rolePermission) {
			$permissions[] = $rolePermission->permission_id;
		}

		return $permissions;
	}

	public function docAuth($userdata, $obj): void
	{
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
	}
	
}