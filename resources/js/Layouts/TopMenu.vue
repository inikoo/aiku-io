<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Tue, 11 Jan 2022 15:54:55 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>

    <div class="border-b border-gray-200 ">



        <span v-for="item in items" :key="item.name">



            <span v-if="item.type==='modelOptions'" :class="[isCurrent(item.module)  ? 'text-gray-700' : 'text-gray-400 group-hover:text-gray-500', 'ml-1 mr-4  inline-block']">


                <span v-show="isCurrent(item.module)">
                     <font-awesome-icon
                         v-if="item['icon']"
                         :icon="item['icon']"
                         :class="[isCurrent(item.module)  ? 'text-gray-800' : 'text-gray-400 group-hover:text-gray-500', 'mr-1 ']"
                         fixed-width aria-hidden="true"/>
                       {{ item['name'] }}
                </span>


                [<span v-if="currentModels[item.module]" class="mx-2 ">
                   <Link
                       as="button"
                       :href="route(item.module+'.index',currentModels[item.module])"
                   >
                                      {{ item.options[currentModels[item.module]]['code'] }}
                       </Link>
                                  </span>


                <Menu as="span" class="inline-block relative">
                    <div>
                        <MenuButton>


                                <span v-show="!currentModels[item.module]">
                                     {{  Object.keys(item.options).length    }} <font-awesome-icon :icon="['fal', 'bars']" class="mr-1" aria-hidden="true"/>
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
                </Menu>

                ]



            </span>
            <span v-else-if="item.type==='modelIndex'" :class="[ item.type==='modelIndex' ?'':'mr-4',    'ml-2 ']">
            <Link
                as="button"
                :href="item['href']"
                :class="[isCurrent(item.module) ? 'text-gray-700' : 'text-gray-400 group-hover:text-gray-500', '']">

                <span  v-show="isCurrent(item.module)">
                <font-awesome-icon
                    v-if="item['icon']"
                    :icon="item['icon']"
                    :class="[isCurrent(item.module)  ? 'text-gray-800' : 'text-gray-400 group-hover:text-gray-500', 'mr-1 ']"
                    fixed-width aria-hidden="true"/>{{ item['name'] }}
                </span>
                <span v-show="!isCurrent(item.module)">
                       {{ currentModels[item.module] }} <font-awesome-icon :icon="['fal', 'bars']" class="mr-1" aria-hidden="true"/>
                </span>

            </Link>
            </span>
            <span v-else :class="[ item.type==='modelIndex' ?'':'mr-4',    'ml-2 ']">
            <Link
                as="button"
                :href="item['href']"
                :class="[isCurrent(item.module) ? 'text-gray-700' : 'text-gray-400 group-hover:text-gray-500', '']">

                <font-awesome-icon
                    v-if="item['icon']"
                    :icon="item['icon']"
                    :class="[isCurrent(item.module)  ? 'text-gray-800' : 'text-gray-400 group-hover:text-gray-500', 'mr-1 ']"
                    fixed-width aria-hidden="true"/>
                       {{ item['name'] }}

            </Link>
            </span>

        </span>
    </div>
</template>

<script>

// Aux icons
import {faAngleDown, faBars} from '@/private/pro-light-svg-icons';

library.add(faAngleDown, faBars);

// Module icons
import {faTachometerAltFast, faClipboardUser, faUserCircle, faStoreAlt, faStore} from '@/private/pro-light-svg-icons';

library.add(faTachometerAltFast, faClipboardUser, faUserCircle, faStoreAlt, faStore);

import {library} from '@fortawesome/fontawesome-svg-core';

import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {Link} from '@inertiajs/inertia-vue3';
import {Menu, MenuButton, MenuItems, MenuItem} from '@headlessui/vue';

const userNavigation = [
    {name: 'Your Profile', href: '#'},
    {name: 'Settings', href: '#'},
    {name: 'Sign out', href: '#'},
];
import {ref} from 'vue';

export default {
    props     : ['items', 'currentModels'],
    components: {

        FontAwesomeIcon, Link, Menu, MenuButton, MenuItems, MenuItem,
    },

    setup(props) {
        const sidebarOpen = ref(false);
        let currentModels = props.currentModels;

        return {
            userNavigation,
            sidebarOpen,
            currentModels,

        };
    },

    methods: {
        isCurrent(module) {
            this.$page.url;
            return route().current(module + '.*');

        }
    },

};
</script>
