let grd_list, opt, empl_profile;
var this_page_id;
var this_page_options;

export function init(param, fn_callback) {
	grd_list = param.grd_list;
	opt = param.opt;
	this_page_id = opt.id;
	this_page_options = opt;	

	empl_profile = opt.variancedata.employee_profile;
	
	fn_callback();

}

export function customsearch(options)
{
	if (opt.variancedata.is_request_myself == true && opt.variancedata.can_approve == false) {
		options.criteria['empl_id'] = empl_profile.empl_id
	}

	if (opt.variancedata.is_request_myself == true &&opt.variancedata.can_approve == true) {
		options.criteria['ra_empl_id'] = empl_profile.empl_id
	}
}		