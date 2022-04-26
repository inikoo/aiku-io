<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 19 Jan 2022 05:09:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Staffing\Applicant;

use App\Actions\UI\WithInertia;
use App\Models\Staffing\Applicant;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Applicant $applicant
 * @property array $breadcrumbs
 */
class ShowEditApplicant
{
    use AsAction;
    use WithInertia;

    public function handle()
    {
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("applicants.edit");
    }

    public function asInertia(Applicant $applicant, array $attributes = []): Response
    {
        $this->set('applicant', $applicant)->fill($attributes);
        $this->validateAttributes();

        $blueprint = [];

        $blueprint[] = [
            'title'    => __('Applicant data'),
            'subtitle' => '',
            'fields'   => [

                'name'     => [
                    'type'  => 'input',
                    'label' => __('Worker number'),
                    'value' => $this->applicant->worker_number
                ],
                'nickname' => [
                    'type'  => 'input',
                    'label' => __('Nickname'),
                    'value' => $this->applicant->nickname
                ],
            ]
        ];

        $blueprint[] = [
            'title'    => __('Personal information'),
            'subtitle' => '',
            'fields'   => [

                'name' => [
                    'type'  => 'input',
                    'label' => __('Name'),
                    'value' => $this->applicant->name
                ],
            ]
        ];

        return Inertia::render(
            'edit-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($this->applicant),
                'navData'     => ['module' => 'human_resources', 'sectionRoot' => 'human_resources.applicants.index'],
                'headerData'  => [
                    'title' => __('Editing').': '.$this->applicant->name,

                    'actionIcons' => [

                        [
                            'route'           => 'human_resources.applicants.show',
                            'routeParameters' => $this->applicant->id,
                            'name'            => __('Exit edit'),
                            'icon'            => ['fal', 'portal-exit'],

                        ]
                    ],


                ],
                'applicant'   => $this->applicant,
                'formData'    => [
                    'blueprint' => $blueprint,
                    'args'      => [
                        'postURL' => "/human_resources/applicants/{$this->applicant->id}",
                    ]

                ],
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(Applicant $applicant): array
    {
        return (new ShowApplicant())->getBreadcrumbs($applicant);
    }


}
