<?php namespace FGTA4\apis;

if (!defined('FGTA4')) {
	die('Forbiden');
}



class division_headerHandler extends WebAPI  {

	public function buildListCriteriaValues(object &$options, array &$criteriaValues) : void
	{
		$criteriaValues['division_isdisabled'] = " A.division_isdisabled = :division_isdisabled";
		$criteriaValues['dept_id'] = " A.dept_id = :dept_id";
	}

}		
		
		
		