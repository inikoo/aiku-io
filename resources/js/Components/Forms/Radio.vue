<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Mon, 30 Aug 2021 00:38:39 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0

  -->

<template>
    <RadioGroup v-model="selected" class="w-4/5">
        <RadioGroupLabel class="sr-only">
            {{ fieldData['label'] }}
        </RadioGroupLabel>
        <div class="bg-white rounded-md -space-y-px">
            <RadioGroupOption as="template" v-for="(setting, settingIdx) in settings" :key="setting.name" :value="setting.value" v-slot="{ checked, active }">
                <div
                    :class="[settingIdx === 0 ? 'rounded-tl-md rounded-tr-md' : '', settingIdx === settings.length - 1 ? 'rounded-bl-md rounded-br-md' : '', checked ? 'bg-indigo-50 border-indigo-200 z-2' : 'border-gray-200', 'relative border p-4 flex cursor-pointer focus:outline-none']">
                    <span
                        :class="[checked ? 'bg-indigo-600 border-transparent' : 'bg-white border-gray-300', active ? 'ring-2 ring-offset-2 ring-indigo-500' : '', 'h-4 w-4 mt-0.5 cursor-pointer rounded-full border flex items-center justify-center']"
                        aria-hidden="true">
                        <span class="rounded-full bg-white w-1.5 h-1.5"/>
                    </span>
                    <div class="ml-3 flex flex-col">
                        <RadioGroupLabel as="span" :class="[checked ? 'text-indigo-900' : 'text-gray-900', 'block text-sm font-medium']">
                            {{ setting.name }}
                        </RadioGroupLabel>

                        <RadioGroupDescription v-show="setting.description" as="span" :class="[checked ? 'text-indigo-700' : 'text-gray-500', 'block text-sm']">
                            {{ setting.description }}
                        </RadioGroupDescription>
                    </div>
                    <div v-if="setting['isOther']" class="ml-3 flex flex-col">
                        <input v-show="  checked " type="text"
                               v-model="form[fieldData['hasOther']['name']]"
                               class="focus:ring-indigo-500 relative focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"/>
                        <div class="absolute inset-y-0 right-0 top-7 pr-3 flex items-center pointer-events-none">
                            <ExclamationCircleIcon v-if="form.errors[fieldData['hasOther']['name']]" class="h-5 w-5 text-red-500" aria-hidden="true"/>
                        </div>
                        <p v-if="form.errors[fieldData['hasOther']['name']]" class="mt-2 text-sm text-red-600">{{ form.errors[fieldData['hasOther']['name']] }}</p>


                    </div>
                </div>
            </RadioGroupOption>
        </div>
    </RadioGroup>
</template>


<script>
import {RadioGroup, RadioGroupDescription, RadioGroupLabel, RadioGroupOption} from '@headlessui/vue';
import {ExclamationCircleIcon} from '@heroicons/vue/outline';

const settings = [
    {value: 'Male', name: 'Male'},
    {value: 'Female', name: 'Female'},
];

export default {
    props     : ['fieldData', 'form'],
    components: {
        RadioGroup,
        RadioGroupDescription,
        RadioGroupLabel,
        RadioGroupOption, ExclamationCircleIcon,
    },
    setup(props) {
        const selected = props.fieldData.value;

        const settings = props.fieldData.options;

        return {
            settings,
            selected,
        };
    },
};
</script>
