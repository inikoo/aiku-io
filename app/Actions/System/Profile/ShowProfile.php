<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 22 Jan 2022 15:31:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Profile;

use App\Actions\UI\WithInertia;
use App\Http\Resources\System\UserResource;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property User $user
 * @property array $breadcrumbs
 * @property bool $canEdit
 */
class ShowProfile
{
    use AsAction;
    use WithInertia;


    public function handle(User $user): User
    {
        return $user;
    }

    #[Pure] public function jsonResponse(User $user): UserResource
    {
        return new UserResource($user);
    }



    public function asInertia(Request $request, array $attributes = []): Response
    {
        $this->set('user', $request->user())->fill($attributes);
        $this->validateAttributes();


        $actionIcons = [];

        $actionIcons['profile.edit'] = [
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];


        return Inertia::render(
            'show-model',
            [
                'headerData' => [
                    'title'       => __('My profile'),
                    'breadcrumbs' => $this->breadcrumbs,

                    'actionIcons' => $actionIcons,


                ],
                'user'       => $this->user
            ]

        );
    }


    public function prepareForValidation(ActionRequest $request): void
    {

        $this->fillFromRequest($request);


        $this->set('breadcrumbs', $this->getBreadcrumbs());
    }

    public function getBreadcrumbs(): array
    {
        return [
                'shop' => [
                    'route'           => 'profile.show',
                    'name'            =>  __('Profile'),
                    'current'         => true
                ],
            ];

    }


}
