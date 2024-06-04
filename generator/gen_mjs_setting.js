const path = require('path')
const fs = require('fs')

const colReset = "\x1b[0m"
const colFgRed = "\x1b[31m"
const colFgGreen = "\x1b[32m"
const colFgYellow = "\x1b[33m"
const colFgBlack = "\x1b[30m"
const colBright = "\x1b[1m"
const BgYellow = "\x1b[43m"

module.exports = async (fsd, genconfig) => {
	try {
		console.log(`-----------------------------------------------`)
		console.log(`Generate Settings MJS...`)

		//var basename = genconfig.basename

		var mjstpl = path.join(genconfig.GENLIBDIR, 'tpl', 'settings_mjs.tpl')
		var tplscript = fs.readFileSync(mjstpl).toString()



		fsd.script = tplscript

	} catch (err) {
		throw err
	}
}