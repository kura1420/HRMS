'use strict'

const dbtype = global.dbtype;
const comp = global.comp;

module.exports = {
	title: "Type Requisition",
	autoid: true,

	jsonOverwrite: true,

	creatorname: "Abdul Syakur",
	creatoremail: "a.syakur14@gmail.com", 
	description: `
		program untuk master type requisition
	`,

	persistent: {
		mst_typereq : {
			primarykeys: ['typereq_id'],
			comment: 'Master Purchase Type Requisition',
			data: {
				typereq_id: {text:'ID', type: dbtype.varchar(36), null:false, uppercase: false, suppresslist:true, hidden:true},
				typereq_name: {text:'Name', type: dbtype.varchar(255), null:false, uppercase: false, options:{required:true,invalidMessage:'Name required'}},
			},
			uniques: {
				'typereq_name' : ['typereq_name']
			},
			defaultsearch: ['typereq_name'],
		}


	},

	schema: {
		title: 'Type Requisition',
		header: 'mst_typereq',
		detils: {

		}
	}
}



