<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 19 Jan 2022 17:44:55 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <page-header :headerData="headerData"/>
    <Table
         v-if="dataTable.records.lenght>0"

        :filters="queryBuilderProps.filters"
        :search="queryBuilderProps.search"
        :columns="queryBuilderProps.columns"
        :on-update="setQueryBuilder"
        :meta="dataTable.records"
    >
        <template #head>
            <tr>
                <HeaderCell :cell="header.sort?sortableHeader(header.sort):staticHeader(headerIdx)"
                            v-for="(header,headerIdx) in dataTable.columns" v-bind:key="headerIdx">
                    {{header.label}}
                </HeaderCell>
            </tr>
        </template>

        <template #body>
            <tr v-for="(record,recordIdx) in dataTable.records.data" v-bind:key="recordIdx">
                <td  v-for="(column,columnIdx) in dataTable.columns" v-bind:key="columnIdx">
                    <Link v-if="column.href" :href="route(column.href.route,record[column.href.column])">{{record[columnIdx]}}</Link>
                    <span v-else-if="column.transformations" >
                        {{column.transformations[record[columnIdx]]}} x {{column.transformations}}
                    </span>
                    <Cell v-else-if="column.toggle" :data="record[columnIdx]?column.toggle[0]:column.toggle[1]" />
                    <span v-else>{{record[columnIdx]}}</span>
                </td>


            </tr>
        </template>
    </Table>
    <EmptyState v-else :data="dataTable.empty??{}"></EmptyState>
</template>

<script>
import PageHeader from '@/Layouts/PageHeader';
import {InteractsWithQueryBuilder, Tailwind2} from '@protonemedia/inertiajs-tables-laravel-query-builder';
import {Link} from '@inertiajs/inertia-vue3';
import Cell from '@/Components/Tables/Cell';
import EmptyState from '@/Components/Tables/EmptyState';

export default {
    mixins: [InteractsWithQueryBuilder],
    components: {
        PageHeader,Table: Tailwind2.Table, HeaderCell: Tailwind2.HeaderCell,Link,Cell,EmptyState
    },
    props     : ['headerData','dataTable'],
    methods:  {
        toggle(value,blueprint) {

        }
    }

};
</script>
