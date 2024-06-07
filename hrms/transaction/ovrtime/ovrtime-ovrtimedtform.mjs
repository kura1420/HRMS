var this_page_id;
var this_page_options;

import {fgta4slideselect} from  '../../../../../index.php/asset/fgta/framework/fgta4libs/fgta4slideselect.mjs'
import * as hnd from  './ovrtime-ovrtimedtform-hnd.mjs'

const reload_header_modified = true;

const txt_caption = $('#pnl_editovrtimedtform-caption')
const txt_title = $('#pnl_editovrtimedtform-title')
const btn_edit = $('#pnl_editovrtimedtform-btn_edit')
const btn_save = $('#pnl_editovrtimedtform-btn_save')
const btn_delete = $('#pnl_editovrtimedtform-btn_delete')
const btn_prev = $('#pnl_editovrtimedtform-btn_prev')
const btn_next = $('#pnl_editovrtimedtform-btn_next')
const btn_addnew = $('#pnl_editovrtimedtform-btn_addnew')
const chk_autoadd = $('#pnl_editovrtimedtform-autoadd')


const pnl_form = $('#pnl_editovrtimedtform-form')
const obj = {
	txt_ovrtimedt_id: $('#pnl_editovrtimedtform-txt_ovrtimedt_id'),
	txt_ovrtime_id: $('#pnl_editovrtimedtform-txt_ovrtime_id'),
	dt_ovrtimedt_dt: $('#pnl_editovrtimedtform-dt_ovrtimedt_dt'),
	cbo_ovrtime_id_type: $('#pnl_editovrtimedtform-cbo_ovrtime_id_type'),
	txt_ovrtimedt_descr: $('#pnl_editovrtimedtform-txt_ovrtimedt_descr')
}


let form;
let header_data;



export async function init(opt) {
	this_page_id = opt.id
	this_page_options = opt;

	txt_caption.template = txt_caption.html();

	form = new global.fgta4form(pnl_form, {
		primary: obj.txt_ovrtimedt_id,
		autoid: true,
		logview: 'trn_ovrtimedt',
		btn_edit: btn_edit,
		btn_save: btn_save,
		btn_delete: btn_delete,		
		objects : obj,
		OnDataSaving: async (data, options) => { await form_datasaving(data, options) },
		OnDataSaveError: async (data, options) => { await form_datasaveerror(data, options) },
		OnDataSaved: async (result, options) => {  await form_datasaved(result, options) },
		OnDataDeleting: async (data, options) => { await form_deleting(data, options) },
		OnDataDeleted: async (result, options) => { await form_deleted(result, options) },
		OnIdSetup : (options) => { form_idsetup(options) },
		OnViewModeChanged : (viewonly) => { form_viewmodechanged(viewonly) },
		OnGettingData: (data) => { form_gettingdata(data) },

	});
	form.getHeaderData = () => {
		return header_data;
	}	

	form.AllowAddRecord = true
	form.AllowRemoveRecord = true
	form.AllowEditRecord = true
	form.CreateRecordStatusPage(this_page_id)
	form.CreateLogPage(this_page_id)





	obj.cbo_ovrtime_id_type.name = 'pnl_editovrtimedtform-cbo_ovrtime_id_type'		
	new fgta4slideselect(obj.cbo_ovrtime_id_type, {
		title: 'Daftar Type/Day',
		returnpage: this_page_id,
		api: $ui.apis.load_ovrtime_id_type,
		fieldValue: 'ovrtime_id_type',
		fieldDisplay: 'ovrtime_name',
		fieldValueMap: 'ovrtime_id',
		fields: [
			{mapping: 'ovrtime_id', text: 'ID'},
			{mapping: 'ovrtime_name', text: 'Name'}
		],
		OnDataLoading: (criteria, options) => {
			
			if (typeof hnd!=='undefined') { 
				if (typeof hnd.cbo_ovrtime_id_type_dataloading === 'function') {
					hnd.cbo_ovrtime_id_type_dataloading(criteria, options);
				}
			}						
		},					
		OnDataLoaded : (result, options) => {
			
			if (typeof hnd!=='undefined') { 
				if (typeof hnd.cbo_ovrtime_id_type_dataloaded === 'function') {
					hnd.cbo_ovrtime_id_type_dataloaded(result, options);
				}
			}
		},
		OnSelected: (value, display, record, args) => {
			if (value!=args.PreviousValue ) {
				if (typeof hnd!=='undefined') {  
					if (typeof hnd.cbo_ovrtime_id_type_selected === 'function') {
						hnd.cbo_ovrtime_id_type_selected(value, display, record, args);
					}
				}
			}
		},

	})				
			


	btn_addnew.linkbutton({ onClick: () => { btn_addnew_click() }  })
	btn_prev.linkbutton({ onClick: () => { btn_prev_click() } })
	btn_next.linkbutton({ onClick: () => { btn_next_click() } })

	document.addEventListener('keydown', (ev)=>{
		if ($ui.getPages().getCurrentPage()==this_page_id) {
			if (ev.code=='KeyS' && ev.ctrlKey==true) {
				if (!form.isInViewMode()) {
					form.btn_save_click();
				}
				ev.stopPropagation()
				ev.preventDefault()
			}
		}
	}, true)
	
	document.addEventListener('OnButtonBack', (ev) => {
		var element = document.activeElement;
		element.blur();
		if ($ui.getPages().getCurrentPage()==this_page_id) {
			ev.detail.cancel = true;
			if (form.isDataChanged()) {
				form.canceledit(()=>{
					$ui.getPages().show('pnl_editovrtimedtgrid', ()=>{
						form.setViewMode()
						$ui.getPages().ITEMS['pnl_editovrtimedtgrid'].handler.scrolllast()
					})					
				})
			} else {
				$ui.getPages().show('pnl_editovrtimedtgrid', ()=>{
					form.setViewMode()
					$ui.getPages().ITEMS['pnl_editovrtimedtgrid'].handler.scrolllast()
				})
			}
		
		}		
	})

	document.addEventListener('OnButtonHome', (ev) => {
		if ($ui.getPages().getCurrentPage()==this_page_id) {
			ev.detail.cancel = true;
		}
	})

	document.addEventListener('OnSizeRecalculated', (ev) => {
		OnSizeRecalculated(ev.detail.width, ev.detail.height)
	})
	
	
	document.addEventListener('OnViewModeChanged', (ev) => {
		if (ev.detail.viewmode===true) {
			form.lock(true)
			btn_addnew.allow = false
			btn_addnew.linkbutton('disable')
			chk_autoadd.attr("disabled", true);	
			chk_autoadd.prop("checked", false);			
		} else {
			form.lock(false)
			btn_addnew.allow = true
			btn_addnew.linkbutton('enable')
			chk_autoadd.removeAttr("disabled");
			chk_autoadd.prop("checked", false);
		}
	})

	if (typeof hnd.init==='function') {
		hnd.init({
			form: form,
			obj: obj,
			opt: opt
		})
	}

}


