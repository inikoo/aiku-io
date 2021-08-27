<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 19 Aug 2021 18:54:53 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <nav class="hidden sm:flex" aria-label="Breadcrumb">
        <ol role="list" class="flex items-center space-x-2">
            <li>
                <div>
                    <Link :href="route('dashboard.index')" class="text-gray-400 hover:text-gray-500">
                        <font-awesome-icon :icon="['fal', 'tachometer-alt-fast']" class="flex-shrink-0 h-4 w-4" aria-hidden="true"/>
                        <span class="sr-only">{{ __('Dashboard') }}</span>
                    </Link>
                </div>
            </li>
            <li v-for="breadcrumb in  breadcrumbs" :key="breadcrumb.name">
                <div class="flex items-center">
                    <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-400" aria-hidden="true"/>
                    <Link :href="route(breadcrumb.route,breadcrumb.route_parameters)" class="ml-2 text-sm font-medium text-gray-500 hover:text-gray-700" :aria-current="breadcrumb.current ? 'page' : undefined">{{ breadcrumb.name }}</Link>
                </div>
            </li>
        </ol>
    </nav>
</template>

<script>
import {ChevronRightIcon, HomeIcon} from '@heroicons/vue/solid';
import {Link} from '@inertiajs/inertia-vue3';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {__} from 'matice';


export default {
    props     : ['breadcrumbs'],
    components: {
        ChevronRightIcon,
        HomeIcon,
        Link, FontAwesomeIcon,
    },

    methods: {
        __: __,
    },
};
</script>
