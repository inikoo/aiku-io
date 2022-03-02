<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Tue, 01 Mar 2022 23:43:57 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
      <span v-bind:title="data['title']" :class="[data['class']]">
        {{ data.slot }}
    </span>

</template>

<script setup>

const props = defineProps(['data']);

</script>