export function OnSizeRecalculated(width, height) {
}


export function getForm() {
	return form
}

export function open(data, rowid, hdata) {
	// console.log(header_data)
	header_data = hdata

	var caption = txt_caption.template;
	caption = caption.replace('{{STATE_BEG}}', '');
	caption = caption.replace('{{STATE_END}}', ' View');
	txt_caption.html(caption);

	txt_title.html(header_data.ovrtimedt_dt)
	if (typeof hnd!=='undefined') { 
		if (typeof hnd.setupTitle === 'function') {
			hnd.setupTitle(txt_title, header_data, 'open');
		}
	}

	var pOpt = form.getDefaultPrompt(false)
	var fn_dataopening = async (options) => {
		options.api = `${global.modulefullname}/ovrtimedt-open`
		options.criteria[form.primary.mapping] = data[form.primary.mapping]
	}

	var fn_dataopened = async (result, options) => {
		var record = result.record;
		updatefilebox(result.record);
/*
		if (record.ovrtime_id_type==null) { record.ovrtime_id_type='--NULL--'; record.ovrtime_name='NONE'; }

*/
		for (var objid in obj) {
			let o = obj[objid]
			if (o.isCombo() && !o.isRequired()) {
				var value =  result.record[o.getFieldValueName()];
				if (value==null ) {
					record[o.getFieldValueName()] = pOpt.value;
					record[o.getFieldDisplayName()] = pOpt.text;
				}
			}
		}

		/* handle data saat opening data */   
		if (typeof hnd.form_dataopening == 'function') {
			hnd.form_dataopening(result, options);
		}


		form.SuspendEvent(true);
		form
			.fill(record)
			.setValue(obj.cbo_ovrtime_id_type, record.ovrtime_id_type, record.ovrtime_name)
			.setViewMode()
			.rowid = rowid



		// Editable
		if (form.AllowEditRecord!=true) {
			btn_edit.hide();
			btn_save.hide();
			btn_delete.hide();
		}
		

		// tambah baris
		if (form.AllowAddRecord) {
			btn_addnew.show()
		} else {
			btn_addnew.hide()
		}	

		// hapus baris
		if (form.AllowRemoveRecord) {
			btn_delete.show()
		} else {
			btn_delete.hide()
		}

		var prevnode = $(`#${rowid}`).prev()
		if (prevnode.length>0) {
			btn_prev.linkbutton('enable')
		} else {
			btn_prev.linkbutton('disable')
		}

		var nextode = $(`#${rowid}`).next()
		if (nextode.length>0) {
			btn_next.linkbutton('enable')
		} else {
			btn_next.linkbutton('disable')
		}	


		/* tambahkan event atau behaviour saat form dibuka
		   apabila ada rutin mengubah form dan tidak mau dijalankan pada saat opening,
		   cek dengan form.isEventSuspended()
		*/ 
		if (typeof hnd.form_dataopened == 'function') {
			hnd.form_dataopened(result, options);
		}


		form.commit()
		form.SuspendEvent(false);



	}

	var fn_dataopenerror = (err) => {
		$ui.ShowMessage('[ERROR]'+err.errormessage);
	}

	form.dataload(fn_dataopening, fn_dataopened, fn_dataopenerror)	
}

