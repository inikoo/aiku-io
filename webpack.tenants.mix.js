const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.js('resources/js/tenants/app.js', 'public/js/t').
    vue({version: 3}).
    postCss('resources/css/tenants/app.css', 'public/css/t', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ]).options({
                   postCss: [tailwindcss('./tailwind.tenants.config.js')],
               }).webpackConfig(require('./webpack.config'));




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
