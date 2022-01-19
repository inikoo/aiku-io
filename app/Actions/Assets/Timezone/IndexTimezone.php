<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 04:04:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Assets\Timezone;

use App\Models\Assets\Timezone;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexTimezone
{
    use AsAction;

    public function handle(): Collection
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%$value%");
            });
        });

        return QueryBuilder::for(Timezone::class)
            ->defaultSort('offset')
            ->allowedFilters([$globalSearch])->get();
    }


    public function asSelectOptions(): array
    {
        $selectOptions = [];
        foreach ($this->handle() as $timezone) {
            $selectOptions[$timezone->id] = $timezone->formatOffset().' '.$timezone->name;
        }
        return $selectOptions;
    }



}
