<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Fri, 20 Aug 2021 18:03:58 Malaysia Time, Kuala Lumpur, Malaysia
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
        :meta="users"
    >

        <template #head>
            <tr>
                <HeaderCell :cell="sortableHeader('username')">Username</HeaderCell>

            </tr>
        </template>

        <template #body>
            <tr v-for="user in users.data" :key="user.id">

                <td><Link :href="route('tenant.users.show',user.id)">{{ user.username }}</Link></td>


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
    props     : ['headerData','users'],

};
</script>
