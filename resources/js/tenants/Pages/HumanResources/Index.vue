<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sat, 21 Aug 2021 04:42:35 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
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
