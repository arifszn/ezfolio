const mix = require('laravel-mix');

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
mix.browserSync('http://ezfolio.test');

mix.webpackConfig({
    module: {
        rules: [
            {
                test: /\.(jsx|js|vue)$/,
                loader: 'eslint-loader',
                enforce: 'pre',
                exclude: /(node_modules)/,
            }
        ]
    },
    plugins: [
        
    ],
});

mix.js('resources/js/client/admin/roots/app.js', 'public/js/client/admin/roots').react();

if (mix.inProduction()) {
    mix.version();
}