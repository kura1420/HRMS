'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Claim",
	autoid: true,
	printing: true,
	creatorname: "Abdul Syakur",
	creatoremail: "a.syakur14@gmail.com", 
	description: `
		program untuk transaksi claim
	`,

	persistent: {
		trn_claim: {
			primarykeys: ['claim_id'],
			comment: 'Claim',
			data: {
				claim_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, },
				claim_code: {text:'Code', type: dbtype.varchar(50), null:false, uppercase: true, suppresslist:false, options:{disabled:true,} },

				activity_id: {
					text:'Activity', type: dbtype.varchar(36), null:false, suppresslist: false,
					options:{required:true,invalidMessage:'Activity required', prompt:'-- PILIH --'},
					comp: comp.Combo({
						table: 'mst_activity',
						field_value: 'activity_id', field_display: 'activity_name',
						api: 'hrms/master/activity/list',
						title: 'Daftar Activity',
						field_mappings: [
							`{mapping: 'activity_id', text: 'ID'}`,
							`{mapping: 'activity_name',  text: 'Name'}`,
						],
						onDataLoadingHandler: true,
						onDataLoadedHandler: true,
						onSelectedHandler: true,
					}),
				},

				claim_descr: {text:'Descr', type: dbtype.varchar(255), null:true, suppresslist:true, options: { multiline:true, } },

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
						onDataLoadingHandler: true,
						onDataLoadedHandler: true,
						onSelectedHandler: true,
					}),
				},

				claim_total: {text:'Total', type: dbtype.int(11), null:false, suppresslist:false, default:0, options: {disabled:true,}},
				
				month_id: {
					text:'Bulan', type: dbtype.varchar(36), null:false, suppresslist: false,
					options:{required:true,invalidMessage:'Bulan required', prompt:'-- PILIH --'},
					comp: comp.Combo({
						table: 'mst_month',
						field_value: 'month_id', field_display: 'month_name',
						api: 'global/general/month/list',
						title: 'Daftar Bulan',
						field_mappings: [
							`{mapping: 'month_id', text: 'ID'}`,
							`{mapping: 'month_name',  text: 'Name'}`,
						],
						onDataLoadingHandler: true,
						onDataLoadedHandler: true,
						onSelectedHandler: true,
					}),
				},

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

				claim_rejectnotes: { text: 'Reject Notes', type: dbtype.varchar(255), null: true,  unset:true, suppresslist: true, options:{disabled: true, multiline:true} },
				claim_isrequest: {text:'Request', type: dbtype.boolean, null:false, default:'0', unset:true, suppresslist: true, options:{disabled:true}},
				claim_requestby: {text:'RequestBy', type: dbtype.varchar(14), suppresslist: true, unset:true, options:{disabled:true}, hidden: true, lookup:'user'},
				claim_requestdate: {text:'RequestDate', type: dbtype.datetime, suppresslist: false, unset:true, comp:comp.Textbox(), options:{disabled:true}, hidden: true},	
				claim_isapproved: { text: 'Approved', type: dbtype.boolean, null: false, default: '0', unset:true, suppresslist: true, options: { disabled: true } },
				claim_approveby: { text: 'Approve By', type: dbtype.varchar(14), suppresslist: true, unset:true, options: { disabled: true }, hidden: true, lookup:'user' },
				claim_approvedate: { text: 'Approve Date', type: dbtype.datetime, suppresslist: false, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true },
				claim_isapprovalprogress: {text:'Progress', type: dbtype.boolean, null:false, default:'0', unset:true, suppresslist: true, options:{disabled:true}, hidden: true},
				claim_isdecline: { text: 'Decline', type: dbtype.boolean, null: false, default: '0', unset:true, suppresslist: true, options: { disabled: true } },
				claim_declineby: { text: 'Decline By', type: dbtype.varchar(14), suppresslist: true, unset:true, options: { disabled: true }, hidden: true, lookup:'user' },
				claim_declinedate: { text: 'Decline Date', type: dbtype.datetime, suppresslist: false, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true },
				claim_ispayment: { text: 'Payment', type: dbtype.boolean, null: false, default: '0', unset:true, suppresslist: true, options: { disabled: true } },
				claim_paymentby: { text: 'Payment By', type: dbtype.varchar(14), suppresslist: true, unset:true, options: { disabled: true }, hidden: true, lookup:'user' },
				claim_paymentdate: { text: 'Payment Date', type: dbtype.datetime, suppresslist: false, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true },
				claim_executeby: { text: 'Execute By', type: dbtype.varchar(14), suppresslist: false, unset:true, options: { disabled: true }, lookup:'user' },
				claim_executedate: { 
					text: 'Execute Date', type: dbtype.datetime, suppresslist: true, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true,
					section: section.End(), 
				},
			},
			uniques: {
				claim_code: ['claim_code'],
			},	
			defaultsearch: ['claim_code'],
		},

		trn_claimdt: {
			primarykeys: ['claimdt_id'],
			comment: 'Claim Date',
			data: {
				claimdt_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, },
				claim_id: {text:'Claim ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, hidden:true, },
				claimdt_val: {text:'Value', type: dbtype.int(11), null:false, suppresslist:false, options:{required:true,invalidMessage:'Value Claim is require'} },
				claimdt_dt: {text:'Date', type: dbtype.date, null:false, suppresslist:false, options: {required: true, invalidMessage: 'Date required'} },
				claimdt_file: { 
					text: "Attachment", type: dbtype.varchar(255), null: false, uppercase: false, suppresslist: true, options: { required: false, invalidMessage: "Attachment reqired" }, 
					comp: comp.Filebox(), 
					tips: '*Wajib sertakan file attachment',
				},
				claimdt_descr: {text:'Descr', type: dbtype.varchar(255), null:true, suppresslist:false, },
			},
			uniques: {
			},
		},
	},

	schema: {
		title: 'Claim',
		header: 'trn_claim',
		detils: {
			claimdt : {
				title: 'Date & Descr', table: 'trn_claimdt', form: true, headerview: 'claimdt_dt',
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



