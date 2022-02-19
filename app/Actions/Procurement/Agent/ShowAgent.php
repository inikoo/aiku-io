<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 04:20:24 Malaysia Time, Kuala Lumpur, Malaysia
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
 */
class ShowAgent
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("procurement.view") || $request->user()->hasPermissionTo("procurement.agent.view.{$this->agent->id}");
    }


    public function asInertia(Agent $agent, array $attributes = []): Response
    {
        $this->set('agent', $agent)->fill($attributes);

        $this->validateAttributes();

        $actionIcons = [];
        if ($this->get('canEdit')) {
            $actionIcons['procurement.agents.edit'] = [
                'routeParameters' => $this->agent->id,
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];
        }

        return Inertia::render(
            'show-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($this->agent),
                'headerData' => [
                    'title'       => $agent->name,
                    'actionIcons' => $actionIcons,
                    'meta'=>[
                        [
                            'icon'=>['fal','hand-holding-box'],
                            'name' => $this->agent->stats->number_suppliers.'  '.__('suppliers'),
                            'href'=>[
                                'route'=>'procurement.agents.show.suppliers.index',
                                'routeParameters'=>$this->agent->id
                            ]
                        ]
                    ]

                ],
                'model'      => $agent
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
        $this->set('canEdit', $request->user()->hasPermissionTo("procurement.edit") || $request->user()->hasPermissionTo("procurement.agent.edit.{$this->agent->id}"));
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
                    'index'=>[
                        'route'   => 'procurement.agents.index',
                        'overlay' => __('Agents index')
                    ],
                    'modelLabel'=>[
                        'label'=>__('agent')
                    ],

                ],
            ]
        );
    }


}
