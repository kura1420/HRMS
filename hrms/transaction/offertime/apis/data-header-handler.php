<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}



class offertime_headerHandler extends WebAPI  {

	public function buildListCriteriaValues(object &$options, array &$criteriaValues) : void
	{
		$criteriaValues['empl_id'] = " A.empl_id = :empl_id "; 
		$criteriaValues['ra_empl_id'] = " A.empl_id = :ra_empl_id OR (A.empl_id != :ra_empl_id AND A.offertime_isrequest = 1)"; 
	}

	public function PreCheckInsert($data, $obj, $options) {
		try {
			$obj->offertime_code = $this->generateCode();
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	protected function generateCode() {
		$sql = "SELECT offertime_code FROM trn_offertime ORDER BY _createdate DESC LIMIT 1";
		$res = $this->db->query($sql);
		$offertime_code = $res->fetchColumn();

		if ($offertime_code) {
			$split = explode('/', $offertime_code);
			$count = intval($split[3]) + 1;
		} else {
			$count = 1;
		}
		$code = str_pad($count, 6, '0', STR_PAD_LEFT);

		$tahun = date('Y');
		$bulan = date('m');
		
		$kode = 'OFTR/' . $tahun . '/' . $bulan . '/' . $code;
		return $kode;
	}

}		
		
		
		