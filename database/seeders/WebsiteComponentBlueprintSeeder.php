<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 25 Mar 2022 00:32:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Web\WebsiteComponentBlueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class WebsiteComponentBlueprintSeeder extends Seeder
{

    public function run()
    {
        $components = [

            [
                'type' => 'footer',
                'name' => 'social-links-only',

            ],
            [
                'type' => 'footer',
                'name' => 'simple-centered'
            ],
        ];

        foreach ($components as $component) {
            WebsiteComponentBlueprint::upsert(
                [
                    [
                        'type'      => $component['type'],
                        'name'      => $component['name'],
                        'sample_arguments' => json_encode(Arr::get($component, 'arguments', [])),
                        'data' => json_encode(Arr::get($component, 'data', []))

                    ],
                ],
                ['type', 'name'],
            );
        }
    }
}
