<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Tue, 24 Aug 2021 19:42:23 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
   class="bg-white border px-2 py-1 rounded   "
  -->

<template>
    <form @submit.prevent="form.post(postURL)" >
        <dl class="divide-y divide-gray-200  ">
            <div class="pb-4 sm:pb-5 sm:grid sm:grid-cols-3 sm:gap-4 ">
                <dt class="text-sm font-medium text-gray-500">
                    {{ fieldData.label }}
                </dt>
                <dd class="sm:col-span-2  ">
                    <div class="mt-1 flex text-sm text-gray-900 sm:mt-0">
                        <div class=" relative  flex-grow">

                            <Select v-if="fieldData.type === 'select'" :options="fieldData['options']" v-model="form[field]"/>
                            <Radio v-else-if="fieldData.type === 'radio'" :fieldData="fieldData" v-model="form[field]"/>
                            <DatePicker v-else-if="fieldData.type === 'date'" v-model="form[field]" >
                                <template v-slot="{ inputValue, inputEvents }">
                                    <input type="text"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block  sm:text-sm border-gray-300 rounded-md   "
                                        :value="inputValue"
                                        v-on="inputEvents"

                                    />
                                </template>
                            </DatePicker>
                            <input v-else @input="handleChange(form)" v-model="form[field]" type="text" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"/>

                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <ExclamationCircleIcon v-if="form.errors[field]" class="h-5 w-5 text-red-500" aria-hidden="true"/>
                                <CheckCircleIcon v-if="form.recentlySuccessful" class="h-5 w-5 text-green-500" aria-hidden="true"/>
                            </div>
                        </div>
                        <span class="ml-4 flex-shrink-0">
                            <button :title=" __('Update')" :disabled="form.processing  || !form.isDirty "   type="submit"><SaveIcon class="h-8 w-8 " :class="form.isDirty ? 'text-indigo-500' : 'text-gray-200'" aria-hidden="true"/></button>
                        </span>
                    </div>
                    <p v-if="form.errors[field]" class="mt-2 text-sm text-red-600">{{ form.errors[field] }}</p>
                </dd>
            </div>
        </dl>
    </form>
</template>

<script>
import {__} from 'matice';
import {Link} from '@inertiajs/inertia-vue3';
import {useForm} from '@inertiajs/inertia-vue3';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {ExclamationCircleIcon, CheckCircleIcon, SaveIcon} from '@heroicons/vue/solid';
import Select from '@/Components/Forms/Select';
import {DatePicker} from 'v-calendar';
import Radio from '@/Components/Forms/Radio';

export default {

    components: {
        Link, FontAwesomeIcon, ExclamationCircleIcon, CheckCircleIcon, SaveIcon, Select,DatePicker,Radio
    },
    props     : ['fieldData', 'field', 'postURL'],
    methods   : {
        __          : __,
        handleChange: function(form) {
            form.clearErrors();
        },
    },
    setup(props) {

        const form = useForm({
                                 [props.field]: props.fieldData.value,

                             });

        console.log(form)


        return {
            form,
        };
    },

};
</script>
