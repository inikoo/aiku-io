<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 19 Jan 2022 02:22:22 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <PageHeader :headerData="headerData"/>
</template>

<script>

import PageHeader from '@/Layouts/PageHeader';
import Card from '@/Components/DataDisplay/Card';

import {library} from '@fortawesome/fontawesome-svg-core';

import {faTasks} from '@/private/pro-light-svg-icons';

import {faCheckCircle,faTimesCircle} from '@fortawesome/free-solid-svg-icons';
library.add(faCheckCircle, faTimesCircle,faTasks);

export default {
    components: {
        PageHeader,Card
    },

    props: ['headerData', 'employee'],

};
</script>


