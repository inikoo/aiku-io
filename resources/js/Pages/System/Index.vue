<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 19 Aug 2021 17:40:57 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <simple-header :breadcrumbs="breadcrumbs" :module="'system'" :actions="actions" :actionIcons="actionIcons">{{ title }}</simple-header>
</template>

<script>
import SimpleHeader from '@/Layouts/PageHeadings/SimpleHeader';
import {__} from 'matice';
import {library} from '@fortawesome/fontawesome-svg-core';
import {faSlidersHSquare} from '@/Private/pro-light-svg-icons';

library.add(faSlidersHSquare);
/*

const buttons = [
  //  {name: __('Hello'), primary: true},
  //  {name: __('Hello2'), primary: false},
];

const actionIcons = [
   // {name: 'settings', icon: ['fal', 'sliders-h-square']},
  //  {name: 'settings', icon: ['fal', 'sliders-h-square']},

];

*/
export default {
    components: {
        SimpleHeader,
    },

    props: ['title', 'buttons', 'breadcrumbs', 'actions', 'actionIcons'],

    methods: {
        __: __,
    },

};
</script>

