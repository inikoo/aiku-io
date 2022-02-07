<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 06 Feb 2022 23:18:34 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template layout="app">
    <PageHeader :headerData="headerData"/>
    <div class="px-4 sm:px-6 md:px-0">
        <div class="pb-6">
            <template v-for="(sectionData,sectionIdx ) in formData['blueprint']" :key="sectionIdx">
                <FormSection :sectionData="sectionData"  :args="formData['args']"/>
            </template>
        </div>
    </div>
</template>

<script setup>
import FormSection from '../components/forms/form-section.vue';
import PageHeader from '../components/navigation/top/page-header.vue';
const props = defineProps(['headerData', 'formData']);
</script>


