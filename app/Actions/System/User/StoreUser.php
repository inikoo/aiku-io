<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 24 Sep 2021 14:38:59 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\User;


use App\Models\Utils\ActionResult;
use App\Models\Account\Tenant;
use App\Models\Buying\Agent;
use App\Models\Buying\Supplier;
use App\Models\HumanResources\Employee;
use App\Models\System\Guest;
use App\Models\System\User;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreUser
{
    use AsAction;

    public function handle(Employee|Tenant|Agent|Supplier|Guest $userable, array $userData, array $roles = []): ActionResult
    {
        $res  = new ActionResult();

        $userData['language_id'] = $userData['language_id'] ?? app('currentTenant')->language_id;
        $userData['timezone_id'] = $userData['timezone_id'] ?? app('currentTenant')->timezone_id;
        /** @var User $user */
        $user = $userable->user()->create($userData);
        $user->stats()->create([]);

        $user->syncRoles($roles);


        $res->model    = $user;
        $res->model_id = $user->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;        }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('system:edit');
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)->uncompromised()],
        ];
    }


    public function prepareForValidation(ActionRequest $request): void
    {
        if ($request->exists('username')) {
            $request->merge(['username' => strtolower($request->get('username'))]);
        }
    }

    public function asController(Employee|Tenant $userable, ActionRequest $request): ActionResult
    {
        $roles = [];


        return $this->handle(
            $userable,
            $request->only('username', 'password'),
            $roles
        );
    }


}
