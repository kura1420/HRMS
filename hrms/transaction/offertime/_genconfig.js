'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Offertime",
	autoid: true,
	printing: true,

	persistent: {
		trn_offertime: {
			primarykeys: ['offertime_id'],
			comment: 'Offertime',
			data: {
				offertime_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, },
				offertime_code: {text:'Code', type: dbtype.varchar(50), null:false, uppercase: true, suppresslist:false, },

				empl_id: {
					text:'Employee', type: dbtype.varchar(36), null:false, suppresslist: false,
					options:{required:false,invalidMessage:'Employee required', prompt:'-- PILIH --'},
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

				docapprv_id: {
					text:'Doc. Approval', type: dbtype.varchar(36), null:false, suppresslist: true,
					options:{required:false,invalidMessage:'Doc. Approval', prompt:'-- PILIH --'},
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
				},

				offertime_rejectnotes: { text: 'Reject Notes', type: dbtype.varchar(255), null: true,  unset:true, suppresslist: true, options:{disabled: true} },
				offertime_isrequest: {text:'Request', type: dbtype.boolean, null:false, default:'0', unset:true, suppresslist: true, options:{disabled:true}},
				offertime_requestby: {text:'RequestBy', type: dbtype.varchar(14), suppresslist: true, unset:true, options:{disabled:true}, hidden: true, lookup:'user'},
				offertime_requestdate: {text:'RequestDate', type: dbtype.datetime, suppresslist: false, unset:true, comp:comp.Textbox(), options:{disabled:true}, hidden: true},	
				offertime_isapproved: { text: 'Approved', type: dbtype.boolean, null: false, default: '0', unset:true, suppresslist: true, options: { disabled: true } },
				offertime_approveby: { text: 'Approve By', type: dbtype.varchar(14), suppresslist: true, unset:true, options: { disabled: true }, hidden: true, lookup:'user' },
				offertime_approvedate: { text: 'Approve Date', type: dbtype.datetime, suppresslist: false, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true },
				offertime_isdeclined: { text: 'Declined', type: dbtype.boolean, null: false, default: '0', unset:true, suppresslist: true, options: { disabled: true } },
				offertime_declineby: { text: 'Decline By', type: dbtype.varchar(14), suppresslist: true, unset:true, options: { disabled: true }, hidden: true, lookup:'user' },
				offertime_declinedate: { text: 'Decline Date', type: dbtype.datetime, suppresslist: false, unset:true, comp: comp.Textbox(), options: { disabled: true }, hidden: true },
			},
			uniques: {
				offertime_code: ['offertime_code'],
			},	
			defaultsearch: ['offertime_code'],
		},

		trn_offertimedt: {
			primarykeys: ['offertimedt_id'],
			comment: 'Offertime Date',
			data: {
				offertimedt_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, },
				offertime_id: {text:'Offertime', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, hidden:true, },
				offertimedt_dt: {text:'Date', type: dbtype.date, null:false, suppresslist:false, options: {required: true, invalidMessage: 'Date required'} },
				offertime_id_type: {
					text:'Type/Day', type: dbtype.varchar(36), null:true, suppresslist: false,
					options:{prompt:'-- PILIH --'},
					tips: '*Wajib memilih tipe atau mengisi keterangan', tipstype: 'visible',
					comp: comp.Combo({
						table: 'mst_offertime',
						field_value: 'offertime_id', field_display: 'offertime_name',
						api: 'hrms/master/offertime/list',
						title: 'Daftar Type/Day',
						field_mappings: [
							`{mapping: 'offertime_id', text: 'ID'}`,
							`{mapping: 'offertime_name',  text: 'Name'}`,
						],
						onDataLoadingHandler: true,
						onDataLoadedHandler: true,
						onSelectedHandler: true,
					}),
				},
				offertimedt_descr: {text:'Descr', type: dbtype.varchar(255), null:true, suppresslist:true, },
			},
			uniques: {
			},
		},
	},

	schema: {
		title: 'Offertime',
		header: 'trn_offertime',
		detils: {
			offertimedt : {
				title: 'Date & Descr', table: 'trn_offertimedt', form: true, headerview: 'offertimedt_dt',
				editorHandler: true,
				listHandler: true
			},
		}
	}
}



