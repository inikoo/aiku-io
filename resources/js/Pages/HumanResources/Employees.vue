
<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sat, 21 Aug 2021 04:59:16 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <simple-header :breadcrumbs="breadcrumbs" :module="'hr'" :buttons="buttons" :actionIcons="actionIcons">{{ title }}</simple-header>
</template>

<script>
import SimpleHeader from '@/Layouts/PageHeadings/SimpleHeader';

export default {
    components: {
        SimpleHeader,
    },
    props     : ['title', 'buttons', 'breadcrumbs', 'buttons', 'actionIcons'],

};
</script>
