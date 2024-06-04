const path = require('path')
const fs = require('fs')

const colReset = "\x1b[0m"
const colFgRed = "\x1b[31m"
const colFgGreen = "\x1b[32m"
const colFgYellow = "\x1b[33m"
const colFgBlack = "\x1b[30m"
const colBright = "\x1b[1m"
const BgYellow = "\x1b[43m"


const DATESERIAL = (new Date()).getTime();

module.exports = async(genconfig) => {
	// siapkan direktori
	try {
		var fsdata = await PrepareFs(genconfig)

		return fsdata
	} catch (err) {
		throw err
	}
}

async function PrepareFs(genconfig) {
	const dbtype = global.dbtype;
	const comp = global.comp;


	var programpath = genconfig.programpath
	var basename = path.basename(programpath);
	var basetableentity = genconfig.basetableentity;

	if (fs.existsSync(path.join(programpath, `${basename}.genlock`))) {
		var genlockfile = path.join(programpath, `${basename}.genlock`);
		var genlockfilecontent = fs.readFileSync(genlockfile)
		throw 'Program sudah di lock, tidak bisa digenerate.\r\n' + genlockfilecontent;
	}


	// Inject Handler
	genconfig.schema.editorHandler = `${basename}-edit-hnd.mjs`;
	genconfig.schema.listHandler = `${basename}-list-hnd.mjs`;

	for (var detilname in genconfig.schema.detils) {
		var detil = genconfig.schema.detils[detilname];
		if (detil.editorHandler!=null) {
			detil.editorHandler = `${basename}-${detilname}form-hnd.mjs`;
		}
		if (detil.listHandler!=null) {
			detil.listHandler = `${basename}-${detilname}grid-hnd.mjs`;
		}
	}


	if (genconfig.approval===true) {
		var tbl_header = genconfig.schema.header;
		var tbl_approval = genconfig.schema.header + 'appr';
		var primarykeys = genconfig.persistent[tbl_header].primarykeys;
		genconfig.schema.detils['approval'] = {title: 'Approval', table: tbl_approval, form: true, headerview: primarykeys, isapprovalform: true}; 
		genconfig.persistent[tbl_approval] = {
			primarykeys: [basetableentity + 'appr_id'],
			comment: 'Approval ' + genconfig.schema.title,
		}

		var data = {}
		data[basetableentity + 'appr_id'] = {text:'ID', type: dbtype.varchar(14), suppresslist: true, null:false}
		data[basetableentity + 'appr_isapproved'] = {text:'Approved', type: dbtype.boolean, null:false, default:'0', suppresslist: true, options:{disabled:true}}
		data[basetableentity + 'appr_by'] = {text:'Approved By', type: dbtype.varchar(14), suppresslist: true, options:{disabled:true}, lookup:'user'}
		data[basetableentity + 'appr_date'] = {text:'Approved Date', type: dbtype.datetime, suppresslist: true, comp:comp.Textbox(), options:{disabled:true}}
		data[basetableentity + '_version'] = {text:'Version', type: dbtype.int(4), null:false, default:'0', suppresslist: true, options:{disabled:true}}
		data[basetableentity + 'appr_isdeclined'] = {text:'Declined', type: dbtype.boolean, null:false, default:'0', suppresslist: true, options:{disabled:true}}
		data[basetableentity + 'appr_declinedby'] = {text:'Declined By', type: dbtype.varchar(14), suppresslist: true, options:{disabled:true}, lookup:'user'}
		data[basetableentity + 'appr_declineddate'] = {text:'Declined Date', type: dbtype.datetime, suppresslist: true, comp:comp.Textbox(), options:{disabled:true}}
		data[basetableentity + 'appr_notes'] = {text:'Notes', type: dbtype.varchar(255), suppresslist: true}
		data[basetableentity + '_id'] = {text:'ID', type: dbtype.varchar(30), null:false, suppresslist: true,}
		data['docauth_descr'] = {text:'Approval', type: dbtype.varchar(90), null:true, uppercase: false, suppresslist: false, options:{disabled: true}}
		data['docauth_order'] = {text:'Order', type: dbtype.int(4), null:false, default:0, suppresslist: true, options:{disabled: true}}
		data['docauth_value'] = {text:'Value', type: dbtype.int(4), null:false, default:100, suppresslist: true, options:{disabled: true}}
		data['docauth_min'] = {text:'Min', type: dbtype.int(4), null:false, default:0, suppresslist: true, options:{disabled: true}}
		data['authlevel_id'] = {text:'LevelId', type: dbtype.varchar(10), null:false, suppresslist: true, options:{disabled: true}}
		data['authlevel_name'] = {text:'Level', type: dbtype.varchar(60), null:false, suppresslist: true, options:{disabled: true}}
		data['auth_id'] = {text:'AuthorisasiId', type: dbtype.varchar(10), null:true, suppresslist: true, options:{disabled: true}}
		data['auth_name'] = {text:'Authorisasi', type: dbtype.varchar(60), null:false, suppresslist: true, options:{disabled: true}}
		
		var uniques = {}
		uniques[basetableentity + '_auth_id'] = [basetableentity + '_id', 'auth_id'] ;

		genconfig.persistent[tbl_approval].data = data;
		genconfig.persistent[tbl_approval].uniques = uniques;
	}



	InitDetilPages(genconfig)

	var jsonOverwrite = genconfig.jsonOverwrite ?? false;
	var commitOverwrite = genconfig.commitOverwrite ?? false;
	var uncommitOverwrite = genconfig.uncommitOverwrite ?? false;
	var approvalOverwrite = genconfig.approvalOverwrite ?? false;
	var xprintOverwrite = genconfig.xprintOverwrite ?? false;

	var fsdata = [
		{name: 'apis', type:'dir'},
		{program:'gen_table', name: `${basename}.sql`},
		{program:'gen_mainphp', name: `${basename}.php`},
		{program:'gen_phtml', name: `${basename}.phtml`},
		{program:'gen_phtml', name: `${basename}.phtml`},
		{program:'gen_mjs', name: `${basename}.mjs`},
		{program:'gen_phtml_list', name: `${basename}-list.phtml`},
		{program:'gen_mjs_list', name: `${basename}-list.mjs`},
		{program:'gen_phtml_edit', name: `${basename}-edit.phtml`},
		{program:'gen_mjs_edit', name: `${basename}-edit.mjs`},
		{program:'gen_mjs_apis', name: `${basename}.apis.mjs`},
		{program:'gen_mjs_setting', name: `${basename}.settings.mjs`, overwrite: false},
		{program:'gen_notes', name: `${basename}.notes.txt`, overwrite: false},
		{program:'gen_json', name: `${basename}.json`, overwrite: jsonOverwrite},
		{program:'gen_api_base', name: path.join('apis', 'xapi.base.php')},
		{program:'gen_api_list', name: path.join('apis', 'list.php')},
		{program:'gen_api_save', name: path.join('apis', 'save.php')},
		{program:'gen_api_open', name: path.join('apis', 'open.php')},
		{program:'gen_api_delete', name: path.join('apis', 'delete.php')},
		{program:'gen_vsworkspace', name: `${basename}.code-workspace`},
	]


	if (genconfig.printing===true) {
		fsdata.push({
			program:'gen_xprint_css', name: `${basename}.xprint.css`, 
			overwrite: xprintOverwrite, backup_on_overwrite:true
		});
		fsdata.push({
			program:'gen_xprint_mjs', name: `${basename}.xprint.mjs`, 
			overwrite: xprintOverwrite, backup_on_overwrite:true
		});
		fsdata.push({
			program:'gen_xprint_php', name: `${basename}.xprint.php`, 
			overwrite: xprintOverwrite, backup_on_overwrite:true
		});
		fsdata.push({
			program:'gen_xprint_phtml', name: `${basename}.xprint.phtml`, 
			overwrite: xprintOverwrite, backup_on_overwrite:true
		});
	}

	var add_approval = genconfig.approval===true;
	var add_commiter = add_approval===true ? true : (genconfig.committer===true);
	if (add_commiter) {
		fsdata.push({
			program:'gen_xtion_commit', name: path.join('apis', 'xtion-commit.php'), 
			overwrite: commitOverwrite, backup_on_overwrite:true
		});
		fsdata.push({
			program:'gen_xtion_uncommit', name: path.join('apis', 'xtion-uncommit.php'), 
			overwrite: uncommitOverwrite, backup_on_overwrite:true
		});

		if (add_approval) {
			fsdata.push({
				program:'gen_xtion_approve', name: path.join('apis', 'xtion-approve.php'), 
				overwrite: approvalOverwrite, backup_on_overwrite:true
			});
		}
	}


	for (var pagename in genconfig.pages) {
		var p = genconfig.pages[pagename]
		if (p.api===true) {
			fsdata.push(p)
		} else {
			var pscrs = ['mjs', 'phtml']
			for (var pscr of pscrs) {
				p[pscr].name = p[pscr].filename
				p[pscr].pagename = pagename
				fsdata.push(p[pscr])
			}
		}
		
	}


	try {

		await PrepareFrameworkDir(genconfig)
		for (let fd of fsdata) {
			if (fd.type==='dir') {
				await PrepareDir(programpath, fd)
			} else {
				await PrepareFile(programpath, fd, fd.overwrite)
			}
			fd.basename = basename
		}

		// Handler
		var handlermod = ['editorHandler', 'listHandler']
		var handlers = [];

		handlers.push({name: 'apis/data-header-handler.php', program:'gen_api_handler', identity:'header'})
		for (var hndname of handlermod) {
			if (genconfig.schema[hndname] != undefined) {
				var gen = hndname=='editorHandler' ? 'gen_blank_editor' : 'gen_blank_list'
				handlers.push({name:genconfig.schema[hndname], program: gen});
			} 
		}

		for (var detilname in genconfig.schema.detils) {
			var detil = genconfig.schema.detils[detilname];
			if (detilname!='approval') { 
				handlers.push({name:`apis/data-${detilname}-handler.php`, program:'gen_api_handler', identity:detilname})
			}
			for (var hndname of handlermod) {
				var gen = hndname=='editorHandler' ? 'gen_blank_editor' : 'gen_blank_list'
				var hndfname = hndname=='editorHandler' ? 'form' : 'grid';
				if (detil[hndname]!=undefined) {
					// handlers.push({name:detil[hndname], program: gen});
					handlers.push({name:`${basename}-${detilname}${hndfname}-hnd.mjs`, program: gen});
				}
				//  else if (detil.handlers===true) {
				// 	handlers.push({name:`${basename}-${detilname}${hndfname}-hnd.mjs`, program: gen});
				// }
			}
		}

		for (var hnd of handlers) {
			let fdhnd = {
				name: hnd.name,
				program: hnd.program,
				identity: hnd.identity
			}
			fsdata.push(fdhnd)
			await PrepareFile(programpath, fdhnd, false)
		}


		return fsdata
	} catch (err) {
		throw err
	}

} 



