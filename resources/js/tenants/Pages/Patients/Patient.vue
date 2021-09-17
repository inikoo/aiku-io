<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Fri, 27 Aug 2021 05:56:57 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <page-header :headerData="headerData"/>

    <card :cardData="cardData"/>


</template>

<script>
import PageHeader from '@/Layouts/PageHeader';
import Card from '@/Components/DataDisplay/Card';

export default {
    components: {
        PageHeader,Card
    },

    props: ['headerData', 'patient','cardData'],

};
</script>


