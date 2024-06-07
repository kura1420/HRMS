let editor, form, obj, opt, empl_profile;

const btn_request = $('#pnl_edit-btn_request')
const btn_unrequest = $('#pnl_edit-btn_unrequest')
const btn_approve = $('#pnl_edit-btn_approve')
const btn_decline = $('#pnl_edit-btn_decline')
const btn_bayar = $('#pnl_edit-btn_bayar')

const apiurl = global.modulefullname + '/posting';

export function init(ed) {
	editor = ed;
	form = editor.form;
	obj = editor.obj;
	opt = editor.opt;

	empl_profile = opt.variancedata.employee_profile;

	
}

	
export function form_newdata(data, options)
{
	data.ovrtime_code = '[AUTO]';

	data.docapprv_id = 'OVRTIME';
	data.docapprv_name = 'Overtime';

	if (opt.variancedata.is_request_myself) {
		data.empl_id = empl_profile.empl_id;
		data.empl_fullname = empl_profile.empl_fullname;
	}

	btn_request.linkbutton('disable');
	btn_unrequest.linkbutton('disable');
	btn_approve.linkbutton('disable');
	btn_decline.linkbutton('disable');
	btn_bayar.linkbutton('disable');
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

export function btn_decline_click()
{
	$.messager.prompt('Reason', 'Notes for decline', function(r){
		if (r) {
			let params = {
				event: 'decline',
				ovrtime_rejectnotes: r,
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
				ovrtime_id: form.getValue(obj.txt_ovrtime_id),
				docapprv_id: form.getValue(obj.cbo_docapprv_id),
			},
			options: {
				api: apiurl,
			},
		};
	
		args.data.event = params.event;

		if (params.ovrtime_rejectnotes) {
			args.data.ovrtime_rejectnotes = params.ovrtime_rejectnotes;
		}

		$ui.mask('executing api...');
		
		let result = await $ui.apicall(apiurl, args);

		$ui.unmask();

		let { message, dataresponse } = result;

		$ui.ShowMessage(`[INFO] ${message}`);

		form.setValue(obj.chk_ovrtime_isrequest, dataresponse.ovrtime_isrequest);
		form.setValue(obj.chk_ovrtime_isapproved, dataresponse.ovrtime_isapproved);
		form.setValue(obj.chk_ovrtime_isdecline, dataresponse.ovrtime_isdecline);
		form.setValue(obj.txt_ovrtime_rejectnotes, dataresponse.ovrtime_rejectnotes);
		form.setValue(obj.chk_ovrtime_ispayment, dataresponse.ovrtime_ispayment);

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

	if (record.ovrtime_isrequest == 0) {
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('enable');
	}

	if (record.ovrtime_isrequest == 1) {
		btn_request_on = false;
		btn_unrequest_on = true;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	if (record.ovrtime_isapproved == 1 || record.ovrtime_isdecline == 1 || record.ovrtime_ispayment == 1) {
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

	if (record.ovrtime_isrequest == 0) {
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('enable');
	}

	if (record.ovrtime_isrequest == 1) {
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

	if (record.ovrtime_isapproved == 1 || record.ovrtime_isdecline == 1 || record.ovrtime_ispayment == 1) {
		btn_request_on = false;
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	if (record.ovrtime_ispayment == 1) {
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

	if (record.ovrtime_isrequest == 0) {
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('enable');
	}

	if (record.ovrtime_isrequest == 1) {
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

	if (record.ovrtime_isapproved == 1 && record.ovrtime_ispayment == 0) {
		btn_request_on = false;
		btn_unrequest_on = false;

		if (record.docapprvlevl_sortorder == record.ovrtime_isapprovalprogress) {
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

	if (record.ovrtime_isdecline == 1) {
		btn_request_on = false;
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;
		btn_bayar_on = false;

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	if (record.ovrtime_ispayment == 1) {
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