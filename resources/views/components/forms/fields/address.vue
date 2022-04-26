<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 24 Apr 2022 13:54:43 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <div class="grid grid-cols-2 gap-3">
        <div class="col-span-2 sm:col-span-1">
            <Select :options="countries" v-model="addressValues['country_id']"/>
        </div>
        <template v-for="(addressFieldData,addressField) in addressFields(addressValues['country_id'])" :key="addressField">
            <div class="flex col-span-2">
                <div class="w-full">
                    <div v-if="addressField==='administrative_area'" >
                        <label for="administrative_area" class="capitalize block text-sm font-medium text-gray-700">{{ addressFieldData.label }}</label>
                        <Select v-if="administrativeAreas(addressValues['country_id']).length && (!addressValues['administrative_area'] || inAdministrativeAreas(addressValues['administrative_area'],addressValues['country_id']))"
                                :options="administrativeAreas(addressValues['country_id'])" :label="'name'" :value-prop="'name'"
                                v-model="addressValues['administrative_area']"/>
                        <input v-else v-model="addressValues['administrative_area']" type="text" name="administrative_area" id="administrative_area" autocomplete="password"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                    </div>
                    <div v-else >
                        <label :for="addressField" class="capitalize block text-xs font-medium text-gray-700">{{ addressFieldData.label }}
                            <span v-if="form.errors[addressField]" class="mt-2 text-sm text-red-600">{{form.errors[addressField] }}</span>
                        </label>
                        <input  @input="handleChange()"  v-model="addressValues[addressField]" type="text" name="address_line_2" :id="addressField" autocomplete="password"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                    </div>
                </div>
                <div class="w-5 self-end">
                    <ExclamationCircleIcon v-if="form.errors[addressField]" class="mb-2 ml-2  h-5 w-5 text-red-500 align-bottom" aria-hidden="true"/>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>

import Select from '../select.vue';
import {ExclamationCircleIcon} from '@heroicons/vue/solid';

//const props = defineProps(['fieldData', 'field', 'form']);
const props = defineProps(['form', 'fieldName','options']);

let addressValues=props.form[props['fieldName']];




const countries = {};
for (const item in props.options.countriesAddressData) {
    countries[item] = props.options.countriesAddressData[item]['label'];
}

const administrativeAreas = (country_id) => props.options.countriesAddressData[country_id]['administrativeAreas'];

const inAdministrativeAreas = (administrativeArea, country_id) => !!props.options.countriesAddressData[country_id]['administrativeAreas'].find(c => c.name === administrativeArea);

const addressFields = (country_id) => {
    console.log(country_id)
    return props.options.countriesAddressData[country_id]['fields'];
}

const handleChange = () => props.form.clearErrors();


</script>
