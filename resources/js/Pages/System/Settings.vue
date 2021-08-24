<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Mon, 23 Aug 2021 21:59:04 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <simple-header :breadcrumbs="breadcrumbs" :module="'system'" :actions="actions" :actionIcons="actionIcons">{{ title }}</simple-header>
    <div class="px-4 sm:px-6 md:px-0">
        <div class="py-6">
            <template v-for="(sectionData,sectionName ) in formBlueprint" >
                <form-section :sectionName="sectionName" :sectionData="sectionData" :postRoute="'/system/settings'" />
            </template>

        </div>
    </div>
</template>

<script>
import SimpleHeader from '@/Layouts/PageHeadings/SimpleHeader';
import FormSection from '@/Components/Forms/FormSection';

export default {

    components: {
        SimpleHeader, FormSection
    },
    props     : ['title', 'buttons', 'breadcrumbs', 'actions', 'actionIcons', 'formBlueprint'],


};
</script>
