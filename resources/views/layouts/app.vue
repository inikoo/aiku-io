<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 06 Feb 2022 22:18:30 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->
<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <span v-for="model in modelsToWatch">{{ watchCurrentModel(model) }}</span>
    <div class="flex h-screen flex-col">
        <navbar-dark :tenant-code="tenantNickname" :current-route="route().current()"  ></navbar-dark>
        <div class="h-max grow flex overflow-hidden bg-white">
            <!-- Static sidebar for mobile -->
            <TransitionRoot as="template" :show="sidebarOpen">
                <Dialog as="div" class="fixed inset-0 flex z-40 lg:hidden" @close="sidebarOpen = false">
                    <TransitionChild
                        as="template"
                        enter="transition-opacity ease-linear duration-300"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="transition-opacity ease-linear duration-300"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <DialogOverlay class="fixed inset-0 bg-gray-600 bg-opacity-75" />
                    </TransitionChild>
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full"
                    >
                        <div
                            class="relative flex-1 flex flex-col max-w-xs w-full bg-white focus:outline-none"
                        >
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >
                                <div class="absolute top-0 right-0 -mr-12 pt-2">
                                    <button
                                        type="button"
                                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                        @click="sidebarOpen = false"
                                    >
                                        <span class="sr-only">Close sidebar</span>
                                        <XIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                                <div class="flex-shrink-0 flex items-center px-4">
                                    <img
                                        class="h-8 w-auto"
                                        src="https://tailwindui.com/img/logos/workflow-logo-indigo-600-mark-gray-900-text.svg"
                                        alt="Workflow"
                                    />
                                </div>
                                <nav aria-label="Sidebar" class="mt-5">
                                    <div class="px-2 space-y-1">
                                        <Link
                                            v-for="item in navigation"
                                            :key="item.name"
                                            :href="item['href']"
                                            :class="[route().current(item['module'] + '.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-base font-medium rounded-md']"
                                        >
                                            <font-awesome-icon
                                                :icon="item['icon']"
                                                :class="[route().current(item['module'] + '.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 h-6 w-6']"
                                                aria-hidden="true"
                                            />
                                            {{ item['name'] }}
                                        </Link>
                                    </div>
                                    <div class="space-y-1 mt-6">
                                        <div
                                            class="space-y-1"
                                            role="group"
                                            aria-labelledby="projects-headline"
                                        >
                                            <Link
                                                v-for="item in secondaryNavigation"
                                                :key="item.name"
                                                :href="item['href']"
                                                class="group flex items-center px-3 py-2 text-base font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50"
                                            >
                                                <span class="truncate">{{ item.name }}</span>
                                            </Link>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                            <div
                                v-if="$page.props.auth.user.userable_type !== 'Tenant'"
                                class="flex-shrink-0 flex border-t border-gray-200 p-4"
                            >
                                <Link :href="route('profile.show')" class="flex-shrink-0 group block">
                                    <div class="flex items-center">
                                        <div>
                                            <Avatar variant="pixel" name="$page.props.auth.user.name" />
                                        </div>
                                        <div class="ml-3">
                                            <p
                                                class="text-base font-medium text-gray-700 group-hover:text-gray-900"
                                            >{{ $page.props.auth.user.name }}</p>
                                            <p
                                                class="text-sm font-medium text-gray-500 group-hover:text-gray-700"
                                            >{{ translations.see_profile }}</p>
                                        </div>
                                    </div>
                                </Link>
                            </div>

                            <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                                <form @submit.prevent="logout" class="flex-shrink-0 group block">
                                    <div class="flex items-center">
                                        <div>
                                            <LogoutIcon
                                                class="inline-block text-gray-400 group-hover:text-gray-500 h-6 w-6 ml-2"
                                                aria-hidden="true"
                                            />
                                        </div>
                                        <div class="ml-5">
                                            <p
                                                class="text-base font-medium text-gray-700 group-hover:text-gray-900"
                                            >{{ translations.log_out }}</p>
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
            <sidebar 
            :nav-location="$page.props.navLocation??[]" 
            :current-route="route().current()" 
            :currentModels="currentModels"></sidebar>

            <div class="flex flex-col min-w-0 flex-1 overflow-hidden">

                <breadcrumbs :breadcrumbs="$page.props.breadcrumbs"></breadcrumbs>





                <div class="flex-1 relative z-0 flex overflow-hidden">
                    <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none">
                        <!-- Start main area-->
                        <div class="absolute inset-0 py-2 px-4 sm:px-6 lg:px-7">
                            <div class="h-full">
                                <slot></slot>
                            </div>
                        </div>
                        <!-- End main area -->
                    </main>
                    <aside
                        class="hidden relative 2xl:flex 2xl:flex-col flex-shrink-0 w-96 border-l border-gray-200"
                    >
                        <!-- Start secondary column (hidden on smaller screens) -->
                        <div class="absolute inset-0 py-6 px-4 sm:px-6 lg:px-8">
                            <div class="h-full">
                                <slot name="secondary"></slot>
                            </div>
                        </div>
                        <!-- End secondary column -->
                    </aside>
                </div>
            </div>
        </div>
        <div class="bg-slate-800 text-gray-100 text-sm		" >03</div>
    </div>



