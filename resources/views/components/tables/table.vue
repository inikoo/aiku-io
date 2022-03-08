<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Tue, 08 Mar 2022 03:45:37 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->


<template>
    <div>
        <div class="flex space-x-4">
            <slot
                name="tableFilter"
                :hasFilters="hasFilters"
                :filters="filters"
                :changeFilterValue="changeFilterValue"
            >
                <TableFilter v-if="hasFilters" :filters="filters" :on-change="changeFilterValue"/>
            </slot>

            <slot
                name="tableGlobalSearch"
                :search="search"
                :changeGlobalSearchValue="changeGlobalSearchValue"
            >
                <div class="flex-grow">
                    <TableGlobalSearch
                        v-if="search && search.global"
                        :value="search.global.value"
                        :on-change="changeGlobalSearchValue"
                    />
                </div>
            </slot>

            <slot
                name="tableAddSearchRow"
                :hasSearchRows="hasSearchRows"
                :search="search"
                :newSearch="newSearch"
                :enableSearch="enableSearch"
            >
                <TableAddSearchRow
                    v-if="hasSearchRows"
                    :rows="search"
                    :new="newSearch"
                    :on-add="enableSearch"
                />
            </slot>

            <slot
                name="tableColumns"
                :hasColumns="hasColumns"
                :columns="columns"
                :changeColumnStatus="changeColumnStatus"
            >
                <TableColumns v-if="hasColumns" :columns="columns" :on-change="changeColumnStatus"/>
            </slot>
        </div>

        <slot
            name="tableSearchRows"
            :hasSearchRows="hasSearchRows"
            :search="search"
            :newSearch="newSearch"
            :disableSearch="disableSearch"
            :changeSearchValue="changeSearchValue"
        >
            <TableSearchRows
                ref="rows"
                v-if="hasSearchRows"
                :rows="search"
                :new="newSearch"
                :on-remove="disableSearch"
                :on-change="changeSearchValue"
            />
        </slot>

        <slot name="tableWrapper" :meta="meta">
            <TableWrapper :class="{'mt-2': !onlyData}">
                <slot name="table">
                    <table class="min-w-full divide-y divide-gray-200 bg-white">
                        <thead class="bg-gray-50">
                        <slot name="head"/>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                        <slot name="body"/>
                        </tbody>
                    </table>
                </slot>

                <slot name="pagination">
                    <Pagination :meta="paginationMeta"/>
                </slot>
            </TableWrapper>
        </slot>
    </div>
</template>

<script setup>
import Pagination from './pagination.vue';
import TableGlobalSearch from './table-global-search.vue';
import TableWrapper from './table-wrapper.vue';

import TableAddSearchRow from './Tailwind2/TableAddSearchRow.vue';
import TableColumns from './Tailwind2/TableColumns.vue';
import TableFilter from './Tailwind2/TableFilter.vue';
import TableSearchRows from './Tailwind2/TableSearchRows.vue';
import {computed, nextTick,reactive, watch} from 'vue';

const props = defineProps({
                              meta: {
                                  type    : Object,
                                  default : () => {
                                      return {};
                                  },
                                  required: false,
                              },

                              columns: {
                                  type    : Object,
                                  default : () => {
                                      return {};
                                  },
                                  required: false,
                              },

                              filters: {
                                  type    : Object,
                                  default : () => {
                                      return {};
                                  },
                                  required: false,
                              },

                              search: {
                                  type    : Object,
                                  default : () => {
                                      return {};
                                  },
                                  required: false,
                              },

                              onUpdate: {
                                  type    : Function,
                                  required: false,
                              },
                          });


const hasFilters = computed(() => Object.keys(props.filters || {}).length > 0);
const hasColumns = computed(() => Object.keys(props.columns || {}).length > 0);
const hasSearchRows = computed(() => Object.keys(props.search || {}).length > 0);
const hasBody = computed(() => !!this.$slots.body);
const onlyData = computed(() => {
    if (hasFilters || hasColumns || hasSearchRows) {
        return false;
    }

    if (!props.search) {
        return true;
    }

    return !props.search.global;
});

const paginationMeta = computed(() => {
    if (hasBody) {
        return props.meta;
    }

    const hasPagination = 'meta' in props.meta || ('total' in props.meta && 'to' in props.meta && 'from' in props.meta);

    if (hasPagination) {
        return props.meta;
    }

    return { meta: { total: 0 } };
});

let queryBuilderData =  reactive({
    columns: props.columns,
    filters: props.filters,
    search : props.search,
});

let newSearch = [];
const disableSearch = (key) =>{
    newSearch = newSearch.filter((search) => search !== key);

    queryBuilderData.search[key].enabled = false;
    queryBuilderData.search[key].value = null;
}

const enableSearch = (key) => {
    queryBuilderData.search[key].enabled = true;
    newSearch.push(key);

    nextTick(() => {
        this.refs["rows"].focus(key);
    });
}

const changeSearchValue = (key, value) => queryBuilderData.search[key].value = value;
const changeGlobalSearchValue = (value) => changeSearchValue("global", value);
const changeFilterValue = (key, value) => queryBuilderData.filters[key].value = value;
const changeColumnStatus = (column, status) => queryBuilderData.columns[column].value = status;

watch(queryBuilderData, () => {
    if (props.onUpdate) {
        props.onUpdate(queryBuilderData);
    }

});

</script>

