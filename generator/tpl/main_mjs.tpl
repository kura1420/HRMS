import {fgta4grid} from  '../../../../../index.php/asset/fgta/framework/fgta4libs/{__FGTAGRID__}'
import {fgta4form} from  '../../../../../index.php/asset/fgta/framework/fgta4libs/{__FGTAFORM__}'
import * as fgta4pages from '../../../../../index.php/asset/fgta/framework/fgta4libs/{__FGTAPAGES__}'
import * as fgta4pageslider from '../../../../../index.php/asset/fgta/framework/fgta4libs/{__FGTAPAGESLIDER__}'
import * as settings from './{__BASENAME__}.settings.mjs'
import * as apis from './{__BASENAME__}.apis.mjs'
import * as pList from './{__BASENAME__}-list.mjs'
import * as pEdit from './{__BASENAME__}-edit.mjs'
/*{__IMPORT_SCRIPT__}*/


const pnl_list = $('#pnl_list')
const pnl_edit = $('#pnl_edit')
/*{__PANELDECLARE_SCRIPT__}*/


var pages = fgta4pages;
var slider = fgta4pageslider;


export const SIZE = {width:0, height:0}


export async function init(opt) {
	// $ui.grd_list = new fgta4grid()
	// $ui.grd_edit = new fgta4grid()

	global.fgta4grid = fgta4grid
	global.fgta4form = fgta4form



	$ui.apis = apis
	document.getElementsByTagName("body")[0].style.margin = '5px 5px 5px 5px'

	opt.variancedata = global.setup.variancedata;
	settings.setup(opt);

	pages
		.setSlider(slider)
		.initPages([
			{panel: pnl_list, handler: pList},
			{panel: pnl_edit, handler: pEdit}/*{__PAGEINIT_SCRIPT__}*/			
		], opt)

	$ui.setPages(pages)


	document.addEventListener('OnButtonHome', (ev) => {
		if (ev.detail.cancel) {
			return
		}

		ev.detail.cancel = true;
		ExitModule();
	})
	
	document.addEventListener('OnSizeRecalculated', (ev) => {
		OnSizeRecalculated(ev.detail.width, ev.detail.height)
	})	



	await PreloadData()

}


export function OnSizeRecalculated(width, height) {
	SIZE.width = width
	SIZE.height = height
}

export async function ExitModule() {
	$ui.home();
}



async function PreloadData() {
	
}