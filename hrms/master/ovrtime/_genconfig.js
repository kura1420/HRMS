'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Overtime",
	autoid: true,

	persistent: {
		mst_ovrtime : {
			primarykeys: ['ovrtime_id'],
			comment: 'Master Overtime',
			data: {
				ovrtime_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, },
				ovrtime_name: {text:'Name', type: dbtype.varchar(255), null:false, uppercase: false, options:{required:true,invalidMessage:'Name required'}},
				ovrtime_descr: {text:'Descr', type: dbtype.varchar(1000), null:true, suppresslist:true, },
				ovrtime_isdisabled: {text:'isDisabled', type: dbtype.boolean, null:false, default:0},
			},
			uniques: {
				'ovrtime_name' : ['ovrtime_name']
			}			
		}


	},

	schema: {
		title: 'Overtime',
		header: 'mst_ovrtime',
		detils: {
		}
	}
}



