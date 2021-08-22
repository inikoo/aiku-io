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

mix.js('resources/js/app.js', 'public/js').
    vue().
    postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ]).webpackConfig(require('./webpack.config'));

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
