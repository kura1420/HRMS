'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Overtime",
	autoid: true,
	printing: true,

	persistent: {
		trn_ovrtime: {
			primarykeys: ['ovrtime_id'],
			comment: 'Overtime',
			data: {
				ovrtime_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, },
				ovrtime_code: {text:'Code', type: dbtype.varchar(50), null:false, uppercase: true, suppresslist:false, options: {disabled:true,} },

				empl_id: {
					text:'Employee', type: dbtype.varchar(36), null:false, suppresslist: false,
					options:{required:true,invalidMessage:'Employee required', prompt:'-- PILIH --', disabled:true,},
					comp: comp.Combo({
						table: 'mst_empl',
						field_value: 'empl_id', field_display: 'empl_fullname',
						api: 'hrms/master/empl/list',
						title: 'Daftar Employee',
						field_mappings: [
							`{mapping: 'empl_id', text: 'ID'}`,
							`{mapping: 'empl_fullname',  text: 'Name'}`,
						],
						onDataLoadingHandler: true,
						onDataLoadedHandler: true,
						onSelectedHandler: true,
						staticfilter: `
            criteria.empl_isexit = 0;
        `,
					}),
				},

				docapprv_id: {
					text:'Doc. Approval', type: dbtype.varchar(36), null:false, suppresslist: true, hidden: true,
					options:{required:true,invalidMessage:'Doc. Approval', prompt:'-- PILIH --', disabled:true,},
					comp: comp.Combo({
						table: 'mst_docapprv',
						field_value: 'docapprv_id', field_display: 'docapprv_name',
						api: 'global/setup/docapprv/list',
						title: 'Daftar Doc. Approval',
						field_mappings: [
							`{mapping: 'docapprv_id', text: 'ID'}`,
							`{mapping: 'docapprv_name',  text: 'Name'}`,
						],
						onDataLoadingHandler: true,
						onDataLoadedHandler: true,
						onSelectedHandler: true,
					}),
					section: section.Begin('Doc. Status'),
				},

				ovrtime_rejectnotes: { text: 'Reject Notes', type: dbtype.varchar(255), null: true,  unset:true, suppresslist: true, options:{disabled: true, multiline:true} },
				ovrtime_isrequest: {text:'Request', type: dbtype.boolean, null:false, default:'0', unset:true, suppresslist: true, options:{disabled:true}},
				ovrtime_requestby: {text:'RequestBy', type: dbtype.varchar(14), suppresslist: true, unset:true, options:{disabled:true}, hidden: true, lookup:'user'},
				ovrtime_requestdate: {text:'RequestDate', type: dbtype.datetime, suppresslist: false, unset:true, comp:comp.Textbox(), options:{disabled:true}, hidden: true},	
				ovrtime_isapproved: { text: 'Approved', type: dbtype.boolean, null: false, default: '0', unset:true, suppresslist: true, options: { disabled: true } },
				ovrtime_approveby: { text: 'Approve By', type: dbtype.varchar(14), suppresslist: true, unset:true, options: { disabled: true }, hidden: true, lookup:'user' },
				ovrtime_approvedate: { text: 'Approve Date', type: dbtype.datetime, suppresslist: false, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true },
				ovrtime_isapprovalprogress: {text:'Progress', type: dbtype.boolean, null:false, default:'0', unset:true, suppresslist: true, options:{disabled:true}, hidden: true},
				ovrtime_isdecline: { text: 'Decline', type: dbtype.boolean, null: false, default: '0', unset:true, suppresslist: true, options: { disabled: true } },
				ovrtime_declineby: { text: 'Decline By', type: dbtype.varchar(14), suppresslist: true, unset:true, options: { disabled: true }, hidden: true, lookup:'user' },
				ovrtime_declinedate: { text: 'Decline Date', type: dbtype.datetime, suppresslist: false, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true },
				ovrtime_ispayment: { text: 'Payment', type: dbtype.boolean, null: false, default: '0', unset:true, suppresslist: true, options: { disabled: true } },
				ovrtime_paymentby: { text: 'Payment By', type: dbtype.varchar(14), suppresslist: true, unset:true, options: { disabled: true }, hidden: true, lookup:'user' },
				ovrtime_paymentdate: { text: 'Payment Date', type: dbtype.datetime, suppresslist: false, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true },
				ovrtime_executeby: { text: 'Execute By', type: dbtype.varchar(14), suppresslist: false, unset:true, options: { disabled: true }, lookup:'user' },
				ovrtime_executedate: { 
					text: 'Execute Date', type: dbtype.datetime, suppresslist: true, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true,
					section: section.End(), 
				},
			},
			uniques: {
				ovrtime_code: ['ovrtime_code'],
			},	
			defaultsearch: ['ovrtime_code'],
		},

		trn_ovrtimedt: {
			primarykeys: ['ovrtimedt_id'],
			comment: 'Overtime Date',
			data: {
				ovrtimedt_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, },
				ovrtime_id: {text:'Overtime', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, hidden:true, },
				ovrtimedt_dt: {text:'Date', type: dbtype.date, null:false, suppresslist:false, options: {required: true, invalidMessage: 'Date required'} },
				ovrtime_id_type: {
					text:'Type/Day', type: dbtype.varchar(36), null:true, suppresslist: false,
					options:{prompt:'-- PILIH --'},
					tips: '*Wajib memilih tipe atau mengisi keterangan', tipstype: 'visible',
					comp: comp.Combo({
						table: 'mst_ovrtime',
						field_value: 'ovrtime_id', field_display: 'ovrtime_name',
						api: 'hrms/master/ovrtime/list',
						title: 'Daftar Type/Day',
						field_mappings: [
							`{mapping: 'ovrtime_id', text: 'ID'}`,
							`{mapping: 'ovrtime_name',  text: 'Name'}`,
						],
						onDataLoadingHandler: true,
						onDataLoadedHandler: true,
						onSelectedHandler: true,
					}),
				},
				ovrtimedt_descr: {text:'Descr', type: dbtype.varchar(255), null:true, suppresslist:true, options:{multiline: true,}},
			},
			uniques: {
			},
		},
	},

	schema: {
		title: 'Overtime',
		header: 'trn_ovrtime',
		detils: {
			ovrtimedt : {
				title: 'Date & Descr', table: 'trn_ovrtimedt', form: true, headerview: 'ovrtimedt_dt',
				editorHandler: true,
				listHandler: true
			},
		},
		xtions: {
			request: {
				buttonname: 'btn_request',
				buttontext: 'Request',
			},
			unrequest: {
				buttonname: 'btn_unrequest',
				buttontext: 'Unrequest',
			},
			approve: {
				buttonname: 'btn_approve',
				buttontext: 'Approve',
			},
			decline: {
				buttonname: 'btn_decline',
				buttontext: 'Decline',
			},
			bayar: {
				buttonname: 'btn_bayar',
				buttontext: 'Pembayar',
			},
		}
	}
}



