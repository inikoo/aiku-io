<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 15 Dec 2021 03:19:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Guest;

use App\Actions\UI\WithInertia;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Models\System\Guest;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * @property Guest $guest
 * @property array $breadcrumbs
 * @property bool $canEdit
 * @property bool $canViewUsers
 */
class ShowGuest
{
    use AsAction;
    use WithInertia;

    public function handle(Guest $guest): Guest
    {
        return $guest;
    }

    #[Pure] public function jsonResponse(Guest $guest): EmployeeResource
    {
        return new EmployeeResource($guest);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("account.view");
    }

    public function asInertia(Guest $guest, array $attributes = []): Response
    {
        $this->set('guest', $guest)->fill($attributes);
        $this->validateAttributes();


        $actionIcons = [];


        if ($this->canEdit) {
            $actionIcons['account.guests.edit'] = [
                'routeParameters' => $this->guest->id,
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];
        }


        return Inertia::render(
            'Common/ShowModel',
            [
                'headerData' => [
                    'module'        => 'users',
                    'title'         => $this->guest->name,
                    'subTitle'      => $this->guest->nickname,
                    'titleTitle'    => __('Name'),
                    'subTitleTitle' => __('nickname'),
                    'breadcrumbs'   => $this->breadcrumbs,
                    'meta'          => [




                    ],
                    'actionIcons'   => $actionIcons,


                ],
                'model'      => $this->guest
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);

        $this->set('canEdit', $request->user()->can('account.edit'));
        $this->set('canViewUsers', $request->user()->can('account.view'));

        $this->set('breadcrumbs', $this->breadcrumbs());
    }

    private function breadcrumbs(): array
    {
        return array_merge(
            (new IndexGuest())->getBreadcrumbs(),
            [
                'shop' => [
                    'route'           => 'account.guests.show',
                    'routeParameters' => $this->guest->id,
                    'name'            => $this->guest->nickname,
                    'current'         => true
                ],
            ]
        );
    }
}
