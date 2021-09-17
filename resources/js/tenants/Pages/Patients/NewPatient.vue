<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sat, 21 Aug 2021 21:59:21 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <PageHeader :headerData="headerData"/>
    <Form :formData="formData" class="mb-10 sm:mb-40"/>
    &nbsp;
</template>

<script setup>
import PageHeader from '@/Layouts/PageHeader';
import Form from '@/Components/Forms/Form';

const props = defineProps( ['headerData', 'formData'])


</script>


