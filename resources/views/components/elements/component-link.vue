<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 02 Mar 2022 02:25:49 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <Link v-bind:title="data.title" :href="route(data.href.route,data.href.parameters)" :class="[data.class,'special-underline']">
        <component
            v-if="data.slot && typeof data.slot === 'object'"
            :is="getComponent(data.slot.type)"
            :data="data.slot.data">
        </component>
        <span v-else>{{ data.slot }}</span>
    </Link>

</template>

<script setup>
import {Link} from '@inertiajs/inertia-vue3';
import Text from './text.vue';
import Number from './number.vue';
import ComponentGroup from './component-group.vue';

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
