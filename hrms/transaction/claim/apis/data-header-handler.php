<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}



class claim_headerHandler extends WebAPI  {

	public function buildListCriteriaValues(object &$options, array &$criteriaValues) : void
	{
		$criteriaValues['empl_id'] = " A.empl_id = :empl_id "; 
		$criteriaValues['ra_empl_id'] = " A.empl_id = :ra_empl_id OR (A.empl_id != :ra_empl_id AND A.claim_isrequest = 1)"; 
	}

	public function PreCheckInsert($data, $obj, $options) {
		try {
			$obj->claim_code = $this->generateCode();
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	protected function generateCode() {
		$sql = "SELECT claim_code FROM trn_claim ORDER BY _createdate DESC LIMIT 1";
		$res = $this->db->query($sql);
		$claim_code = $res->fetchColumn();

		if ($claim_code) {
			$split = explode('/', $claim_code);
			$count = intval($split[3]) + 1;
		} else {
			$count = 1;
		}
		$code = str_pad($count, 6, '0', STR_PAD_LEFT);

		$tahun = date('Y');
		$bulan = date('m');
		
		$kode = 'CLAIM/' . $tahun . '/' . $bulan . '/' . $code;
		return $kode;
	}

	public function DataOpen(array &$record) : void 
	{
		$SQL = "SELECT * FROM mst_docapprvlevl WHERE docapprv_id = :docapprv_id ORDER BY docapprvlevl_sortorder DESC LIMIT 1";
		$stmt = $this->db->prepare($SQL);
		$stmt->execute(['docapprv_id' => $record['docapprv_id']]);
		$row = $stmt->fetch(\PDO::FETCH_OBJ);

		$record['docapprvlevl_sortorder'] = $row->docapprvlevl_sortorder;
	}

}		
		
		
		