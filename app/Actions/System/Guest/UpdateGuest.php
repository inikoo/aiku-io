<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 15 Dec 2021 03:36:44 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Guest;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\System\Guest;
use App\Rules\Phone;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateGuest
{
    use AsAction;
    use WithUpdate;

    public function handle(Guest $guest, array $contactData, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $guest->contact()->update($contactData);

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
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('human-resources:edit');
    }

    public function rules(): array
    {
        return [
            'nickname'   => 'sometimes|required|string',
            'name'   => 'sometimes|required|string',
            'email'  => 'sometimes|email',
            'phone'  => ['string', new Phone()],

        ];
    }

    public function asController(Guest $guest, ActionRequest $request): ActionResult
    {
        return $this->handle(
            $guest,
            $request->only('name', 'email', 'phone'),
            $request->only('nickname')
        );
    }

}
