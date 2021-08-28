// noinspection JSIgnoredPromiseFromCall
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 18 Aug 2021 22:58:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

require('./bootstrap');

import {createApp, h} from 'vue';
import {createInertiaApp,Link} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';
import AppLayout from '@/Layouts/AppLayout';


const appName = window.document.getElementsByTagName('title')[0]?.innerText ||
    'Laravel';

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
