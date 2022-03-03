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
        $info = [];

        if ($this->customer->status != 'approved') {
            $info[] = [
                'type' => 'badge',
                'data' =>
                    match ($this->customer->status) {
                        'working' => [
                            'type'  => 'ok',
                            'title' => __('Status'),
                            'slot'  =>  __('Working')
                        ],
                        'pending-approval' => [
                            'type'      => 'in-process',
                            'slot'       => __('For approval')
                        ],
                        'left' => [
                            'type'      => 'cancelled',
                            'class' => 'text-green-600',
                            'slot'       => __('Left')
                        ],
                        default => [
                            'type'      => 'cancelled',
                            'class' => 'text-gray-700',
                            'slot'       => 'Unknown'
                        ]
                    }

            ];
        }else{
            $info[] = [
                'type' => 'badge',
                'data' =>
                    match ($this->customer->state) {
                        'active' => [
                            'type'  => 'ok',
                            'title' => __('Active'),
                            'slot'  =>  __('Active')
                        ],
                        'losing' => [
                            'type'      => 'warning',
                            'slot'       => __('Loosing')
                        ],
                        'lost' => [
                            'type'      => 'cancelled',
                            'slot'       => __('Lost')
                        ],
                        default => [
                            'type'      => 'cancelled',
                            'class' => 'text-gray-700',
                            'slot'       => 'Unknown'
                        ]
                    }

            ];


        }


        if ($this->customer->shop->type == 'fulfilment_house') {
            $info[] =  [
                'type' => 'group',
                'data' => [
                    'components' => [
                        [
                            'type' => 'icon',
                            'data' => [
                                'icon' => ['fal', 'pallet'],
                                'type' => 'page-header'
                            ]
                        ],
                        [
                            'type' => 'number',
                            'data' => [
                                'slot' => $this->customer->fulfilmentCustomer->number_unique_stocks
                            ]
                        ],
                        [
                            'type' => 'link',
                            'data' => [
                                'slot'  => ' '.trans_choice(__('stored good'), $this->customer->fulfilmentCustomer->number_unique_stocks),
                                'class' => 'pr-1',
                                'href'  => [
                                    'route'           => 'marketing.shops.show.customers.show.unique_stocks.index',
                                    'parameters' => [$this->customer->shop_id, $this->customer->id]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }





        return Inertia::render(
            'show-model',
            [
                'navData'     => ['module' => 'shops', 'metaSection' => $this->metaSection, 'sectionRoot' => $this->sectionRoot],
                'breadcrumbs' => $this->breadcrumbs,
                'headerData'  => [
                    'title' => $this->title,
                    'info'  => $info,
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
