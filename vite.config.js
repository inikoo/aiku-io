/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 04 Feb 2022 00:13:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

import { defineConfig } from 'vite'
import tailwindcss from 'tailwindcss'
import autoprefixer from 'autoprefixer'
import laravel from 'vite-plugin-laravel'
import vue from '@vitejs/plugin-vue'
import inertia from './resources/scripts/vite/inertia-layout'

export default defineConfig({
                                plugins: [
                                    inertia(),
                                    vue(),
                                    laravel({
                                                postcss: [
                                                    tailwindcss(),
                                                    autoprefixer(),
                                                ],
                                            }),
                                ],
                            })

/*
import vue from '@vitejs/plugin-vue';
const { resolve } = require('path');
const Dotenv = require('dotenv');

Dotenv.config();

const APP_URL = process.env.APP_URL || '';

console.log(APP_URL)
export default {
    plugins: [
        vue(),
    ],

    root: 'resources',
    base: `${APP_URL}`,

    build: {
        outDir: resolve(__dirname, 'public/dist'),
        emptyOutDir: true,
        manifest: true,
        target: 'es2018',
        rollupOptions: {
            input: '/js/t/app.js'
        }
    },

    server: {
        strictPort: true,
        port: 3000
    },

    resolve: {
        alias: {
            '@': '/js',
            '@t': '/js/tenants',
        }
    },

    optimizeDeps: {
        include: [
            'vue',
            '@inertiajs/inertia',
            '@inertiajs/inertia-vue3',
            '@inertiajs/progress',
            'axios'
        ]
    }
}
*/
