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


            <div class="mt-10 divide-y divide-gray-200" v-for="(sectionData,sectionIdx ) in formData['blueprint']" :key="sectionIdx">
                <div class="space-y-1">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ sectionData.title }}
                    </h3>
                    <p v-show="sectionData['subtitle']" class="max-w-2xl text-sm text-gray-500">
                        {{ sectionData['subtitle'] }}
                    </p>
                </div>
                <div class="mt-6 pt-4 sm:pt-5 ">
                    <template v-for="(fieldData,field ) in sectionData.fields">
                        <FieldForm :field="field" :fieldData="fieldData" :args="formData['args']"/>
                    </template>

                </div>
            </div>


        </div>
    </div>
</template>

<script setup>
import PageHeader from '@c/navigation/top/page-header.vue';
import FieldForm from '@c/forms/field-form.vue';

const props = defineProps(['headerData', 'formData']);
</script>


