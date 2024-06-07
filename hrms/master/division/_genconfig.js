'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Division",
	autoid: true,

	persistent: {
		mst_division : {
			primarykeys: ['division_id'],
			comment: 'Master Division',
			data: {
				division_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, },
				division_code: {text:'Code', type: dbtype.varchar(50), null:false, uppercase: true, options:{required:true,invalidMessage:'Code required'}},
				division_name: {text:'Name', type: dbtype.varchar(255), null:false, uppercase: false, options:{required:true,invalidMessage:'Name required'}},
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
					}),
				},
				division_descr: {text:'Descr', type: dbtype.varchar(1000), null:true, suppresslist:true, },
				division_isdisabled: {text:'isDisabled', type: dbtype.boolean, null:false, default:0},
			},
			uniques: {
				'division_code' : ['division_code'],
			},
			defaultsearch: ['division_code', 'division_name'],			
		}


	},

	schema: {
		title: 'Division',
		header: 'mst_division',
		detils: {
		}
	}
}



