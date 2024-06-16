<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}



class reqstion_reqstionitmHandler extends WebAPI  {

	public function DataSaving(object &$obj, object &$key) : void
	{
		$userdata = $this->auth->session_get_user(); 

		$SQL = "SELECT 
			SUM(reqstionitm_val) AS total 
		FROM trn_reqstionitm 
		WHERE reqstion_id = :reqstion_id AND reqstionitm_id != :reqstionitm_id";
		$stmt = $this->db->prepare($SQL);
		$stmt->execute([
			':reqstion_id' => $obj->reqstion_id,
			':reqstionitm_id' => $obj->reqstionitm_id,
		]);

		$row = $stmt->fetch(\PDO::FETCH_OBJ);

		$total = $row->total ?? 0;
		$total += $obj->reqstionitm_val;

		$sql = "
				update trn_reqstion
				set
				reqstion_total = :reqstion_total,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				reqstion_id = :reqstion_id
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':reqstion_total' => $total,
				':reqstion_id' => $obj->reqstion_id,
				':_modifyby' => $userdata->username,
				':_modifydate' => date("Y-m-d H:i:s"),
			]);
	}

	public function PreCheckDelete($data, &$key, &$options)
	{
		$userdata = $this->auth->session_get_user(); 

		$SQL = "SELECT 
			SUM(reqstionitm_val) AS total 
		FROM trn_reqstionitm
		WHERE reqstion_id = :reqstion_id";
		$stmt = $this->db->prepare($SQL);
		$stmt->execute([
			':reqstion_id' => $data->reqstion_id,
		]);

		$row = $stmt->fetch(\PDO::FETCH_OBJ);

		$total = $row->total ?? 0;
		$total -= $data->reqstionitm_val;

		$sql = "
				update trn_reqstion
				set
				reqstion_total = :reqstion_total,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				reqstion_id = :reqstion_id
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':reqstion_total' => $total,
				':reqstion_id' => $data->reqstion_id,
				':_modifyby' => $userdata->username,
				':_modifydate' => date("Y-m-d H:i:s"),
			]);
	}

}		
		
		
		