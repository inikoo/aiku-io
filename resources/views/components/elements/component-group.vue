<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 02 Mar 2022 00:09:47 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <span v-bind:title="data['title']" :class="data['class']">
    <component v-for="(component,idx) in data['components']" :key="idx"
               :is="getComponent(component['type'])"
               :data="component['data']">

    </component>
    </span>
</template>

<script setup>

import Badge from './badge.vue';
import Text from './text.vue';
import Icon from './icon.vue';
import ComponentLink from './component-link.vue';
import Number from './number.vue';
import LockedLink from './locked-link.vue';
import Flag from './flag.vue';

const props = defineProps(['data']);

const getComponent = (componentName) => {

    const components = {
        'badge': Badge,
        'text' : Text,
        'icon' : Icon,
        'number': Number,
        'link': ComponentLink,
        'locked_link': LockedLink,
        'flag': Flag
    };
    return components[componentName] ?? null;

};

</script>
