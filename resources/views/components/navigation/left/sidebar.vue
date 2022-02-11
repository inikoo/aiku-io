<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Mon, 07 Feb 2022 23:42:15 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <div class="hidden lg:flex lg:flex-shrink-0">
        <div class="flex flex-col   w-40  xl:w-64 ">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex-1 flex flex-col min-h-0 border-r border-gray-200 bg-gray-100">
                <div class="flex-1 flex flex-col pt-0 pb-4 overflow-y-auto">
                    <nav class="mt-5 flex-1" aria-label="Sidebar">
                        <div class="px-2 space-y-1">
                            <div
                                v-for="(item,idx) in layout"
                                :key="idx"
                                v-show="getItemVisibility(item.route)"
                            >
                                <Link
                                    v-for="(section,href) in item['sections']"
                                    :href="route(href,getSectionRouteParameters(item, item.fallbackModel))"
                                    :class="[navLocation['sectionRoot']===href ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']"
                                    v-show="navLocation['metaSection']?  navLocation['metaSection']===section['metaSection']   : true"
                                >
                                    <font-awesome-icon
                                        fixed-width
                                        :icon="section['icon']"
                                        :class="[navLocation['sectionRoot']===href? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 ']"
                                        aria-hidden="true"
                                    />
                                    {{ section['name'] }}



                                </Link>
                            </div>
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
                                    class="group flex items-center px-3 py-2 text-xs font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50"
                                >
                                    <span class="truncate">{{ item.name }}</span>
                                </Link>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="flex-shrink-0 w-full flex border-t border-gray-200 p-4">
                    <Link
                        v-if="$page.props.auth.user.userable_type !== 'Tenant'"
                        :href="route('profile.show')"
                    >
                        <div>
                            <Avatar variant="pixel" name="$page.props.auth.user.name" />
                        </div>
                    </Link>
                    <div class="ml-3 w-full">
                        <p
                            v-if="$page.props.auth.user.userable_type !== 'Tenant'"
                            class="text-sm font-medium text-gray-700 group-hover:text-gray-900"
                        >{{ $page.props.auth.user.name }}</p>
                        <div class="flex text-xs font-medium text-gray-500">
                            <div class="flex-1">
                                <Link
                                    v-if="$page.props.auth.user.userable_type !== 'Tenant'"
                                    :href="route('profile.show')"
                                >{{ translations.see_profile }}</Link>
                                <span v-else>{{ $page.props.auth.user.name }}</span>
                            </div>
                            <div class="flex-1 text-right">

                                <Link :href="route('logout')" method="post" as="button">
                                    <LogoutIcon
                                        class="inline-block text-gray-400 group-hover:text-gray-500 h-3 w-3 ml-1"
                                        aria-hidden="true"
                                    />
                                </Link>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Avatar from "vue-boring-avatars";
import { LogoutIcon } from '@heroicons/vue/outline';
import {inject} from 'vue';

const props = defineProps(['navLocation','currentRoute']);


const layout = inject('layout')
const translations = inject('translations')
const currentModels = inject('currentModels')
console.log(currentModels.value)
let secondaryNavigation=[];

const getItemVisibility=(route)=>{
    const rootRoute=route.substring(0, route.indexOf('.'));
    const rootCurrentRoute=props.currentRoute.substring(0, props.currentRoute.indexOf('.'));
    return rootRoute===rootCurrentRoute;
}

const getSectionRouteParameters=(module,fallbackModel)=>{
    let  moduleCode=module.code;
    if (moduleCode === 'inventory')
        moduleCode = 'warehouse';
    else if (moduleCode === 'shops')
        moduleCode = 'shop';

    if (['shop', 'website', 'warehouse', 'workshop'].includes(moduleCode)) {
        return currentModels[moduleCode] ?? fallbackModel ?? 1;
    }

    return {};
}

</script>
