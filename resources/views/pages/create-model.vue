<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 07 Apr 2022 16:36:48 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<!--suppress ALL -->
<template layout="app">
    <PageHeader :headerData="headerData"/>

    <form class="space-y-8 pb-32" @submit.prevent="handleFormSubmit">

        <div v-for="(sectionData,sectionIdx ) in formData['blueprint']" :key="sectionIdx" class="mt-10 divide-y divide-blue-200">
            <div class="space-y-1">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ sectionData.title }}
                </h3>
                <p v-show="sectionData['subtitle']" class="max-w-2xl text-sm text-gray-500">
                    {{ sectionData['subtitle'] }}
                </p>
            </div>
            <div class="mt-6 pt-4 sm:pt-5 ">

                <div v-for="(fieldData,fieldName ) in sectionData.fields" class="mt-10 divide-y divide-red-200">
                    <dl class="divide-y divide-green-200  ">
                        <div class="pb-4 sm:pb-5 sm:grid sm:grid-cols-3 sm:gap-4 ">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ fieldData.label }}
                            </dt>

                            <dd class="sm:col-span-2  ">
                                <div class="mt-1 flex text-sm text-gray-900 sm:mt-0">
                                    <div class=" relative  flex-grow">
                                        <component
                                            :is="getComponent(fieldData['type'])"
                                            :form=form
                                            :fieldName=fieldName
                                            :options="fieldData['options']">

                                        </component>
                                    </div>
                                    <span class="ml-4 flex-shrink-0 w-5 ">

                                    </span>
                                    <span class="ml-2 flex-shrink-0">

                                     </span>
                                </div>

                            </dd>
                        </div>
                    </dl>
                </div>

            </div>
        </div>

        <div class="pt-5 border-t-2 border-indigo-500">
            <div class="flex justify-end">
                <Link :href="route(formData.cancel.route,formData.cancel.routeParameters)" type="button"
                      class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </Link>
                <button type="submit" :disabled="form.processing"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save
                </button>
            </div>
        </div>

    </form>


</template>

<script setup>
import {defineAsyncComponent} from 'vue';

import {Link, useForm} from '@inertiajs/inertia-vue3';

import PageHeader from '@c/navigation/top/page-header.vue';
import Input from '@c/forms/fields/input.vue';

const Date = defineAsyncComponent(() => import('@c/forms/fields/date.vue'));
const Toggle = defineAsyncComponent(() => import('@c/forms/fields/toggle.vue'));

const Phone = defineAsyncComponent(() => import('@c/forms/fields/phone.vue'));
const Address = defineAsyncComponent(() => import('@c/forms/fields/address.vue'));
const JobPositions = defineAsyncComponent(() => import('@c/forms/fields/job-positions.vue'));

const props = defineProps(['headerData', 'formData']);

const getComponent = (componentName) => {

    const components = {
        'input'        : Input,
        'toggle'       : Toggle,
        'phone'        : Phone,
        'date'         : Date,
        'address'      : Address,
        'job-positions': JobPositions,

    };
    return components[componentName] ?? null;

};

let fields = {};
Object.entries(props.formData.blueprint).forEach(([key, val]) => {
    Object.entries(val.fields).forEach(([fieldName, fieldData]) => {
        fields[fieldName] = fieldData['value'];
    });
});
console.log(fields);

const form = useForm(fields);

const handleFormSubmit = () => {
    form.post(props.formData.postURL);
};


</script>




