const path = require('path');

const config = {
	entry: {
		'./media/js/blocks/dist/index': './media/js/blocks/src/index.js',
		'./media/js/mptt-functions': './media/js/mptt-functions.js',
		'./media/js/events/event': './media/js/events/event.js',
	},
	output: {
		path: path.resolve(__dirname),
		filename: '[name].min.js',
		library: [ 'wp', '[name]' ],
		libraryTarget: 'window',
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
		'lodash'   : 'lodash',
		//https://www.cssigniter.com/importing-gutenberg-core-wordpress-libraries-es-modules-blocks/
		'wp.i18n': {
			window: [ 'wp', 'i18n' ]
		},
	},
};

module.exports = config;
