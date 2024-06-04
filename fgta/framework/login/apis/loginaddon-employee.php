<?php


$LoginAddonExecute = function(&$userdata, $db) {

	// cek apakah tabel mst_empl sudah ada
	$stmt = $db->prepare("SHOW TABLES LIKE 'mst_empl';");
	$stmt->execute();
	$rows  = $stmt->fetchall(\PDO::FETCH_ASSOC);
	if (count($rows)==0) {
		return;
	}


	try {
		// $sql = "
		// 	select 
		// 	  A.empl_id
		// 	, A.empl_name
		// 	, A.site_id, A.dept_id
		// 	, (select site_name from mst_site where site_id = A.site_id) as site_name 
		// 	, (select dept_name from mst_dept where dept_id = A.dept_id) as dept_name
		// 	, A.empl_isdisabled
		// 	from 
		// 	mst_empl A inner join mst_empluser B on B.empl_id = A.empl_id 
		// 	where B.user_id = :user_id
		// ";
		// $stmt = $db->prepare($sql);
		// $stmt->execute([
		// 	':user_id' => $userdata->username
		// ]);
		// $row  = $stmt->fetch(\PDO::FETCH_ASSOC);
		$row = null;

		if ($row!=null) {
			$userdata->employee_id = $row['empl_id'];
			$userdata->empl_id = $row['empl_id'];
			$userdata->empl_name = $row['empl_name'];
			$userdata->dept_id = $row['dept_id'];
			$userdata->dept_name = $row['dept_name'];
			$userdata->site_id = $row['site_id'];
			$userdata->site_name = $row['site_name'];

			if ($row['empl_isdisabled']==1) {
				throw new \Exception("karyawan non aktif.");
			}
		}



	} catch (\Exception $ex) {
		throw $ex;
	}	


};