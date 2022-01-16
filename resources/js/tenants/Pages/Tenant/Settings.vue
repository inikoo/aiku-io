<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Mon, 23 Aug 2021 21:59:04 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <page-header :headerData="headerData"/>
    <div class="px-4 sm:px-6 md:px-0">
        <div class="py-6">
            <template v-for="(sectionData,sectionName ) in formData['blueprint']" >
                <form-section :sectionName="sectionName" :sectionData="sectionData" :postURL="formData['postURL']" />
            </template>

        </div>
    </div>
</template>

<script>
import PageHeader from '@/Layouts/PageHeader';
import FormSection from '@/Components/Forms/FormSection';

export default {

    components: {
        PageHeader, FormSection
    },
    props     : ['headerData', 'formData'],


};
</script>
