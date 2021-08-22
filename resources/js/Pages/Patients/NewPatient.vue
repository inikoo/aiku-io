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
                    <template #trigger>
                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                            <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name"/>
                        </button>

                        <span v-else class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                 {{ __('Adult') }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </span>
                    </template>

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
                            <Multiselect autocomplete="password" id="country" name="country"   class="max-w-lg block focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                         searchable
                                         v-model="value"
                                         :options="options"

                                         :classes="{
  container: 'relative max-w-lg  sm:text-sm w-full flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
  containerDisabled: 'cursor-default bg-gray-100',
  containerOpen: 'rounded-b-none',
  containerOpenTop: 'rounded-t-none',
  containerActive: 'ring ring-green-500 ring-opacity-30',
  singleLabel: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5',
  multipleLabel: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5',
  search: 'w-full absolute inset-0 outline-none appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5',
  tags: 'flex-grow flex-shrink flex flex-wrap items-center mt-1 pl-2',
  tag: 'bg-green-500 text-white text-sm font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap',
  tagDisabled: 'pr-2 !bg-gray-400 text-white',
  tagRemove: 'flex items-center justify-center p-1 mx-0.5 rounded-sm hover:bg-black hover:bg-opacity-10 group',
  tagRemoveIcon: 'bg-multiselect-remove bg-center bg-no-repeat opacity-30 inline-block w-3 h-3 group-hover:opacity-60',
  tagsSearchWrapper: 'inline-block relative mx-1 mb-1 flex-grow flex-shrink h-full',
  tagsSearch: 'absolute inset-0 border-0 outline-none appearance-none p-0 text-base font-sans box-border w-full',
  tagsSearchCopy: 'invisible whitespace-pre-wrap inline-block h-px',
  placeholder: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400',
  caret: 'bg-multiselect-caret bg-center bg-no-repeat w-2.5 h-4 py-px box-content mr-3.5 relative z-10 opacity-40 flex-shrink-0 flex-grow-0 transition-transform transform pointer-events-none',
  caretOpen: 'rotate-180 pointer-events-auto',
  clear: 'pr-3.5 relative z-10 opacity-40 transition duration-300 flex-shrink-0 flex-grow-0 flex hover:opacity-80',
  clearIcon: 'bg-multiselect-remove bg-center bg-no-repeat w-2.5 h-4 py-px box-content inline-block',
  spinner: 'bg-multiselect-spinner bg-center bg-no-repeat w-4 h-4 z-10 mr-3.5 animate-spin flex-shrink-0 flex-grow-0',
  dropdown: 'absolute -left-px -right-px bottom-0 transform translate-y-full border border-gray-300 -mt-px overflow-y-scroll z-50 bg-white flex flex-col rounded-b',
  dropdownTop: '-translate-y-full top-px bottom-auto flex-col-reverse rounded-b-none rounded-t',
  dropdownHidden: 'hidden',
  options: 'flex flex-col p-0 m-0 list-none',
  optionsTop: 'flex-col-reverse',
  option: 'flex items-center justify-start box-border text-left cursor-pointer text-base leading-snug py-2 px-3',
  optionPointed: 'text-gray-800 bg-gray-100',
  optionSelected: 'text-white bg-green-500',
  optionDisabled: 'text-gray-300 cursor-not-allowed',
  optionSelectedPointed: 'text-white bg-green-500 opacity-90',
  optionSelectedDisabled: 'text-green-100 bg-green-500 bg-opacity-50 cursor-not-allowed',
  noOptions: 'py-2 px-3 text-gray-600 bg-white',
  noResults: 'py-2 px-3 text-gray-600 bg-white',
  fakeInput: 'bg-transparent absolute left-0 right-0 -bottom-px w-full h-px border-0 p-0 appearance-none outline-none text-transparent',
  spacer: 'h-9 py-px box-content',
}"
                            />
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
import Multiselect from '@vueform/multiselect'

export default {
    components: {
        SimpleHeader, JetDropdown, JetDropdownLink, JetNavLink, JetResponsiveNavLink,Multiselect
    },

    props  : ['title', 'buttons', 'breadcrumbs', 'actions', 'actionIcons'],
    methods: {
        __: __,
    },

    data() {
        return {
            value: 'MY',
            options: require('../../data/countries')
        }
    }


};
</script>


