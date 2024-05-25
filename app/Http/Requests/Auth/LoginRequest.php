<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'auth' => ['required', 'string'],
            'password' => ['nullable', 'string'],
            'code' => ['nullable', 'string'],
            'captcha' => [
                config('captcha.disable') ? 'nullable' : 'required',
                'captcha_api:' . request('key') . ',' . config('captcha.type')
            ],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = $this->checkRequest();

        if (is_null($user)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'auth' => trans('auth.failed'),
            ]);
        }

        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'auth' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('auth')) . '|' . $this->ip());
    }

    public function checkRequest(): ?User
    {
        if ($this->phone && $this->code && ($this->code === Cache::get('phone_code_' . $this->phone))) {
            return User::where('phone', $this->phone)->first();
        } else {
            $user = User::where('username', $this->auth)->orWhere('phone', $this->auth)->orWhere('email', $this->auth)->first();

            return ($user && Hash::check($this->password, $user->password)) ? $user : null;
        }
    }
}
