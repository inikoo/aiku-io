<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 19 Aug 2021 17:57:47 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <Head :title="headerData.pageTitle??headerData.title??''" />
    <div class="mb-6">
        <div class="flex">
            <div v-if="displayBreadcrumbs">
                <nav class="sm:hidden" aria-label="Back">
                    <a href="#" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                        <ChevronLeftIcon class="flex-shrink-0 -ml-1 mr-1 h-5 w-5 text-gray-400" aria-hidden="true"/>
                        {{ translations.back }}
                    </a>
                </nav>
                <breadcrumbs :breadcrumbs="headerData['breadcrumbs']"/>
            </div>
            <div class="flex-grow ">
                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">{{ translations.select_a_tab }}</label>
                    <select id="tabs" name="tabs" class="block w-full focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        <option v-for="tab in sections" :key="tab.name" :selected="tab['current']">{{ tab.name }}</option>
                    </select>
                </div>
                <div class="hidden sm:block">
                    <nav class="flex space-x-4 justify-end" aria-label="Tabs">
                        <Link v-for="tab in sections" :key="tab.name" :href="tab['href']"
                              :class="[tab['current'] ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700', 'px-3 py-0 font-medium text-sm rounded-md']"
                              :aria-current="tab['current'] ? 'page' : undefined">
                            {{ tab.name }}
                        </Link>
                    </nav>
                </div>
            </div>
        </div>
        <div class="mt-2 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <span v-bind:title="headerData.titleTitle">{{ headerData.title }}</span> <span v-bind:title="headerData.subTitleTitle" class="text-gray-700 text-lg sm:text-xl  ">{{ headerData.subTitle }}</span>
                </h2>

                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                    <div v-for="(meta,metaIdx) in headerData['meta']" :key="metaIdx" class="mt-2 flex items-center text-sm text-gray-500">
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
            <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
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
import Breadcrumbs from '@/Layouts/Breadcrumbs';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {Link, usePage} from '@inertiajs/inertia-vue3';
import Badge from '@/Components/Badge';
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