</template>


<script>
import { ref } from 'vue';
import { Dialog, DialogOverlay, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { Link } from '@inertiajs/inertia-vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';

import { MenuIcon, LogoutIcon, XIcon, BellIcon, MenuAlt2Icon } from '@heroicons/vue/outline';
import { usePage } from '@inertiajs/inertia-vue3';
import { SearchIcon } from '@heroicons/vue/solid';
import Header from '../components/navigation/top/page-header.vue';

import { faDiceD10 } from '@/private/pro-duotone-svg-icons';

library.add(faDiceD10);

// App icons
import { faSlidersHSquare, faHistory, faPlus, faEdit, faPortalExit, faRobot, faAngleRight, faAngleDown, faLayerGroup,faTachometerAltFast,faBars } from '@/private/pro-light-svg-icons';
library.add(faSlidersHSquare, faHistory, faPlus, faEdit, faPortalExit, faRobot, faAngleRight, faAngleDown, faLayerGroup,faTachometerAltFast,faBars);

import { faBirthdayCake, faMars, faVenus } from '@/private/pro-regular-svg-icons';
library.add(faBirthdayCake, faMars, faVenus);
import { provide, computed } from 'vue'

import TopMenu from '../components/navigation/top/top-menu.vue';
import Avatar from "vue-boring-avatars";
import NavbarDark from '../components/navigation/top/navbar-dark.vue';
import Sidebar from '../components/navigation/left/sidebar.vue';
import Breadcrumbs from '../components/navigation/top/breadcrumbs.vue';


// Module icons
import {
     faClipboardUser, faDiceD4, faStoreAlt, faPersonCarry, faGlobe,
    faWarehouseAlt, faAppleCrate, faAbacus, faIndustry, faInventory, faPalletAlt,
    faUserSecret,faHandHoldingBox,faUser,faShoppingCart
} from '@/private/pro-light-svg-icons';

library.add( faClipboardUser, faDiceD4, faStoreAlt, faPersonCarry, faGlobe,
            faWarehouseAlt, faAppleCrate, faAbacus, faIndustry, faInventory, faPalletAlt,
            faUserSecret,faHandHoldingBox,faUser,faShoppingCart);

// For employee model
import { faTasks } from '@/private/pro-light-svg-icons';
library.add(faTasks);

// For inventory
import { faBox } from '@/private/pro-light-svg-icons';
library.add(faBox);

// For user model
import { faCheckCircle, faTimesCircle } from '@fortawesome/free-solid-svg-icons';
library.add(faCheckCircle, faTimesCircle);
import { faUserAlien } from '@/private/pro-light-svg-icons';
library.add(faUserAlien);



// Section icons
let currentModels;
let initTranslations;
let initLocale;
let initLayout;

export default {
    components: {
        Breadcrumbs,
        Sidebar,
        NavbarDark,
        TopMenu,
        Dialog, DialogOverlay, TransitionChild, TransitionRoot, Link, FontAwesomeIcon, MenuIcon, XIcon, LogoutIcon, SearchIcon, BellIcon, MenuAlt2Icon, Header, Avatar

    },
    setup() {

        initTranslations = usePage().props.value.translations;
        initLocale = usePage().props.value.locale;

        const translations = computed(() => {

            if (usePage().props.value.translations) {



                initTranslations = usePage().props.value.translations;
                return usePage().props.value.translations
            } else {
                return initTranslations
            }
        })


        const locale = computed(() => {

            if (usePage().props.value.locale) {
                initLocale = usePage().props.value.locale;
                return usePage().props.value.locale
            } else {
                return initLocale
            }
        })

        const layout = computed(() => {

            if (usePage().props.value.layout) {
                let layout = usePage().props.value.layout;
                //for (const module of layout){
                //    const root= module.route.substring(0, module.route.indexOf('.'));
                //    module.current=route().current(root+'.*');
                //}

                initLayout=layout;
                console.log(layout)

                return layout;
            } else {
                return initLayout
            }
        })


        console.log(usePage().props.value.headerData)

        provide('translations', translations)
        provide('locale', locale.value)
        provide('layout', layout)


        const sidebarOpen = ref(false);
        let secondaryNavigation = [];

        const tenantName = usePage().props.value.tenantName;
        const tenantNickname = usePage().props.value.tenantNickname;

        currentModels = usePage().props.value.currentModels;

        let navigation = [];
        let sections;
        const modules = usePage().props.value.modules;

        let modelsToWatch = [];
        for (const module in modules) {

            if (!(module === 'dashboard' || module === 'profile')) {

                if (modules[module].type === 'modelOptions') {
                    modelsToWatch.push(module);
                }

                sections = [];
                for (const section in modules[module]['sections']) {

                    sections.push(
                        {
                            name: modules[module]['sections'][section].name,
                            route: section,
                            icon: modules[module]['sections'][section]['icon'],
                        },
                    );
                }
                // todo: just use the array
                navigation.push(
                    {

                        sections: sections,
                        options: modules[module]['options'] ?? {},
                        route: modules[module].route,
                        routeParameters: modules[module].routeParameters,
                        name: modules[module].name,
                        code: modules[module].code,
                        icon: modules[module].icon,
                        fallbackModel: modules[module].fallbackModel,
                        type: modules[module].type,
                        currentModel: modules[module].currentModel,
                        indexRoute: modules[module].indexRoute,
                        module: module,
                        modelIndex: modules[module].modelIndex,
                        bgColor: modules[module].bgColor,
                    },
                );

            }
        }

        return {
            navigation, secondaryNavigation, sidebarOpen, tenantName, currentModels, modelsToWatch, translations,tenantNickname
        };
    },
    methods: {
        logout() {
            this.$inertia.post(route('logout'));
        },
        watchCurrentModel(model) {

            if (route().current(model + 's.*')) {
                let actualModel = model;
                if (model === 'fulfilment_house' || model === 'ecommerce_shop') {
                    actualModel = 'shop';
                }

                currentModels[model] = route().params[actualModel];
            }

            return null;
        },
        getSectionRouteParameters(moduleName, fallbackModel) {


            if (moduleName === 'inventory')
                moduleName = 'warehouse';

            if (['warehouse', 'ecommerce_shop', 'website', 'fulfilment_house', 'workshop'].includes(moduleName)) {
                return currentModels[moduleName] ?? fallbackModel ?? 1;
            }

            return {};
        },
        getLeftMenuVisibility(module) {
            let prefix = module.module;
            if (module.type === 'modelOptions') {
                prefix = prefix + 's.show'
            }
            if (module.type === 'modelIndex') {
                return route().current().startsWith(prefix) && !route().current().startsWith(prefix + '.show')
            } else {

                // Dont show left menu if is a single shop and navigate to shops index via breadcrumbs
                if (module.type === 'standard' && route().current() === prefix + 's.index') {
                    return false;
                }


                return route().current().startsWith(prefix)
            }



        }
    }

};
</script>
