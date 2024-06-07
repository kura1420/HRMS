<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}



class month_headerHandler extends WebAPI  {

	public function sortListOrder(array &$sortData) : void
	{
		$sortData['_createdate'] = 'ASC';
	}

}		
		
		
		