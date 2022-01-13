<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 13 Jan 2022 05:00:04 Malaysia Time, Kuala Lumpur, Malaysia
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


