const path = require('path');

const config = {
	entry: "./media/js/blocks/src/index.js",

	output: {
		path: path.join(__dirname, "/media/js/blocks/dist"),
		filename: "index.js"
	},

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
