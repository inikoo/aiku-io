<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 23 Apr 2022 14:17:53 Plane Instanbul-Malaga (Near Brancaleone IT)
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Supplier;

use App\Actions\UI\WithInertia;
use App\Http\Controllers\Assets\CountrySelectOptionsController;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property array $breadcrumbs
 */
class ShowCreateSupplierInTenant
{
    use AsAction;
    use WithInertia;

    public function handle(): void
    {
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("employees.edit");
    }

    public function asInertia(): Response
    {
        $this->validateAttributes();

        $blueprint = [];

        $blueprint[] = [
            'title'    => __('Supplier Id'),
            'subtitle' => '',
            'fields'   => [

                'code' => [
                    'type'  => 'input',
                    'label' => __('Code'),
                    'value' => 'a'
                ],
                'name' => [
                    'type'  => 'input',
                    'label' => __('Name'),
                    'value' => 'a'
                ],

            ]
        ];

        $blueprint[] = [
            'title'    => __('Contact details'),
            'subtitle' => '',
            'fields'   => [

                'company_name' => [
                    'type'  => 'input',
                    'label' => __('Company name'),
                    'value' => 'x'
                ],
                'contact_name' => [
                    'type'  => 'input',
                    'label' => __('Contact name'),
                    'value' => 'x'
                ],
                'email'        => [
                    'type'    => 'input',
                    'label'   => __('Email'),
                    'value'   => 'raul@caca.com',
                    'options' => ['type' => 'email']
                ],
                'phone'        => [
                    'type'    => 'phone',
                    'label'   => __('Phone'),
                    'value'   => '',
                    'options'=>[
                        'defaultCountry'=>app('currentTenant')->country->code
                    ]
                ],
                'address'        => [
                    'type'    => 'address',
                    'label'   => __('Address'),
                    'value'   => ['country_id' => app('currentTenant')->country_id],
                    'options'=>[
                        'countriesAddressData' => (new CountrySelectOptionsController())->getCountriesAddressData(),

                    ]
                ],
            ]
        ];


        return Inertia::render(
            'create-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData'     => ['module' => 'procurement', 'sectionRoot' => 'procurement.suppliers.index'],
                'headerData'  => [
                    'title' => __('New supplier'),

                    'actionIcons' => [

                        [
                            'name'  => __('Cancel'),
                            'icon'  => ['fal', 'portal-exit'],
                            'route' => 'procurement.suppliers.index'
                        ],
                    ],


                ],
                'formData'    => [
                    'blueprint' => $blueprint,
                    'postURL'   => route('procurement.suppliers.store'),
                    'cancel'    => [
                        'route' => 'procurement.suppliers.index'
                    ]

                ],


            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(): array
    {
        return (new IndexSupplierInTenant())->getBreadcrumbs();
    }


}
