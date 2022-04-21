<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 13 Apr 2022 15:32:38 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>


    <fieldset class=" mb-10" v-for="(fieldset,fieldsetIdx) in options['blueprint']" :key="fieldsetIdx">

        <div class="pb-1 border-b border-gray-300">
            <legend class="text-sm font-medium text-gray-600">{{ fieldset['title'] }}</legend>
        </div>


        <div v-if="fieldset['scopes']" class="mt-2">
            <h2>{{ fieldset['scopes']['title'] }}</h2>


            <Multiselect

                mode="tags"
                v-model="form[fieldName]['scopes'][fieldset.key]"
                :options="fieldset['scopes']['options']"
                label="name"
                valueProp="id"
                track-by="name"
                :search="true"
            >
                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                    <div class="multiselect-tag is-user">
                        <span :title="option.name">{{ option.code }}</span>
                        <span
                            v-if="!disabled"
                            class="multiselect-tag-remove"
                            @mousedown.prevent="handleTagRemove(option, $event)"
                        >
          <span class="multiselect-tag-remove-icon"></span>
        </span>
                    </div>
                </template>

            </Multiselect>
        </div>


        <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-3 sm:gap-x-4">


            <template v-for="(option,optionID) in fieldset['options']" :key="optionID">

                <div v-if="option['subOptions']"
                     :class="[ form[fieldName]['positions'][option.key]?'border-indigo-500 ring-2 ring-indigo-500':'',  'relative bg-white border rounded-lg shadow-sm p-4 flex  focus:outline-none']">


                    <div class="flex-1 flex">

                        <div class="flex flex-col">
                            <span class="block text-sm font-medium text-gray-900"> {{ option.name }} </span>
                            <span class="mt-1 flex items-center text-xs text-gray-500"> {{ option.description }} </span>

                            <div class="grid grid-cols-2 gap-3 mt-3 ">

                                <label
                                    v-for="(subOption,subOptionId) in option['subOptions']" :key="subOptionId"
                                    :class="[form[fieldName]['positions'][subOption.key]?'bg-indigo-600 border-transparent text-white hover:bg-indigo-700':'bg-white border-gray-200 text-gray-900 hover:bg-gray-50','border rounded-md py-1 px-3 flex items-center justify-center text-xs font-medium  sm:flex-1 cursor-pointer focus:outline-none']">
                                    <input type="checkbox"
                                           @change="switchSubPosition(option,subOption.key)"
                                           v-model="form[fieldName]['positions'][subOption.key]"
                                           class="sr-only" aria-labelledby="memory-option-0-label">
                                    <p>{{ subOption.name }}</p>
                                </label>
                            </div>

                        </div>
                    </div>


                    <CheckCircleIcon :class="[form[fieldName]['positions'][option.key]?'':'hidden','h-5 w-5 text-indigo-600']" aria-hidden="true" />


                </div>

                <label v-else
                       :class="[ form[fieldName]['positions'][option.key] ?'border-indigo-500 ring-2 ring-indigo-500' :'', 'relative bg-white border rounded-lg shadow-sm p-4 flex cursor-pointer focus:outline-none']">


                    <input v-model="form[fieldName]['positions'][option.key]" type="checkbox" class="sr-only" aria-labelledby="project-type-0-label"
                           aria-describedby="project-type-0-description-0 project-type-0-description-1">


                    <div class="flex-1 flex relative">

                        <div class="flex flex-col">
                            <span id="project-type-0-label" class="block text-sm font-medium text-gray-900"> {{ option.name }} </span>
                            <span id="project-type-0-description-0" class="mt-1 flex items-center text-xs text-gray-500"> {{ option.description }} </span>
                        </div>

                        <CheckCircleIcon :class="[ form[fieldName]['positions'][option.key]?'':'hidden'  , 'absolute -top-1.5 right-0   h-5 w-5 text-indigo-600']" aria-hidden="true" />
                    </div>


                </label>

            </template>


        </div>
    </fieldset>
</template>


<script setup>
import Multiselect from '@vueform/multiselect';
import {CheckCircleIcon} from '@heroicons/vue/solid';

const props = defineProps(['form', 'fieldName', 'options']);

const handleChange = (form) => {
    if (form.type === 'edit') {
        form.clearErrors();

    }

};


let value = [];

const switchSubPosition = (option, subKey) => {

    if (props.form[props['fieldName']]['positions'][subKey]) {
        props.form[props['fieldName']]['positions'][option.key] = true;

        Object.keys(option['subOptions']).forEach(function(key) {
            if (option['subOptions'][key]['key'] !== subKey) {
                props.form[props['fieldName']]['positions'][option['subOptions'][key]['key']] = false;

            }
        });
    } else {
        props.form[props['fieldName']]['positions'][option.key] = false;

    }

};


</script>
<style src="@vueform/multiselect/themes/default.css"></style>
