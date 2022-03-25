<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 06 Feb 2022 22:18:30 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->
<!-- This example requires Tailwind CSS v2.0+ -->
<!--suppress NpmUsedModulesInstalled, JSFileReferences -->
<template>
    <div class="flex h-screen flex-col ">
        <navbar-dark  :current-route="route().current()"></navbar-dark>
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
                        <DialogOverlay class="fixed inset-0 bg-gray-600 bg-opacity-75"/>
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
                                        <XIcon class="h-6 w-6 text-white" aria-hidden="true"/>
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
                                            <Avatar variant="pixel" name="$page.props.auth.user.name"/>
                                        </div>
                                        <div class="ml-3">
                                            <p
                                                class="text-base font-medium text-gray-700 group-hover:text-gray-900"
                                            >{{ $page.props.auth.user.name }}</p>
                                            <p
                                                class="text-sm font-medium text-gray-500 group-hover:text-gray-700"
                                            >{{ locale.__('View profile') }}</p>
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
                                            >{{ locale.__('Log out') }}</p>
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
                :nav-data="$page.props.navData??[]"
                :current-route="route().current()">


            </sidebar>

            <div class="flex flex-col min-w-0 flex-1 overflow-hidden">

                <breadcrumbs :breadcrumbs="$page.props.breadcrumbs??[]"></breadcrumbs>


                <div class="flex-1 relative z-0 flex overflow-hidden">
                    <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none  max-w-screen-2xl	   ">
                        <!-- Start main area-->
                        <div class="absolute inset-0 py-2 px-4 sm:px-6 lg:px-7">
                            <div class="h-full">
                                <slot></slot>
                            </div>
                        </div>
                        <!-- End main area -->
                    </main>
                    <aside
                        class="hidden relative 2xl:flex 2xl:flex-col   flex-shrink-0 w-52  3xl:w-1/4  border-l border-gray-200"
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
        <div class="bg-slate-800 text-gray-100 text-sm"> {{ locale.language }} </div>
    </div>
</template>



<script setup>
import {Link} from '@inertiajs/inertia-vue3';
import FontAwesomeIcon from '@s/fa-icons';
import {usePage} from '@inertiajs/inertia-vue3';
import {ref, watchEffect} from 'vue';

import NavbarDark from '@c/navigation/top/navbar-dark.vue';
import {Dialog, DialogOverlay, TransitionChild, TransitionRoot} from '@headlessui/vue';
import {LogoutIcon, XIcon} from '@heroicons/vue/outline';

import Avatar from 'vue-boring-avatars';
import Breadcrumbs from '@c/navigation/top/breadcrumbs.vue';
import Sidebar from '@c/navigation/left/sidebar.vue';

import {useLocaleStore} from '@s/stores/locale.js';
import {useLayoutStore} from '@s/stores/layout.js';


const locale = useLocaleStore();
const layout = useLayoutStore();

watchEffect(() => {
    if (usePage().props.value.language) {locale.language = usePage().props.value.language;}
    if (usePage().props.value.translations) {locale.translations = usePage().props.value.translations;}
    if (usePage().props.value.modules) {layout.modules = usePage().props.value.modules;}
    if (usePage().props.value.currentModels) {layout.currentModels = usePage().props.value.currentModels;}
    if (usePage().props.value.tenant) {layout.tenant = usePage().props.value.tenant;}

});

const logout = () => $inertia.post(route('logout'));



const sidebarOpen = ref(false);



</script>

