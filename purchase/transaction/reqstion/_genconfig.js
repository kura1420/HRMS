'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Requisition",
	autoid: true,
	printing: true,

	jsonOverwrite: true,

	creatorname: "Abdul Syakur",
	creatoremail: "a.syakur14@gmail.com", 
	description: `
		program untuk transaction requisition
	`,

	persistent: {
		trn_reqstion : {
			primarykeys: ['reqstion_id'],
			comment: 'Transaction Purchase Requisition',
			data: {
				reqstion_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, hidden:true},
				reqstion_code: {text:'Code', type: dbtype.varchar(50), null:false, uppercase: true, suppresslist:false, options:{disabled:true,} },

				empl_id: {
					text:'Employee', type: dbtype.varchar(36), null:false, suppresslist: false,
					options:{required:true,invalidMessage:'Employee required', prompt:'-- PILIH --',disabled:true,},
					comp: comp.Combo({
						table: 'mst_empl',
						field_value: 'empl_id', field_display: 'empl_fullname',
						api: 'hrms/master/empl/list',
						title: 'Daftar Employee',
						field_mappings: [
							`{mapping: 'empl_id', text: 'ID'}`,
							`{mapping: 'empl_fullname',  text: 'Name'}`,
						],
					}),
				},

				reqstion_subject: {text:'Subject', type: dbtype.varchar(255), null:false, uppercase: false, options:{required:true,invalidMessage:'Subject required'}},
				reqstion_dt: {text:'Tgl. Dibutuhkan', type: dbtype.date, null:false, suppresslist:false, options: {required: true, invalidMessage: 'Tgl. Dibutuhkan wajib diisi'} },
				reqstion_total: {text:'Total', type: dbtype.int(11), null:false, suppresslist:false, default:0, options: {disabled:true,}},
				reqstion_descr: {text:'Descr', type: dbtype.varchar(255), null:false, suppresslist:true, options: { required:true,invalidMessage:'Descr required', multiline:true, } },

				docapprv_id: {
					text:'Doc. Approval', type: dbtype.varchar(36), null:false, suppresslist: true,
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

				reqstion_rejectnotes: { text: 'Reject Notes', type: dbtype.varchar(255), null: true,  unset:true, suppresslist: true, options:{disabled: true, multiline:true} },
				reqstion_isrequest: {text:'Request', type: dbtype.boolean, null:false, default:'0', unset:true, suppresslist: true, options:{disabled:true}},
				reqstion_requestby: {text:'RequestBy', type: dbtype.varchar(14), suppresslist: true, unset:true, options:{disabled:true}, hidden: true, lookup:'user'},
				reqstion_requestdate: {text:'RequestDate', type: dbtype.datetime, suppresslist: false, unset:true, comp:comp.Textbox(), options:{disabled:true}, hidden: true},	
				reqstion_isapproved: { text: 'Approved', type: dbtype.boolean, null: false, default: '0', unset:true, suppresslist: true, options: { disabled: true } },
				reqstion_approveby: { text: 'Approve By', type: dbtype.varchar(14), suppresslist: true, unset:true, options: { disabled: true }, hidden: true, lookup:'user' },
				reqstion_approvedate: { text: 'Approve Date', type: dbtype.datetime, suppresslist: false, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true },
				reqstion_isapprovalprogress: {text:'Progress', type: dbtype.boolean, null:false, default:'0', unset:true, suppresslist: true, options:{disabled:true}, hidden: true},
				reqstion_isdecline: { text: 'Decline', type: dbtype.boolean, null: false, default: '0', unset:true, suppresslist: true, options: { disabled: true } },
				reqstion_declineby: { text: 'Decline By', type: dbtype.varchar(14), suppresslist: true, unset:true, options: { disabled: true }, hidden: true, lookup:'user' },
				reqstion_declinedate: { text: 'Decline Date', type: dbtype.datetime, suppresslist: false, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true },
				reqstion_ispayment: { text: 'Payment', type: dbtype.boolean, null: false, default: '0', unset:true, suppresslist: true, options: { disabled: true } },
				// reqstion_paymentby: { text: 'Payment By', type: dbtype.varchar(14), suppresslist: true, unset:true, options: { disabled: true }, hidden: true, lookup:'user' },
				// reqstion_paymentdate: { text: 'Payment Date', type: dbtype.datetime, suppresslist: false, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true },
				reqstion_executeby: { text: 'Execute By', type: dbtype.varchar(14), suppresslist: false, unset:true, options: { disabled: true }, lookup:'user' },
				reqstion_executedate: { 
					text: 'Execute Date', type: dbtype.datetime, suppresslist: true, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true,
					section: section.End(), 
				},
			},
			uniques: {
				'reqstion_code': ['reqstion_code'],
			},
			defaultsearch: [ 'reqstion_code', 'reqstion_name' ],
		},

		trn_reqstionitm: {
			primarykeys: ['reqstionitm_id'],
			comment: 'Purchase Requisition Item',
			data: {
				reqstionitm_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, },
				
				typereq_id: {
					text:'Permintaan', type: dbtype.varchar(36), null:false, suppresslist: false,
					options:{required:true,invalidMessage:'Permintaan required', prompt:'-- PILIH --'},
					comp: comp.Combo({
						table: 'mst_typereq',
						field_value: 'typereq_id', field_display: 'typereq_name',
						api: 'purchase/master/typereq/list',
						title: 'Daftar Permintaan',
						field_mappings: [
							`{mapping: 'typereq_id', text: 'ID'}`,
							`{mapping: 'typereq_name',  text: 'Name'}`,
						],
					}),
				},

				reqstionitm_name: {text:'Name', type: dbtype.varchar(255), null:false, uppercase: false, options:{required:true,invalidMessage:'Name required'}},
				reqstionitm_val: {text:'Value', type: dbtype.int(11), null:false, suppresslist:false, options:{required:true,invalidMessage:'Value is require'} },
				reqstionitm_file: { 
					text: "Attachment", type: dbtype.varchar(255), null: true, uppercase: false, suppresslist: true,
					comp: comp.Filebox(), 
				},
				reqstionitm_descr: {text:'Descr', type: dbtype.varchar(255), null:true, suppresslist:false, },

				reqstion_id: {text:'FK ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, hidden:true},
			},
			uniques: {

			},
		},
	},

	schema: {
		title: 'Requisition',
		header: 'trn_reqstion',
		detils: {
			reqstionitm : {
				title: 'Item', table: 'trn_reqstionitm', form: true, headerview: 'reqstionitm_dt',
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
		}
	}
}



