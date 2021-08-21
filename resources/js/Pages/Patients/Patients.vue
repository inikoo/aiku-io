<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sat, 21 Aug 2021 21:11:19 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <simple-header :breadcrumbs="breadcrumbs" :module="'patients'" :actions="actions" :actionIcons="actionIcons">{{ title }}</simple-header>
</template>

<script>
import SimpleHeader from '@/Layouts/PageHeadings/SimpleHeader';

export default {
    components: {
        SimpleHeader,
    },

    props: ['title', 'buttons', 'breadcrumbs', 'actions', 'actionIcons'],



};
</script>

