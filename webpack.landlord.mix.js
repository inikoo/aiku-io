/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Sep 2021 17:44:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.js('resources/js/landlord/app.js', 'public/js/l').
    vue({version: 3}).
    postCss('resources/css/landlord/app.css', 'public/css/l', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ]).options({
                   postCss: [tailwindcss('./tailwind.landlord.config.js')],
               }).webpackConfig(require('./webpack.config'));

