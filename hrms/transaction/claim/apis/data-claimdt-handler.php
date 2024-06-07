<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}



class claim_claimdtHandler extends WebAPI  {

	public function DataSaving(object &$obj, object &$key) : void
	{
		$userdata = $this->auth->session_get_user(); 

		$SQL = "SELECT * FROM trn_claim WHERE claim_id = :claim_id";
		$stmt = $this->db->prepare($SQL);
		$stmt->execute([
			':claim_id' => $obj->claim_id
		]);
		$claim = $stmt->fetch(\PDO::FETCH_OBJ);

		$detailDate = date('n', strtotime($obj->claimdt_dt));

		if ($detailDate != $claim->month_id) {
			throw new \Exception('Bulan dan tanggal claim tidak sesuai');
		}

		$SQL = "SELECT 
			SUM(claimdt_val) AS total 
		FROM trn_claimdt 
		WHERE claim_id = :claim_id AND claimdt_id != :claimdt_id";
		$stmt = $this->db->prepare($SQL);
		$stmt->execute([
			':claim_id' => $obj->claim_id,
			':claimdt_id' => $obj->claimdt_id,
		]);

		$row = $stmt->fetch(\PDO::FETCH_OBJ);

		$total = $row->total ?? 0;
		$total += $obj->claimdt_val;

		$sql = "
				update trn_claim
				set
				claim_total = :claim_total,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				claim_id = :claim_id
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':claim_total' => $total,
				':claim_id' => $obj->claim_id,
				':_modifyby' => $userdata->username,
				':_modifydate' => date("Y-m-d H:i:s"),
			]);
	}

	public function PreCheckDelete($data, &$key, &$options)
	{
		$userdata = $this->auth->session_get_user(); 

		$SQL = "SELECT 
			SUM(claimdt_val) AS total 
		FROM trn_claimdt 
		WHERE claim_id = :claim_id";
		$stmt = $this->db->prepare($SQL);
		$stmt->execute([
			':claim_id' => $data->claim_id,
		]);

		$row = $stmt->fetch(\PDO::FETCH_OBJ);

		$total = $row->total ?? 0;
		$total -= $data->claimdt_val;

		$sql = "
				update trn_claim
				set
				claim_total = :claim_total,
				_modifyby = :_modifyby,
				_modifydate = :_modifydate
				where
				claim_id = :claim_id
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':claim_total' => $total,
				':claim_id' => $data->claim_id,
				':_modifyby' => $userdata->username,
				':_modifydate' => date("Y-m-d H:i:s"),
			]);
	}

}		
		
		
		