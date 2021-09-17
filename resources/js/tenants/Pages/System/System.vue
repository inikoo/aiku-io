<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 19 Aug 2021 17:40:57 Malaysia Time, Kuala Lumpur, Malaysia
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

