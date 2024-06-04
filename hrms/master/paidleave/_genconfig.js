'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Paid Leave",
	autoid: true,

	persistent: {
		mst_paidleave : {
			primarykeys: ['paidleave_id'],
			comment: 'Master Paid Leave",',
			data: {
				paidleave_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, },
				paidleave_code: {text:'Code', type: dbtype.varchar(50), null:false, uppercase: true, options:{required:true,invalidMessage:'Code required'}},
				paidleave_name: {text:'Name', type: dbtype.varchar(255), null:false, uppercase: false, options:{required:true,invalidMessage:'Name required'}},
				paidleave_isdisabled: {text:'isDisabled', type: dbtype.boolean, null:false, default:0},
				paidleave_iscutting: {text:'isCutting', type: dbtype.boolean, null:false, default:0},
				paidleave_descr: {text:'Descr', type: dbtype.varchar(1000), null:true, suppresslist:true, },

				// saldo
				paidleave_expenable: {text:'isExpired', type: dbtype.boolean, null:false, default:0},
				paidleave_qty: {text:'Qty', type: dbtype.int(11), null:false, uppercase: false, default:0, },
				period_id: {
					text:'Period', type: dbtype.varchar(36), null:true, suppresslist: false,
					options:{required:false,invalidMessage:'Period harus diisi', prompt:'-- PILIH --'},
					comp: comp.Combo({
						table: 'mst_period',
						field_value: 'period_id', field_display: 'period_name',
						api: 'global/general/period/list',
						title: 'Daftar Period',
						field_mappings: [
							`{mapping: 'period_id', text: 'ID'}`,
							`{mapping: 'period_name',  text: 'Name'}`,
						],
						onDataLoadingHandler: true,
						onDataLoadedHandler: true,
						onSelectedHandler: true,
					}),
				},
			},
			uniques: {
				'paidleave_code' : ['paidleave_code'],
				'paidleave_name' : ['paidleave_name'],
			}			
		}


	},

	schema: {
		title: 'Paid Leave',
		header: 'mst_paidleave',
		detils: {
		}
	}
}



