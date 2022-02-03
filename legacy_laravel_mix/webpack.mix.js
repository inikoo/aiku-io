/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Sep 2021 17:40:57 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

const mix = require('laravel-mix');
require('laravel-mix-merge-manifest')
if (['landlord', 'tenants'].includes(process.env.npm_config_section)) {





    require(`${__dirname}/webpack.${process.env.npm_config_section}.mix.js`)
    if (mix.inProduction()) {
        mix.version();
    } else {
        mix.sourceMaps();
        mix.browserSync({
                            proxy: process.env.APP_URL,
                        });
    }

    if (process.env.MIX_ANALYZE_BUNDLE === 'Yes') {
        require('laravel-mix-bundle-analyzer');
        mix.bundleAnalyzer();
    }

    mix.mergeManifest();

} else {
    console.log(
        '\x1b[41m%s\x1b[0m',
        'Provide correct --section argument to build command: landlord, tenants'
    )
    throw new Error('Provide correct --section argument to build command!')
}
