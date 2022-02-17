<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 17 Feb 2022 02:42:24 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div v-show="data.title" class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ data.title ?? '' }}</h3>
            <p v-show="data.subTitle" class="mt-1 max-w-2xl text-sm text-gray-500">{{ data.subTitle ?? '' }}</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">


                <div v-for="(component,componentId) in data.components" :key="componentId"   :class="[ component.span===1?'sm:col-span-1':'sm:col-span-2']" >
                    <item v-if="component.type==='item'" :item="component"  />

                </div>


            </dl>
        </div>
    </div>
</template>

<script setup>
import Item from './item.vue';
import Attachments from './attachments.vue';

const props = defineProps(['data']);

console.log(props.data);

</script>
