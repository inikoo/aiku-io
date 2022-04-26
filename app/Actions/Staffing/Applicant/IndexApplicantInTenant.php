<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 07 Apr 2022 14:49:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Staffing\Applicant;

use App\Actions\HumanResources\ShowHumanResourcesDashboard;
use App\Actions\Staffing\ShowStaffingDashboard;
use App\Actions\UI\WithInertia;
use App\Http\Resources\HumanResources\EmployeeInertiaResource;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Models\HumanResources\Employee;
use App\Models\Marketing\Shop;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;


/**
 * @property \Illuminate\Support\Collection $allowed_recruiters
 * @property bool $canViewAll
 */
class IndexApplicantInTenant extends IndexApplicant
{


    public function authorize(ActionRequest $request): bool
    {
        $canView = $request->user()->hasPermissionTo("staffing.applicants.view");
        $this->canViewAll=$canView;
        if (!$canView) {
            $this->allowed_recruiters = Employee::withTrashed()->get()->pluck('id')->filter(function ($employeeId) use ($request) {
                $request->user()->can("staffing.applicants.$employeeId.view");
            });
            $canView = $this->allowed_recruiters->count()>0;
        }

        return $canView;
    }

    public function queryConditions($query){
       // $select=array_merge(array_diff( $this->select, ['id','name'] ), ['customers.id as id', 'shops.code as shop_code','customers.name as name']);

        $select=$this->select;
        if(!$this->canViewAll){
            $query->whereIn('recruiter_id',$this->allowed_recruiters->all()) ;
        }
        $query->select($select)->leftJoin('recruiters','applicants.recruiter_id','=','recruiters.id');

        return $query;
    }

    public function asInertia()
    {
        $this->set('canViewAll',false);
        $this->set('allowed_recruiters',[]);
        $this->validateAttributes();

        return $this->getInertia();
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => __('Applicants'),
                'breadcrumbs' => $this->getBreadcrumbs(),
                'sectionRoot' => 'staffing.dashboard',
                'module' => 'staffing'
            ]
        );
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowStaffingDashboard())->getBreadcrumbs(),
            [
                'staffing.applicants.index' => [
                    'route' => 'staffing.applicants.index',
                    'modelLabel' => [
                        'label' => __('applicants')
                    ],
                ],
            ]
        );
    }

}
