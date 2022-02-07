<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 25 Aug 2021 04:59:05 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <form @submit.prevent="form.post(formData['postURL'])" >
        <div class="px-4 sm:px-6 md:px-0">
            <div class="pb-6">
                <template v-for="(sectionData ) in formData['blueprint']">
                    <div class="mt-10 divide-y divide-gray-200">
                        <div class="space-y-1">
                            <h3 v-if="sectionData['conditionalTitle'] && form[sectionData['conditionalTitle']['if'][0]]=== sectionData['conditionalTitle']['if'][1] " class="text-lg leading-6 font-medium text-gray-900">

                                {{ sectionData['conditionalTitle'].title }}
                            </h3>
                            <h3 v-else class="text-lg leading-6 font-medium text-gray-900">
                                {{ sectionData.title }}
                            </h3>
                            <p v-show="sectionData['subtitle']" class="max-w-2xl text-sm text-gray-500">
                                {{ sectionData['subtitle'] }}
                            </p>
                        </div>
                        <div class="mt-6 pt-4 sm:pt-5 ">
                            <template v-for="(fieldData,field ) in sectionData.fields">
                                <field v-if="!fieldData['conditional'] || form[fieldData['conditional']['if'][0]]===fieldData['conditional']['if'][1]"  :args="formData.args"   :form="form" :field="field" :fieldData="fieldData"/>
                            </template>

                        </div>
                    </div>

                </template>
            </div>
        </div>
        <div class="pt-5">
            <div class="flex justify-end">

                <Link v-if="formData['cancelRoute']" :href="route(formData['cancelRoute'])" as="button" type="button"
                      class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ translations.cancel }}
                </Link>

                <button type="submit"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ translations.save }}
                </button>
            </div>
        </div>
    </form>
</template>

<script>

import {Link, useForm} from '@inertiajs/inertia-vue3';
import Field from './field.vue';
import { inject } from 'vue'

export default {
    components: {
        Link, Field,
    },
    props     : ['formData'],
    setup(props) {

        const translations = inject('translations')


        let fields = {};
        props.formData['blueprint'].forEach(function(section) {
            Object.entries(section.fields).forEach(entry => {

                const [key, field] = entry;


                if (field.type === 'address') {

                    fields['country_id'] = field.value['county_id'];
                    fields['administrative_area'] = '';
                    fields['dependant_locality'] = '';
                    fields['locality'] = '';
                    fields['postal_code'] = '';
                    fields['sorting_code'] = '';
                    fields['address_line_2'] = '';
                    fields['address_line_1'] = '';
                } else {
                    fields[key] = field.value;
                }

                if (field['hasOther']) {
                    fields[field['hasOther']['name']] = field['hasOther']['value'];
                }

            });

        });


        const form = useForm(fields);

        return {
            form,translations
        };
    }
};
</script>
