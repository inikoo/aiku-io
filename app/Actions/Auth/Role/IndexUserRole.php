<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 29 Mar 2022 00:42:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Auth\Role;


use App\Actions\System\Profile\ShowProfile;
use App\Actions\UI\WithInertia;
use App\Http\Resources\System\RoleInertiaResource;
use App\Http\Resources\System\RoleResource;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;

use function __;

/**
 * @property User $user
 * @property string $routeName
 * @property string $title
 * @property array $breadcrumbs
 */
class IndexUserRole
{
    use AsAction;
    use WithInertia;

    public function handle(): Collection
    {

      return $this->user->getAllPermissions();


    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root')
            || match ($this->routeName) {
                'profile.roles.index' => true
            };
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        return RoleResource::collection($this->handle());
    }

    public function asInertia(User $user,Request $request)
    {
        $this->set('routeName', $request->route()->getName());
        $this->set('routeParameters', $request->route()->parameters());
        $this->set('user', $user);



        $this->validateAttributes();


        return Inertia::render(
            'index-model',
            [
                'headerData' => [
                    'title'       => $this->title,
                    'breadcrumbs' => $this->breadcrumbs,

                ],

                'dataTable' => [
                    'records' => RoleInertiaResource::collection($this->handle()),
                    'columns' => [

                        'name'        => [
                            'sort'  => 'name',
                            'label' => __('Name')
                        ],
                        'permissions' => [
                            'bullet-list' => true,
                            'label'       => __('Permissions')
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
                'title' => match ($this->routeName) {
                    'profile.roles.index' => __('My roles'),
                    default => __('Roles')
                }

            ]
        );
        $this->fillFromRequest($request);

        $this->set('breadcrumbs', $this->breadcrumbs());
    }

    private function breadcrumbs(): array
    {
        return match ($this->routeName) {
            'profile.roles.index' => array_merge(
                (new ShowProfile())->getBreadcrumbs(),
                [
                    $this->routeName => [
                        'route' => $this->routeName,
                        'current' => false
                    ],
                ]
            ),
            default => [],
        };
    }

    public function getBreadcrumbs(): array
    {
        $this->validateAttributes();

        return $this->breadcrumbs();
    }
}
