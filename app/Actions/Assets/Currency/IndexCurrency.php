<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 02:57:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Assets\Currency;



use App\Models\Assets\Currency;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;


class IndexCurrency
{
    use AsAction;


    public function handle(): Collection
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%$value%")
                    ->orWhere('code', $value);
            });
        });

        return QueryBuilder::for(Currency::class)
            ->defaultSort('name')
            ->allowedFilters([$globalSearch])->get();
    }


    public function asSelectOptions(): array
    {
        return  $this->handle()->mapWithKeys(function ($item) {
            return [$item['id'] => $item['name'].' ('.$item['code'].')' ] ;
        })->all();


    }



}
