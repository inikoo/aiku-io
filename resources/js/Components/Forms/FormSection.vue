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
            <p v-if="sectionData['subtitle']" class="max-w-2xl text-sm text-gray-500">
                {{ sectionData['subtitle'] }}
            </p>
        </div>
        <div class="mt-6">

            <template v-for="(fieldData,field ) in sectionData.fields" >
              <edit-field  :field="field"  :fieldData="fieldData"  :postRoute="postRoute" />
            </template>

        </div>
    </div>

</template>

<script>

import EditField from '@/Components/Forms/EditField';

export default {
    components: {
        EditField
    },
    props: ['sectionName', 'sectionData', 'postRoute'],


};
</script>
