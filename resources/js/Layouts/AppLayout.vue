<!--
  ~  Author: Raul Perusquia <raul@inikoo.com>
  ~  Created: Mon, 16 Aug 2021 19:57:35 Malaysia Time, Kuala Lumpur, Malaysia
  ~  Copyright (c) 2021, Inikoo
  ~  Version 4.0
  -->
<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <span  v-for="model in modelsToWatch" >
    {{watchCurrentModel(model)}}
    </span>
    <div class="h-screen flex overflow-hidden bg-white">
        <!-- Static sidebar for mobile -->
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog as="div" class="fixed inset-0 flex z-40 lg:hidden" @close="sidebarOpen = false">
                <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300"
                                 leave-from="opacity-100" leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-gray-600 bg-opacity-75"/>
                </TransitionChild>
                <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform"
                                 leave-from="translate-x-0" leave-to="-translate-x-full">
                    <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white focus:outline-none">
                        <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100" leave-to="opacity-0">
                            <div class="absolute top-0 right-0 -mr-12 pt-2">
                                <button type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
                                    <span class="sr-only">Close sidebar</span>
                                    <XIcon class="h-6 w-6 text-white" aria-hidden="true"/>
                                </button>
                            </div>
                        </TransitionChild>
                        <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                            <div class="flex-shrink-0 flex items-center px-4">
                                <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-logo-indigo-600-mark-gray-900-text.svg" alt="Workflow"/>
                            </div>
                            <nav aria-label="Sidebar" class="mt-5">
                                <div class="px-2 space-y-1">
                                    <Link v-for="item in navigation" :key="item.name" :href="item['href']"
                                          :class="[route().current(item['module']+'.*')  ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-base font-medium rounded-md']">
                                        <font-awesome-icon :icon="item['icon']" :class="[route().current(item['module']+'.*')  ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 h-6 w-6']"
                                                           aria-hidden="true"/>
                                        {{ item['name'] }}
                                    </Link>
                                </div>
                                <div class="space-y-1 mt-6">

                                    <div class="space-y-1 " role="group" aria-labelledby="projects-headline">
                                        <Link v-for="item in secondaryNavigation" :key="item.name" :href="item['href']"
                                              class="group flex items-center px-3 py-2 text-base font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50">
                                            <span class="  truncate">
                                                {{ item.name }}
                                            </span>
                                        </Link>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                            <Link :href="route('profile.show')" class="flex-shrink-0 group block">
                                <div class="flex items-center">
                                    <div>
                                        <img class="inline-block h-10 w-10 rounded-full"
                                             src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80" alt=""/>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-base font-medium text-gray-700 group-hover:text-gray-900">
                                            {{ $page.props.auth.user.name }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-500 group-hover:text-gray-700">
                                            {{ __('View profile') }}
                                        </p>
                                    </div>
                                </div>
                            </Link>
                        </div>

                        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                            <form @submit.prevent="logout" class="flex-shrink-0 group block">

                                <div class="flex items-center">
                                    <div>
                                        <LogoutIcon class="inline-block text-gray-400 group-hover:text-gray-500 h-6 w-6 ml-2" aria-hidden="true"/>
                                    </div>
                                    <div class="ml-5">
                                        <p class="text-base font-medium text-gray-700 group-hover:text-gray-900">
                                            {{ __('Log out') }}
                                        </p>

                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>
                </TransitionChild>
                <div class="flex-shrink-0 w-14" aria-hidden="true">
                    <!-- Force sidebar to shrink to fit close icon -->
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-64">
                <!-- Sidebar component, swap this element with another sidebar if you like -->
                <div class="flex-1 flex flex-col min-h-0 border-r border-gray-200 bg-gray-100">
                    <div class="flex-1 flex flex-col pt-0 pb-4 overflow-y-auto">

                        <div class="flex items-center flex-shrink-0 px-4 py-2 border-b-2 mb-2	">
                            <font-awesome-icon :icon="['fal', 'tachometer-alt-fast']" class="mr-3" aria-hidden="true"/>
                            {{ tenantName }} {{currentModels['shop']}}
                        </div>

                        <div class="flex items-center flex-shrink-0 px-4">
                            <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-logo-indigo-600-mark-gray-900-text.svg" alt="Workflow"/>
                        </div>
                        <nav class="mt-5 flex-1" aria-label="Sidebar">
                            <div class="px-2 space-y-1">
                                <div v-for="item in navigation" key="item.name" v-show="route().current(item['module']+'.*')">
                                    <Link v-for="section in item['sections']" :href="section.href"
                                          :class="[route().current(section.href) ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                                        <font-awesome-icon fixed-width :icon="section['icon']" :class="[route().current(section.href) ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 ']"
                                                           aria-hidden="true"/>
                                        {{ section['name'] }}
                                    </Link>
                                </div>
                            </div>
                            <div class="space-y-1 mt-6">

                                <div class="space-y-1 " role="group" aria-labelledby="projects-headline">
                                    <Link v-for="item in secondaryNavigation" :key="item.name" :href="item['href']"
                                          class="group flex items-center px-3 py-2 text-xs font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50">
                                            <span class="  truncate">
                                                {{ item.name }}
                                            </span>
                                    </Link>
                                </div>
                            </div>
                        </nav>
                    </div>
                    <div class="flex-shrink-0 w-full flex border-t border-gray-200 p-4">
                        <Link :href="route('profile.show')">
                            <div>
                                <img class="inline-block h-9 w-9 rounded-full"
                                     src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80" alt=""/>
                            </div>
                        </Link>
                        <div class="ml-3 w-full">
                            <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
                                {{ $page.props.auth.user.name }}
                            </p>
                            <div class="flex text-xs font-medium text-gray-500">
                                <div class="flex-1">
                                    <Link :href="route('profile.show')">{{ __('View profile') }}</Link>
                                </div>
                                <div class="flex-1 text-right">
                                    <form @submit.prevent="logout" class="float-right">
                                        <button type="submit">
                                            <LogoutIcon class="inline-block text-gray-400 group-hover:text-gray-500 h-3 w-3 ml-1" aria-hidden="true"/>
                                            {{ __('Log out') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>


        <div class="flex flex-col min-w-0 flex-1 overflow-hidden">

            <TopMenu :items="navigation" :currentModels="currentModels"   />


            <div class="lg:hidden">

                <div class="flex items-center justify-between bg-gray-50 border-b border-gray-200 px-4 py-1.5">
                    <div>
                        <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Workflow"/>
                    </div>
                    <div>
                        <button type="button" class="-mr-3 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900" @click="sidebarOpen = true">
                            <span class="sr-only">{{ __('Open sidebar') }}</span>
                            <MenuIcon class="h-6 w-6" aria-hidden="true"/>
                        </button>
                    </div>
                </div>
            </div>

            <div class="relative z-10 flex-shrink-0 flex h-6 bg-white shadow">
                <button type="button" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <MenuAlt2Icon class="h-6 w-6" aria-hidden="true"/>
                </button>


                <div class="flex-1 px-4 flex justify-between">

                    <div class="flex-1 flex">
                        <form class="w-full flex md:ml-0" action="#" method="GET">
                            <label for="search-field" class="sr-only">Search</label>
                            <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                    <SearchIcon class="h-5 w-5" aria-hidden="true"/>
                                </div>
                                <input id="search-field"
                                       class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent sm:text-sm"
                                       placeholder="Search" type="search" name="search"/>
                            </div>
                        </form>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <button type="button" class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="h-4 w-4" aria-hidden="true"/>
                        </button>


                    </div>
                </div>
            </div>


            <div class="flex-1 relative z-0 flex overflow-hidden ">
                <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none">
                    <!-- Start main area-->
                    <div class="absolute inset-0 py-2 px-4 sm:px-6 lg:px-7 ">
                        <div class="h-full ">
                            <slot></slot>
                        </div>
                    </div>
                    <!-- End main area -->
                </main>
                <aside class="hidden relative xl:flex xl:flex-col flex-shrink-0 w-96 border-l border-gray-200">
                    <!-- Start secondary column (hidden on smaller screens) -->
                    <div class="absolute inset-0 py-6 px-4 sm:px-6 lg:px-8">
                        <div class="h-full ">

                            <slot name="secondary"></slot>
                        </div>
                    </div>
                    <!-- End secondary column -->
                </aside>
            </div>
        </div>
    </div>
</template>


<script>
import {ref} from 'vue';
import {Dialog, DialogOverlay, TransitionChild, TransitionRoot} from '@headlessui/vue';
import {__} from 'matice';
import {Link} from '@inertiajs/inertia-vue3';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {library} from '@fortawesome/fontawesome-svg-core';

import {MenuIcon, LogoutIcon, XIcon, BellIcon, MenuAlt2Icon} from '@heroicons/vue/outline';
import {usePage} from '@inertiajs/inertia-vue3';
import {SearchIcon} from '@heroicons/vue/solid';
import Header from '@/Layouts/PageHeader';

// App icons
import {faSlidersHSquare, faHistory, faPlus, faEdit, faPortalExit, faRobot, faAngleRight, faAngleDown} from '@/private/pro-light-svg-icons';

library.add(faSlidersHSquare, faHistory, faPlus, faEdit, faPortalExit, faRobot, faAngleRight, faAngleDown);
import {faBirthdayCake, faMars, faVenus} from '@/private/pro-regular-svg-icons';

library.add(faBirthdayCake, faMars, faVenus);

import TopMenu from '@/Layouts/TopMenu';

// Section icons
let currentModels;
export default {
    components: {
        TopMenu,
        Dialog, DialogOverlay, TransitionChild, TransitionRoot, Link, FontAwesomeIcon, MenuIcon, XIcon, LogoutIcon, SearchIcon, BellIcon, MenuAlt2Icon, Header,

    }, setup() {

        const sidebarOpen = ref(false);
        let secondaryNavigation = [];

        const tenantName=usePage().props.value.tenant;
        currentModels=usePage().props.value.currentModels;

        console.log(currentModels)

        let navigation = [];
        let sections;
        const modules = usePage().props.value.modules;

        let modelsToWatch=[];
        for (const module in modules) {

            if (module !== 'dashboard') {

                if(modules[module].type==='modelOptions'){
                    modelsToWatch.push(module)
                }

                sections = [];
                for (const section in modules[module]['sections']) {

                    sections.push(
                        {
                            name: modules[module]['sections'][section].name,
                            href: route(section),
                            icon: modules[module]['sections'][section]['icon'],
                        },
                    );
                }


                console.log(modules[module]['options'] ?? {})


                navigation.push(
                    {

                        href        : route(modules[module]['route']),
                        sections    : sections,
                        module      : module,

                        name        : modules[module].name,
                        icon        : modules[module].icon,
                        type        : modules[module].type,
                        currentModel: modules[module].currentModel,
                        options     : modules[module]['options'] ?? {},

                        //options     : modules[module]['options'] ?? {},
                       // hasOptions  : modules[module]['options'] && Object.keys(modules[module]['options']).length > 1,
                    },
                );
            }
        }

        return {
            navigation, secondaryNavigation, sidebarOpen,tenantName,currentModels,modelsToWatch
        };
    }, methods: {
        __: __,
        logout() {
            this.$inertia.post(route('logout'));
        },
        watchCurrentModel(model){





            if(route().current(model + '.*')){
                let actualModel=model
                console.log(route().params)
                currentModels[model]=route().params[actualModel];
            }

            return null;
        }
    },

};
</script>
