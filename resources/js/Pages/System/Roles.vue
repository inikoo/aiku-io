<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Fri, 20 Aug 2021 18:03:58 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <simple-header :breadcrumbs="breadcrumbs" :module="'system'" :actions="actions" :actionIcons="actionIcons">{{ title }}</simple-header>
</template>

<script>
import SimpleHeader from '@/Layouts/PageHeadings/SimpleHeader';

export default {
    components: {
        SimpleHeader,
    },
    props     : ['title', 'buttons', 'breadcrumbs', 'actions', 'actionIcons'],

};
</script>
