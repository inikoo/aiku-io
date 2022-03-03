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


                    <component-group
                        v-if="column.components !== undefined "
                        :data="{
                            'class':column['class'],
                            'title':column['title'],
                            'components':getComponentsData(record,column['components'])
                            }">
                        }
                    </component-group>
                    <span v-else-if="column.type==='number'">{{ number(record[column['resolver']]) }}</span>
                    <span v-else>{{ record[column['resolver']] }}</span>


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
import EmptyState from '../components/tables/empty-state.vue';

import {useLocaleStore} from '../../scripts/stores/locale';
import ComponentGroup from '../components/elements/component-group.vue';

const locale = useLocaleStore();

/*
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


 */


const number = (value) => {
    if (Number.isFinite(value)) {
        return new Intl.NumberFormat(locale['language']).format(value);
    }
    return value;

};

const getValues = (record, indices) => {
    if (typeof indices === 'string') {
        indices = [indices];
    }

    return indices.map(function(index) {
        return record[index];
    });

};

const getComponentsData = (record, components) => {

    let componentData = [];
    for (let i in components) {

        let type = components[i].type;
        let resolver=components[i]['resolver'].type;

        if (type === 'link' && components[i]['resolver'].parameters.href.permission && !record[components[i]['resolver'].parameters.href.permission]) {
            type = 'locked_link';
            resolver='slot';
        }

        componentData.push(
            {
                'type': type,
                'data': getComponentData(resolver, record, components[i]['resolver']),
            },
        );

    }
    return componentData;

};

const getComponentData = (type, record, resolver) => {

    const resolvers = {
        boolean(record, parameters) {
            return record[parameters.indices] ? parameters.values[0] : parameters.values[1];
        },
        link(record, parameters) {
            return {
                'slot': record[parameters.indices] ?? parameters.indices,
                'href': {
                    'route'     : parameters.href.route,
                    'parameters': getValues(record, parameters.href.indices),
                },

            };
        },

        slot(record, parameters) {
            return {
                'slot': record[parameters.indices] ?? parameters.indices,
            };
        },
        country(record, parameters) {
            return {
                'code': record[parameters.indices][0],
                'name': record[parameters.indices][1]
            };
        },
    };
    return resolvers[type](record, resolver.parameters) ?? null;

};

</script>


