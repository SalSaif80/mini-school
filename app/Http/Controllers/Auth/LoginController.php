<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\UserRedirectHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        if ($user instanceof User) {
            activity()
                ->performedOn($user)
                ->log('تم تسجيل الدخول بنجاح');
        }

        $welcomeMessage = UserRedirectHelper::getWelcomeMessage($user);
        return UserRedirectHelper::redirectToDashboard($user, $welcomeMessage);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user instanceof User) {
            activity()
                ->performedOn($user)
                ->log('تم تسجيل الخروج بنجاح');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
