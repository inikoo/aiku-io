<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 01 Sep 2021 16:38:53 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <div class="grid grid-cols-2 gap-3">
        <div class="col-span-2 sm:col-span-1">
            <Select :options="countries" v-model="form['country_id']"/>
        </div>
        <template v-for="(addressFieldData,addressField) in addressFields(form['country_id'])" :key="addressField">
            <div class="flex col-span-2">
                <div class="w-full">
                    <div v-if="addressField==='administrative_area'" >
                        <label for="administrative_area" class="capitalize block text-sm font-medium text-gray-700">{{ addressFieldData.label }}</label>
                        <Select v-if="administrativeAreas(form['country_id']).length && (!form['administrative_area'] || inAdministrativeAreas(form['administrative_area'],form['country_id']))"
                                :options="administrativeAreas(form['country_id'])" :label="'name'" :value-prop="'name'"
                                v-model="form['administrative_area']"/>
                        <input v-else v-model="form['administrative_area']" type="text" name="administrative_area" id="administrative_area" autocomplete="password"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                    </div>
                    <div v-else >
                        <label :for="addressField" class="capitalize block text-xs font-medium text-gray-700">{{ addressFieldData.label }}
                            <span v-if="form.errors[addressField]" class="mt-2 text-sm text-red-600">{{form.errors[addressField] }}</span>
                        </label>
                        <input  @input="handleChange()"  v-model="form[addressField]" type="text" name="address_line_2" :id="addressField" autocomplete="password"
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

import Select from './select.vue';
import {ExclamationCircleIcon} from '@heroicons/vue/solid';

const props = defineProps(['fieldData', 'field', 'postURL', 'countriesAddressData', 'form']);

const countries = {};
for (const item in props.countriesAddressData) {
    countries[item] = props.countriesAddressData[item]['label'];
}

const administrativeAreas = (country_id) => props.countriesAddressData[country_id]['administrativeAreas'];

const inAdministrativeAreas = (administrativeArea, country_id) => !!props.countriesAddressData[country_id]['administrativeAreas'].find(c => c.name === administrativeArea);

const addressFields = (country_id) => props.countriesAddressData[country_id]['fields'];

const handleChange = () => props.form.clearErrors();


</script>
