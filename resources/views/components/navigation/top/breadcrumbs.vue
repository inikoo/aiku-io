<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 19 Aug 2021 18:54:53 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <div v-if="displayBreadcrumbs">
        <nav class="hidden sm:flex  bg-white border-b h-8 border-gray-200 " aria-label="Breadcrumb">
            <ol role="list" class=" w-full mx-auto px-4 flex space-x-4 sm:px-6 lg:px-8">
                <li class="flex">
                    <div class="flex items-center">
                        <Link :href="route('dashboard.index')" class="text-gray-400 hover:text-gray-500">
                            <font-awesome-icon
                                :icon="['fal', 'tachometer-alt-fast']"
                                class="flex-shrink-0 h-4 w-4"
                                aria-hidden="true"
                            />
                            <span class="sr-only">{{ translations['dashboard'] }}</span>
                        </Link>
                    </div>
                </li>
                <li v-for="(breadcrumb, breadcrumbIdx) in  breadcrumbs" :key="breadcrumbIdx" class="flex">
                    <div class="flex items-center">
                        <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-400" aria-hidden="true"/>
                        <Link :href="route(breadcrumb.route,breadcrumb.routeParameters)"
                              class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                              :aria-current="(breadcrumbIdx !== Object.keys(breadcrumbs).length - 1) ? 'page' : undefined">
                <span v-if="breadcrumb.model">
                            <font-awesome-icon
                                v-if="breadcrumb.model.icon"
                                :icon="breadcrumb.model.icon"
                                class="flex-shrink-0 h-4 w-4 mr-1"
                                :title="breadcrumb.model.label"
                                aria-hidden="true"
                            />
                            <span v-else>{{ breadcrumb.model.label }}:</span>
                        </span>
                            {{ breadcrumb.name }}
                        </Link>
                        <span
                            class="ml-1 text-sm font-medium text-gray-400"
                            v-if="breadcrumb.suffix"
                        >{{ breadcrumb.suffix }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</template>

<script setup>
import {ChevronRightIcon} from '@heroicons/vue/solid';
import {Link} from '@inertiajs/inertia-vue3';
import {inject} from 'vue';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';

const props = defineProps(['breadcrumbs']);
const translations = inject('translations');

let displayBreadcrumbs = Object.keys(props.breadcrumbs).length > 0;


</script>
