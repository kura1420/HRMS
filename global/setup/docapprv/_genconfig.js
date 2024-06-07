'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Document Approval",
	autoid: false,

	persistent: {
		mst_docapprv : {
			primarykeys: ['docapprv_id'],
			comment: 'Master Activity',
			data: {
				docapprv_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: true, suppresslist:false, },
				docapprv_name: {text:'Name', type: dbtype.varchar(255), null:false, uppercase: false, options:{required:true,invalidMessage:'Name required'}},
				docapprv_descr: {text:'Descr', type: dbtype.varchar(1000), null:true, suppresslist:true, },
				docapprv_isdisabled: {text:'isDisabled', type: dbtype.boolean, null:false, default:0},
			},
			uniques: {
				'docapprv_name' : ['docapprv_name'],
			},
			defaultsearch: ['docapprv_name'],
		},

		mst_docapprvlevl: {
			primarykeys: ['docapprvlevl_id'],
			data: {
				docapprvlevl_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: true, suppresslist:true, },
				docapprvlevl_sortorder: {text:'SortOrder', type: dbtype.int(4), null:false, default:0},
				docapprvlevl_isdisabled: {text:'isDisabled', type: dbtype.boolean, null:false, default:0},
				docapprvlevl_isequaldept: {text:'isEqualDept', type: dbtype.boolean, null:false, default:1, options: {labelWidth:100,}},
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
				docapprv_id: {text:'docapprv_id', type: dbtype.varchar(36), null:false, uppercase: true, suppresslist:true, hidden:true, },
			},
			defaultsearch: [],
			uniques: {},
		},
	},

	schema: {
		title: 'Document Approval',
		header: 'mst_docapprv',
		detils: {
			docapprvlevl : {
				title: 'Level', table: 'mst_docapprvlevl', form: true, headerview: 'empl_id',
				editorHandler: true,
				listHandler: true
			},
		}
	}
}



