<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class UserRedirectHelper
{

    public static function redirectToDashboard(User $user, ?string $successMessage = null): RedirectResponse
    {
        $redirect = match ($user->user_type) {
            User::ADMIN => redirect()->route('admin.dashboard'),
            User::TEACHER => redirect()->route('teacher.dashboard'),
            User::STUDENT => redirect()->route('student.dashboard'),
            default => redirect()->route('login'),
        };

        if ($successMessage) {
            return $redirect->with('success', $successMessage);
        }

        return $redirect;
    }

    public static function getDashboardRoute(User $user): string
    {
        return match ($user->user_type) {
            User::ADMIN => 'admin.dashboard',
            User::TEACHER => 'teacher.dashboard',
            User::STUDENT => 'student.dashboard',
            default => 'login',
        };
    }

    /**
     * الحصول على رسالة ترحيب مناسبة بناءً على نوع المستخدم
     */
    public static function getWelcomeMessage(User $user): string
    {
        return match ($user->user_type) {
            User::ADMIN => 'مرحباً بك في لوحة تحكم الإدارة',
            User::TEACHER => 'مرحباً بك في لوحة تحكم المعلم',
            User::STUDENT => 'مرحباً بك في لوحة تحكم الطالب',
            default => 'مرحباً بك',
        };
    }

}
