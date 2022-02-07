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
            <HomeIcon class="flex-shrink-0 h-5 w-5" aria-hidden="true" />
            <span class="sr-only">Home</span>
          </Link>
        </div>
      </li>
      <li v-for="page in breadcrumbs" :key="page.name" class="flex">
        <div class="flex items-center">
            <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-400" aria-hidden="true" />
            <Link :href="route(page.route,page.routeParameters)" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700" :aria-current="page.current ? 'page' : undefined">{{ page.name }}</Link>
        </div>
      </li>
    </ol>
  </nav>
    </div>
</template>

<script setup>
import { HomeIcon ,ChevronRightIcon} from '@heroicons/vue/solid'
import { Link } from '@inertiajs/inertia-vue3';
import {inject} from 'vue';

const props = defineProps(['breadcrumbs']);
const translations = inject('translations')

let displayBreadcrumbs = Object.keys(props.breadcrumbs).length > 0;


</script>
