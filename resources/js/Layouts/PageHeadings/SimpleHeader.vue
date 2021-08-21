<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 19 Aug 2021 17:57:47 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <div class="">

        <div class="flex">
            <div v-if="displayBreadcrumbs">
                <nav class="sm:hidden" aria-label="Back">
                    <a href="#" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                        <ChevronLeftIcon class="flex-shrink-0 -ml-1 mr-1 h-5 w-5 text-gray-400" aria-hidden="true"/>
                        Back
                    </a>
                </nav>
                <simple-breadcrumb :breadcrumbs="breadcrumbs"/>
            </div>
            <div class="flex-grow ">
                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <select id="tabs" name="tabs" class="block w-full focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        <option v-for="tab in sections" :key="tab.name" :selected="tab['current']">{{ tab.name }}</option>
                    </select>
                </div>
                <div class="hidden sm:block 	  ">
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
                    <slot></slot>
                </h2>
            </div>
            <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">


                <button v-for="button in actions" :key="button.name" type="button" :class="button.primary ? 'ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' :
                         'ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'">
                    <Link :href="route(button.route)">{{ button.name }}</Link>
                </button>


            </div>
            <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">


                <span v-for="actionIcon in actionIcons" :key="actionIcon.name">
                    <button>
                    <font-awesome-icon :icon="actionIcon.icon" class="text-gray-400 hover:text-gray-500 ml-3" size="lg" aria-hidden="true"/>
                    </button>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import {ChevronLeftIcon, ChevronRightIcon} from '@heroicons/vue/solid';
import SimpleBreadcrumb from '@/Layouts/Breadcrumbs/SimpleBreadcrumb';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {Link, usePage} from '@inertiajs/inertia-vue3';

export default {
    props     : ['breadcrumbs', 'actions', 'module', 'actionIcons'],
    components: {
        ChevronLeftIcon,
        ChevronRightIcon, SimpleBreadcrumb, FontAwesomeIcon, Link,
    },
    setup(props) {

        let sections = [];

        const items = usePage().props.value.modules[props.module]['sections'];
        for (const item in items) {
            sections.push(
                {
                    name: items[item].name, href: route(item), current: route().current(item),
                },
            );
        }

        let displayBreadcrumbs = Object.keys(props.breadcrumbs).length > 0;
        return {
            sections, displayBreadcrumbs,
        };
    },

};
</script>
