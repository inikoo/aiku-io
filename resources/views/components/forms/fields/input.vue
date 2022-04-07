<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Fri, 08 Apr 2022 01:15:30 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->
<template>
    <div class="mt-1 relative rounded-md shadow-sm">
    <input  v-model="form[fieldName]" type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" />
        <div v-if="form.errors[fieldName]" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <ExclamationCircleIcon class="h-5 w-5 text-red-500" aria-hidden="true" />
        </div>
    </div>
    <p v-if="form.errors[fieldName]" class="mt-2 text-sm text-red-600" id="email-error">{{ form.errors[fieldName] }}</p>

</template>


<script setup>
import { ExclamationCircleIcon } from '@heroicons/vue/solid'

const props = defineProps(['form', 'fieldName','options']);

</script>
