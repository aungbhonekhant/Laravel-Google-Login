<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callBackGoogle()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())->first();

            if(!$user)
            {
                $newUser = User::create([
                    "name" => $googleUser->getName(),
                    "email" => $googleUser->getEmail(),
                    "google_id" => $googleUser->getId(),
                ]);

                Auth::login($newUser);

                return redirect()->intended('dashboard');
            }else {
                Auth::login($user);
                return redirect()->intended('dashboard');
            }
        } catch (\Throwable $th) {
            dd('Something went wrong! ', $th->getMessage());
        }
    }
}
