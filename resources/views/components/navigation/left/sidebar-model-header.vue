<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 13 Feb 2022 02:24:19 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <template v-if="module.code==='marketing'">
        <Link v-if="layout.currentModels['shop'] && navData['metaSection']==='shop'" class="ml-4 pt-1 mb-2  "
              :href="route('marketing.shops.show',layout.currentModels['shop'])">
            <font-awesome-icon class="ml-1 mr-2" :icon="['fal','store-alt']"></font-awesome-icon>
            <span class="text-sm text-gray-600 font-light tracking-tighter"> {{ getModelLabel(module, 'shop') }} </span>
        </Link>

    </template>
    <template v-if="module.code==='inventory'">
        <Link v-if="layout.currentModels['warehouse'] && navData['metaSection']!=='warehouses'" class="hover:bg-gray-50 rounded-md hover:text-gray-900 ml-4 pt-1 mb-0 mt-2 flex"
              :href="route('inventory.warehouses.show',layout.currentModels['warehouse'])">
            <font-awesome-icon class="ml-1 mr-2" :icon="['fal','warehouse-alt']"></font-awesome-icon>
            <span class="text-sm text-gray-600 font-light tracking-tighter"> {{ getModelLabel(module, 'warehouse') }} </span>
        </Link>
        <Link
            v-else
            :href="route('inventory.warehouses.index')"
            :class="[navData['sectionRoot']==='inventory.warehouses.index' ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-xs 2xl:text-sm   font-medium rounded-md']"
        >
            <font-awesome-icon
                fixed-width
                :icon="['fal','bars']"
                :class="[navData['sectionRoot']==='inventory.warehouses.index'? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 ']"
                aria-hidden="true"
            />
           todo

        </Link>
    </template>
</template>

<script setup>
import {Link} from '@inertiajs/inertia-vue3';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {useLayoutStore} from '../../../../scripts/stores/layout';

const props = defineProps(['module', 'navData']);
const layout = useLayoutStore();
const getModelLabel = (module, model) => {

    if(module['visibleModels'][layout['currentModels'][model]]){
        return module['visibleModels'][layout['currentModels'][model]]['code'];
    }
    return '';
};
</script>
