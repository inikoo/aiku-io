const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .vue({version: 3})
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .webpackConfig(require('./webpack.config'));

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



/*

.webpackConfig((webpack) => {
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


 */
