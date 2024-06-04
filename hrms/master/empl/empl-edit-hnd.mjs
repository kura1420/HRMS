let editor, form, obj, opt;

export function init(ed) {
	editor = ed;
	form = editor.form;
	obj = editor.obj;
	opt = editor.opt;



	
}

export function cbo_division_id_dataloading(criteria, options) {
	criteria.division_isdisabled = 0;
}
	
export function cbo_division_id_dataloaded(result, options) {
	const { records } = result;

	records.forEach((record, key) => {
		if (key === 0) {
			record.division_code = '-';
		}
	});
}	

export function cbo_user_id_dataloading(criteria, options) {
	criteria.user_disabled = 0;
}

export function cbo_user_id_dataloaded(result, options) {
	const { records } = result;

	records.forEach((record, key) => {
		if (key === 0) {
			record.user_email = '-';
		}
	});
}