export function createnew(hdata) {
	header_data = hdata

	var caption = txt_caption.template;
	caption = caption.replace('{{STATE_BEG}}', 'Create New ');
	caption = caption.replace('{{STATE_END}}', '');
	txt_caption.html(caption);

	txt_title.html(header_data.ovrtimedt_dt)
	if (typeof hnd!=='undefined') { 
		if (typeof hnd.setupTitle === 'function') {
			hnd.setupTitle(txt_title, header_data, 'new');
		}
	}

	form.createnew(async (data, options)=>{
		data.ovrtime_id = hdata.ovrtime_id
		data.ovrtimedt_value = 0

		data.ovrtimedt_dt = global.now()

		data.ovrtime_id_type = '--NULL--'
		data.ovrtime_name = 'NONE'

		if (typeof hnd.form_newdata == 'function') {
			hnd.form_newdata(data, options);
		}


		form.rowid = null
		options.OnCanceled = () => {
			$ui.getPages().show('pnl_editovrtimedtgrid')
		}
	})
}


async function form_datasaving(data, options) {
	options.api = `${global.modulefullname}/ovrtimedt-save`

	// options.skipmappingresponse = ['ovrtime_id_type', ];
	options.skipmappingresponse = [];
	for (var objid in obj) {
		var o = obj[objid]
		if (o.isCombo() && !o.isRequired()) {
			var id = o.getFieldValueName()
			options.skipmappingresponse.push(id)
			// console.log(id)
		}
	}

	if (typeof hnd.form_datasaving == 'function') {
		hnd.form_datasaving(data, options);
	}	
}


async function form_datasaveerror(err, options) {
	// apabila mau olah error messagenya
	// $ui.ShowMessage(err.errormessage)
	console.error(err)
	if (typeof hnd.form_datasaveerror == 'function') {
		hnd.form_datasaveerror(err, options);
	}
	if (options.supress_error_dialog!=true) {
		$ui.ShowMessage('[ERROR]'+err.message);
	}
}

async function form_datasaved(result, options) {
	var data = {}
	Object.assign(data, form.getData(), result.dataresponse)

	/*
	form.setValue(obj.cbo_ovrtime_id_type, result.dataresponse.ovrtime_name!=='--NULL--' ? result.dataresponse.ovrtime_id_type : '--NULL--', result.dataresponse.ovrtime_name!=='--NULL--'?result.dataresponse.ovrtime_name:'NONE')

	*/

	var pOpt = form.getDefaultPrompt(false)
	for (var objid in obj) {
		var o = obj[objid]
		if (o.isCombo() && !o.isRequired()) {
			var value =  result.dataresponse[o.getFieldValueName()];
			var text = result.dataresponse[o.getFieldDisplayName()];
			if (value==null ) {
				value = pOpt.value;
				text = pOpt.text;
			}
			form.setValue(o, value, text);
		}
	}
	form.rowid = $ui.getPages().ITEMS['pnl_editovrtimedtgrid'].handler.updategrid(data, form.rowid)
	var rowdata = {
		data: data,
		rowid: form.rowid
	}

	
	var autoadd = chk_autoadd.prop("checked")
	if (autoadd) {
		setTimeout(()=>{
			btn_addnew_click()
		}, 1000)
	}

	if (reload_header_modified) {
		var currentRowdata =  $ui.getPages().ITEMS['pnl_edit'].handler.getCurrentRowdata();
		if (currentRowdata!=null) {
			$ui.getPages().ITEMS['pnl_edit'].handler.open(currentRowdata.data, currentRowdata.rowid, false, (err, data)=>{
				$ui.getPages().ITEMS['pnl_list'].handler.updategrid(data, currentRowdata.rowid);
			});	
		}
	}

	if (typeof hnd.form_datasaved == 'function') {
		hnd.form_datasaved(result, rowdata, options);
	}

}

