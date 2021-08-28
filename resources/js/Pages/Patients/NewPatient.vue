<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sat, 21 Aug 2021 21:59:21 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>

    <page-header :headerData="headerData"/>


    <Form :formData="formData"/>


</template>

<script>
import PageHeader from '@/Layouts/PageHeader';
import Form from '@/Components/Forms/Form';

export default {
    components: {

        PageHeader, Form,
    },

    props: ['headerData', 'formData'],

};
</script>


