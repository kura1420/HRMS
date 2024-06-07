<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}



class dept_headerHandler extends WebAPI  {

	public function buildListCriteriaValues(object &$options, array &$criteriaValues) : void
	{
		$criteriaValues['dept_isdisabled'] = " A.dept_isdisabled = :dept_isdisabled";
	}

}		
		
		
		