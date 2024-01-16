<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Info(
 *    title="Groene Vingers",
 *    version="3.0.0",
 * )
 */
class LoginController extends Controller
{
    public function showapi()
    {
        return redirect('/documentation');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);
            $role = $user->role->first()->name;
            // Store access token and role in session
            session(['access_token' => $token]);
            session(['user_role' => $role]);

            return redirect('/documentation');
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Session::flush();

        return redirect('/');
    }
}
