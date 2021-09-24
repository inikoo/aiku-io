<?php

namespace App\Actions\System\User;

use App\Models\System\User;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUser
{
    use AsAction;

    public function handle(User $user,array $data): User
    {
         $user->update($data);
         return $user;
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

    public function asController(User $user, ActionRequest $request): User
    {



        return $this->handle(
            $user,
            $request->only('username', 'password'),
        );
    }
}
