<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AllowedUser;

class GoogleLogin extends Controller
{

    public function loginGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackFromGoogle()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();

            // Check Users Email If Already There
            if(AllowedUser::isAllowed($user->getEmail())){
                $is_user = User::where('email', $user->getEmail())->first();
                if (!$is_user) {
    
                    $saveUser = User::updateOrCreate([
                        'google_id' => $user->getId(),
                    ], [
                        'name' => $user->getName(),
                        'email' => $user->getEmail(),
                        'password' => Hash::make($user->getName() . '@' . $user->getId())
                    ]);
                } else {
                    $saveUser = User::where('email',  $user->getEmail())->update([
                        'google_id' => $user->getId(),
                    ]);
                    $saveUser = User::where('email', $user->getEmail())->first();
                }
    
    
                Auth::loginUsingId($saveUser->id);
            } else {
                return redirect()->route('login')->with('error', 'You are not allowed to login with '. $user->getEmail(). ' email address. Please contact the administrator.');
            }

            return redirect()->route('home');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
