<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Tue, 11 Jan 2022 23:07:53 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <page-header :headerData="headerData"/>




</template>

<script>
import PageHeader from '@/Layouts/PageHeader';
import Card from '@/Components/DataDisplay/Card';

export default {
    components: {
        PageHeader,Card
    },

    props: ['headerData', 'shop'],

};
</script>


