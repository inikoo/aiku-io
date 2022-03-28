<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 29 Mar 2022 00:42:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Guest;

use App\Actions\Auth\User\UpdateUser;
use App\Actions\WithUpdate;
use App\Models\HumanResources\Guest;
use App\Models\Utils\ActionResult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateGuest
{
    use AsAction;
    use WithUpdate;
    use WithAttributes;

    public function handle(Guest $guest, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $guest->update($modelData);

        $guest->update(Arr::except($modelData, ['data']));
        $guest->update($this->extractJson($modelData));

        $res->changes = $guest->getChanges();

        $res->model = $guest;

        $res->model_id = $guest->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';


        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('human-resources:edit')
            || $request->user()->hasPermissionTo("account.edit");
    }

    public function rules(): array
    {
        return [
            'nickname' => 'sometimes|required|string',
            'name'     => 'sometimes|required|string',
            'email'    => 'sometimes|email',
            'phone'    => 'sometimes|phone:AUTO',
            'username' => 'sometimes|required|string|unique:App\Models\Auth\User,username',
            'password' => ['sometimes', 'required', Password::min(8)->uncompromised()],
            'status'   => 'sometimes|required|boolean'

        ];
    }

    public function asController(Guest $guest, ActionRequest $request): ActionResult
    {
        $request->validate();

        $modelData = $request->only(
            'name',
            'email',
            'phone',
            'identity_document_number',
            'name',
            'email',
            'phone',
            'nickname',
            'emergency_contact',
        );

        if ($data = $request->only('address')) {
            $modelData['data'] = $data;
        }


        return $this->handle(
            $guest,
            $modelData
        );
    }

    public function asInertia(Guest $guest, Request $request): RedirectResponse
    {
        $this->set('guest', $guest);
        $this->fillFromRequest($request);
        $this->validateAttributes();

        $modelData = $request->only(
            'name',
            'email',
            'phone',
            'identity_document_number',
            'name',
            'email',
            'phone',
            'nickname',
            'emergency_contact',
        );

        if ($data = $request->only('address')) {
            $modelData['data'] = $data;
        }

        $this->handle(
            $guest,
            $modelData

        );

        UpdateUser::run($guest->user, $request->only('status', 'username', 'password'));


        return Redirect::route('account.guests.edit', $guest->id);
    }

}
