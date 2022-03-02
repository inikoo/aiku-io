<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 02 Mar 2022 23:32:51 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
      <span v-bind:title="data['title']" :class="[data['class']]">
        {{ getFormattedValue(data.slot) }}
    </span>

</template>

<script setup>

import {useLocaleStore} from '../../../scripts/stores/locale';

const locale = useLocaleStore();

const props = defineProps(['data']);

const getFormattedValue = (value) => {
    if (Number.isFinite(value)) {
        return new Intl.NumberFormat(locale['language']).format(value);
    }
    return value;

};

</script>
