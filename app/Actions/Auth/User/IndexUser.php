<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 29 Mar 2022 00:42:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Auth\User;


use App\Actions\Account\Tenant\ShowTenant;
use App\Actions\UI\WithInertia;
use App\Http\Resources\System\UserInertiaResource;
use App\Http\Resources\System\UserResource;
use App\Models\Auth\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

use function __;
use function app;

/**
 * @property string $module
 * @property string $page
 * @property string $title
 * @property array $breadcrumbs
 */
class IndexUser
{
    use AsAction;
    use WithInertia;


    public function handle(): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('username', 'LIKE', "%$value%");
            });
        });

        return QueryBuilder::for(User::class)
            ->allowedSorts(['username', 'name', 'status', 'userable_type'])
            ->where('tenant_id', App('currentTenant')->id)
            ->defaultSort('username')
            ->allowedFilters(['username', $globalSearch])
            ->paginate()
            ->withQueryString();
    }

    public function authorize(ActionRequest $request): bool
    {
        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('account.users.view')
            );
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters(['username', 'status'])
            ->paginate();

        return UserResource::collection($users);
    }

    public function asInertia()
    {
        $this->set('module', 'tenant');
        $this->validateAttributes();


        return Inertia::render(
            'index-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData'     => ['module' => 'account',  'sectionRoot' => 'account.users.index'],

                'headerData' => [
                    'title'       => $this->title,

                ],

                'dataTable' => [
                    'records' => UserInertiaResource::collection($this->handle()),
                    'columns' => [
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
                                                    'tittle' => __('Blocked')
                                                ],
                                            ]
                                        ],


                                    ]
                                ]
                            ],


                        ],

                        [
                            'sort'       => 'username',
                            'label'      => __('Username'),
                            'components' => [
                                [
                                    'type'     => 'link',
                                    'resolver' => [
                                        'type' => 'link',

                                        'parameters' => [
                                            'href'    => [
                                                'route'   => 'account.users.show',
                                                'indices' => 'id'
                                            ],
                                            'indices' => 'username'
                                        ],


                                    ]
                                ]
                            ],

                        ],
                        [
                            'sort'     => 'userable_type',
                            'label'    => __('Type'),
                            'resolver' => 'userable_type'
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

                ]
            );
        });
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => __('Users'),

            ]
        );
        $this->fillFromRequest($request);

    }

    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowTenant())->getBreadcrumbs(),
            [
                'account.users.index' => [
                    'route'   => 'account.users.index',
                    'modelLabel'=>[
                        'label'=>__('users')
                    ],
                ],
            ]
        );
    }




}
