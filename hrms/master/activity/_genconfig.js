'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Activity",
	autoid: true,

	persistent: {
		mst_activity : {
			primarykeys: ['activity_id'],
			comment: 'Master Activity',
			data: {
				activity_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, },
				activity_name: {text:'Name', type: dbtype.varchar(255), null:false, uppercase: false, options:{required:true,invalidMessage:'Name required'}},
				activity_descr: {text:'Descr', type: dbtype.varchar(1000), null:true, suppresslist:true, },
				activity_isdisabled: {text:'isDisabled', type: dbtype.boolean, null:false, default:0},
			},
			uniques: {
				'activity_name' : ['activity_name'],
			}			
		}


	},

	schema: {
		title: 'Activity',
		header: 'mst_activity',
		detils: {
		}
	}
}



