<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 15 Dec 2021 03:36:44 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Guest;

use App\Actions\System\User\UpdateUser;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\System\Guest;
use App\Rules\Phone;
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

    public function handle(Guest $guest, array $contactData, array $modelData): ActionResult
    {
        $res = new ActionResult();



        $guest->contact->update($contactData);

        $res->changes = array_merge($res->changes, $guest->contact->getChanges());

        $guest->update($modelData);

        $guest->update(Arr::except($modelData, ['data']));
        $guest->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $guest->getChanges());

        $res->model = $guest;

        $res->model_id = $guest->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';


        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('human-resources:edit') ||
            $request->user()->hasPermissionTo("account.edit");
    }

    public function rules(): array
    {
        return [
            'nickname'   => 'sometimes|required|string',
            'name'   => 'sometimes|required|string',
            'email'  => 'sometimes|email',
            'phone'  => ['string', new Phone()],
            'username' => 'sometimes|required|string|unique:App\Models\System\User,username',
            'password' => ['sometimes', 'required', Password::min(8)->uncompromised()],
            'status'   => 'sometimes|required|boolean'

        ];
    }

    public function asController(Guest $guest, ActionRequest $request): ActionResult
    {
        $request->validate();
        return $this->handle(
            $guest,
            $request->only('name', 'email', 'phone'),
            $request->only('nickname')
        );
    }

    public function asInertia(Guest $guest, Request $request): RedirectResponse
    {

        $this->set('guest', $guest);
        $this->fillFromRequest($request);
        $this->validateAttributes();

        $contact = $request->only('name', 'email', 'phone', 'identity_document_number');
        if ($contactData = $request->only('address')) {
            $contact['data'] = $contactData;
        }
        $this->handle(
            $guest,
            $contact,
            $request->only(
                'name','email', 'phone',
                'nickname',
                'emergency_contact',
            )
        );

        UpdateUser::run($guest->user,$request->only('status', 'username','password'));


        return Redirect::route('account.guests.edit', $guest->id);
    }

}
