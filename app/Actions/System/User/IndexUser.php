<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 20 Oct 2021 16:29:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\User;


use App\Actions\Account\Tenant\ShowTenant;
use App\Actions\UI\WithInertia;
use App\Http\Resources\System\UserResource;
use App\Models\System\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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
            ->allowedSorts(['username'])
            ->allowedFilters(['username', $globalSearch])
            ->paginate()
            ->withQueryString();
    }

    public function authorize(ActionRequest $request): bool
    {
        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('users.view')
            );


    }

     public function jsonResponse():AnonymousResourceCollection
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters(['username', 'status'])
            ->paginate();
        return  UserResource::collection($users);
    }

    public function asInertia()
    {

        $this->set('module', 'tenant');
        $this->validateAttributes();


        return Inertia::render(
            $this->page,
            [
                'headerData' => [
                    'module'      => 'tenant',
                    'title'       => $this->title,
                    'breadcrumbs' => $this->breadcrumbs,

                ],
                'users'      => $this->handle(),


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
                'page'  => 'Tenant/Users',
                'title' => __('Users'),

            ]
        );
        $this->fillFromRequest($request);

        $this->set('breadcrumbs',$this->breadcrumbs());


    }


    private function breadcrumbs(): array
    {

        return array_merge(
            (new ShowTenant())->getBreadcrumbs(),
            [
                'tenant.users.index' => [
                    'route'   => 'tenant.users.index',
                    'name'    => $this->title,
                    'current' => false
                ],
            ]
        );


    }

    public function getBreadcrumbs($module): array
    {
        $this->set('module', $module);
        $this->validateAttributes();
        return $this->breadcrumbs();

    }


}
