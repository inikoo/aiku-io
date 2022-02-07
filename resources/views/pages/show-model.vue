<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 06 Feb 2022 23:18:46 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template layout="app">
    <PageHeader :headerData="headerData"/>
</template>

<script setup>
import PageHeader from '../components/navigation/top/page-header.vue';
const props = defineProps(['headerData']);
</script>


