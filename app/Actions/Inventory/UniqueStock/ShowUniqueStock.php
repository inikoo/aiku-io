<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 04 Mar 2022 16:33:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\UniqueStock;

use App\Actions\UI\WithInertia;
use App\Models\Inventory\UniqueStock;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property UniqueStock $uniqueStock
 * @property array $breadcrumbs
 * @property string $sectionRoot
 * @property string $title
 * @property string $metaSection
 * @property string $module
 */
class ShowUniqueStock
{
    use AsAction;
    use WithInertia;

    public function asInertia(UniqueStock $uniqueStock): Response
    {
        $this->set('uniqueStock', $uniqueStock);
        $this->validateAttributes();
        return $this->getInertia();

    }


    public function getInertia(): Response
    {
        $info = [];




        return Inertia::render(
            'show-model',
            [
                'navData'     => ['module' => $this->module, 'metaSection' => $this->metaSection, 'sectionRoot' => $this->sectionRoot],
                'breadcrumbs' => $this->breadcrumbs,
                'headerData'  => [
                    'title' => $this->title,
                    'info'  => $info,
                ],

            ]

        );
    }


}
