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

const path = require('path');

/*
mix.js('resources/js/app.js', 'public/js').
    vue().
    postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ]).webpackConfig(require('./webpack.config'));
*/

mix.js("resources/js/app.js", "public/js")
.vue({ version: 3 })
    .postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
]).webpackConfig((webpack) => {
    return {

        plugins: [
            new webpack.DefinePlugin({
                                         __VUE_OPTIONS_API__: true,
                                         __VUE_PROD_DEVTOOLS__: false,
                                     }),
        ],
        resolve: {
            alias: {
                '@': path.resolve('resources/js'),
            },
        },
    };
})


if (mix.inProduction()) {
    mix.version();
}else{
    mix.sourceMaps();
}

if(process.env.MIX_ANALYZE_BUNDLE==='Yes'){
    require('laravel-mix-bundle-analyzer');
    mix.bundleAnalyzer();
}

mix.browserSync({
                    proxy: process.env.APP_URL,
                });
