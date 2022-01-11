<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Tue, 11 Jan 2022 04:25:59 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
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
