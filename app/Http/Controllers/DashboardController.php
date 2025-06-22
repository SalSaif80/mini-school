<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\UserRedirectHelper;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return UserRedirectHelper::redirectToDashboard($user);
    }
}
