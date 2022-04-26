<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 07 Apr 2022 16:34:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property array $breadcrumbs
 */
class ShowCreateEmployee
{
    use AsAction;
    use WithInertia;
    use WithJobPositionBlueprint;

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
            'title'    => __('Personal information'),
            'subtitle' => '',
            'fields'   => [

                'name'          => [
                    'type'  => 'input',
                    'label' => __('Name'),
                    'value' => ''
                ],

                'identity_document_number'          => [
                    'type'  => 'input',
                    'label' => __('Identity document number'),
                    'value' => ''
                ],
                'date_of_birth' => [
                    'type'  => 'date',
                    'label' => __('Date of birth'),
                    'value' => ''
                ],
            ]
        ];

        $blueprint[] = [
            'title'    => __('Employee data'),
            'subtitle' => '',
            'fields'   => [

                'worker_number'          => [
                    'type'  => 'input',
                    'label' => __('Worker number'),
                    'value' => ''
                ],
                'nickname'      => [
                    'type'  => 'input',
                    'label' => __('Nickname'),
                    'value' => ''
                ],
                'create_user'   => [
                    'type'  => 'toggle',
                    'label' => __('Create user'),
                    'value' => true
                ],
                'job_title'     => [
                    'type'  => 'input',
                    'label' => __('Job title'),
                    'value' => ''
                ],
                'job_positions' => [
                    'type'    => 'job-positions',
                    'label'   => __('Job positions'),
                    'value'   => [
                        'positions' => [],
                        'scopes' => [],
                    ],
                    'options' => [
                        'blueprint' => $this->getJobPositionBlueprint()
                    ]
                ],
            ]
        ];

        return Inertia::render(
            'create-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData'     => ['module' => 'human_resources', 'sectionRoot' => 'human_resources.employees.index'],
                'headerData'  => [
                    'title' => __('New employee'),

                    'actionIcons' => [

                        [
                            'name'  => __('Cancel'),
                            'icon'  => ['fal', 'portal-exit'],
                            'route' => 'human_resources.employees.index'
                        ],
                    ],


                ],
                'formData'    => [
                    'blueprint' => $blueprint,
                    'postURL'   => "/human_resources/employees/create",
                    'cancel'    => [
                        'route' => 'human_resources.employees.index'
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
        return (new IndexEmployee())->getBreadcrumbs();
    }


}
