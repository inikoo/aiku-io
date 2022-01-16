<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 12 Jan 2022 23:31:07 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>

    <Link
        as="button"
        :href="href"
        :class="[isCurrent ? 'text-gray-700' : 'text-gray-400 group-hover:text-gray-500', 'ml-2']">

        <font-awesome-icon
            v-if="icon"
            :icon="icon"
            :class="[isCurrent  ? 'text-gray-800' : 'text-gray-400 group-hover:text-gray-500', 'mr-1 ']"
            fixed-width
             aria-hidden="true"/> {{ label }}


    </Link>


</template>

<script>


import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {Link} from '@inertiajs/inertia-vue3';

export default {
    props     : ['icon', 'label', 'href', 'isCurrent'],
    components: {

        FontAwesomeIcon, Link,
    },

    setup() {

        return {};
    },

};
</script>
