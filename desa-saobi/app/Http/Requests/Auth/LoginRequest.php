<?php

namespace App\Http\Requests\Auth;

use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nik' => ['nullable', 'string', 'digits:16'],
            'email' => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $isNikLogin = $this->filled('nik');

        if ($isNikLogin) {
            $nik = trim((string) $this->input('nik'));

            // 1. Validasi mutlak: NIK wajib terdaftar di master data penduduk Desa Saobi
            $penduduk = Penduduk::where('nik', $nik)->first();
            if (!$penduduk) {
                RateLimiter::hit($this->throttleKey());
                throw ValidationException::withMessages([
                    'nik' => 'NIK tidak terdaftar dalam data penduduk Desa Saobi. Akses ditolak.',
                ]);
            }

            // 2. Cari akun login berdasarkan NIK atau relasi user_id penduduk
            $user = User::where('nik', $nik)->first() ?? $penduduk->user;
            if (!$user) {
                RateLimiter::hit($this->throttleKey());
                throw ValidationException::withMessages([
                    'nik' => 'Akun warga untuk NIK ini belum dibuat oleh Admin. Silakan hubungi perangkat desa.',
                ]);
            }

            $credentials = [
                'id' => $user->id,
                'password' => $this->password,
            ];
        } else {
            if (!$this->filled('email')) {
                throw ValidationException::withMessages([
                    'email' => 'Silakan masukkan email atau NIK Anda.',
                ]);
            }

            $credentials = [
                'email' => $this->input('email'),
                'password' => $this->password,
            ];
        }

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                $isNikLogin ? 'password' : 'email' => 'Kata sandi yang Anda masukkan salah.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
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
        $identifier = $this->filled('nik') ? $this->input('nik') : $this->input('email');
        return Str::transliterate(Str::lower((string) $identifier).'|'.$this->ip());
    }
}
