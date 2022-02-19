<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 06 Feb 2022 23:18:46 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <page-header :headerData="headerData"/>

    <EmptyState v-if="dataTable.records.meta.total === 0 && queryBuilderProps.search.global.value==null "
                :data="dataTable.empty ?? {}"></EmptyState>
    <Tailwind2.Table v-else
                     :filters="queryBuilderProps.filters"
                     :search="queryBuilderProps.search"
                     :columns="queryBuilderProps.columns"
                     :on-update="setQueryBuilder"
                     :meta="dataTable.records"
    >
        <template #head>
            <tr>
                <Tailwind2.HeaderCell
                    :cell="header.sort ? sortableHeader(header.sort) : staticHeader(headerIdx)"
                    v-for="(header,headerIdx) in dataTable.columns"
                    v-bind:key="headerIdx"
                >{{ header.label }}
                </Tailwind2.HeaderCell>
            </tr>
        </template>

        <template #body>
            <tr v-for="(record,recordIdx) in dataTable.records.data" v-bind:key="recordIdx">
                <td v-for="(column,columnIdx) in dataTable.columns" v-bind:key="columnIdx">
                    <template v-if="column.href">
                        <span v-if="column.href.with_permission && !record[column.href.with_permission]">
                            <font-awesome-icon
                                fixed-width
                                :icon="['fal', 'lock-alt']"
                                aria-hidden="true"
                            />
                            {{ getCellValue(record, columnIdx) }}
                        </span>
                        <Link
                            v-else-if="record[column.href.if] || checkRouteParametersIntegrity(record, column.href.column)"
                            :href="route(column.href.route, processRouteParameters(record, column.href.column))"
                        >{{ getCellValue(record, columnIdx) }}
                        </Link>
                        <span v-else class="text-gray-300">{{ column.href.notSetLabel }}</span>
                    </template>
                    <span
                        v-else-if="column.transformations"
                    >{{ column.transformations[record[columnIdx]] }} x {{ column.transformations }}</span>
                    <Cell
                        v-else-if="column.toggle"
                        :data="record[columnIdx] ? column.toggle[0] : column.toggle[1]"
                    />
                    <span v-else-if="column.location" class="relative"><country-flag size="small" class="absolute bottom-[3px]  inline-block align-middle " :country="record[columnIdx][0]" :title="record[columnIdx][1]"/> <span
                        class="ml-5">{{ record[columnIdx][2] }}</span></span>
                    <span v-else>{{ getCellValue(record, columnIdx) }}</span>
                </td>
            </tr>
        </template>
    </Tailwind2.Table>

</template>

<script>
import app from '../layouts/app.vue';

import qs from 'qs';
import filter from 'lodash-es/filter';
import forEach from 'lodash-es/forEach';
import isEqual from 'lodash-es/isEqual';
import map from 'lodash-es/map';
import pickBy from 'lodash-es/pickBy';

