<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 08 Mar 2022 22:01:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\WorkTarget;


use App\Enums\Interval;
use Carbon\Carbon;
use Lorisleiva\Actions\ActionRequest;


use function __;


/**
 * @property \App\Enums\Interval $interval
 */
class IndexWorkTargetInTenantWithInterval extends IndexWorkTargetInTenant
{



    public function queryConditions($query)
    {
        return $query
            ->select($this->select)
            ->when($this->interval, function ($query, $interval) {
                switch ($interval->value){
                    case 'today':
                        $query->where('date', Carbon::today()->format('Y-m-d'));
                        break;
                    case 'yesterday':
                        $query->where('date', Carbon::yesterday()->format('Y-m-d'));
                }


            });
    }

    public function asInertiaWithInterval(Interval $interval)
    {
        $this->interval=$interval;
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

        $this->fillFromRequest($request);
        $this->intervalTabs=$this->getIntervalTabs($this->interval->value);

    }




}
