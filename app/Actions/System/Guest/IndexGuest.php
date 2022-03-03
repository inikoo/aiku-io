<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 27 Jan 2022 19:20:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Guest;

use App\Actions\Account\Tenant\ShowTenant;
use App\Actions\UI\WithInertia;
use App\Http\Resources\System\GuestInertiaResource;
use App\Http\Resources\System\GuestResource;
use App\Models\HumanResources\Guest;
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
class IndexGuest
{
    use AsAction;
    use WithInertia;

    public function handle(): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('guests.name', 'LIKE', "%$value%")
                    ->orWhere('guests.nickname', 'LIKE', "%$value%");
            });
        });


        return QueryBuilder::for(Guest::class)
            ->defaultSort('-guests.id')
            ->select(['nickname', 'id', 'name', 'status'])
            ->allowedSorts(['nickname', 'name', 'status'])
            ->allowedFilters(['state', 'nickname', 'name', $globalSearch])
            ->paginate()
            ->withQueryString();
    }

    public function authorize(ActionRequest $request): bool
    {
        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('account.view')
            );
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        $guests = QueryBuilder::for(Guest::class)
            ->allowedFilters(['nickname', 'worker_number', 'state'])
            ->paginate();

        return GuestResource::collection($guests);
    }

    public function asInertia()
    {
        $this->validateAttributes();


        $actionIcons = [];


        if ($this->canEdit) {
            $actionIcons['account.guests.create'] = [
                'name' => __('Create guest'),
                'icon' => ['fal', 'plus']
            ];
        }


        return Inertia::render(
            'index-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData'     => ['module' => 'account', 'sectionRoot' => 'account.guests.index'],

                'headerData' => [
                    'module'      => 'account',
                    'title'       => $this->title,
                    'actionIcons' => $actionIcons,
                ],
                'dataTable'  => [
                    'records'  => GuestInertiaResource::collection($this->handle()),
                    'columns'  => [

                        [
                            'sort'  => 'status',
                            'label' => __('Status'),

                            'components' => [
                                [
                                    'type'     => 'icon',
                                    'resolver' => [
                                        'type' => 'boolean',

                                        'parameters' => [
                                            'indices' => 'status',
                                            'values'  => [
                                                [
                                                    'icon'  => 'check-circle',
                                                    'class' => 'text-green-600',
                                                    'title' => __('Active')
                                                ],
                                                [
                                                    'icon'   => 'times-circle',
                                                    'class'  => 'text-red-700',
                                                    'tittle' => __('Collaboration finished')
                                                ],
                                            ]
                                        ],


                                    ]
                                ]
                            ],


                        ],


                        [
                            'sort'       => 'nickname',
                            'label'      => __('Username'),
                            'components' => [
                                [
                                    'type'     => 'link',
                                    'resolver' => [
                                        'type'       => 'link',
                                        'parameters' => [
                                            'href'    => [
                                                'route'   => 'account.guests.show',
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
                    ],
                ]


            ]
        )->table(function (InertiaTable $table) {
            $table->addSearchRows(
                [
                    'guests.name'     => __('Name'),
                    'guests.nickname' => __('Nickname'),

                ]
            );
        });
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => __('Guests'),

            ]
        );
        $this->fillFromRequest($request);

        $this->set(
            'canEdit',
            ($request->user()->can('guests.edit'))
        );
    }

    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowTenant())->getBreadcrumbs(),
            [
                'account.guests.index' => [
                    'route'      => 'account.guests.index',
                    'modelLabel' => [
                        'label' => __('guests')
                    ],
                ],
            ]
        );
    }


}
