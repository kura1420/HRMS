'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Period",
	autoid: false,

	persistent: {
		mst_period : {
			primarykeys: ['period_id'],
			comment: 'Master Period',
			data: {
				period_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: true, suppresslist:false, options:{required:true,invalidMessage:'ID required'}},
				period_name: {text:'Name', type: dbtype.varchar(255), null:false, uppercase: false, options:{required:true,invalidMessage:'Name required'}},
				period_descr: {text:'Descr', type: dbtype.varchar(1000), null:true, suppresslist:true, },
				period_isdisabled: {text:'isDisabled', type: dbtype.boolean, null:false, default:0},
			},
			uniques: {
				'period_name' : ['period_name']
			},
			defaultsearch: ['period_id', 'period_name'],
		}


	},

	schema: {
		title: 'Period',
		header: 'mst_period',
		detils: {
		}
	}
}