async function PrepareFrameworkDir(genconfig) {
	//console.log(genconfig)
	// get modulegroup
	var modulename = genconfig.modulename
	var modules = modulename.split('/')
	var modulegroup = modules[0]

	try {
		var framework_dir = path.join(genconfig.dirname, 'apps', modulegroup, 'framework')
		if (!fs.existsSync(framework_dir)) {
			fs.mkdirSync(framework_dir)
		}

		var general_dir = path.join(framework_dir, 'general')
		if (!fs.existsSync(general_dir)) {
			fs.mkdirSync(general_dir)
		}		

		var apis_dir = path.join(general_dir, 'apis')
		if (!fs.existsSync(apis_dir)) {
			fs.mkdirSync(apis_dir)
		}


		var general_json = path.join(general_dir, 'general.json')
		if (!fs.existsSync(general_json)) {
			var tpl = path.join(genconfig.GENLIBDIR, 'tpl', 'general_json.tpl')
			var tplscript = fs.readFileSync(tpl).toString()
			fs.writeFileSync(general_json, tplscript)
		}

		var general_loglist_api = path.join(apis_dir, 'loglist.php')
		if (!fs.existsSync(general_loglist_api)) {
			var tpl = path.join(genconfig.GENLIBDIR, 'tpl', 'general_loglist_api.tpl')
			var tplscript = fs.readFileSync(tpl).toString()
			fs.writeFileSync(general_loglist_api, tplscript)
		}

	} catch (err) {
		
		throw err
	}
}


