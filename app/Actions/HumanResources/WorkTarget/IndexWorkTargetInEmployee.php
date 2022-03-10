<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 07 Mar 2022 23:13:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\WorkTarget;


use App\Actions\HumanResources\Employee\ShowEmployee;
use App\Models\HumanResources\Employee;
use Carbon\Carbon;
use Lorisleiva\Actions\ActionRequest;


use function __;


/**
 * @property Employee $employee
 */
class IndexWorkTargetInEmployee extends IndexWorkTarget
{

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("employees.attendance");
    }

    public function queryConditions($query)
    {
        return $query
            ->select($this->select)
            ->where('employee_id', $this->employee->id)
            ->where('date', '<=', Carbon::today());
    }

    public function asInertia(Employee $employee)
    {
        $this->employee = $employee;
        $this->validateAttributes();

        return $this->getInertia();
    }


    public function prepareForValidation(ActionRequest $request): void
    {
        $this->select = array_merge(
            $this->select, [

                         ]
        );

        unset($this->columns['employee_name']);


        $request->merge(
            [
                'title'       => __('Timesheets'),
                'breadcrumbs' => $this->getBreadcrumbs($this->employee),
                'sectionRoot' => 'human_resources.employees.index',
            ]
        );
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(Employee $employee): array
    {
        return array_merge(
            (new ShowEmployee())->getBreadcrumbs($employee),
            [
                'human_resources.employees.show.timesheets.index' => [
                    'route'           => 'human_resources.employees.show.timesheets.index',
                    'routeParameters' => [$this->employee->id],
                    'modelLabel'      => [
                        'label' => __('timesheets')
                    ],
                ]
            ]
        );
    }


}
