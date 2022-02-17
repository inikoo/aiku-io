import { createApp, h } from 'vue'
import NProgress from 'nprogress'

import {createInertiaApp, Link} from '@inertiajs/inertia-vue3'
import { importPageComponent } from '@/scripts/vite/import-page-component'
//import { InertiaProgress } from '@inertiajs/progress';
//InertiaProgress.init();

import '@/css/nprogress.css';
import { Inertia } from '@inertiajs/inertia'


let timeout = null

Inertia.on('start', () => {
    timeout = setTimeout(() => NProgress.start(), 250)
})

Inertia.on('progress', (event) => {
    // @ts-ignore
    if (NProgress.isStarted() && event.detail.progress.percentage) {
        // @ts-ignore
        NProgress.set((event.detail.progress.percentage / 100) * 0.9)
    }
})

Inertia.on('finish', (event) => {
    clearTimeout(timeout)
    if (!NProgress.isStarted()) {
        return
    } else if (event.detail.visit.completed) {
        NProgress.done()
    } else if (event.detail.visit.interrupted) {
        NProgress.set(0)
    } else if (event.detail.visit.cancelled) {
        NProgress.done()
        NProgress.remove()
    }
})


/**
 * Echo exposes an expressive API for subscribing to channel and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

import { createPinia } from 'pinia'

createInertiaApp({
    resolve: (name) => importPageComponent(name, import.meta.glob('../views/pages/**/*.vue')),
    setup({el, app, props, plugin}) {
        createApp({render: () => h(app, props)})
            .use(plugin)
            .use(createPinia())
            .component('InertiaLink', Link) // Hack for inertiajs-tables-laravel-query-builder
            // @ts-ignore
            .mixin({methods: {route}})

            .mount(el);
    },
}).then()

