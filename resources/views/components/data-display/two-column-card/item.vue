<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 17 Feb 2022 16:16:37 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>

            <dt v-show="item.label" class="text-sm font-medium text-gray-500">{{ item.label }}</dt>
            <dd class="mt-1 text-sm text-gray-900">
                <div v-if="item['valueType']==='list'">
                    <div v-for="listItem in item.value" :title="listItem.overlay">
                        <font-awesome-icon
                            :icon="listItem.icon??['fal','dot-circle']" :class="[listItem.icon, item.defaultMissingIconDisplay??'hidden','','text-gray-400 hover:text-gray-500 mr-2']" aria-hidden="true"/>

                        {{ listItem.text }}
                    </div>
                </div>
                <span v-else>
                 <font-awesome-icon
                     :icon="item.value.icon??['fal','dot-circle']" :class="[item.value.icon, item.defaultMissingIconDisplay??'hidden','','text-gray-400 hover:text-gray-500 mr-2']" aria-hidden="true"/>

                {{ item.value.text }}
            </span>
            </dd>


</template>

<script setup>
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';

const props = defineProps(['item']);

</script>
