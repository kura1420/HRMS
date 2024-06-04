<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}



class paidleave_headerHandler extends WebAPI  {

	public function DataSaving($obj, $key) {

		try {
			if ($obj->paidleave_expenable) {
				if ($obj->paidleave_qty <= 0) {
					throw new \Exception("Jumlah cuti harus lebih dari 0");
				}

				if ($obj->period_id == '--NULL--') {
					throw new \Exception("Periode harus diisi");
				}

				$obj->paidleave_iscutting = 1;
			} else {
				if ($obj->paidleave_qty > 0) {
					$obj->paidleave_qty = 0;
				}

				if ($obj->period_id != '--NULL--') {
					$obj->period_id = '--NULL--';
				}
			}
		} catch (\Exception $ex) {
			throw $ex;
		}
	}

}		
		
		
		