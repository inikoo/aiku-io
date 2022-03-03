<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 03 Mar 2022 18:42:11 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  absolute bottom-[3px]  inline-block align-middle
  -->

<template>
    <span class="relative mr-6">
    <country-flag size="small" class="absolute bottom-[1px]  inline-block align-middle" :country="data.code" :title="data.name"/>
    </span>

</template>

<script setup>
import CountryFlag from 'vue-country-flag-next';

const props = defineProps(['data']);

</script>
