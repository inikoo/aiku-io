<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 25 Jan 2022 16:39:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Profile;

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
class UpdateProfile
{
    use AsAction;
    use WithUpdate;
    use WithAttributes;


    private array $fields;

    public function __construct()
    {
        $this->fields = ['language_id','password'];
    }

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

    public function authorize(): bool
    {

        if ($this->user->userable_type == 'Tenant') {
            return false;
        }
        return true;

    }

    public function rules(): array
    {
        return [
            'language_id' => 'sometimes|required|exists:App\Models\Assets\Language,id',
            'password' => ['sometimes', 'required', 'confirmed', Password::min(8)->uncompromised()],

        ];
    }



    public function asController(User $user, ActionRequest $request): ActionResult
    {
        $request->validate();

        return $this->handle(
            $user,
            $request->only($this->fields),
        );
    }

    public function asInertia( Request $request): RedirectResponse
    {

        $this->set('user', $request->user());

        $this->fillFromRequest($request);
        $this->validateAttributes();

        //todo: deal with errors and no changes
        $res=$this->handle(
            $this->user,
            $this->only($this->fields),
        );

        return Redirect::route('profile.edit')->with('locale_updated', true);
    }
}
