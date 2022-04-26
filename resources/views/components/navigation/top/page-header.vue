<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 06 Feb 2022 22:03:06 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <Head :title="headerData['pageTitle']??headerData.title??''"/>
    <div class="mb-6">

        <div v-if="headerData['topTabs']">
            <div class="sm:hidden">
                <label for="tabs" class="sr-only">Select a tab</label>
                <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                <select id="tabs" name="tabs" class="block w-full focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                    <option v-for="tab in headerData['topTabs']"  :data="tab.href['data']"  :key="tab.name" :selected="tab.current">{{ tab.name }}</option>
                </select>
            </div>
            <div class="hidden sm:block">
                <nav class="flex space-x-4" aria-label="Tabs">
                    <Link v-for="tab in headerData['topTabs']"
                       :key="tab.name"
                          :href="route(tab.href.route,tab.href['routeParameters'])"
                          as="button" type="button"  :data="tab.href['data']" method="patch"
                       :class="[tab.current ? 'bg-gray-700 text-gray-200' : 'text-gray-500 hover:text-gray-700', 'px-3 py-1 font-medium text-sm rounded-md']" :aria-current="tab.current ? 'page' : undefined">
                        {{ tab.name }}
                    </Link>
                </nav>
            </div>
        </div>

        <div class="mt-2 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <span v-bind:title="headerData['titleTitle']">{{ headerData.title }}</span>
                    <span v-if="headerData['inModel']" class="text-lg font-normal mx-2"> <span class="font-light">∈{{ headerData['inModel']['model'] }}</span> {{ headerData['inModel']['modelName'] }}</span>

                    <span v-if="headerData['subTitleTitle']" v-bind:title="headerData['subTitleTitle']" class="ml-3 text-gray-700 text-lg sm:text-xl  ">{{ headerData['subTitle'] }}</span>
                </h2>


                <div class="md:hidden mt-4 flex-shrink-0 flex ">
                    <button v-for="(button,idx) in headerData['actionIcons']" :key="idx" type="button" :class="[button['primary'] ?
                        'border-transparent text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' :
                        'border-gray-300    text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
                        'mb-3 inline-flex items-center px-4 py-2 border rounded-md shadow-sm text-sm font-medium']">
                        <Link :href="route(button['route'],button['routeParameters'])">{{ button.name }}</Link>
                    </button>


                </div>

                <div class="mt-1 flex flex-row flex-wrap mt-0 -ml-6 ">
                    <div v-for="(info,infoIdx) in headerData['info']" :key="infoIdx" class="mt-2 ml-6 flex items-center text-sm text-gray-500">
                        <component
                            :is="getComponent(info['type'])"
                            :data="info['data']">
                            <component
                                v-if="info['slot']"
                                :is="getComponent(info['slot']['type'])"
                                :data="info['slot']['data']">
                            </component>
                        </component>
                    </div>
                </div>


            </div>
            <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
                <button v-for="button in headerData['actions']" :key="button.name" type="button" :class="button['primary'] ? 'ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' :
                         'ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'">
                    <Link :href="route(button.route)">{{ button.name }}</Link>
                </button>


            </div>


            <div class="hidden md:block mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
                <span class="ml-2" v-for="(actionIcon,idx) in headerData['actionIcons']" :key="idx">
                    <Link :href="route(actionIcon['route'],actionIcon['routeParameters'])" as="button">
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

<script setup>
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {Link} from '@inertiajs/inertia-vue3';
import {Head} from '@inertiajs/inertia-vue3';
import {useLocaleStore} from '../../../../scripts/stores/locale.js';

import Badge from '../../elements/badge.vue';
import Text from '../../elements/text.vue';
import Number from '../../elements/number.vue';
import ComponentGroup from '../../elements/component-group.vue';
import ComponentLink from '../../elements/component-link.vue';
import Icon from '../../elements/icon.vue';

const props = defineProps(['headerData']);
const locale = useLocaleStore();

let sections = [];

const getComponent = (componentName) => {
    const components = {
        'group': ComponentGroup,
        'badge': Badge,
        'text' : Text,
        'number': Number,
        'icon': Icon,
        'link': ComponentLink

    };
    return components[componentName] ?? null;
};




</script>
