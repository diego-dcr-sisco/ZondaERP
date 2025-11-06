<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
            'email' => ['nullable', 'string'],
            'username' => ['nullable', 'string', 'sometimes'],
            'password' => ['required', 'string'],
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

        // Obtener el valor del campo de login (puede ser email o username)
        $loginValue = $this->input('email') ?? $this->input('username');
        $password = $this->input('password');

        // DEPURACIÓN 1: Ver datos recibidos
        \Log::info('Login attempt', [
            'loginValue' => $loginValue,
            'password_length' => strlen($password),
            'all_input' => $this->except('password')
        ]);
        
        if (!$loginValue || !$password) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Determinar si se usa email o username
        $loginField = str_contains($loginValue, '@') ? 'email' : 'username';

        // DEPURACIÓN 2: Ver campo de login usado
        \Log::info('Login field determined', [
            'loginField' => $loginField,
            'loginValue' => $loginValue
        ]);


        // Buscar el usuario manualmente para depurar
        $user = User::where($loginField, $loginValue)->first();

        // DEPURACIÓN 3: Ver si el usuario existe
        if (!$user) {
            \Log::warning('User not found', [
                'loginField' => $loginField,
                'loginValue' => $loginValue,
                'table' => 'user' // Verifica que sea la tabla correcta
            ]);
            
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // DEPURACIÓN 4: Verificar contraseña manualmente
        $passwordValid = Hash::check($password, $user->password);
        
        \Log::info('Password verification', [
            'user_id' => $user->id,
            'password_valid' => $passwordValid,
            'user_email' => $user->email,
            'user_username' => $user->username
        ]);

        if (!$passwordValid) {
            \Log::warning('Invalid password', [
                'user_id' => $user->id,
                'loginField' => $loginField
            ]);
            
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'password' => trans('auth.failed_password'),
            ]);
        }

        // DEPURACIÓN 5: Verificar estado del usuario
        if ($user->status_id != 2) { // Ajusta según tu lógica de estados
            \Log::warning('User not active', [
                'user_id' => $user->id,
                'status_id' => $user->status_id
            ]);
            
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'Usuario inactivo',
            ]);
        }

        // Construir las credenciales correctamente
        $credentials = [
            $loginField => $loginValue,
            'password' => $password
        ];

        // DEPURACIÓN 6: Intentar autenticación
        $authAttempt = Auth::attempt($credentials, $this->boolean('remember'));
        
        \Log::info('Auth attempt result', [
            'attempt_result' => $authAttempt,
            'credentials_field' => $loginField,
            'remember' => $this->boolean('remember')
        ]);

        if (!$authAttempt) {
            \Log::error('Auth::attempt failed despite valid credentials', [
                'user_id' => $user->id,
                'loginField' => $loginField,
                'credentials' => $credentials
            ]);
            
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'password' => trans('auth.failed_password'),
            ]);
        }

        // DEPURACIÓN 7: Verificar autenticación exitosa
        \Log::info('Login successful', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name
        ]);

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
            'email' => trans('auth.throttle', [
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
        $loginValue = $this->input('email') ?? $this->input('username');
        return Str::transliterate(Str::lower($loginValue) . '|' . $this->ip());
    }
}