<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 13 Feb 2022 02:24:19 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <div v-if="module.code==='marketing' && layout.currentModels.shop && navData['metaSection']==='shop'" class="ml-4 pt-1 mb-2 ">
        <font-awesome-icon class="ml-1 mr-2" :icon="['fal','store-alt']"></font-awesome-icon>
        <span class="text-sm text-gray-600 font-light tracking-tighter"> {{getShopLabel(module)}} </span>
    </div>
</template>

<script setup>
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {useLayoutStore} from '../../../../scripts/stores/layout';

const props = defineProps(['module','navData']);
const layout = useLayoutStore();
const getShopLabel = (module) => {

    return module.visibleModels[layout.currentModels.shop]['code']
}
</script>
