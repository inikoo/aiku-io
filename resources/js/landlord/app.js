/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Sep 2021 18:06:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

// noinspection JSIgnoredPromiseFromCall


require('./bootstrap');

import {createApp, h} from 'vue';
import {createInertiaApp,Link} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';
import AppLayout from '@/Layouts/AppLayout';


const appName = window.document.getElementsByTagName('title')[0]?.innerText ||
    'Aiku';

createInertiaApp({
                     title  : (title) => `${title} - ${appName}`,
                     resolve: name => {
                         const page = require(`./Pages/${name}`).default;
                         if (page.layout === undefined) {
                             page.layout = AppLayout;
                         }
                         return page;
                     },

                     setup({el, app, props, plugin}) {
                         return createApp({render: () => h(app, props)}).
                             use(plugin).
                             component('InertiaLink',
                                       Link) // Here happens the magic ;-)
                             .mixin({methods: {route}}).
                             mount(el);
                     },
                 });

InertiaProgress.init({color: '#4B5563'});
