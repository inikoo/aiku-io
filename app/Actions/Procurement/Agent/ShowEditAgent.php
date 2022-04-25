<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Feb 2022 01:55:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Agent;

use App\Actions\Procurement\ShowProcurementDashboard;
use App\Actions\UI\WithInertia;
use App\Models\Procurement\Agent;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Agent $agent
 * @property array $breadcrumbs
 */
class ShowEditAgent
{
    use AsAction;
    use WithInertia;

    public function handle()
    {
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("procurement.agents.edit");
    }

    public function asInertia(Agent $agent, array $attributes = []): Response
    {
        $this->set('agent', $agent)->fill($attributes);
        $this->validateAttributes();

        $blueprint = [];

        $blueprint[] = [
            'title'    => __('Agent Id'),
            'subtitle' => '',
            'fields'   => [

                'code' => [
                    'type'  => 'input',
                    'label' => __('Code'),
                    'value' => $this->agent->code
                ],
                'name' => [
                    'type'  => 'input',
                    'label' => __('Name'),
                    'value' => $this->agent->name
                ],
            ]
        ];

        $blueprint[] = [
            'title'    => __('Contact information'),
            'subtitle' => '',
            'fields'   => [

                'contact_name' => [
                    'type'  => 'input',
                    'label' => __('Contact name'),
                    'value' => $this->agent->contact_name
                ],
                'company_name' => [
                    'type'  => 'input',
                    'label' => __('Company name'),
                    'value' => $this->agent->company_name
                ],
                'email'        => [
                    'type'  => 'input',
                    'label' => __('Email'),
                    'value' => $this->agent->email
                ],
                'phone'        => [
                    'type'  => 'phone',
                    'label' => __('Phone'),
                    'value' => $this->agent->phone
                ],
            ]
        ];

        return Inertia::render(
            'edit-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($this->agent),
                'headerData'  => [

                    'title' => __('Editing').': '.$this->agent->name,


                    'actionIcons' => [
                        [
                            'route'           => 'procurement.agents.show',
                            'routeParameters' => $this->agent->id,
                            'name'            => __('Exit edit'),
                            'icon'            => ['fal', 'portal-exit']
                        ],
                    ],


                ],
                'agent'       => $this->agent,
                'formData'    => [
                    'blueprint' => $blueprint,
                    'args'      => [
                        'postURL' => "/procurement/agents/{$this->agent->id}",
                    ]

                ],
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(Agent $agent): array
    {
        return array_merge(
            (new ShowProcurementDashboard())->getBreadcrumbs(),
            [
                'procurement.agents.show' => [
                    'route'           => 'procurement.agents.show',
                    'routeParameters' => $agent->id,
                    'name'            => $agent->code,
                    'index'           => [
                        'route'   => 'procurement.agents.index',
                        'overlay' => __('Agents index')
                    ],
                    'modelLabel'      => [
                        'label' => __('agent')
                    ],

                ],
            ]
        );
    }


}