async function PrepareDir(programpath, fd) {
	var name = fd.name
	var fspath = path.join(programpath, name)

	process.stdout.write(`Cek direktori ${name}... `);
	try {
		if (fs.existsSync(fspath)) {
			process.stdout.write(`${BgYellow}${colFgBlack}exist${colReset} `);
			// direktori sudah ada, cek apakah benar direktori
			if (!fs.lstatSync(fspath).isDirectory()) {
				throw `${colBright}${fspath}${colReset} bukan direktori`
			} else {
				console.log('OK.')
			}
		} else {
			// direktori belum ada, buat baru
			console.log(`-> ${colFgYellow}buat direktori baru${colReset} '${fspath}' `)
			fs.mkdirSync(fspath)
		}

		fd.write = async () => {}
	} catch (err) {
		
		throw err
	}
}

async function PrepareFile(programpath, fd, overwrite=true) {
	try {
		fd.programpath = programpath
		fd.write = async () => {
			var fspath = path.join(fd.programpath, fd.name)
			// process.stdout.write(`Cek file ${fspath}... `);
			try {
				var fspath_dirname = path.dirname(fspath)
				var fspath_filename = path.basename(fspath)
				
				//------------------------------
				// disable disini apabila mau testing, tidak bikin file backup
				if (fs.existsSync(fspath)) {
					console.log('File Exist');
					if (fd.backup_on_overwrite===true && overwrite===true) {
						process.stdout.write(`Creating backup file `);
						
						// cek apakah direktori backup ada
						var fspath_backupdir = path.join(fspath_dirname, 'backup');
						if (!fs.existsSync(fspath_backupdir)) {
							// direktori backup tidak ada, buat dulu direktori backup
							fs.mkdirSync(fspath_backupdir)
						}
						fs.copyFileSync(fspath, path.join(fspath_backupdir, `backup [${DATESERIAL}] ${fspath_filename}`))
					}
				} 
				//console.log('OK.')
				//------------------------------

				process.stdout.write(`Writing ${fd.name}... `);

				var dowrite = false;
				if (fs.existsSync(fspath)) {
					if (overwrite) {
						dowrite = true;
					}
				} else {
					dowrite = true;
				}

				// dowrite = true;
				if (dowrite) {
					// process.stdout.write(`Writing script file `);
					fs.writeFileSync(fspath, fd.script)
					console.log('OK.')
				} else {
					console.log('SKIP.')
				}
	
			} catch (err) {
				throw err
			}			
		}
	} catch (err) {
		throw err
	}
}



