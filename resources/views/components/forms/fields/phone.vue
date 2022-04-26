
<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 24 Apr 2022 13:45:29 Malaysia Time, Cala de Mijas, Spain
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <div class="mt-1 relative rounded-md shadow-sm">
        <Phone  v-model="form[fieldName]" :defaultCountry="defaultCountry" ></Phone>

        <div v-if="form.errors[fieldName] || form.recentlySuccessful " class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <ExclamationCircleIcon v-if="form.errors[fieldName]" class="h-5 w-5 text-red-500" aria-hidden="true" />
            <CheckCircleIcon v-if="form.recentlySuccessful" class="mt-1.5  h-5 w-5 text-green-500" aria-hidden="true"/>

        </div>
    </div>
    <p v-if="form.errors[fieldName]" class="mt-2 text-sm text-red-600" id="email-error">{{ form.errors[fieldName] }}</p>

</template>


<script setup>
import Phone from '@c/forms/fields/primitives/phone.vue';

import { ExclamationCircleIcon,CheckCircleIcon } from '@heroicons/vue/solid'

const props = defineProps(['form', 'fieldName','options']);



let defaultCountry=null
if(props.options!==undefined && props.options.defaultCountry ){
    defaultCountry=props.options.defaultCountry;
}



</script>
