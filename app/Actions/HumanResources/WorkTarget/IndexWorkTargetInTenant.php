<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 07 Mar 2022 20:24:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\WorkTarget;


use App\Actions\HumanResources\ShowHumanResourcesDashboard;
use Carbon\Carbon;
use Lorisleiva\Actions\ActionRequest;


use function __;


/**
 * @property array $intervalTabs
 */
class IndexWorkTargetInTenant extends IndexWorkTarget
{

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("employees.attendance");
    }

    public function queryConditions($query)
    {



        return $query
            ->select($this->select)
            ->whereDate('date', '<=', Carbon::today());
    }

    public function asInertia()
    {
        $this->validateAttributes();

        return $this->getInertia();
    }


    public function prepareForValidation(ActionRequest $request): void
    {
        $this->select = array_merge(
            $this->select, [

                         ]
        );


        $request->merge(
            [
                'title'              => __('Timesheets'),
                'breadcrumbs'        => $this->getBreadcrumbs(),
                'sectionRoot'        => 'human_resources.timesheets.index',
                'tabRoute'           => 'human_resources.timesheets.interval',
                'tabRouteParameters' => []
            ]
        );

        $this->fillFromRequest($request);
        $this->intervalTabs=$this->getIntervalTabs('all');


    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowHumanResourcesDashboard())->getBreadcrumbs(),
            [
                'human_resources.timesheets.index' => [
                    'route'      => 'human_resources.timesheets.index',
                    'modelLabel' => [
                        'label' => __('timesheets')
                    ],
                ]
            ]
        );
    }


}
