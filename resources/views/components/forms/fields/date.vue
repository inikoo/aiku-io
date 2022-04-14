<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 13 Apr 2022 15:08:50 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <DatePicker v-model="form[fieldName]">
        <template v-slot="{ inputValue, inputEvents }">
            <input type="text"
                   class="focus:ring-indigo-500 focus:border-indigo-500 block  sm:text-sm border-gray-300 rounded-md   "
                   :value="inputValue"
                   v-on="inputEvents"

            />
            <div v-if="form.errors[fieldName] || form.recentlySuccessful " class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <ExclamationCircleIcon v-if="form.errors[fieldName]" class="h-5 w-5 text-red-500" aria-hidden="true" />
                <CheckCircleIcon v-if="form.recentlySuccessful" class="mt-1.5  h-5 w-5 text-green-500" aria-hidden="true"/>

            </div>
        </template>
    </DatePicker>
    <p v-if="form.errors[fieldName]" class="mt-2 text-sm text-red-600" id="email-error">{{ form.errors[fieldName] }}</p>
</template>


<script setup>
import { ExclamationCircleIcon,CheckCircleIcon } from '@heroicons/vue/solid'
import {DatePicker} from 'v-calendar';
import 'v-calendar/dist/style.css';

const props = defineProps(['form', 'fieldName','options']);

const handleChange = (form) => {
    if(form.type==='edit'){
        form.clearErrors();

    }
}




</script>
