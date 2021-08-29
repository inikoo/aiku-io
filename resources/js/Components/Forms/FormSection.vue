<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 25 Aug 2021 04:59:05 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <div class="mt-10 divide-y divide-gray-200">
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
                <field v-if="type === 'form'" :form="form" :field="field" :fieldData="fieldData"/>
                <field-form v-else :field="field" :fieldData="fieldData" :postURL="postURL"/>
            </template>

        </div>
    </div>

</template>

<script>

import FieldForm from '@/Components/Forms/FieldForm';
import Field from '@/Components/Forms/Field';

export default {
    components: {
        FieldForm, Field,
    },
    props     : ['sectionName', 'sectionData', 'postURL', 'type', 'form'],

};
</script>
