<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 20 Oct 2021 16:32:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Account\Tenant;

use App\Actions\UI\WithInertia;
use App\Http\Resources\Account\TenantResource;
use App\Models\Account\Tenant;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * @property string $module
 * @property string $page
 * @property string $title
 * @property Tenant $tenant
 * @property array $breadcrumbs
 */
class ShowTenant
{
    use AsAction;
    use WithInertia;

    public function __construct()
    {
        $this->module = 'tenant';
    }

    public function handle()
    {
    }

    #[Pure] public function jsonResponse(Tenant $tenant): TenantResource
    {
        return new TenantResource($tenant);
    }


    public function asInertia(array $attributes = []): Response
    {


        $this->set('tenant',app('currentTenant'))->fill($attributes);
        $this->validateAttributes();

        return Inertia::render(
            $this->page,
            [
                'headerData' => [
                    'module'      => $this->module,
                    'title'       => $this->title,
                    'breadcrumbs' => $this->breadcrumbs,
                    'actionIcons' => [
                        'tenant.logbook'  => [
                            'name' => 'History',
                            'icon' => ['fal', 'history']
                        ],
                        'tenant.settings' => [
                            'name' => 'Settings',
                            'icon' => ['fal', 'sliders-h-square']
                        ],
                    ]
                ]
            ]
        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'page'  => 'Tenant/Tenant',
                'title' => $this->tenant->name,

            ]
        );
        $this->fillFromRequest($request);

        $this->set('breadcrumbs', $this->breadcrumbs());
    }


    private function breadcrumbs(): array
    {
        return [
            'tenant' => [
                'route'   => 'tenant.show',
                'name'    => _('Account'),
                'current' => false
            ],
        ];
    }

    public function getBreadcrumbs(): array
    {


        return $this->breadcrumbs();
    }

}
