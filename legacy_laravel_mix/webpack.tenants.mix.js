const mix = require('laravel-mix');

mix.js('resources/js/tenants/app.js', 'public/js/t').
    vue({version: 3}).
    postCss('resources/css/tenants/app.css', 'public/css/t', [
        require('tailwindcss')('./tailwind.tenants.config.js'),
        require('autoprefixer'),
]).webpackConfig(require('./webpack.config'));

