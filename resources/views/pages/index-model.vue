<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 06 Feb 2022 23:18:46 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template layout="app">
    <page-header :headerData="headerData"/>

    <EmptyState v-if="dataTable['records'].meta.total === 0 && queryBuilderProps.search.global.value==null "
                :data="dataTable.empty ?? {}"></EmptyState>

    <Table v-else
           :filters="queryBuilderProps.filters"
           :search="queryBuilderProps.search"
           :columns="queryBuilderProps.columns"
           :on-update="setQueryBuilder"
           :meta="dataTable['records']">

        <template #head>
            <tr>
                <HeaderCell
                    :cell="header.sort ? sortableHeader(header.sort) : staticHeader(headerIdx)"
                    v-for="(header,headerIdx) in dataTable.columns"
                    v-bind:key="headerIdx"
                >{{ header.label }}
                </HeaderCell>
            </tr>
        </template>
        <template #body>
            <tr v-for="(record,recordIdx) in dataTable['records'].data" v-bind:key="recordIdx" :class="recordIdx % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                <td v-for="(column,columnIdx) in dataTable.columns" v-bind:key="columnIdx" class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">


                    <component-group
                        v-if="column.components !== undefined "
                        :data="{
                            'class':column['class'],
                            'title':column['title'],
                            'components':getComponentsData(record,column['components'])
                            }">
                        }
                    </component-group>
                    <span v-else-if="column.type==='number'">{{ __number(record[column['resolver']]) }}</span>
                    <span v-else-if="column.type==='date'">{{ __date(record[column['resolver']]) }}</span>

                    <span v-else>{{ record[column['resolver']] }}</span>


                </td>
            </tr>
        </template>

    </Table>

</template>


<!--suppress NpmUsedModulesInstalled, JSFileReferences -->
<script setup>
import PageHeader from '@c/navigation/top/page-header.vue';
import EmptyState from '@c/tables/empty-state.vue';
import ComponentGroup from '@c/elements/component-group.vue';
import Table from '@c/tables/table.vue';
import HeaderCell from '@c/tables/header-cell.vue';

import useTable from '@s/useTable.js';
import useI18n from '@s/i18n/useI18n.js';
import IntervalTabs from '@c/tabs/interval-tabs.vue';

const props = defineProps({
                              headerData       : {},
                              dataTable        : {},
                              queryBuilderProps: {
                                  type    : Object,
                                  required: true,
                              },
                          });

const {__number, __date} = useI18n();

const {sortableHeader, staticHeader, setQueryBuilder} = useTable(props);



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
        let resolver = components[i]['resolver'].type;

        if (type === 'link' && components[i]['resolver'].parameters.href.permission && !record[components[i]['resolver'].parameters.href.permission]) {
            type = 'locked_link';
            resolver = 'slot';
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

const getSlot = (record, parameters) => {

    switch (parameters.type ?? 'text') {
        case 'number':
            return __number(record[parameters.indices] ?? 0);
        case 'date':
            return __date(record[parameters.indices] ?? '');
        default:
            return record[parameters.indices] ?? parameters.indices;
    }
};

const getComponentData = (type, record, resolver) => {

    const resolvers = {
        boolean(record, parameters) {
            return record[parameters.indices] ? parameters.values[0] : parameters.values[1];
        },
        link(record, parameters) {
            return {
                'slot': getSlot(record, parameters),
                'href': {
                    'route'     : parameters.href.route,
                    'parameters': getValues(record, parameters.href.indices),
                },

            };
        },

        slot(record, parameters) {
            return {
                'slot': getSlot(record, parameters),
            };
        },
        country(record, parameters) {
            return {
                'code': record[parameters.indices][0],
                'name': record[parameters.indices][1],
            };
        },
    };
    return resolvers[type](record, resolver.parameters) ?? null;

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

};

</script>


