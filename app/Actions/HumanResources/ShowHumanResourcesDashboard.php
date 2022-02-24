<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 07 Feb 2022 14:24:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources;


use App\Actions\UI\WithInertia;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class ShowHumanResourcesDashboard
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {


        return $request->user()->hasPermissionTo("employees.view");
    }


    public function asInertia(array $attributes = []): Response
    {
        $this->fill($attributes);

        $this->validateAttributes();


        return Inertia::render(
            'show-dashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData' => ['module' => 'human_resources'],
                'headerData'  => [
                    'title' => __('Human resources'),
                    'meta'  => [
                        [
                            'icon' => ['fal', 'user-hard-hat'],
                            'name' => App('currentTenant')->stats->number_employees_state_working,
                            'href' => [
                                'route' => 'human_resources.employees.index',
                            ]
                        ],
                    ]
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
            'human_resources.dashboard' => [
                'route' => 'human_resources.dashboard',
                'name'  => __('Human resources'),
            ]
        ];
    }


}
