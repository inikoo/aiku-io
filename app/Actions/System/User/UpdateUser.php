<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 24 Sep 2021 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\User;

use App\Actions\Migrations\MigrationResult;
use App\Models\System\User;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUser
{
    use AsAction;

    public function handle(User $user,array $data): MigrationResult
    {
        $res = new MigrationResult();

        $user->update($data);
        $res->changes = array_merge($res->changes, $user->getChanges());

        $res->model    = $user;
        $res->model_id = $user->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('system:edit');
    }

    public function rules(): array
    {
        return [
            'username' => 'sometimes|required|string|unique:users',
            'password' => ['sometimes','required', 'confirmed', Password::min(8)->uncompromised()],
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if($request->exists('username')){
            $request->merge(['username' => strtolower($request->get('username'))]);
        }
    }

    public function asController(User $user, ActionRequest $request): MigrationResult
    {

        return $this->handle(
            $user,
            $request->only('username', 'password'),
        );
    }
}
