const path = require('path');

const config = {
	entry: {
		'./media/js/blocks/dist/index.js': './media/js/blocks/src/index.js',
		'./media/js/mptt-functions.min.js': './media/js/mptt-functions.js',
		'./media/js/mptt-elementor-editor.min.js': './media/js/mptt-elementor-editor.js',
		'./media/js/mce-timeTable-buttons.min.js': './media/js/mce-timeTable-buttons.js',
		'./media/js/events/event.min.js': './media/js/events/event.js',
	},
	output: {
		path: path.resolve(__dirname),
		filename: '[name]',
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
