<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 24 Sep 2021 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Account\Account;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

/**
 * @property \App\Models\Account\Tenant $account
 */
class UpdateAccount
{
    use AsAction;
    use WithUpdate;
    use WithAttributes;


    private array $updatable;

    #[Pure] public function __construct()
    {
        $this->updatable = ['name','country_id','currency_id','language_id','timezone_id'];

    }


    public function handle(array $modelData): ActionResult
    {

        $res = new ActionResult();

        $this->account->update(Arr::except($modelData, ['data', 'settings']));
        $this->account->update($this->extractJson($modelData, ['data', 'settings']));
        $res->changes = array_merge($res->changes, $this->account->getChanges());

        $res->model    = $this->account;
        $res->model_id = $this->account->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {



        return $request->user()->tokenCan('root') || $request->user()->tokenCan('system:edit') ||
            $request->user()->hasPermissionTo("account.edit");
    }

    public function rules(): array
    {
        return [
            'name'   => 'sometimes|required|string',
            'country_id'  => 'sometimes|required|exists:App\Models\Assets\Country,id',
            'currency_id' => 'sometimes|required|exists:App\Models\Assets\Currency,id',
            'language_id' => 'sometimes|required|exists:App\Models\Assets\Language,id',
            'timezone_id' => 'sometimes|required|exists:App\Models\Assets\Timezone,id',
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if ($request->exists('username')) {
            $request->merge(['username' => strtolower($request->get('username'))]);
        }
    }

    public function asController(ActionRequest $request): ActionResult
    {
        $this->set('account',app('currentTenant'));
        $request->validate();

        return $this->handle(
            $request->only($this->updatable),
        );
    }

    public function asInertia(Request $request): RedirectResponse
    {
        $this->set('account',app('currentTenant'));
        $this->fillFromRequest($request);
        $this->validateAttributes();

        //todo: deal with errors and no changes
        $this->handle(
            $this->only($this->updatable),
        );

        return Redirect::route('account.edit');
    }
}
