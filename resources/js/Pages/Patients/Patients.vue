<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sat, 21 Aug 2021 21:11:19 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>
    <simple-header :breadcrumbs="breadcrumbs" :module="'patients'" :actions="actions" :actionIcons="actionIcons">{{ title }}</simple-header>

    <Table

        class="mt-5"

        :filters="queryBuilderProps.filters"
        :search="queryBuilderProps.search"
        :columns="queryBuilderProps.columns"
        :on-update="setQueryBuilder"
        :meta="patients"
    >

        <template #head>
            <tr>
                <HeaderCell :cell="sortableHeader('id')">ID</HeaderCell>
                <HeaderCell :cell="sortableHeader('name')">Name</HeaderCell>
                <HeaderCell :cell="sortableHeader('gender')" v-show="showColumn('gender')" >Gender</HeaderCell>
                <HeaderCell :cell="sortableHeader('date_of_birth')" v-show="showColumn('date_of_birth')">Date of birth</HeaderCell>
                <HeaderCell :cell="sortableHeader('date_of_birth')" v-show="showColumn('age')">Age</HeaderCell>

            </tr>
        </template>

        <template #body>
            <tr v-for="patient in patients.data" :key="patient.id">
                <td><Link :href="route('patients.show',patient.id)">{{ patient['formatted_id'] }}</Link></td>
                <td>{{ patient.name }}</td>
                <td v-show="showColumn('gender')">{{ patient['gender'] }}</td>
                <td v-show="showColumn('date_of_birth')">{{ patient['date_of_birth'] }}</td>
                <td v-show="showColumn('age')">{{ patient['age'] }}</td>

            </tr>
        </template>
    </Table>

</template>

<script>
import SimpleHeader from '@/Layouts/PageHeadings/SimpleHeader';
import {InteractsWithQueryBuilder, Tailwind2} from '@protonemedia/inertiajs-tables-laravel-query-builder';
import {Link} from '@inertiajs/inertia-vue3';

export default {
    mixins: [InteractsWithQueryBuilder],

    components: {
        SimpleHeader, Table: Tailwind2.Table, HeaderCell: Tailwind2.HeaderCell,Link
    },

    props: [
        'title', 'buttons', 'breadcrumbs', 'actions', 'actionIcons','patients'

    ],

};
</script>

