<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 19 Jan 2022 06:04:45 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <PageHeader :headerData="headerData"/>
</template>

<script setup>
import PageHeader from '@/Layouts/PageHeader';
const props = defineProps(['headerData']);
</script>


