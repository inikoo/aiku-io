<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Mon, 23 Aug 2021 21:59:04 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <simple-header :breadcrumbs="breadcrumbs" :module="'system'" :actions="actions" :actionIcons="actionIcons">{{ title }}</simple-header>

    <form class="space-y-8 divide-y divide-gray-200" autocomplete="off" @submit.prevent="form.post('/system/settings')">

        <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">


            <div class="pt-8 space-y-6 sm:pt-10 sm:space-y-5">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ form_blueprints.locale['title'] }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        {{ form_blueprints.locale['subtitle'] }}
                    </p>

                    <span class="ml-4 flex-shrink-0">
                            <button type="button" class="bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                              Update
                            </button>
                    </span>


                </div>
                <div class="space-y-6 sm:space-y-5">


                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="country" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            {{ __('Country / Region') }}
                        </label>

                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <Select :options="countries" v-model="form.country"/>
                            <div class="mt-2 text-sm text-red-600" v-if="form.errors.country">{{ form.errors.country }}</div>
                        </div>


                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="currency" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            {{ __('Currency') }}
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <Select :options="currencies" v-model="form.currency"/>
                            <div class="mt-2 text-sm text-red-600" v-if="form.errors.currency">{{ form.errors.currency }}</div>
                        </div>

                    </div>







                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="language" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            {{ __('Language') }}
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <Select :options="languages" v-model="form.language"/>
                            <div class="mt-2 text-sm text-red-600" v-if="form.errors.language">{{ form.errors.language }}</div>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="timezone" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            {{ __('Timezone') }}
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <Select :options="timezones" v-model="form.timezone"/>
                            <div class="mt-2 text-sm text-red-600" v-if="form.errors.timezone">{{ form.errors.timezone }}</div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="pt-5">
            <div class="flex justify-end">
                <Link :href="route('system.index')" as="button" type="button"
                      class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Cancel') }}
                </Link>
                <button type="submit" :disabled="form.processing"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Save') }}
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import SimpleHeader from '@/Layouts/PageHeadings/SimpleHeader';
import {__} from 'matice';
import Select from '@/Components/Forms/Select';
import {Link} from '@inertiajs/inertia-vue3';
import {useForm} from '@inertiajs/inertia-vue3';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';

export default {

    components: {
        Select,
        SimpleHeader, Link,FontAwesomeIcon
    },
    props     : ['title', 'buttons', 'breadcrumbs', 'actions', 'actionIcons', 'form_blueprints'],
    methods   : {
        __: __,
    },
    setup() {

        const form = useForm({
                                 country : null,
                                 currency: null,
                                 language: null,
                                 timezone: null,

                             });

        let currencies = [];
        const currenciesData = require('../../../data/currencies');
        for (const currencyCode in currenciesData) {

            const currencyData = currenciesData[currencyCode];
            currencies.push(
                {
                    value: currencyCode, label: currencyCode + ' -  ' + currencyData.name + ' (' + currencyData.symbol_native + ')',
                },
            );
        }
        let languages = [];
        const languagesData = require('../../../data/languages');
        for (const key in languagesData) {

            const languageData = languagesData[key];

            languages.push(
                {
                    value: languageData.code, label: languageData.name,
                },
            );
        }

        let timezones = [];
        const timezonesData = require('../../../data/timezones');
        for (const key in timezonesData) {

            const timezoneData = timezonesData[key];

            const utcTimezoneCodes = timezoneData.utc;

            for (const key2 in utcTimezoneCodes) {
                timezones.push(
                    {
                        value: utcTimezoneCodes[key2], label: 'UTC' + (timezoneData.offset >= 0 ? '+' : '') + timezoneData.offset + ':00 ' + utcTimezoneCodes[key2] + ' [' + timezoneData.abbr + ']',
                    },
                );
            }

        }

        return {
            currencies, languages, timezones, form,
        };
    },

    data() {
        return {
            countries: require('../../../data/countries'),

        };
    },
};
</script>