async function form_deleting(data, options) {
	options.api = `${global.modulefullname}/ovrtimedt-delete`
	if (typeof hnd.form_deleting == 'function') {
		hnd.form_deleting(data);
	}
}

async function form_deleted(result, options) {
	options.suppressdialog = true
	$ui.getPages().show('pnl_editovrtimedtgrid', ()=>{
		$ui.getPages().ITEMS['pnl_editovrtimedtgrid'].handler.removerow(form.rowid)
	});

	if (reload_header_modified) {
		var currentRowdata =  $ui.getPages().ITEMS['pnl_edit'].handler.getCurrentRowdata();
		if (currentRowdata!=null) {
			$ui.getPages().ITEMS['pnl_edit'].handler.open(currentRowdata.data, currentRowdata.rowid, false, (err, data)=>{
				$ui.getPages().ITEMS['pnl_list'].handler.updategrid(data, currentRowdata.rowid);
			});	
		}

	}

	if (typeof hnd.form_deleted == 'function') {
		hnd.form_deleted(result, options);
	}
	
}

function updatefilebox(record) {
	// apabila ada keperluan untuk menampilkan data dari object storage

}


function form_gettingdata(data) {
	if (hnd!=null) {
		if (typeof hnd.form_gettingdata == 'function') {
			hnd.form_gettingdata(data);
		}
	}
}

function form_viewmodechanged(viewonly) {

	console.log('View Mode changed');
	var caption = txt_caption.template;

	if (viewonly) {
		caption = caption.replace('{{STATE_BEG}}', '');
		caption = caption.replace('{{STATE_END}}', ' View');
		txt_caption.html(caption);

		btn_prev.linkbutton('enable')
		btn_next.linkbutton('enable')
		if (btn_addnew.allow) {
			btn_addnew.linkbutton('enable')
		} else {
			btn_addnew.linkbutton('disable')
		}
	} else {
		var currcaption = txt_caption.html();
		if (currcaption.substring(0,10)!='Create New') {
			caption = caption.replace('{{STATE_BEG}}', '');
			caption = caption.replace('{{STATE_END}}', ' Edit');
			txt_caption.html(caption);
		} 

		btn_prev.linkbutton('disable')
		btn_next.linkbutton('disable')
		btn_addnew.linkbutton('disable')
	}
	


	if (typeof hnd.form_viewmodechanged == 'function') {
		hnd.form_viewmodechanged(viewonly);
	}
}


function form_idsetup(options) {
	var objid = obj.txt_ovrtimedt_id
	switch (options.action) {
		case 'fill' :
			objid.textbox('disable') 
			break;

		case 'createnew' :
			// console.log('new')
			if (form.autoid) {
				objid.textbox('disable') 
				objid.textbox('setText', '[AUTO]') 
			} else {
				objid.textbox('enable') 
			}
			break;
			
		case 'save' :
			objid.textbox('disable') 
			break;	
	}
}

function btn_addnew_click() {
	createnew(header_data)
}


function btn_prev_click() {
	var prevode = $(`#${form.rowid}`).prev()
	if (prevode.length==0) {
		return
	} 
	
	var trid = prevode.attr('id')
	var dataid = prevode.attr('dataid')
	var record = $ui.getPages().ITEMS['pnl_editovrtimedtgrid'].handler.getGrid().DATA[dataid]

	if (form.isDataChanged()) {
		var datachangemessage = form.getDataChangeMessage();
		$ui.ShowMessage(datachangemessage, {
			"Ya" : () => {
				open(record, trid, header_data);
			},
			"Tidak" : () => {}
		})
	} else {
		open(record, trid, header_data);
	}
}

function btn_next_click() {
	var nextode = $(`#${form.rowid}`).next()
	if (nextode.length==0) {
		return
	} 

	var trid = nextode.attr('id')
	var dataid = nextode.attr('dataid')
	var record = $ui.getPages().ITEMS['pnl_editovrtimedtgrid'].handler.getGrid().DATA[dataid]

	if (form.isDataChanged()) {
		var datachangemessage = form.getDataChangeMessage();
		$ui.ShowMessage(datachangemessage, {
			"Ya" : () => {
				open(record, trid, header_data);
			},
			"Tidak" : () => {}
		})
	} else {
		open(record, trid, header_data);
	}
}