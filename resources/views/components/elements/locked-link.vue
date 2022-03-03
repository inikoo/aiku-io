<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 03 Mar 2022 18:03:50 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <span v-bind:title="data.title"  :class="[data.class]">


        <component
            v-if="data.slot && typeof data.slot === 'object'"
            :is="getComponent(data.slot.type)"
            :data="data.slot.data">
        </component>
        <span v-else>{{ data.slot }}</span>

         <font-awesome-icon
             class="ml-1"
             fixed-width
             :icon="['fal', 'lock-alt']"
             aria-hidden="true"
         />
    </span>

</template>

<script setup>

import Text from './text.vue';
import Number from './number.vue';
import ComponentGroup from './component-group.vue';

import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {library} from '@fortawesome/fontawesome-svg-core';
import {faLockAlt} from '@/private/pro-light-svg-icons';
library.add(faLockAlt);

const props = defineProps(['data']);

const getComponent = (componentName) => {

    const components = {
        'text'  : Text,
        'number': Number,
        'group' : ComponentGroup,
    };
    return components[componentName] ?? null;
};

</script>
