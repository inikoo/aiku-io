<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 19 Aug 2021 18:54:53 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <div v-if="displayBreadcrumbs">
        <nav class="hidden sm:flex text-gray-600 bg-white border-b h-8 border-gray-200 " aria-label="Breadcrumb">
            <ol role="list" class=" w-full mx-auto px-4 flex space-x-4 sm:px-6 lg:px-8">
                <li class="flex">
                    <div class="flex items-center">
                        <Link :href="route('dashboard.index')" class="hover:text-gray-700">
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
                        <ChevronRightIcon class="flex-shrink-0 h-5 w-5 mr-2" aria-hidden="true"/>

                        <Link class="mr-2 hover:text-gray-700" v-if="breadcrumb.index" :href="route(breadcrumb.index.route,breadcrumb.index['routeParameters'])">
                            <font-awesome-icon

                                :icon="breadcrumb.index.icon??['fal', 'bars']"
                                class="flex-shrink-0 h-4 w-4"
                                aria-hidden="true"
                                :title="breadcrumb.index.overlay"
                            />
                        </Link>

                        <template v-if="breadcrumb['modelLabel']">

                             <span class="mr-1 text-sm ">
                                 <span class="font-light">{{ breadcrumb['modelLabel'].label }}</span>
                                 <span v-show="breadcrumb['name']" class="font-semibold">→</span>
                             </span>


                        </template>


                        <font-awesome-icon
                            v-show="!breadcrumb['name']"
                            :icon="['fal', 'bars']"
                            class="flex-shrink-0 h-4 w-4 ml-1"
                            aria-hidden="true"

                        />


                        <Link :href="route(breadcrumb.route,breadcrumb['routeParameters'])"
                              class="hover:text-gray-700 text-sm font-light"
                              :aria-current="(breadcrumbIdx !== Object.keys(breadcrumbs).length - 1) ? 'page' : undefined">
                            {{ breadcrumb.name }}
                        </Link>


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
