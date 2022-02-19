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
                                :icon="['fal', 'home']"
                                class="flex-shrink-0 h-4 w-4"
                                aria-hidden="true"
                            />
                            <span class="sr-only">{{ locale.__('dashboard') }}</span>
                        </Link>
                    </div>
                </li>
                <li v-for="(breadcrumb, breadcrumbIdx) in  breadcrumbs" :key="breadcrumbIdx" class="flex">
                    <div class="flex items-center">
                        <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-400 mr-2" aria-hidden="true"/>

                        <Link class="mr-2" v-if="breadcrumb.index" :href="route(breadcrumb.index.route,breadcrumb.index.routeParameters)">
                            <font-awesome-icon

                                :icon="breadcrumb.index.icon??['fal', 'bars']"
                                class="flex-shrink-0 h-4 w-4"
                                aria-hidden="true"
                                :title="breadcrumb.index.overlay"
                            />
                        </Link>

                        <span v-if="breadcrumb.modelLabel" class="mr-1 text-sm "><span class="font-light">{{breadcrumb.modelLabel.label}}</span>
                            <span v-if="breadcrumb.name" class="font-semibold">→</span>
                            <span v-else class="font-thin ml-1.5 	">≡</span>
                        </span>

                        <Link :href="route(breadcrumb.route,breadcrumb['routeParameters'])"
                              class="  text-gray-500 hover:text-gray-700"
                              :aria-current="(breadcrumbIdx !== Object.keys(breadcrumbs).length - 1) ? 'page' : undefined">


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
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {useLocaleStore} from '../../../../scripts/stores/locale.js';

const props = defineProps(['breadcrumbs']);
const locale = useLocaleStore();

let displayBreadcrumbs = Object.keys(props.breadcrumbs).length > 0;


</script>
