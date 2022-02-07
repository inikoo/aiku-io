<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 19 Jan 2022 03:28:57 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <span v-bind:title="data.nameTitle" :class="[getBadgeClass(data.badge),'inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium']">
        {{ data.name }}
    </span>
    <span class="text-sm ml-2">
    <span v-if="data.preSuffixHtml" v-html="data.preSuffixHtml"></span>
    <span v-bind:title="data.suffixTitle" v-if="data.suffix" > {{ data.suffix }}</span>
    <span v-if="data.postSuffixHtml" v-html="data.postSuffixHtml"></span>
    </span>
</template>

<script>

export default {
    props: ['data'],

    methods: {

        getBadgeClass(type) {
            switch (type) {
                case 'ok':
                    return 'bg-green-100 text-green-800';
                case 'error':
                    return 'bg-red-100 text-red-800';
                case 'warning':
                    return 'bg-yellow-100 text-yellow-800';
                case 'in-process':
                    return 'bg-purple-100 text-purple-800';
                default:
                    return 'bg-gray-100 text-gray-800';

            }

        },
    },

};
</script>
