<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 06 Feb 2022 22:03:06 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <Head :title="headerData.pageTitle??headerData.title??''" />
    <div class="mb-6">

        <div class="mt-2 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <span v-bind:title="headerData.titleTitle">{{ headerData.title }}</span> <span v-bind:title="headerData.subTitleTitle" class="text-gray-700 text-lg sm:text-xl  ">{{ headerData.subTitle }}</span>
                </h2>


                <div class="md:hidden mt-4 flex-shrink-0 flex ">
                    <button v-for="(button,href) in headerData['actionIcons']" :key="button.name" type="button" :class="[button.primary ?
                        'border-transparent text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' :
                        'border-gray-300    text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
                        'mb-3 inline-flex items-center px-4 py-2 border rounded-md shadow-sm text-sm font-medium']">
                        <Link :href="route(href,button['routeParameters'])">{{ button.name }}</Link>
                    </button>


                </div>

                <div class="mt-1 flex flex-row flex-wrap mt-0 -ml-6 ">
                    <div v-for="(meta,metaIdx) in headerData['meta']" :key="metaIdx" class="mt-2 ml-6 flex items-center text-sm text-gray-500">
                        <Badge v-if="meta.badge" :data="meta" ></Badge>
                        <template v-else>
                            <span class="text-gray-400"><font-awesome-icon v-if="meta.icon" :icon="meta.icon"  :class="[meta.iconClass,'flex-shrink-0 mr-1.5 h-5 w-5']"  aria-hidden="true"/></span>
                            <Link v-if="meta.href" v-bind:title="meta.nameTitle" :href="route(meta.href.route,meta.href.routeParameters)">
                                {{ meta.name }}
                            </Link>
                            <span v-else :class="meta.nameClass" v-bind:title="meta.nameTitle">{{ meta.name }}</span>
                        </template>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
                <button v-for="button in headerData['actions']" :key="button.name" type="button" :class="button.primary ? 'ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' :
                         'ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'">
                    <Link :href="route(button.route)">{{ button.name }}</Link>
                </button>


            </div>



            <div class="hidden md:block mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
                <span class="ml-2" v-for="(actionIcon,href) in headerData['actionIcons']" :key="actionIcon.name">
                    <Link :href="route(href,actionIcon['routeParameters'])" as="button">
                    <font-awesome-icon
                        :flip="actionIcon.flip"
                        :title="actionIcon.name" :icon="actionIcon.icon" class="text-gray-400 hover:text-gray-500 ml-3" size="lg" aria-hidden="true"/>
                    </Link>
                </span>
            </div>

            <slot name="action_options"></slot>
        </div>
    </div>
</template>

<script>
import {ChevronLeftIcon, ChevronRightIcon} from '@heroicons/vue/solid';
import Breadcrumbs from './breadcrumbs.vue';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {Link} from '@inertiajs/inertia-vue3';
import Badge from '../../elements/badge.vue';
import { Head } from '@inertiajs/inertia-vue3'
import { inject } from 'vue'

export default {
    props     : ['headerData'],
    components: {
        Badge,
        ChevronLeftIcon,
        ChevronRightIcon, Breadcrumbs, FontAwesomeIcon, Link,Head
    },
    setup(props) {

        const translations = inject('translations')


        let sections = [];

        /*
        const items = usePage().props.value.modules[props.headerData.module]['sections'];

        for (const item in items) {
            sections.push(
                {
                    name: items[item].name, href: route(item), current: route().current(item),
                },
            );
        }
        */

        let displayBreadcrumbs = Object.keys(props.headerData['breadcrumbs']).length > 0;
        return {
            sections, displayBreadcrumbs,translations
        };
    }

};
</script>
