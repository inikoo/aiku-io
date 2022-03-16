<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 09 Mar 2022 01:43:27 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->
<template >
    <div class="sm:hidden">
        <label for="tabs" class="sr-only">Select a tab</label>
        <select id="tabs" name="tabs" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            <option v-for="tab in tabs" :key="tab.name" :selected="tab.current">{{ tab.name }}</option>
        </select>
    </div>
    <div class="hidden sm:block mb-3">
        <div class="border-b border-gray-200 flex flex-row">
            <nav v-if="tabs.left" class="-mb-px grow flex space-x-8 " aria-label="Tabs">
                <a v-for="tab in tabs.left" :key="tab.name" :href="route(tab.href.route,tab.href.parameters)"
                   :class="[tab.current ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-1 px-1 border-b-2 font-medium text-sm ']"
                   :aria-current="tab.current ? 'page' : undefined">
                    {{ tab.name }}
                </a>
            </nav>
            <nav  v-if="tabs.right" class="-mb-px grow flex space-x-8 justify-end" aria-label="Tabs">
                <a v-for="tab in tabs.right" :key="tab.name" :href="route(tab.href.route,tab.href.parameters)"
                   :class="[tab.current ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-1 px-1 border-b-2 font-medium text-sm ']"
                   :aria-current="tab.current ? 'page' : undefined">
                    {{ tab.name }}
                </a>
            </nav>
        </div>
    </div>
</template>
<script setup>

const props = defineProps({
                              tabs: {},
                          });

</script>
