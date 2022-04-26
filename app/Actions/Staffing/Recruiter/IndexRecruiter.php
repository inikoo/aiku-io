<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 07 Apr 2022 15:45:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Staffing\Recruiter;

use App\Actions\Staffing\ShowStaffingDashboard;
use App\Actions\UI\WithInertia;
use App\Http\Resources\Staffing\RecruiterInertiaResource;
use App\Http\Resources\Staffing\RecruiterResource;
use App\Models\Staffing\Recruiter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @property array $breadcrumbs
 * @property bool $canEdit
 * @property string $title
 */
class IndexRecruiter
{
    use AsAction;
    use WithInertia;

    public function handle(): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('recruiters.name', 'LIKE', "%$value%")
                    ->orWhere('recruiters.nickname', 'LIKE', "%$value%");
            });
        });


        return QueryBuilder::for(Recruiter::class)
            ->defaultSort('-recruiters.id')
            ->select(['nickname', 'id', 'name'])
            ->allowedSorts(['nickname', 'name'])
            ->allowedFilters([$globalSearch])
            ->paginate()
            ->withQueryString();
    }

    public function authorize(ActionRequest $request): bool
    {
        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('staffing.recruiters.view')
            );
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        $recruiters = QueryBuilder::for(Recruiter::class)
            ->allowedFilters(['nickname', 'state'])
            ->paginate();

        return RecruiterResource::collection($recruiters);
    }

    public function asInertia()
    {
        $this->validateAttributes();


        $actionIcons = [];


        if ($this->canEdit) {
            $actionIcons[] = [
                'route' => 'staffing.recruiters.create',
                'name'  => __('Create recruiter'),
                'icon'  => ['fal', 'plus']
            ];
        }


        return Inertia::render(
            'index-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData'     => ['module' => 'staffing', 'sectionRoot' => 'staffing.recruiters.index'],

                'headerData' => [
                    'title' => $this->title,

                    'actionIcons' => $actionIcons,
                ],
                'dataTable'  => [
                    'records' => RecruiterInertiaResource::collection($this->handle()),
                    'columns' => [


                        [
                            'sort'       => 'code',
                            'label'      => __('Code'),
                            'components' => [
                                [
                                    'type'     => 'link',
                                    'resolver' => [
                                        'type' => 'link',

                                        'parameters' => [
                                            'href'    => [
                                                'route'   => 'staffing.recruiters.show',
                                                'indices' => 'id'
                                            ],
                                            'indices' => 'nickname'
                                        ],


                                    ]
                                ]
                            ],

                        ],


                        [
                            'sort'     => 'name',
                            'label'    => __('Name'),
                            'resolver' => 'name'
                        ],
                    ]
                ]


            ]
        )->table(function (InertiaTable $table) {
            $table->addSearchRows(
                [
                    'contacts.name'       => __('Name'),
                    'recruiters.nickname' => __('Nickname'),

                ]
            );
        });
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => __('Recruiters'),

            ]
        );
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowStaffingDashboard())->getBreadcrumbs(),
            [
                'staffing.recruiters.index' => [
                    'route'      => 'staffing.recruiters.index',
                    'modelLabel' => [
                        'label' => __('recruiters')
                    ],
                ],
            ]
        );
    }


}
