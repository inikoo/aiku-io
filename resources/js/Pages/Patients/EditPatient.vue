<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Fri, 27 Aug 2021 06:34:49 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <page-header :headerData="headerData"/>
    <div class="px-4 sm:px-6 md:px-0">
        <div class="pb-6">
            <template v-for="(sectionData ) in formData['blueprint']">
                <form-section  :sectionData="sectionData" :postURL="formData['postURL']"/>
            </template>

        </div>
    </div>


</template>

<script>
import FormSection from '@/Components/Forms/FormSection';
import PageHeader from '@/Layouts/PageHeader';

export default {
    components: {

        PageHeader, FormSection,
    },

    props  : ['headerData', 'formData'],


};
</script>


