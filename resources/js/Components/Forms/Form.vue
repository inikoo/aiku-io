<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 25 Aug 2021 04:59:05 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <form @submit.prevent="form.post(postURL)">
    <div class="px-4 sm:px-6 md:px-0">
        <div class="py-6">
            <template v-for="(sectionData,sectionName ) in formBlueprint" >
                <form-section :type="'form'" :form="form"  :sectionName="sectionName" :sectionData="sectionData" />
            </template>
        </div>
    </div>

    <div class="pt-5">
        <div class="flex justify-end">
            <Link :href="route('patients')"  as="button" type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{__('Cancel')}}
            </Link>
            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{__('Save')}}
            </button>
        </div>
    </div>
    </form>
</template>

<script>

import FormSection from '@/Components/Forms/FormSection';
import {Link, useForm} from '@inertiajs/inertia-vue3';
import {__} from 'matice';

export default {
    components: {
        FormSection,Link
    },
    props: [ 'formBlueprint', 'postURL'],
    setup() {

        let fields={};
        fields['name']='';

        const form = useForm(fields);

        return {
            form,
        };
    },
    methods: {
        __: __
    }
};
</script>
