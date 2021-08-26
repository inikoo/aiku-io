<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sat, 21 Aug 2021 21:59:21 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>


    <simple-header :breadcrumbs="breadcrumbs" :module="'patients'" :actions="actions" :actionIcons="actionIcons">
        {{ title }}
        <template v-slot:action_options>
            <div class="ml-3 relative">
                <jet-dropdown align="right" width="48">

                    <template #content>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Type of patient') }}
                        </div>

                        <jet-dropdown-link :href="route('profile.show')">
                            {{ __('Adult') }}
                        </jet-dropdown-link>
                        <jet-dropdown-link :href="route('profile.show')">
                            {{ __('Minor') }}
                        </jet-dropdown-link>


                    </template>
                </jet-dropdown>
            </div>
        </template>

    </simple-header>


    <form class="space-y-8 divide-y divide-gray-200" autocomplete="off">
        <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">


            <div class="pt-8 space-y-6 sm:pt-10 sm:space-y-5">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Personal Information
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Use a permanent address where you can receive mail.
                    </p>
                </div>
                <div class="space-y-6 sm:space-y-5">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="first-name" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            First name
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <input type="text" name="first-name" id="first-name" autocomplete="password"
                                   class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md"/>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="last-name" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            Last name
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <input type="text" name="last-name" id="last-name" autocomplete="password"
                                   class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md"/>
                        </div>
                    </div>


                </div>
            </div>


        </div>
        <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">


            <div class="pt-8 space-y-6 sm:pt-10 sm:space-y-5">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('Contact Information') }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Use a permanent address where you can receive mail.
                    </p>
                </div>
                <div class="space-y-6 sm:space-y-5">


                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="email" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            Email address
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <input autocomplete="password" id="email" name="email" type="email" class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"/>
                        </div>
                    </div>


                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="country" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            Country / Region
                        </label>

                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <Select :id="'country'" :name="'country'" :options="options" :value="value"   />
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="street-address" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            Street address
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <input type="text" name="street-address" id="street-address" autocomplete="password"
                                   class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"/>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="city" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            City
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <input type="text" name="city" id="city" autocomplete="password"
                                   class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm   border-gray-300 rounded-md"/>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="state" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            State / Province
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <input autocomplete="password" type="text" name="state" id="state" class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md"/>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="zip" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            ZIP / Postal
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <input type="text" name="zip" id="zip" autocomplete="password"
                                   class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md"/>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="pt-5">
            <div class="flex justify-end">
                <button type="button"
                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </button>
                <button type="submit"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save
                </button>
            </div>
        </div>
    </form>





</template>

<script>
import SimpleHeader from '@/Layouts/PageHeadings/SimpleHeader';
import JetDropdown from '@/Jetstream/Dropdown';
import JetDropdownLink from '@/Jetstream/DropdownLink';
import JetNavLink from '@/Jetstream/NavLink';
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink';
import {__} from 'matice';
import Select from '@/Components/Forms/Select';

export default {
    components: {
        Select,
        SimpleHeader, JetDropdown, JetDropdownLink, JetNavLink, JetResponsiveNavLink
    },

    props  : ['title', 'buttons', 'breadcrumbs', 'actions', 'actionIcons'],
    methods: {
        __: __,
    },




};
</script>


