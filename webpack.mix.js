const mix = require('laravel-mix');
const ESLintPlugin = require('eslint-webpack-plugin');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.disableNotifications();

mix.webpackConfig({
    module: {
        rules: [
            {
                test: /\.less$/,
                loader: 'less-loader',
                options: {
                    lessOptions: {
                        javascriptEnabled: true,
                    }
                }
            }
        ]
    },
    plugins: [
        new ESLintPlugin({
            extensions: [`js`, `jsx`],
            exclude: [
                'node_modules'
            ],
        })
    ],
});

mix.js('resources/js/client/admin/roots/app.js', 'public/js/client/admin/roots')
    .js('resources/js/client/frontend/roots/projects.js', 'public/js/client/frontend/roots/projects.js')
    .js('resources/js/client/frontend/roots/error.js', 'public/js/client/frontend/roots/error.js')
    .react();

if (mix.inProduction()) {
    mix.version();
}