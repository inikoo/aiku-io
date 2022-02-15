<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Mon, 07 Feb 2022 19:08:50 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <Disclosure as="nav" class="bg-gray-800" v-slot="{ open }">
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="flex items-center px-2 lg:px-0">
                    <div class="flex-shrink-0">
                        <font-awesome-icon :icon="['fad', 'dice-d10']" class="text-gray-100" size="2x" />

                    </div>
                    <div class="hidden lg:block lg:ml-6">
                        <div class="flex space-x-4">

                            <Link v-for="(module,idx) in layout.modules" :key="idx"

                                  :class="[isCurrent(module.route) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'px-3 py-2 rounded-md text-sm font-medium']"

                                  :href="getLink(module)">
                                <navbar-dark-button-label :module="module" ></navbar-dark-button-label>
                            </Link>


                        </div>
                    </div>
                </div>
                <div class="flex-1 flex justify-center px-2 lg:ml-6 lg:justify-end">
                    <div class="max-w-lg w-full lg:max-w-xs">
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <SearchIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                            </div>
                            <input id="search" name="search" class="block w-full pl-10 pr-3 py-2 border border-transparent rounded-md leading-5 bg-gray-700 text-gray-300 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-white focus:ring-white focus:text-gray-900 sm:text-sm" placeholder="Search" type="search" />
                        </div>
                    </div>
                </div>
                <div class="flex lg:hidden">
                    <!-- Mobile menu button -->
                    <DisclosureButton class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Open main menu</span>
                        <MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                        <XIcon v-else class="block h-6 w-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>
                <div class="hidden lg:block lg:ml-4">
                    <div class="flex items-center">
                        <button type="button" class="flex-shrink-0 bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="h-6 w-6" aria-hidden="true" />
                        </button>


                    </div>
                </div>
            </div>
        </div>

        <DisclosurePanel class="lg:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <DisclosureButton  v-for="(module,idx) in layout.modules" :key="idx"
                                   as="a" :href="route(module.route)"  class="bg-gray-900 text-white   block px-3 py-2 rounded-md text-base font-medium ">{{module.name}}</DisclosureButton>

                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->

            </div>
            <div class="pt-4 pb-3 border-t border-gray-700">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                    </div>

                    <button type="button" class="ml-auto flex-shrink-0 bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                        <span class="sr-only">View notifications</span>
                        <BellIcon class="h-6 w-6" aria-hidden="true" />
                    </button>
                </div>

            </div>
        </DisclosurePanel>
    </Disclosure>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';


import { SearchIcon } from '@heroicons/vue/solid'
import { BellIcon, MenuIcon, XIcon } from '@heroicons/vue/outline'
import {useLayoutStore} from '../../../../scripts/stores/layout.js';
import NavbarDarkButtonLabel from './navbar-dark-button-label.vue';
const props = defineProps(['tenantCode','currentRoute']);


const layout = useLayoutStore();



const getLink = (module)=>{

    if(module.code==='marketing' && layout.currentModels.shop){
        return route('marketing.shops.show',layout.currentModels.shop)
    }
    return route(module.route)
}

const isCurrent = (route) => {
    const rootRoute=route.substring(0, route.indexOf('.'));
    const rootCurrentRoute=props.currentRoute.substring(0, props.currentRoute.indexOf('.'));
    return rootRoute===rootCurrentRoute;

};

</script>
