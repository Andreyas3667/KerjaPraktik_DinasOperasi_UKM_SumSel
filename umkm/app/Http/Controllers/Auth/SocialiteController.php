<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    public function redirectToGoogle() { return Socialite::driver('google')->redirect(); }
    public function handleGoogleCallback() {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'nama' => $googleUser->getName(),
                'role' => 'user',
                'telepon' => '',
                'alamat' => '',
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => null, // Paksa verifikasi email
            ]
        );
        if (!$user->hasVerifiedEmail()) {
            event(new Registered($user)); // Kirim email verifikasi
        }
        Auth::login($user);
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
        return redirect()->route('dashboard');
    }

    public function redirectToFacebook() { return Socialite::driver('facebook')->redirect(); }
    public function handleFacebookCallback() {
        $fbUser = Socialite::driver('facebook')->user();
        $user = User::firstOrCreate(
            ['email' => $fbUser->getEmail()],
            [
                'nama' => $fbUser->getName(),
                'role' => 'user',
                // tambahkan field default kosong untuk telepon/alamat
            ]
        );
        Auth::login($user);
        return redirect()->route('dashboard');
    }
}
