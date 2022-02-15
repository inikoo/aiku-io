<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Mon, 07 Feb 2022 19:08:50 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
   <span class="hidden xl:inline-block">
        <font-awesome-icon class="mr-1" v-if="module.code==='marketing'" :icon="layout.currentModels.shop?['fal','diamond']:['fal','bars']" ></font-awesome-icon>
       {{ module.name }}
   </span>
    <span class="xl:hidden">{{ module.shortName }}</span>
</template>

<script setup>
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {useLayoutStore} from '../../../../scripts/stores/layout';

const props = defineProps(['module']);
const layout = useLayoutStore();

</script>
