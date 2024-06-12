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
				dept_id: {
					text:'Departement', type: dbtype.varchar(36), null:false, suppresslist: false,
					options:{required:false,invalidMessage:'Departement required', prompt:'-- PILIH --'},
					comp: comp.Combo({
						table: 'mst_dept',
						field_value: 'dept_id', field_display: 'dept_name',
						api: 'hrms/master/dept/list',
						title: 'Daftar Departement',
						field_mappings: [
							`{mapping: 'dept_id', text: 'ID'}`,
							`{mapping: 'dept_code', text: 'Code'}`,
							`{mapping: 'dept_name',  text: 'Name'}`,
						],
						onDataLoadingHandler: true,
						onDataLoadedHandler: true,
						onSelectedHandler: true,
						staticfilter: `
            criteria.dept_isdisabled = 0;
        `,
						OnSelectedScript: `
			form.setValue(obj.cbo_division_id, '0', '-- PILIH --')
		`,
					}),
				},
				division_id: {
					text:'Divisi', type: dbtype.varchar(36), null:true, suppresslist: false,
					options:{ prompt:'-- PILIH --' },
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
						staticfilter: `
            criteria.division_isdisabled = 0;
			criteria.dept_id = form.getValue(obj.cbo_dept_id);
        `,
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



