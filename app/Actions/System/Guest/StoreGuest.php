<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 15 Dec 2021 03:32:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Guest;

use App\Models\Utils\ActionResult;
use App\Models\System\Guest;
use App\Rules\Phone;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreGuest
{
    use AsAction;


    public function handle(array $contactData, array $modelData): ActionResult
    {
        $res  = new ActionResult();

        $guest = Guest::create($modelData);
        $guest->contact()->create($contactData);
        $guest->save();

        $res->model    = $guest;
        $res->model_id = $guest->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('guest:store');
    }

    public function rules(): array
    {
        return [
            'name'   => 'required|string',
            'email'  => 'email',
            'phone'  => ['string', new Phone()],
            'nickname'=>'required|string',

        ];
    }

    public function asController(ActionRequest $request): ActionResult
    {
        return $this->handle(
            $request->only('name', 'email', 'phone'),
            $request->only('nickname')
        );
    }
}
