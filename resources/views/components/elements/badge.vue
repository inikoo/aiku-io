<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 19 Jan 2022 03:28:57 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
      <span v-bind:title="data['title']" :class="[data.class,getBadgeClass(data.type),'inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium']">
        <component
            v-if="data.slot && typeof data.slot === 'object'"
            :is="getComponent(data.slot.type)"
            :data="data.slot.data">
        </component>
        <span v-else>{{ data.slot }}</span>
    </span>

</template>

<script setup>

import Text from './text.vue';
import Number from './number.vue';
import ComponentGroup from './component-group.vue';

const props = defineProps(['data']);

const getBadgeClass = (type) => {
    switch (type) {
        case 'ok':
            return 'bg-green-100 text-green-800';
        case 'error':
            return 'bg-red-100 text-red-800';
        case 'warning':
            return 'bg-yellow-100 text-yellow-800';
        case 'in-process':
            return 'bg-purple-100 text-purple-800';
        default:
            return 'bg-gray-100 text-gray-800';

    }
};

const getComponent = (componentName) => {

    const components = {
        'text'  : Text,
        'number': Number,
        'group' : ComponentGroup,
    };
    return components[componentName] ?? null;
};

</script>
