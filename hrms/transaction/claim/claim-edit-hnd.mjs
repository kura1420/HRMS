let editor, form, obj, opt, empl_profile;

const apiurl = global.modulefullname + '/posting';

const btn_request = $('#pnl_edit-btn_request')
const btn_unrequest = $('#pnl_edit-btn_unrequest')
const btn_approve = $('#pnl_edit-btn_approve')
const btn_decline = $('#pnl_edit-btn_decline')
const btn_bayar = $('#pnl_edit-btn_bayar')

export function init(ed) {
	editor = ed;
	form = editor.form;
	obj = editor.obj;
	opt = editor.opt;


	empl_profile = opt.variancedata.employee_profile;
}

export function form_newdata(data, options)
{
	data.claim_code = '[AUTO]';

	data.docapprv_id = 'CLAIM';
	data.docapprv_name = 'Claim';

	if (opt.variancedata.is_request_myself) {
		data.empl_id = empl_profile.empl_id;
		data.empl_fullname = empl_profile.empl_fullname;
	}
}	

export function form_dataopened(result, options)
{
	const { record } = result;

	setButtonRADP(record);
}

export function form_datasaving(data, options)
{
	setButtonRADP(data);
}

export function btn_request_click() 
{
	let params = {
		event: 'request',
	};

	posting(params);
}

export function btn_unrequest_click() 
{
	let params = {
		event: 'unrequest',
	};

	posting(params);
}

export function btn_approve_click() 
{
	let params = {
		event: 'approve',
	};

	posting(params);
}

export async function btn_decline_click() 
{
	$.messager.prompt('Reason', 'Notes for decline', function(r){
		if (r) {
			let params = {
				event: 'decline',
				claim_rejectnotes: r,
			};
		
			posting(params);
		}
	});
}

export function btn_bayar_click()
{
	let params = {
		event: 'payment',
	};

	posting(params);
}

async function posting(params) 
{
	try {
		let args = {
			data: {
				empl_id: form.getValue(obj.cbo_empl_id),
				claim_id: form.getValue(obj.txt_claim_id),
				docapprv_id: form.getValue(obj.cbo_docapprv_id),
			},
			options: {
				api: apiurl,
			},
		};
	
		args.data.event = params.event;

		if (params.claim_rejectnotes) {
			args.data.claim_rejectnotes = params.claim_rejectnotes;
		}

		$ui.mask('executing api...');
		
		let result = await $ui.apicall(apiurl, args);

		$ui.unmask();

		let { message, dataresponse } = result;

		$ui.ShowMessage(`[INFO] ${message}`);

		form.setValue(obj.chk_claim_isrequest, dataresponse.claim_isrequest);
		form.setValue(obj.chk_claim_isapproved, dataresponse.claim_isapproved);
		form.setValue(obj.chk_claim_isdecline, dataresponse.claim_isdecline);
		form.setValue(obj.txt_claim_rejectnotes, dataresponse.claim_rejectnotes);
		form.setValue(obj.chk_claim_ispayment, dataresponse.claim_ispayment);

		setButtonRADP(dataresponse);

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

function setButtonRADP(record)
{
	if (opt.variancedata.is_request_myself == true && opt.variancedata.can_approve == false && opt.variancedata.can_payment == false) {
		permissionRequest(record);
	}

	if (opt.variancedata.is_request_myself == true && opt.variancedata.can_approve == true && opt.variancedata.can_payment == false) {
		permissionRequestApprove(record)
	}

	if (opt.variancedata.is_request_myself == true && opt.variancedata.can_approve == true && opt.variancedata.can_payment == true) {
		permissionApprovePayment(record)
	}
}

function permissionRequest(record)
{
	let btn_request_on = true;
	let btn_unrequest_on = false;
	let btn_approve_on = false;
	let btn_decline_on = false;
	let btn_bayar_on = false;

	if (record.claim_isrequest == 0) {
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('enable');
	}

	if (record.claim_isrequest == 1) {
		btn_request_on = false;
		btn_unrequest_on = true;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	if (record.claim_isapproved == 1 || record.claim_isdecline == 1 || record.claim_ispayment == 1) {
		btn_request_on = false;
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	btn_request.linkbutton(btn_request_on ? 'enable' : 'disable');
	btn_unrequest.linkbutton(btn_unrequest_on ? 'enable' : 'disable');
	btn_approve.linkbutton(btn_approve_on ? 'enable' : 'disable');
	btn_decline.linkbutton(btn_decline_on ? 'enable' : 'disable');
	btn_bayar.linkbutton(btn_bayar_on ? 'enable' : 'disable');
}

function permissionRequestApprove(record)
{
	let btn_request_on = true;
	let btn_unrequest_on = false;
	let btn_approve_on = false;
	let btn_decline_on = false;
	let btn_bayar_on = false;

	if (record.claim_isrequest == 0) {
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

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
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	if (record.claim_isapproved == 1 || record.claim_isdecline == 1 || record.claim_ispayment == 1) {
		btn_request_on = false;
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	if (record.claim_ispayment == 1) {
		btn_request_on = false;
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	btn_request.linkbutton(btn_request_on ? 'enable' : 'disable');
	btn_unrequest.linkbutton(btn_unrequest_on ? 'enable' : 'disable');
	btn_approve.linkbutton(btn_approve_on ? 'enable' : 'disable');
	btn_decline.linkbutton(btn_decline_on ? 'enable' : 'disable');
	btn_bayar.linkbutton(btn_bayar_on ? 'enable' : 'disable');
}

function permissionApprovePayment(record)
{
	let btn_request_on = true;
	let btn_unrequest_on = false;
	let btn_approve_on = false;
	let btn_decline_on = false;
	let btn_bayar_on = false;

	if (record.claim_isrequest == 0) {
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

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
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	if (record.claim_isapproved == 1 && record.claim_ispayment == 0) {
		btn_request_on = false;
		btn_unrequest_on = false;

		if (record.docapprvlevl_sortorder == record.claim_isapprovalprogress) {
			btn_approve_on = false;
			btn_decline_on = false;
			btn_bayar_on = true;
		} else {
			btn_approve_on = true;
			btn_decline_on = true;
			btn_bayar_on = false;	
		}

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	if (record.claim_isdecline == 1) {
		btn_request_on = false;
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	if (record.claim_ispayment == 1) {
		btn_request_on = false;
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	btn_request.linkbutton(btn_request_on ? 'enable' : 'disable');
	btn_unrequest.linkbutton(btn_unrequest_on ? 'enable' : 'disable');
	btn_approve.linkbutton(btn_approve_on ? 'enable' : 'disable');
	btn_decline.linkbutton(btn_decline_on ? 'enable' : 'disable');
	btn_bayar.linkbutton(btn_bayar_on ? 'enable' : 'disable');
}