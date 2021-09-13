
<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sat, 21 Aug 2021 04:59:16 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <page-header :headerData="headerData"/>
    <Table


        :filters="queryBuilderProps.filters"
        :search="queryBuilderProps.search"
        :columns="queryBuilderProps.columns"
        :on-update="setQueryBuilder"
        :meta="employees"
    >

        <template #head>
            <tr>
                <HeaderCell :cell="sortableHeader('employees.id')">ID</HeaderCell>
                <HeaderCell :cell="sortableHeader('contacts.name')">Name</HeaderCell>


            </tr>
        </template>

        <template #body>
            <tr v-for="employee in employees.data" :key="employee.id">
                <td><Link :href="route('employees.show',employee.id)">{{ employee['formatted_id'] }}</Link></td>
                <td>{{ employee.name }}</td>

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
    props     : ['headerData','employees'],

};
</script>
