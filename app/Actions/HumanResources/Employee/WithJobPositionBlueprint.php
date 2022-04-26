<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 25 Apr 2022 09:13:28 Central European Summer Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Models\Inventory\Warehouse;
use App\Models\Marketing\Shop;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

trait WithJobPositionBlueprint{
    protected function getJobPositionBlueprint(): array
    {
        $blueprint = [];
        foreach (
            config("app_type.".app('currentTenant')->appType->code.".job_positions.blueprint")
            as $fieldSetKey => $fieldSet
        ) {
            $options = [];
            foreach ($fieldSet['positions'] as $positionKey => $positions) {
                $option = [
                    'key'         => $positionKey,
                    'name'        => ucfirst(Lang::get("job_positions.$positionKey.name")),
                    'description' => Str::ucfirst(Lang::get("job_positions.$positionKey.description"))

                ];
                if (is_array($positions)) {
                    $subOptions = [];
                    foreach ($positions as $positionKey) {
                        $subOptions[] = [
                            'key'  => $positionKey,
                            'name' => ucfirst(
                                Lang::get(
                                    'job_positions.grade.'.
                                    config("app_type.".app('currentTenant')->appType->code.".job_positions.positions.$positionKey.grade")
                                )
                            ),
                        ];
                    }
                    $option['subOptions'] = $subOptions;
                }
                $options[] = $option;
            }

            $_blueprint = [
                'title'   => Str::ucfirst(__($fieldSet['title'])),
                'options' => $options,
                'key'     => $fieldSetKey
            ];

            $scope = Arr::get($fieldSet, 'scope');


            if ($scope == 'shops' and Shop::count() > 1) {
                $_blueprint['scopes']['options']     = Shop::all()->map(function ($item) {
                    return $item->only(['id', 'code', 'name']);
                })->all();
                $_blueprint['scopes']['title']       = __('Shops');
                $_blueprint['scopes']['placeholder'] = __('Select shops');
            } elseif ($scope == 'warehouses' and Warehouse::count() > 1) {
                $_blueprint['scopes']['options']     = Warehouse::all()->map(function ($item) {
                    return $item->only(['id', 'code', 'name']);
                })->all();
                $_blueprint['scopes']['title']       = __('Warehouses');
                $_blueprint['scopes']['placeholder'] = __('Select warehouses');
            }

            $blueprint[] = $_blueprint;
        }

        return $blueprint;
    }
}
