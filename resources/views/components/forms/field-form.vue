<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Tue, 24 Aug 2021 19:42:23 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
   class="bg-white border px-2 py-1 rounded   "
  -->

<template>
    <form @submit.prevent="form.post(postURL)">
        <dl class="divide-y divide-gray-200  ">
            <div class="pb-4 sm:pb-5 sm:grid sm:grid-cols-3 sm:gap-4 ">
                <dt class="text-sm font-medium text-gray-500">
                    {{ fieldData.label }}
                </dt>

                <dd class="sm:col-span-2  ">
                    <div class="mt-1 flex text-sm text-gray-900 sm:mt-0">
                        <div class=" relative  flex-grow">

                            <Select v-if="fieldData.type === 'select'" :options="fieldData['options']" v-model="form[field]"/>
                            <Radio v-else-if="fieldData.type === 'radio'" :fieldData="fieldData" v-model="form[field]" :form="form"/>
                            <DatePicker v-else-if="fieldData.type === 'date'" v-model="form[field]">
                                <template v-slot="{ inputValue, inputEvents }">
                                    <input type="text"
                                           class="focus:ring-indigo-500 focus:border-indigo-500 block  sm:text-sm border-gray-300 rounded-md   "
                                           :value="inputValue"
                                           v-on="inputEvents"

                                    />
                                </template>
                            </DatePicker>

                            <Phone v-else-if="fieldData.type === 'phone'" v-model="form[field]"></Phone>

                            <Address v-else-if="fieldData.type === 'address'" :fieldData="fieldData" :form="form" :countriesAddressData="args['countriesAddressData']"/>
                            <ToggleWithIcon v-else-if="fieldData.type === 'toggleWithIcon'" :initial-value="form[field]" v-model="form[field]"></ToggleWithIcon>

                            <input v-else @input="handleChange(form)" v-model="form[field]" type="text" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"/>


                        </div>
                        <span class="ml-4 flex-shrink-0 w-5 ">
                               <ExclamationCircleIcon v-if="form.errors[field]" class="mt-1.5  h-5 w-5 text-red-500" aria-hidden="true"/>
                                <CheckCircleIcon v-if="form.recentlySuccessful" class="mt-1.5  h-5 w-5 text-green-500" aria-hidden="true"/>
                        </span>
                        <span class="ml-2 flex-shrink-0">
                            <button :title="locale.__('Update')" :disabled="form.processing  || !form.isDirty " type="submit">
                                <SaveIcon class="h-7 w-7 " :class="form.isDirty ? 'text-indigo-500' : 'text-gray-200'" aria-hidden="true"/>
                            </button>
                        </span>
                    </div>
                    <p v-if="form.errors[field]" class="mt-2 text-sm text-red-600">{{ form.errors[field] }}</p>
                </dd>
            </div>
        </dl>
    </form>
</template>

<script setup>

import {defineAsyncComponent} from 'vue';

import {useForm} from '@inertiajs/inertia-vue3';
import {SaveIcon} from '@heroicons/vue/solid';
import Select from './select.vue';
import {DatePicker} from 'v-calendar';
import Radio from './radio.vue';
import Address from './fields/address.vue';
import Phone from './fields/phone.vue';
import ToggleWithIcon from './toggle-with-icon.vue';
import {useLocaleStore} from '../../../scripts/stores/locale.js';

const props = defineProps(['fieldData', 'field', 'args']);

const locale = useLocaleStore();

const Date = defineAsyncComponent(() => import('@c/forms/fields/date.vue'));

const JobPositions = defineAsyncComponent(() => import('@c/forms/fields/job-positions.vue'));

const getComponent = (componentName) => {

    const components = {
        'input'        : Input,
        'date'         : Date,
        'phone'        : Phone,
        'job-positions': JobPositions,

    };
    return components[componentName] ?? null;

};

const postURL = props.fieldData['postURL'] ?? props.args['postURL'];
let formFields = {};

if (props.fieldData['type'] === 'address') {

    formFields['country_id'] = props.fieldData.value.country_id;
    formFields['administrative_area'] = props.fieldData.value.administrative_area;
    formFields['dependant_locality'] = props.fieldData.value.dependant_locality;
    formFields['locality'] = props.fieldData.value.locality;
    formFields['postal_code'] = props.fieldData.value.postal_code;
    formFields['sorting_code'] = props.fieldData.value.sorting_code;
    formFields['address_line_2'] = props.fieldData.value.address_line_2;
    formFields['address_line_1'] = props.fieldData.value.address_line_1;

} else {
    formFields = {
        [props.field]: props.fieldData.value,
    };

    if (props.fieldData['hasOther']) {
        formFields[props.fieldData['hasOther']['name']] = props.fieldData['hasOther']['value'];
    }

}

const form = useForm(formFields);

</script>
