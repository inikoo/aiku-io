<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 29 Mar 2022 00:42:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Auth\User;


use App\Models\Account\Tenant;
use App\Models\Auth\User;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\Guest;
use App\Models\Procurement\Agent;
use App\Models\Procurement\Supplier;
use App\Models\Utils\ActionResult;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

use function app;
use function class_basename;

class StoreUser
{
    use AsAction;

    public function handle(Employee|Tenant|Agent|Supplier|Guest $userable, array $userData): ActionResult
    {
        $res = new ActionResult();

        $userData['language_id'] = $userData['language_id'] ?? app('currentTenant')->language_id;
        $userData['timezone_id'] = $userData['timezone_id'] ?? app('currentTenant')->timezone_id;
        $userData['name']        = match (class_basename($userable::class)) {
            'Tenant' => 'Admin',
            default => $userable->name
        };

        /** @var User $user */


        $userData['userable_type']=class_basename($userable::class);
        $userData['userable_id']=$userable->id;


        $user=app('currentTenant')->users()->create($userData);

        $user->stats()->create([]);



        $res->model    = $user;
        $res->model_id = $user->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('system:edit');
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|unique:App\Models\Auth\User,username',
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
        return $this->handle(
            $userable,
            $request->only('username', 'password'),
        );
    }


}
