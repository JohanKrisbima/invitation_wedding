<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Jumlah maksimal percobaan login yang gagal sebelum akses ditangguhkan sementara.
     */
    protected int $maxAttempts = 5;

    /**
     * Durasi penangguhan dalam detik jika melebihi batas maksimal percobaan (180 detik = 3 menit).
     */
    protected int $decaySeconds = 180;

    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $throttleKey = $this->throttleKey($request);

        // 1. Cek apakah pengguna sudah melebihi batas percobaan login gagal
        if (RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = (int) ceil($seconds / 60);

            $durationText = $seconds < 60
                ? "{$seconds} detik"
                : "{$minutes} menit";

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan masuk yang gagal. Akses sementara ditangguhkan demi keamanan server & akun. Silakan coba lagi dalam {$durationText}.",
            ]);
        }

        $remember = $request->boolean('remember');

        // 2. Coba autentikasi
        if (Auth::attempt($credentials, $remember)) {
            // Berhasil login: bersihkan riwayat rate limiter untuk email dan IP ini
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        // 3. Gagal login: catat hitungan kegagalan dengan waktu decay
        RateLimiter::hit($throttleKey, $this->decaySeconds);

        $retriesLeft = RateLimiter::retriesLeft($throttleKey, $this->maxAttempts);

        $message = 'Email atau kata sandi yang Anda masukkan tidak cocok.';
        if ($retriesLeft > 0) {
            $message .= " Sisa percobaan: {$retriesLeft} kali lagi sebelum akun ditangguhkan sementara.";
        } else {
            $decayMinutes = (int) ceil($this->decaySeconds / 60);
            $message .= " Batas percobaan habis. Akses Anda ditangguhkan selama {$decayMinutes} menit.";
        }

        throw ValidationException::withMessages([
            'email' => $message,
        ]);
    }

    /**
     * Dapatkan kunci throttle untuk request saat ini berdasarkan email dan IP address.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}
