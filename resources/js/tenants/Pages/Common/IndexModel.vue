<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 19 Jan 2022 17:44:55 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <page-header :headerData="headerData" />
    <Table
        v-if="dataTable.records.meta.total > 0"
        :filters="queryBuilderProps.filters"
        :search="queryBuilderProps.search"
        :columns="queryBuilderProps.columns"
        :on-update="setQueryBuilder"
        :meta="dataTable.records"
    >
        <template #head>
            <tr>
                <HeaderCell
                    :cell="header.sort ? sortableHeader(header.sort) : staticHeader(headerIdx)"
                    v-for="(header,headerIdx) in dataTable.columns"
                    v-bind:key="headerIdx"
                >{{ header.label }}</HeaderCell>
            </tr>
        </template>

        <template #body>
            <tr v-for="(record,recordIdx) in dataTable.records.data" v-bind:key="recordIdx">
                <td v-for="(column,columnIdx) in dataTable.columns" v-bind:key="columnIdx">
                    <template v-if="column.href">
                        <span
                            v-if="column.href.with_permission && !record[column.href.with_permission]"
                        >
                            <font-awesome-icon
                                fixed-width
                                :icon="['fal', 'lock-alt']"
                                aria-hidden="true"
                            />
                            {{ getCellValue(record, columnIdx) }}
                        </span>
                        <Link
                            v-else-if="record[column.href.if] || checkRouteParemetersIntegrity(record, column.href.column)"
                            :href="route(column.href.route, processRouteParemeters(record, column.href.column))"
                        >{{ getCellValue(record, columnIdx) }}</Link>
                        <span v-else class="text-gray-300">{{ column.href.notSetLabel }}</span>
                    </template>
                    <span
                        v-else-if="column.transformations"
                    >{{ column.transformations[record[columnIdx]] }} x {{ column.transformations }}</span>
                    <Cell
                        v-else-if="column.toggle"
                        :data="record[columnIdx] ? column.toggle[0] : column.toggle[1]"
                    />
                    <span v-else>{{ getCellValue(record, columnIdx) }}</span>
                </td>
            </tr>
        </template>
    </Table>
    <EmptyState v-else :data="dataTable.empty ?? {}"></EmptyState>
</template>

<script>
import PageHeader from '@/Layouts/PageHeader';
import { InteractsWithQueryBuilder, Tailwind2 } from '@protonemedia/inertiajs-tables-laravel-query-builder';
import { Link } from '@inertiajs/inertia-vue3';
import Cell from '@/Components/Tables/Cell';
import EmptyState from '@/Components/Tables/EmptyState';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faLockAlt } from '@/private/pro-light-svg-icons';
library.add(faLockAlt);
import { inject } from 'vue'

export default {


    mixins: [InteractsWithQueryBuilder],
    components: {
        PageHeader, Table: Tailwind2.Table, HeaderCell: Tailwind2.HeaderCell, Link, Cell, EmptyState, FontAwesomeIcon
    },
    props: ['headerData', 'dataTable'],
    methods: {

        getCellValue(record, columnIdx) {

            let value = record[columnIdx];
            if (Number.isFinite(value)) {
                return new Intl.NumberFormat(this.locale).format(value)
            }
            return value
        },



        toggle(value, blueprint) {

        },
        checkRouteParemetersIntegrity(record, indices) {

            if (typeof indices === 'string') {
                indices = [indices];
            }
            let pass = true;
            for (let index of indices) {
                if (!record[index]) {
                    pass = false
                    break;
                }
            }
            return pass;


        },

        processRouteParemeters(record, indices) {
            if (typeof indices === 'string') {
                indices = [indices];
            }

            return indices.map(function (index) {
                return record[index];
            })

        }
    },
     setup() {

        const locale = inject('locale')
        return {
          locale
        };
    }

};
</script>
