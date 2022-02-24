<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 24 Sep 2021 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\User;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

/**
 * @property User $user
 */
class UpdateUser
{
    use AsAction;
    use WithUpdate;
    use WithAttributes;

    public function handle(User $user, array $modelData): ActionResult
    {

        $res = new ActionResult();

        $user->update(Arr::except($modelData, ['data', 'settings']));
        $user->update($this->extractJson($modelData, ['data', 'settings']));
        $res->changes = array_merge($res->changes, $user->getChanges());

        $res->model    = $user;
        $res->model_id = $user->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {

        if ($this->user->userable_type == 'Tenant') {
            return false;
        }

        return $request->user()->tokenCan('root') || $request->user()->tokenCan('system:edit') ||
            $request->user()->hasPermissionTo("account.users.edit")
            ;
    }

    public function rules(): array
    {
        return [
            'username' => 'sometimes|required|alpha_dash|unique:App\Models\Auth\User,username',
            'password' => ['sometimes', 'required', Password::min(8)->uncompromised()],
            'status'   => 'sometimes|required|boolean'
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if ($request->exists('username')) {
            $request->merge(['username' => strtolower($request->get('username'))]);
        }
    }

    public function asController(User $user, ActionRequest $request): ActionResult
    {
        $request->validate();

        return $this->handle(
            $user,
            $request->only('username', 'password', 'status'),
        );
    }

    public function asInertia(User $user, Request $request): RedirectResponse
    {

        $this->set('user', $user);

        $this->fillFromRequest($request);
        $this->validateAttributes();

        //todo: deal with errors and no changes
        $res=$this->handle(
            $user,
            $this->only(['username', 'password', 'status']),
        );

        return Redirect::route('account.users.edit', $user->id);
    }
}
