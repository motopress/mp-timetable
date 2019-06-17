const path = require('path');

const config = {
	entry: "./media/js/blocks/src/index.js",
	output: {
		path: path.join(__dirname, "/media/js/blocks/dist"),
		filename: "index.js"
	},
	// entry: "./media/js/mptt-functions.js",
	// output: {
	// 	path: path.join(__dirname, "/media/js"),
	// 	filename: "mptt-functions.min.js"
	// },

	// entry: "./media/js/events/event.js",
	// output: {
	// 	path: path.join(__dirname, "/media/js/events"),
	// 	filename: "event.min.js"
	// },

	module: {
		rules: [
		  {
			test: /\.js$/,
			exclude: /node_modules/,
			use: {
			  loader: "babel-loader"
			},
		  }
		]
	},

	externals: {
		'react'    : 'React',
		'react-dom': 'ReactDOM',
		'lodash'   : 'lodash'
	},
};

module.exports = config;
