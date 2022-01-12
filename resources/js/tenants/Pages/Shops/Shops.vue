<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Tue, 11 Jan 2022 04:25:59 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <page-header :headerData="headerData"/>
    <Table


        :filters="queryBuilderProps.filters"
        :search="queryBuilderProps.search"
        :columns="queryBuilderProps.columns"
        :on-update="setQueryBuilder"
        :meta="shops"
    >

        <template #head>
            <tr>

                <HeaderCell :cell="sortableHeader('code')">Code</HeaderCell>
                <HeaderCell :cell="sortableHeader('name')">Name</HeaderCell>


            </tr>
        </template>

        <template #body>
            <tr v-for="shop in shops.data" :key="shop.id">

                <td><Link :href="route('shop.index',shop.id)">{{ shop.code }}</Link></td>

                <td>{{ shop.name }}</td>

            </tr>
        </template>
    </Table>
</template>

<script>
import PageHeader from '@/Layouts/PageHeader';
import {InteractsWithQueryBuilder, Tailwind2} from '@protonemedia/inertiajs-tables-laravel-query-builder';
import {Link} from '@inertiajs/inertia-vue3';

export default {
    mixins: [InteractsWithQueryBuilder],
    components: {
        PageHeader,Table: Tailwind2.Table, HeaderCell: Tailwind2.HeaderCell,Link
    },
    props     : ['headerData','shops'],

};
</script>
