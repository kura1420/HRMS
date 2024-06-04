'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Overtime",
	autoid: true,

	persistent: {
		mst_offertime : {
			primarykeys: ['offertime_id'],
			comment: 'Master Overtime',
			data: {
				offertime_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, },
				offertime_name: {text:'Name', type: dbtype.varchar(255), null:false, uppercase: false, options:{required:true,invalidMessage:'Name required'}},
				offertime_descr: {text:'Descr', type: dbtype.varchar(1000), null:true, suppresslist:true, },
				offertime_isdisabled: {text:'isDisabled', type: dbtype.boolean, null:false, default:0},
			},
			uniques: {
				'offertime_name' : ['offertime_name']
			}			
		}


	},

	schema: {
		title: 'Overtime',
		header: 'mst_offertime',
		detils: {
		}
	}
}



