<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 06 Apr 2022 18:08:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Staffing;


use App\Actions\UI\WithInertia;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class ShowStaffingDashboard
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {


        return $request->user()->hasPermissionTo("staffing.view");
    }


    public function asInertia(array $attributes = []): Response
    {
        $this->fill($attributes);

        $this->validateAttributes();


        return Inertia::render(
            'show-dashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData' => ['module' => 'staffing'],
                'headerData'  => [
                    'title' => __('Staffing'),
                    'info' => [
                        [
                            'type' => 'group',
                            'data' => [
                                'components' => [
                                    [
                                        'type' => 'icon',
                                        'data' => [
                                            'icon' => ['fal', 'people-arrows'],
                                            'type' => 'page-header'
                                        ]
                                    ],
                                    [
                                        'type' => 'number',
                                        'data' => [
                                            'slot' => App('currentTenant')->staffingStats->number_applicants
                                        ]
                                    ],
                                    [
                                        'type' => 'link',
                                        'data' => [
                                            'slot'  => ' '.trans_choice(__('applicant'), App('currentTenant')->staffingStats->number_applicants),
                                            'class' => 'pr-1',
                                            'href'  => [
                                                'route' => 'staffing.applicants.index',
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],

                    ],

                ]
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(): array
    {
        return [
            'staffing.dashboard' => [
                'route' => 'staffing.dashboard',
                'name'  => __('Staffing'),
            ]
        ];
    }


}
