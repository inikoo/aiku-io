<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 22:46:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;

use App\Actions\UI\WithInertia;
use App\Models\CRM\Customer;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Customer $customer
 * @property array $breadcrumbs
 * @property string $sectionRoot
 * @property string $title
 * @property string $metaSection
 */
class ShowCustomer
{
    use AsAction;
    use WithInertia;


    public function getInertia(): Response
    {
        return Inertia::render(
            'show-model',
            [
                'navData'     => ['module' => 'shops', 'metaSection' => $this->metaSection, 'sectionRoot' => $this->sectionRoot],
                'breadcrumbs' => $this->breadcrumbs,
                'headerData'  => [
                    'title' => $this->title,
                    'meta'  => [
                        [
                            'name' => $this->customer->status
                        ]
                    ]
                ],
                'model'       => $this->customer,
                'blocks'      => [
                    'two-column-card' => [
                        'components' => [
                            [
                                'type'      => 'item',
                                'span'      => 1,
                                'valueType' => 'list',
                                'value'     => [
                                    [
                                        'overlay' => __('Contact name'),
                                        'text'    => $this->customer->contact_name

                                    ],
                                    [
                                        'overlay' => __('Company'),
                                        'text'    => $this->customer->company_name

                                    ]
                                ]
                            ],
                            [
                                'type'  => 'item',
                                'span'  => 1,
                                'value' => [
                                    'icon'    => ['fal', 'at'],
                                    'overlay' => __('Email'),
                                    'text'    => $this->customer->email
                                ]
                            ]

                        ],

                    ]


                ]
            ]

        );
    }


}
