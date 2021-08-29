<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 29 Aug 2021 01:46:29 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ cardData['title'] }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                {{ cardData['subTitle'] }}
            </p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">

                <template v-for="row in cardData['blueprint']">
                    <template v-if="row.type==='two_columns'">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ row.data[0]['title'] }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ row.data[0]['value'] }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ row.data[1]['title'] }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ row.data[1]['value'] }}
                            </dd>
                        </div>
                    </template>
                    <div v-else-if="row.type==='one_column'" class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ row.data['title'] }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ row.data['value'] }}
                        </dd>
                    </div>
                    <div v-else-if="row.type==='attachments'" class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ row.data['title'] }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                <li v-for="attachment in row['attachments']" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                    <div class="w-0 flex-1 flex items-center">
                                        <PaperClipIcon class="flex-shrink-0 h-5 w-5 text-gray-400" aria-hidden="true"/>
                                        <span class="ml-2 flex-1 w-0 truncate">
                                            {{ attachment['filename'] }}
                                        </span>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="'attachment.url'" class="font-medium text-indigo-600 hover:text-indigo-500">
                                            {{ row.data['download'] }}
                                        </a>
                                    </div>
                                </li>

                            </ul>
                        </dd>
                    </div>
                    <div v-else-if="row.type==='contacts'" class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ row['title'] }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200 p-2">
                                <li v-for="contact in row['contacts']" class="grid grid-cols-1 gap-x-4 gap-y-2s sm:grid-cols-2 text-sm">
                                    <div class="sm:col-span-1">

                                        <div class=" ">
                                            {{ contact['name'] }}
                                        </div>
                                        <div class="text-gray-400 ">
                                            {{ contact['pivot']['relation'] }}
                                        </div>

                                    </div>
                                    <div class="sm:col-span-1">

                                        <div  v-show="contact['email']" class=" flex mb-1">
                                            <AtSymbolIcon class=" h-5 w-3 text-gray-400 mr-2" aria-hidden="true"/>
                                            <div><a :href="`mailto:${contact.email}`">{{ contact['email'] }}</a></div>
                                        </div>
                                        <div v-show="contact['phone']" class="flex mb-1">
                                            <PhoneIcon class=" h-5 w-3 text-gray-400 mr-2" aria-hidden="true"/>
                                            <span><a :href="`tel:${contact.phone}`">{{ contact['phone'] }}</a></span>
                                        </div>
                                        <div v-show="contact['formatted_address']" class="flex ">
                                            <LocationMarkerIcon class=" h-5 w-3 text-gray-400 mr-2" aria-hidden="true"/>
                                            <span style="white-space: pre;">{{ contact['formatted_address'] }}</span>
                                        </div>

                                    </div>
                                </li>

                            </ul>
                        </dd>
                    </div>
                </template>


            </dl>
        </div>
    </div>


</template>

<script>

import {PaperClipIcon, AtSymbolIcon, PhoneIcon, LocationMarkerIcon} from '@heroicons/vue/outline';

export default {
    components: {
        PaperClipIcon, AtSymbolIcon, PhoneIcon, LocationMarkerIcon,
    },

    props: ['cardData'],

};
</script>


