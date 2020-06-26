const defaultConfig = require( './node_modules/@wordpress/scripts/config/webpack.config.js' );
const path = require( 'path' );
const postcssPresetEnv = require( 'postcss-preset-env' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const IgnoreEmitPlugin = require( 'ignore-emit-webpack-plugin' );
const OptimizeCSSAssetsPlugin = require( 'optimize-css-assets-webpack-plugin' );
const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );

// Check if local.json exists
try {
	var localEnv = require( './local.json' ).devURL;
} catch ( err ) {
	// Fallback if it does not
	var localEnv = 'https://iceberg.test';
}

module.exports = {
	...defaultConfig,
	entry: {
		index: path.resolve( process.cwd(), 'src', 'index.js' ),
		style: path.resolve( process.cwd(), 'src', 'style.scss' ),
		// editor: path.resolve( process.cwd(), 'src', 'editor.scss' ),
	},
	optimization: {
		...defaultConfig.optimization,
		splitChunks: {
			cacheGroups: {
				editor: {
					name: 'editor',
					test: /editor\.(sc|sa|c)ss$/,
					chunks: 'all',
					enforce: true,
				},
				style: {
					name: 'style',
					test: /style\.(sc|sa|c)ss$/,
					chunks: 'all',
					enforce: true,
				},
				admin: {
					name: 'admin',
					test: /admin\.(sc|sa|c)ss$/,
					chunks: 'all',
					enforce: true,
				},
				default: false,
			},
		},
	},
	module: {
		...defaultConfig.module,
		rules: [
			...defaultConfig.module.rules,
			{
				test: /\.(sc|sa|c)ss$/,
				exclude: /node_modules/,
				use: [
					{
						loader: MiniCssExtractPlugin.loader,
					},
					{
						loader: 'css-loader',
					},
					{
						loader: 'sass-loader',
					},
					{
						loader: 'postcss-loader',
						options: {
							ident: 'postcss',
							plugins: () => [
								postcssPresetEnv( {
									stage: 3,
									features: {
										'custom-media-queries': {
											preserve: false,
										},
										'custom-properties': {
											preserve: true,
										},
										'nesting-rules': true,
									},
								} ),
							],
						},
					},
				],
			},
		],
	},
	plugins: [
		...defaultConfig.plugins,
		new MiniCssExtractPlugin( {
			filename: '[name].build.css',
		} ),
		new BrowserSyncPlugin(
			{
				host: 'localhost',
				port: 3000,
				proxy: localEnv,
				open: true,
				files: [ 'build/*.php', 'build/*.js', 'build/*.css' ],
			},
			{
				injectCss: true,
				reload: false,
			}
		),
		new OptimizeCSSAssetsPlugin( {
			cssProcessorPluginOptions: {
				preset: [ 'default', { discardComments: { removeAll: true } } ],
			},
		} ),
		new IgnoreEmitPlugin( [
			'editor.js',
			'style.js',
			'admin.js',
			'index.deps.json',
			'editor.deps.json',
			'style.deps.json',
			'admin.deps.json',
			'index.js.map',
			'editor.build.css.map',
			'style.build.css.map',
			'admin.build.css.map',
		] ),
	],
};
