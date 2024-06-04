let editor, form, obj, opt;

export function init(ed) {
	editor = ed;
	form = editor.form;
	obj = editor.obj;
	opt = editor.opt;

	obj.chk_paidleave_expenable.checkbox({
		onChange: function (checked) {
			obj.txt_paidleave_qty.numberbox('setValue', 0);
			form.setValue(obj.cbo_period_id, '--NULL--', 'NONE');

			if (checked) {
				form.setDisable(obj.txt_paidleave_qty, false);
				form.setDisable(obj.cbo_period_id, false);
			} else {
				form.setDisable(obj.txt_paidleave_qty, true);
				form.setDisable(obj.cbo_period_id, true);
			}
		}
	});

	
}

export function form_newdata(data, options) {
	form.setDisable(obj.txt_paidleave_qty, true);
	form.setDisable(obj.cbo_period_id, true);
}