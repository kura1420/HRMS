let editor, form, obj, opt, empl_profile;

const txt_caption = $('#pnl_edit-caption');

const apiurl = global.modulefullname + '/posting';

const btn_request = $('#pnl_edit-btn_request');
const btn_unrequest = $('#pnl_edit-btn_unrequest');
const btn_approve = $('#pnl_edit-btn_approve');
const btn_decline = $('#pnl_edit-btn_decline');

export function init(ed) {
	editor = ed;
	form = editor.form;
	obj = editor.obj;
	opt = editor.opt;


	empl_profile = opt.variancedata.employee_profile;

	form.setDisable(obj.txt_claim_code, true);
	
	btn_request.linkbutton({ onClick: () => { btn_request_click(); } });
	btn_unrequest.linkbutton({ onClick: () => { btn_unrequest_click(); } });
	btn_approve.linkbutton({ onClick: () => { btn_approve_click(); } });
	btn_decline.linkbutton({ onClick: () => { btn_decline_click(); } });
}

export function form_newdata(data, options)
{
	data.claim_code = '[AUTO]';

	data.docapprv_id = 'CLAIM';
	data.docapprv_name = 'Claim';
	form.setDisable(obj.cbo_docapprv_id, true);

	if (opt.variancedata.is_request_myself) {
		data.empl_id = empl_profile.empl_id;
		data.empl_fullname = empl_profile.empl_fullname;

		form.setDisable(obj.cbo_empl_id, true);
	}

	btn_request.linkbutton('disable');
	btn_unrequest.linkbutton('disable');
	btn_approve.linkbutton('disable');
	btn_decline.linkbutton('disable');
}	

export function form_dataopened(result, options)
{
	const { record } = result;

	form.setDisable(obj.cbo_docapprv_id, true);

	if (opt.variancedata.is_request_myself) {
		form.setDisable(obj.cbo_empl_id, true);
	}

	setButtonRAD(record);
}

export function form_datasaving(data, options)
{
	setButtonRAD(data);
}

function btn_request_click() 
{
	let caption = txt_caption.text();

	if (caption.includes('View')) {
		let args = {
			data: {
				claim_id: form.getValue(obj.txt_claim_id),
				docapprv_id: form.getValue(obj.cbo_docapprv_id),
				event: 'request',
			},
			options: {
				api: apiurl,
			},
		};

		posting(args);
	} else {
		$ui.ShowMessage('[WARNING] Anda masih dalam mode EDIT');
	}
}

function btn_unrequest_click() 
{
	let caption = txt_caption.text();

	if (caption.includes('View')) {
		let args = {
			data: {
				claim_id: form.getValue(obj.txt_claim_id),
				docapprv_id: form.getValue(obj.cbo_docapprv_id),
				event: 'unrequest',
			},
			options: {
				api: apiurl,
			},
		};

		posting(args);
	} else {
		$ui.ShowMessage('[WARNING] Anda masih dalam mode EDIT');
	}
}

function btn_approve_click() 
{
	let caption = txt_caption.text();

	if (caption.includes('View')) {
		let args = {
			data: {
				claim_id: form.getValue(obj.txt_claim_id),
				docapprv_id: form.getValue(obj.cbo_docapprv_id),
				event: 'approve',
			},
			options: {
				api: apiurl,
			},
		};

		posting(args);
	} else {
		$ui.ShowMessage('[WARNING] Anda masih dalam mode EDIT');
	}
}

async function btn_decline_click() 
{
	let caption = txt_caption.text();

	if (caption.includes('View')) {
		$.messager.prompt('Reason', 'Notes for decline', function(r){
			if (r){
				let args = {
					data: {
						claim_id: form.getValue(obj.txt_claim_id),
						docapprv_id: form.getValue(obj.cbo_docapprv_id),
						claim_rejectnotes: r,
						event: 'decline',
					},
					options: {
						api: apiurl,
					},
				};
		
				posting(args);
			}
		});
	} else {
		$ui.ShowMessage('[WARNING] Anda masih dalam mode EDIT');
	}
}

async function posting(args) 
{
	try {
		$ui.mask('executing api...');

		let result = await $ui.apicall(apiurl, args);

		$ui.unmask();

		let { message, dataresponse } = result;

		$ui.ShowMessage(`[INFO] ${message}`);

		form.setValue(obj.chk_claim_isrequest, dataresponse.claim_isrequest);
		form.setValue(obj.chk_claim_isapproved, dataresponse.claim_isapproved);
		form.setValue(obj.chk_claim_isdeclined, dataresponse.claim_isdeclined);
		form.setValue(obj.txt_claim_rejectnotes, dataresponse.claim_rejectnotes);

		setButtonRAD(dataresponse);

		var data = {}
		Object.assign(data, form.getData(), result.dataresponse);

		$ui.getPages().ITEMS['pnl_list'].handler.updategrid(data, form.rowid);

		form.commit();
	} catch (error) {
		let { message } = error;

		$ui.unmask();
		$ui.ShowMessage(`[ERROR] ${message}`);
	}
}

function setButtonRAD(record)
{
	let btn_request_on = true;
	let btn_unrequest_on = false;
	let btn_approve_on = false;
	let btn_decline_on = false;

	if (opt.variancedata.is_request_myself == true && opt.variancedata.can_approve == false) {
		if (record.claim_isrequest == 0) {
			btn_unrequest_on = false;
			btn_approve_on = false;
			btn_decline_on = false;

			$('#pnl_edit-btn_edit').linkbutton('enable');
		}

		if (record.claim_isrequest == 1) {
			btn_request_on = false;
			btn_unrequest_on = true;
			btn_approve_on = false;
			btn_decline_on = false;

			$('#pnl_edit-btn_edit').linkbutton('disable');
		}

		if (record.claim_isapproved == 1 || record.claim_isdeclined == 1) {
			btn_request_on = false;
			btn_unrequest_on = false;
			btn_approve_on = false;
			btn_decline_on = false;

			$('#pnl_edit-btn_edit').linkbutton('disable');
		}
	}

	if (opt.variancedata.is_request_myself == true && opt.variancedata.can_approve == true) {
		if (record.claim_isrequest == 0) {
			btn_unrequest_on = false;
			btn_approve_on = false;
			btn_decline_on = false;

			$('#pnl_edit-btn_edit').linkbutton('enable');
		}

		if (record.claim_isrequest == 1) {
			if (record.empl_id == empl_profile.empl_id) {
				btn_unrequest_on = true;
			} else {
				btn_unrequest_on = false;
			}

			btn_request_on = false;
			
			btn_approve_on = true;
			btn_decline_on = true;

			$('#pnl_edit-btn_edit').linkbutton('disable');
		}

		if (record.claim_isapproved == 1 || record.claim_isdeclined == 1) {
			btn_request_on = false;
			btn_unrequest_on = false;
			btn_approve_on = false;
			btn_decline_on = false;

			$('#pnl_edit-btn_edit').linkbutton('disable');
		}
	}

	btn_request.linkbutton(btn_request_on ? 'enable' : 'disable');
	btn_unrequest.linkbutton(btn_unrequest_on ? 'enable' : 'disable');
	btn_approve.linkbutton(btn_approve_on ? 'enable' : 'disable');
	btn_decline.linkbutton(btn_decline_on ? 'enable' : 'disable');
}