<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sat, 21 Aug 2021 03:49:12 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->
<template>
    <Head title="Dashboard" />
    <page-header :headerData="headerData"/>
</template>

<script>
import PageHeader from '@/Layouts/PageHeader';

export default {
    components: {
        PageHeader,
    },
    props     : ['headerData'],

};
</script>