function InitDetilPages(genconfig) {
	genconfig.pages = {}

	var gen_api = {
		list: "gen_api_tdetil_list",
		open: "gen_api_tdetil_open",
		save: "gen_api_tdetil_save",
		delete: "gen_api_tdetil_delete"
	}

	for (var detilname in  genconfig.schema.detils) {
		var detil = genconfig.schema.detils[detilname]


		var overwrite = detil.overwrite===undefined || detil.overwrite===null ? {} : detil.overwrite;




		if (detil.form===true) {

			var overwrite_mjs_list =  overwrite.mjs_list === false ? false : true;
			var overwrite_phtml_list = overwrite.phtml_list === false ? false : true;
			var overwrite_mjs_form = overwrite.mjs_form === false ? false : true;
			var overwrite_phtml_form = overwrite.phtml_form === false ? false : true;
			var overwrite_api = overwrite.api === false ? false : true;

			genconfig.pages[`${detilname}grid`] = {
				mjs: {
					program: 'gen_mjs_detilgrid', panel: `pnl_edit${detilname}grid`,handler: `pEdit${CapFL(detilname)}grid`, filename: `${genconfig.basename}-${detilname}grid.mjs`, 
					detilname: detilname,
					overwrite: overwrite_mjs_list
				},
				
				phtml: {
					program: 'gen_phtml_detilgrid', panel: `pnl_edit${detilname}grid`, filename: `${genconfig.basename}-${detilname}grid.phtml`, 
					detilname: detilname,
					overwrite: overwrite_phtml_list
				}
			}

			genconfig.pages[`${detilname}form`] = {
				mjs: {
					program: 'gen_mjs_detilform', panel: `pnl_edit${detilname}form`, handler: `pEdit${CapFL(detilname)}form`, filename: `${genconfig.basename}-${detilname}form.mjs`, 
					detilname: detilname,
					overwrite: overwrite_mjs_form
				},

				phtml: {
					program: 'gen_phtml_detilform',  panel: `pnl_edit${detilname}form`, filename: `${genconfig.basename}-${detilname}form.phtml`, 
					detilname: detilname,
					overwrite: overwrite_phtml_form
				}
			}

			var apis = ['list', 'open', 'save', 'delete']
			for (var api of apis) {
				genconfig.pages[`${detilname}-${api}`] = {
					api: true,
					program: gen_api[api],
					name: path.join('apis', `${detilname}-${api}.php`),
					detilname: detilname,
					overwrite: overwrite_api
				}
			}

		} else {

			var overwrite_mjs =  overwrite.mjs === true ? true : false;
			var overwrite_phtml = overwrite.phtml === true ? true : false;
			var genHandlerMjs = detil.genHandler===undefined ? 'gen_mjs_blank' : 'gen_mjs_'+detil.genHandler;
			var genHandlerPhtml = detil.genHandler===undefined ? 'gen_phtml_blank' : 'gen_phtml_'+detil.genHandler;


			genconfig.pages[detilname] = {
				mjs: {
					program: genHandlerMjs, panel: `pnl_edit${detilname}`, handler: `pEdit${CapFL(detilname)}`, filename: `${genconfig.basename}-${detilname}.mjs`, 
					detilname: detilname,
					overwrite: overwrite_mjs
				},
				
				phtml: {
					program: genHandlerPhtml, panel: `pnl_edit${detilname}`, filename: `${genconfig.basename}-${detilname}.phtml`, 
					detilname: detilname,
					overwrite: overwrite_phtml
				}					
			}
		}

	}
}


function CapFL(string) {
	return string[0].toUpperCase() +  string.slice(1);
}