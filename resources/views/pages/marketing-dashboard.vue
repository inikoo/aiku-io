
<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sat, 05 Mar 2022 22:28:30 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template layout="app">
    <PageHeader :headerData="headerData"/>
    <div>
        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div v-for="item in stats.models" :key="item.name" class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    <Link :class="['special-underline']" :href="route(item.href.route,item.href.parameters)">{{ item.name }}</Link>
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ item.stat }}
                </dd>
            </div>
        </dl>
    </div>
</template>

<script setup>
import {Link} from '@inertiajs/inertia-vue3';
import PageHeader from '../components/navigation/top/page-header.vue';
const props = defineProps(['headerData','stats']);
/*
const stats = [
    { name: 'Total Subscribers', stat: '71,897' },
    { name: 'Avg. Open Rate', stat: '58.16%' },
    { name: 'Avg. Click Rate', stat: '24.57%' },
]
*/
</script>


