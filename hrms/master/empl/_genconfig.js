'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Employee",
	autoid: true,

	persistent: {
		mst_empl : {
			primarykeys: ['empl_id'],
			comment: 'Master Employee',
			data: {
				empl_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, },
				empl_fullname: {text:'Fullname', type: dbtype.varchar(255), null:false, uppercase: false, options:{required:true,invalidMessage:'Fullname required'}},
				empl_isexit: {text:'isExit', type: dbtype.boolean, null:false, default:0},
				empl_dtjoin: {text:'Tgl. Join', type: dbtype.date, null:true, suppresslist:false,},
				division_id: {
					text:'Divisi', type: dbtype.varchar(36), null:false, suppresslist: false,
					options:{required:false,invalidMessage:'Divisi required', prompt:'-- PILIH --'},
					comp: comp.Combo({
						table: 'mst_division',
						field_value: 'division_id', field_display: 'division_name',
						api: 'hrms/master/division/list',
						title: 'Daftar Divisi',
						field_mappings: [
							`{mapping: 'division_id', text: 'ID'}`,
							`{mapping: 'division_code', text: 'Code'}`,
							`{mapping: 'division_name',  text: 'Name'}`,
						],
						onDataLoadingHandler: true,
						onDataLoadedHandler: true,
						onSelectedHandler: true,
					}),
				},
				user_id: {
					text:'Auth', type: dbtype.varchar(14), null:false, suppresslist: false,
					options:{required:false,invalidMessage:'Auth required', prompt:'-- PILIH --'},
					comp: comp.Combo({
						table: 'fgt_user',
						field_value: 'user_id', field_display: 'user_name',
						api: 'fgta/framework/fguser/list',
						title: 'Daftar Auth',
						field_mappings: [
							`{mapping: 'user_id', text: 'ID'}`,
							`{mapping: 'user_name', text: 'Username'}`,
							`{mapping: 'user_email',  text: 'Email'}`,
						],
						onDataLoadingHandler: true,
						onDataLoadedHandler: true,
						onSelectedHandler: true,
					}),
				},
			},
			defaultsearch: ['empl_name', 'empl_dtjoin'],
			uniques: {
				user_id: ['user_id'],
			}			
		}


	},

	schema: {
		title: 'Employee',
		header: 'mst_empl',
		detils: {
		}
	}
}



