<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 06 Feb 2022 23:18:46 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template layout="app">
    <PageHeader :headerData="headerData"/>
    <template v-for="(blockData,block) in blocks" >
        <two-column-card v-if="block==='two-column-card'"  :data="blockData"/>
    </template>
</template>

<script setup>
import PageHeader from '../components/navigation/top/page-header.vue';
import TwoColumnCard from '../components/data-display/two-column-card/card.vue';
const props = defineProps(['headerData','blocks','model','breadcrumbs']);
console.log(props.blocks)

</script>


