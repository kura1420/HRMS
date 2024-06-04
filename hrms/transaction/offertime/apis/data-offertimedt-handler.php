<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}



class offertime_offertimedtHandler extends WebAPI  {

	public function DataSaving($obj, $key)
	{
		if ($obj->offertime_id_type == '--NULL--' && $obj->offertimedt_descr == '--NULL--') {
			throw new \Exception('Silahkan isi pada salah satu tipe atau deskripsi');
		}
	}

}		
		
		
		