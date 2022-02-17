<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 17 Feb 2022 16:25:17 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <div  class="sm:col-span-2">
        <dt class="text-sm font-medium text-gray-500">Attachments</dt>
        <dd class="mt-1 text-sm text-gray-900">
            <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                    <div class="w-0 flex-1 flex items-center">
                        <PaperClipIcon class="flex-shrink-0 h-5 w-5 text-gray-400" aria-hidden="true" />
                        <span class="ml-2 flex-1 w-0 truncate"> resume_back_end_developer.pdf </span>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500"> Download </a>
                    </div>
                </li>
                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                    <div class="w-0 flex-1 flex items-center">
                        <PaperClipIcon class="flex-shrink-0 h-5 w-5 text-gray-400" aria-hidden="true" />
                        <span class="ml-2 flex-1 w-0 truncate"> coverletter_back_end_developer.pdf </span>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500"> Download </a>
                    </div>
                </li>
            </ul>
        </dd>
    </div>
</template>

<script setup>
const props = defineProps(['attachments']);
import { PaperClipIcon } from '@heroicons/vue/solid'

</script>
