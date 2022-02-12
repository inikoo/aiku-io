<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 12 Feb 2022 00:18:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Reports;


use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class ShowReportsDashboard
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return
            $request->user()->hasPermissionTo("financials.accounts_receivable.view") ||
            $request->user()->hasPermissionTo("financials.accounts_payable.view")
            ;
    }


    public function asInertia(array $attributes = []): Response
    {
        $this->fill($attributes);

        $this->validateAttributes();


        return Inertia::render(
            'show-dashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData' => ['account' => 'reports'],

                'headerData' => [
                    'title'       => __('Reports'),


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
            'warehouse' => [
                'route'           => 'reports.dashboard',
                'name'            => __('Reports'),
                'current'         => false
            ]
        ];
    }


}
