<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Fri, 14 Jan 2022 14:44:28 Malaysia Time, Kuala Lumpur, Malaysia
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
        :meta="websites"
    >

        <template #head>
            <tr>
                <HeaderCell :cell="sortableHeader('code')">Code</HeaderCell>
                <HeaderCell :cell="sortableHeader('name')">Name</HeaderCell>
                <HeaderCell :cell="sortableHeader('url')">Url</HeaderCell>
            </tr>
        </template>

        <template #body>
            <tr v-for="website in websites.data" :key="website.id">

                <td><Link :href="route('websites.index',website.id)">{{ website.code }}</Link></td>
                <td>{{ website.name }}</td>
                <td>{{ website.url }}</td>

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
    props     : ['headerData','websites'],

};
</script>
