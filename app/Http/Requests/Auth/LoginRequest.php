<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate($guard = 'ecommerce')
    {
        $this->ensureIsNotRateLimited();

        $subdomain = current(explode('.', $this->getHost()));

        $credentials = match ($subdomain) {
            'agents' => [
                'status' => true,
                'jar_username' => $this->get('username'),
                'password' => $this->get('password'),
            ],
            default => array_merge(
                $this->only('username', 'password'),
                ['status' => true, 'tenant_id' => App('currentTenant')->id]
            ),
        };


        if (!Auth::guard($guard)->attempt(
            $credentials,
            $this->boolean('remember')
        )) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                                                        'username' => __('auth.failed'),
                                                    ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
                                                    'username' => trans('auth.throttle', [
                                                        'seconds' => $seconds,
                                                        'minutes' => ceil($seconds / 60),
                                                    ]),
                                                ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey(): string
    {
        return Str::lower($this->input('username')).'|'.$this->ip();
    }
}
