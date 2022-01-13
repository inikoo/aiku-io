<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Tue, 11 Jan 2022 15:54:55 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->


<template>
    <div class="border-b border-gray-200 ml-2 ">
        <span v-for="item in items" :key="item.name">
            <span v-if="item.type==='modelOptions'" :class="[isCurrent(item.module)  ? 'text-gray-700' : 'text-gray-400 group-hover:text-gray-500', 'mr-4  inline-block']">

                <TopMenuLink
                    v-if=" (currentModels[item.module]  && !isCurrent(item.module+'s') ) "
                    :href="route(item.module+'s.index')" :icon="['fal', 'bars']"/>
                <TopMenuLink
                    v-if="(!isCurrent(item.module+'s') && currentModels[item.module]  )"
                    :href="route(item.module+'.index',currentModels[item.module])" :icon="item['icon']" :label="item['code']" :isCurrent="isCurrent(item.module)"/>

                [ <TopMenuLink
                v-if="currentModels[item.module]"
                :href="route(item.module+'.index',currentModels[item.module])"
                :label="item.options[
                    currentModels[item.module]]
                    ['code']" :isCurrent="isCurrent(item.module)"/>
                    <Menu as="span" class="inline-block relative ml-2">
                    <div>
                        <MenuButton>


                                <span v-show="!currentModels[item.module]">
                                     {{ Object.keys(item.options).length }} <font-awesome-icon :icon="['fal', 'bars']" class="mr-1" aria-hidden="true"/>
                                  </span>

                                  <font-awesome-icon :icon="['fal', 'angle-down']" class="mr-1" aria-hidden="true"/>

                        </MenuButton>
                    </div>
                    <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                        <MenuItems class="z-20 absolute   mt-0.5 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <MenuItem v-for="(model,modelID) in item.options" :key="model.code" v-slot="{ active }">
                                <Link
                                    :href="route(item.module+'.index',modelID)"
                                    :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">
                                    {{ model.name }}  </Link>
                            </MenuItem>
                        </MenuItems>
                    </transition>
                </Menu> ]
            </span>
            <span v-else-if="item.type==='modelIndex'" :class="'mr-2'">
                <TopMenuLink v-if="(isCurrent(item.module) || !currentModels[item.module.slice(0, -1)]  )" :href="item['href']" :icon="item['icon']" :label="item['code']" :isCurrent="isCurrent(item.module)"/>
            </span>
            <TopMenuLink v-else :href="item['href']" :icon="item['icon']" :label="item['code']" :isCurrent="isCurrent(item.module)" :class="'mr-4'"/>
        </span>
    </div>
</template>

<script>
/* global route */


// Aux icons
import {faAngleDown, faBars} from '@/private/pro-light-svg-icons';

library.add(faAngleDown, faBars);
// Module icons
import {faTachometerAltFast, faClipboardUser, faUserCircle, faStoreAlt, faPersonCarry} from '@/private/pro-light-svg-icons';

library.add(faTachometerAltFast, faClipboardUser, faUserCircle, faStoreAlt, faPersonCarry);

import {library} from '@fortawesome/fontawesome-svg-core';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {Link} from '@inertiajs/inertia-vue3';
import {Menu, MenuButton, MenuItems, MenuItem} from '@headlessui/vue';
import {ref} from 'vue';
import TopMenuLink from '@/Layouts/TopMenuILink';

export default {
    props     : ['items', 'currentModels'],
    components: {TopMenuLink, FontAwesomeIcon, Link, Menu, MenuButton, MenuItems, MenuItem},

    setup(props) {
        console.log(props.items)

        console.log(props.currentModels)

        const sidebarOpen = ref(false);
        return {sidebarOpen};
    },

    methods: {
        isCurrent(module) {
            this.$page.url;
            return route().current(module + '.*');

        },
    },

};
</script>
