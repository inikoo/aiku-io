<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 16 Jan 2022 16:08:37 Malaysia Time, Kuala Lumpur, Malaysia
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

    props: ['headerData', 'warehouse'],

};
</script>


