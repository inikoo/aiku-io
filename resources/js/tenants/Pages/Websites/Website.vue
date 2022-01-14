<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Fri, 14 Jan 2022 14:44:09 Malaysia Time, Kuala Lumpur, Malaysia
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

    props: ['headerData', 'website'],

};
</script>


