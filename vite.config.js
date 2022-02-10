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

