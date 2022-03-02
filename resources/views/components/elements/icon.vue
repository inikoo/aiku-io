<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 02 Mar 2022 00:52:33 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <font-awesome-icon
        :icon="data['icon']"
        :class="[data['class'],getTypeClass(data['type'])]"
        aria-hidden="true"
    />
</template>

<script setup>
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';

const props = defineProps(['data']);

const getTypeClass = (type) => {

    const typeClasses = {
        'page-header':'text-gray-400 flex-shrink-0 mr-1.5 h-5 w-5'
    }
    return typeClasses[type]??''

}

</script>
