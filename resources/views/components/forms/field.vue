
<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Fri, 27 Aug 2021 03:40:05 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>

        <dl class="divide-y divide-gray-200">
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">
                    {{ fieldData.label }}
                </dt>
                <dd class="sm:col-span-2  ">
                    <div class="mt-1 flex text-sm text-gray-900 sm:mt-0">
                        <div class=" relative  flex-grow">

                            <Select v-if="fieldData.type === 'select'" :options="fieldData['options']" v-model="form[field]"/>
                            <Radio v-else-if="fieldData.type === 'radio'" :fieldData="fieldData" v-model="form[field]" :form="form"/>
                            <DatePicker v-else-if="fieldData.type === 'date'" v-model="form[field]" >
                                <template v-slot="{ inputValue, inputEvents }">
                                    <input type="text"
                                           class="focus:ring-indigo-500 focus:border-indigo-500 block  sm:text-sm border-gray-300 rounded-md   "
                                           :value="inputValue"
                                           v-on="inputEvents"

                                    />
                                </template>
                            </DatePicker>
                            <Phone  v-else-if="fieldData.type === 'phone'" v-model="form[field]" :defaultCountry="fieldData['defaultCountryCode']"  ></Phone>

                            <Address v-else-if="fieldData.type === 'address'" :fieldData="fieldData" :form="form" :countriesAddressData="args['countriesAddressData']"/>

                            <input v-else @input="handleChange(form)" v-model="form[field]" type="text" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"/>

                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <ExclamationCircleIcon v-if="form.errors[field]" class="h-5 w-5 text-red-500" aria-hidden="true"/>
                            </div>
                        </div>

                    </div>
                    <p v-if="form.errors[field]" class="mt-2 text-sm text-red-600">{{ form.errors[field] }}</p>
                </dd>
            </div>
        </dl>

</template>

<script>
import {Link} from '@inertiajs/inertia-vue3';
import {ExclamationCircleIcon} from '@heroicons/vue/solid';
import Select from './select.vue';
import {DatePicker} from 'v-calendar';
import Radio from './radio.vue';
import Address from './fields/address.vue';
import Phone from './fields/phone.vue';

export default {

    components: {
        Link, ExclamationCircleIcon, Select,DatePicker,Radio,Address,Phone
    },
    props     : ['fieldData', 'field','form','args'],
    methods   : {
        handleChange: function(form) {
            form.clearErrors();
        },
    },
    setup(props) {


    }

};
</script>
