<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 19 Feb 2022 02:05:08 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Supplier;


use App\Actions\UI\WithInertia;
use App\Models\Procurement\Supplier;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Supplier $supplier
 * @property string $title
 * @property array $breadcrumbs
 * @property array $edit
 * @property string $sectionRoot
 */
class ShowSupplier
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("procurement.suppliers.view.{$this->supplier->id}");
    }

    public function handle()
    {
    }

    public function getInertia(): Response
    {
        $actionIcons = [];


        if ($this->edit['can']) {
            $actionIcons[$this->edit['route']] = [
                'routeParameters' => $this->edit['routeParameters'],
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];
        }

        $headerData = [
            'title'       => $this->title,
            'actionIcons' => $actionIcons,

        ];
        if (!empty($this->inModel)) {
            $headerData['inModel'] = $this->inModel;
        }

        $headerData['info'] = [
            [
                'type' => 'group',
                'data' => [
                    'title'      => __('Products'),
                    'components' => [
                        [
                            'type' => 'icon',
                            'data' => [
                                'icon' => ['fal', 'hand-receiving'],
                                'type' => 'page-header'
                            ]
                        ],
                        [
                            'type' => 'text',
                            'data' => [
                                'class'=>'mr-1',
                                'type'=>'number',
                                'slot' => $this->supplier->stats->number_products,

                            ]
                        ],

                        [
                            'type' => 'link',
                            'data' => [
                                'href'      =>  [
                                    'route'           => 'procurement.suppliers.show.products.index',
                                    'parameters' => $this->supplier->id
                                ],
                                'slot' => __('products')

                            ]
                        ]
                    ]
                ]
            ]

        ];


        return Inertia::render(
            'show-model',
            [
                'navData'     => ['procurement' => 'shops', 'sectionRoot' => $this->sectionRoot],
                'breadcrumbs' => $this->breadcrumbs,
                'headerData'  => $headerData,

            ]

        );
    }


}
