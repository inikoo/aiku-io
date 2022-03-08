/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 08 Mar 2022 01:23:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

import filter from 'lodash-es/filter';
import map from 'lodash-es/map';
import forEach from 'lodash-es/forEach';
import qs from 'qs';
import pickBy from 'lodash-es/pickBy';
import {Inertia} from '@inertiajs/inertia';
import {computed, watch, reactive} from 'vue';
import isEqual from 'lodash-es/isEqual';

export default function(props) {

    const getFilterForQuery = (search, filters) => {
        let filtersWithValue = {};

        const filtersAndSearch = Object.assign({}, filters, search);

        forEach(filtersAndSearch, (filterOrSearch) => {
            if (filterOrSearch.value) {
                filtersWithValue[filterOrSearch.key] = filterOrSearch.value;
            }
        });

        return filtersWithValue;
    };
    const getColumnsForQuery = (columns) => {
        let enabledColumns = filter(columns, (column) => {
            return column.enabled;
        });

        if (enabledColumns.length === Object.keys(columns || {}).length) {
            return [];
        }

        return map(enabledColumns, (column) => {
            return column.key;
        });
    };

    let queryBuilderData =
            reactive({
                         page   : props.queryBuilderProps.page || 1,
                         sort   : props.queryBuilderProps.sort || '',
                         filter : getFilterForQuery(
                             props.queryBuilderProps.search || {},
                             props.queryBuilderProps.filters || {},
                         ),
                         columns: getColumnsForQuery(
                             props.queryBuilderProps.columns || {},
                         ),
                     });

    let queryBuilderDataIteration = 0;

    const staticHeader = (columnKey) => {
        return _headerCellData(columnKey, false);
    };

    const sortableHeader = (columnKey) => {
        return _headerCellData(columnKey, true);
    };

    const _headerCellData = (columnKey, sortable) => {
        let sort = false;

        if (queryBuilderData.sort === columnKey) {
            sort = 'asc';
        } else if (queryBuilderData.sort === `-${columnKey}`) {
            sort = 'desc';
        }

        let show = true;

        if (props.queryBuilderProps.columns) {
            const columnData = props.queryBuilderProps.columns[columnKey];

            show = columnData ? columnData.enabled : true;
        }

        return {
            key   : columnKey,
            sort,
            show,
            sortable,
            onSort: sortBy,
        };
    };

    const sortBy = (column) => {
        queryBuilderData.page = 1;
        queryBuilderData.sort =
            queryBuilderData.sort === column ? `-${column}` : column;

    };

    const showColumn = (columnKey) => {
        if (!props.queryBuilderProps.columns) {
            return false;
        }

        const column = props.queryBuilderProps.columns[columnKey];

        return column ? column.enabled : false;
    };

    const setQueryBuilder = (data) => {
        let page = queryBuilderData.page || 1;

        let filter = getFilterForQuery(
            data.search || {},
            data.filters || {},
        );

        if (!isEqual(filter, queryBuilderData.filter)) {
            page = 1;
        }

        queryBuilderData.page=page;
        queryBuilderData.sort=queryBuilderData.sort || '';
        queryBuilderData.filter=filter;
        queryBuilderData.columns=getColumnsForQuery(data.columns || {});

        queryBuilderDataIteration++;
    };

    const queryBuilderString = computed(() => {


        let query = qs.stringify(queryBuilderData, {
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

    });

    watch(queryBuilderString, () => {
        const query = queryBuilderString.value;

        Inertia.get(location.pathname + `?${query}`, {},
                    {replace: true, preserveState: true});

    });

    return {
        sortableHeader, staticHeader, showColumn, setQueryBuilder,
    };
}
