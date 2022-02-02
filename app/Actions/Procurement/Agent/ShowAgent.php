<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 04:20:24 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Agent;


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
            'Common/ShowModel',
            [
                'headerData' => [
                    'module'      => 'agents',
                    'title'       => $agent->name,
                    'breadcrumbs' => $this->getBreadcrumbs($this->agent),
                    'actionIcons' => $actionIcons,

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
            (new IndexAgent())->getBreadcrumbs(),
            [
                'agents.show' => [
                    'route'           => 'procurement.agents.show',
                    'routeParameters' => $agent->id,
                    'name'            => $agent->code,
                    'model'           => [
                        'label' => __('Agent'),
                        'icon'  => ['fal', 'user-secret'],
                    ],

                ],
            ]
        );
    }


}