export default {

    layout: app,
    props : {
        headerData       : {}, dataTable: {},
        queryBuilderProps: {
            type    : Object,
            required: true,
        },
    },
    data() {
        const queryBuilderData = {
            page   : this.queryBuilderProps.page || 1,
            sort   : this.queryBuilderProps.sort || '',
            filter : this.getFilterForQuery(
                this.queryBuilderProps.search || {},
                this.queryBuilderProps.filters || {},
            ),
            columns: this.getColumnsForQuery(this.queryBuilderProps.columns || {}),
        };

        return {queryBuilderData, queryBuilderDataIteration: 0};
    },

    methods: {

        getFilterForQuery(search, filters) {
            let filtersWithValue = {};

            const filtersAndSearch = Object.assign({}, filters, search);

            forEach(filtersAndSearch, (filterOrSearch) => {
                if (filterOrSearch.value) {
                    filtersWithValue[filterOrSearch.key] = filterOrSearch.value;
                }
            });

            return filtersWithValue;
        },

        getColumnsForQuery(columns) {
            let enabledColumns = filter(columns, (column) => {
                return column.enabled;
            });

            if (enabledColumns.length === Object.keys(columns || {}).length) {
                return [];
            }

            return map(enabledColumns, (column) => {
                return column.key;
            });
        },

        staticHeader(columnKey) {
            return this._headerCellData(columnKey, false);
        },

        sortableHeader(columnKey) {
            return this._headerCellData(columnKey, true);
        },

        _headerCellData(columnKey, sortable) {
            let sort = false;

            if (this.queryBuilderData.sort === columnKey) {
                sort = 'asc';
            } else if (this.queryBuilderData.sort === `-${columnKey}`) {
                sort = 'desc';
            }

            let show = true;

            if (this.queryBuilderProps.columns) {
                const columnData = this.queryBuilderProps.columns[columnKey];

                show = columnData ? columnData.enabled : true;
            }

            return {
                key   : columnKey,
                sort,
                show,
                sortable,
                onSort: this.sortBy,
            };
        },

        sortBy(column) {
            this.queryBuilderData.page = 1;
            this.queryBuilderData.sort =
                this.queryBuilderData.sort === column ? `-${column}` : column;
        },

        showColumn(columnKey) {
            if (!this.queryBuilderProps.columns) {
                return false;
            }

            const column = this.queryBuilderProps.columns[columnKey];

            return column ? column.enabled : false;
        },

        setQueryBuilder(data) {
            let page = this.queryBuilderData.page || 1;

            let filter = this.getFilterForQuery(
                data.search || {},
                data.filters || {},
            );

            if (!isEqual(filter, this.queryBuilderData.filter)) {
                page = 1;
            }

            this.queryBuilderData = {
                page,
                sort   : this.queryBuilderData.sort || '',
                filter,
                columns: this.getColumnsForQuery(data.columns || {}),
            };

            this.queryBuilderDataIteration++;
        },
    },

    computed: {
        queryBuilderString() {
            let query = qs.stringify(this.queryBuilderData, {
                filter(prefix, value) {
                    if (typeof value === 'object' && value !== null) {
                        return pickBy(value);
                    }
                    return value;
                },

                skipNulls         : true,
                strictNullHandling: true,
            });

            if (!query || query === 'page=1') {
                query = 'remember=forget';
            }

            return query;
        },
    },

    watch: {
        queryBuilderData: {
            deep: true,
            handler() {
                if (this.$inertia) {
                    const query = this.queryBuilderString;

                    this.$inertia.get(location.pathname + `?${query}`, {}, {replace: true, preserveState: true});
                }
            },
        },
    },

};
</script>

<script setup>
import PageHeader from '../components/navigation/top/page-header.vue';
import {Tailwind2} from '@protonemedia/inertiajs-tables-laravel-query-builder';

import {Link} from '@inertiajs/inertia-vue3';
import Cell from '../components/tables/cell.vue';
import EmptyState from '../components/tables/empty-state.vue';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {library} from '@fortawesome/fontawesome-svg-core';
import {faLockAlt} from '@/private/pro-light-svg-icons';

library.add(faLockAlt);
import CountryFlag from 'vue-country-flag-next';
import {useLocaleStore} from '../../scripts/stores/locale';

const locale = useLocaleStore();

const getCellValue = (record, columnIdx) => {
    let value = record[columnIdx];
    if (Number.isFinite(value)) {
        return new Intl.NumberFormat(locale.language).format(value);
    }
    return value;

};

const toggle = (value, blueprint) => {};

const checkRouteParametersIntegrity = (record, indices) => {

    if (typeof indices === 'string') {
        indices = [indices];
    }
    let pass = true;
    for (let index of indices) {
        if (!record[index]) {
            pass = false;
            break;
        }
    }
    return pass;

};

const processRouteParameters = (record, indices) => {
    if (typeof indices === 'string') {
        indices = [indices];
    }

    return indices.map(function(index) {
        return record[index];
    });

};


</script>


