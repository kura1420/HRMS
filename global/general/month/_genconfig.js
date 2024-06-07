'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Month",
	autoid: false,

	creatorname: "Abdul Syakur",
	creatoremail: "a.syakur14@gmail.com", 
	description: `
		program untuk master bulan
	`,

	persistent: {
		mst_month : {
			primarykeys: ['month_id'],
			comment: 'Master Month',
			data: {
				month_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: true, suppresslist:false, options:{required:true,invalidMessage:'ID required'}},
				month_name: {text:'Name', type: dbtype.varchar(255), null:false, uppercase: false, options:{required:true,invalidMessage:'Name required'}},
			},
			uniques: {
				'month_name' : ['month_name']
			},
			defaultsearch: ['month_id', 'month_name'],
		}


	},

	schema: {
		title: 'Month',
		header: 'mst_month',
		detils: {
		}
	}
}



