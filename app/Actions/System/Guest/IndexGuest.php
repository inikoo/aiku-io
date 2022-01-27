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
use App\Models\System\Guest;
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
            ->select(['nickname', 'id','name','status'])
            ->with([
                       'contact' => function ($query) {
                           $query->select('contactable_id', 'contactable_type');
                       }
                   ])
            ->allowedSorts(['nickname', 'name','status'])
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
            'Common/IndexModel',
            [
                'headerData' => [
                    'module'      => 'account',
                    'title'       => $this->title,
                    'breadcrumbs' => $this->breadcrumbs,
                    'actionIcons' => $actionIcons,
                ],
                'dataTable'  => [
                    'records' => GuestInertiaResource::collection($this->handle()),
                    'columns' => [
                        'status' => [
                            'sort'  => 'status',
                            'label' => __('Status'),
                            'toggle'=>[
                                [
                                    'icon'=>'check-circle',
                                    'iconClass'=>'text-green-600',
                                    'cellTitle'=>__('Active')
                                ],
                                [
                                    'icon'=>'times-circle',
                                    'iconClass'=>'text-red-700',
                                    'cellTitle'=>__('Inactive')
                                ],
                            ]
                        ],
                        'nickname'      => [
                            'sort'  => 'nickname',
                            'label' => __('Nickname'),
                            'href'  => [
                                'route'  => 'account.guests.show',
                                'column' => 'id'
                            ],
                        ],

                        'name'          => [
                            'sort'         => 'name',
                            'label'        => __('Name')
                        ],
                    ]
                ]


            ]
        )->table(function (InertiaTable $table) {
            $table->addSearchRows(
                [
                    'guests.name'      => __('Name'),
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

        $this->set('breadcrumbs', $this->breadcrumbs());
    }

    private function breadcrumbs(): array
    {
        return array_merge(
            (new ShowTenant())->getBreadcrumbs(),
            [
                'account.guests.index' => [
                    'route'   => 'account.guests.index',
                    'name'    => $this->title,
                    'current' => false
                ],
            ]
        );
    }

    public function getBreadcrumbs(): array
    {
        $this->validateAttributes();

        return $this->breadcrumbs();
    }


}
