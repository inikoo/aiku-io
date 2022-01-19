<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Tue, 18 Jan 2022 00:01:34 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
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
import FormSection from '@/Components/Forms/FormSection';
import PageHeader from '@/Layouts/PageHeader';
const props = defineProps(['headerData', 'formData']);


</script>


