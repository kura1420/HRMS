let editor, form, obj, opt, empl_profile;

const apiurl = global.modulefullname + '/posting';

const btn_request = $('#pnl_edit-btn_request')
const btn_unrequest = $('#pnl_edit-btn_unrequest')
const btn_approve = $('#pnl_edit-btn_approve')
const btn_decline = $('#pnl_edit-btn_decline')

export function init(ed) {
	editor = ed;
	form = editor.form;
	obj = editor.obj;
	opt = editor.opt;


	empl_profile = opt.variancedata.employee_profile;
	
}

	
export function form_newdata(data, options)
{
	data.reqstion_code = '[AUTO]';

	data.docapprv_id = 'PURREQ';
	data.docapprv_name = 'Purchase Requisition';

	if (opt.variancedata.is_request_myself) {
		data.empl_id = empl_profile.empl_id;
		data.empl_fullname = empl_profile.empl_fullname;
	}
}		

export function form_dataopened(result, options)
{
	const { record } = result;

	setButtonRAD(record);
}

export function form_datasaving(data, options)
{
	setButtonRAD(data);
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
				reqstion_rejectnotes: r,
			};
		
			posting(params);
		}
	});
}

async function posting(params) 
{
	try {
		let args = {
			data: {
				empl_id: form.getValue(obj.cbo_empl_id),
				reqstion_id: form.getValue(obj.txt_reqstion_id),
				docapprv_id: form.getValue(obj.cbo_docapprv_id),
			},
			options: {
				api: apiurl,
			},
		};
	
		args.data.event = params.event;

		if (params.reqstion_rejectnotes) {
			args.data.reqstion_rejectnotes = params.reqstion_rejectnotes;
		}

		$ui.mask('executing api...');
		
		let result = await $ui.apicall(apiurl, args);

		$ui.unmask();

		let { message, dataresponse } = result;

		$ui.ShowMessage(`[INFO] ${message}`);

		form.setValue(obj.chk_reqstion_isrequest, dataresponse.reqstion_isrequest);
		form.setValue(obj.chk_reqstion_isapproved, dataresponse.reqstion_isapproved);
		form.setValue(obj.chk_reqstion_isdecline, dataresponse.reqstion_isdecline);
		form.setValue(obj.txt_reqstion_rejectnotes, dataresponse.reqstion_rejectnotes);
		form.setValue(obj.chk_reqstion_ispayment, dataresponse.reqstion_ispayment);

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
	if (opt.variancedata.is_request_myself == true && opt.variancedata.can_approve == false) {
		permissionRequest(record);
	}

	console.log(opt.variancedata.is_request_myself, opt.variancedata.can_approve);
	if (opt.variancedata.is_request_myself == true && opt.variancedata.can_approve == true) {
		permissionRequestApprove(record)
	}
}

function permissionRequest(record)
{
	let btn_request_on = true;
	let btn_unrequest_on = false;
	let btn_approve_on = false;
	let btn_decline_on = false;

	if (record.reqstion_isrequest == 0) {
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;

		$('#pnl_edit-btn_edit').linkbutton('enable');
	}

	if (record.reqstion_isrequest == 1) {
		btn_request_on = false;
		btn_unrequest_on = true;
		btn_approve_on = false;
		btn_decline_on = false;

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	if (record.reqstion_isapproved == 1 || record.reqstion_isdecline == 1) {
		btn_request_on = false;
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	btn_request.linkbutton(btn_request_on ? 'enable' : 'disable');
	btn_unrequest.linkbutton(btn_unrequest_on ? 'enable' : 'disable');
	btn_approve.linkbutton(btn_approve_on ? 'enable' : 'disable');
	btn_decline.linkbutton(btn_decline_on ? 'enable' : 'disable');
}

function permissionRequestApprove(record)
{
	let btn_request_on = true;
	let btn_unrequest_on = false;
	let btn_approve_on = false;
	let btn_decline_on = false;

	if (record.reqstion_isrequest == 0) {
		btn_unrequest_on = false;
		btn_approve_on = false;
		btn_decline_on = false;

		$('#pnl_edit-btn_edit').linkbutton('enable');
	}

	if (record.reqstion_isrequest == 1) {
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

	if (record.reqstion_isapproved == 1 || record.reqstion_isdecline == 1) {

		if (record.docapprvlevl_sortorder == record.reqstion_isapprovalprogress) {
			btn_approve_on = false;
			btn_decline_on = false;
		} else {
			btn_approve_on = true;
			btn_decline_on = true;
		}

		$('#pnl_edit-btn_edit').linkbutton('disable');
	}

	btn_request.linkbutton(btn_request_on ? 'enable' : 'disable');
	btn_unrequest.linkbutton(btn_unrequest_on ? 'enable' : 'disable');
	btn_approve.linkbutton(btn_approve_on ? 'enable' : 'disable');
	btn_decline.linkbutton(btn_decline_on ? 'enable' : 'disable');
}