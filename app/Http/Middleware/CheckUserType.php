<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$allowedTypes): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // تحديد نوع المستخدم
        $userType = $this->getUserType($user);

        // التحقق من الصلاحيات
        if (!empty($allowedTypes) && !in_array($userType, $allowedTypes)) {
            // إعادة توجيه للداشبورد المناسب
            return $this->redirectToDashboard($userType);
        }

        return $next($request);
    }

        /**
     * تحديد نوع المستخدم
     */
    private function getUserType($user)
    {
        // استخدام الـ methods الموجودة في User model
        if ($user->isStudent()) {
            return 'student';
        }

        if ($user->isTeacher()) {
            return 'teacher';
        }

        // افتراضياً admin إذا لم يكن student أو teacher
        return 'admin';
    }

    /**
     * إعادة التوجيه للداشبورد المناسب
     */
    private function redirectToDashboard($userType)
    {
        switch ($userType) {
            case 'student':
                return redirect()->route('student.dashboard')->with('error', 'ليس لديك صلاحية للوصول لهذه الصفحة');
            case 'teacher':
                return redirect()->route('teacher.dashboard')->with('error', 'ليس لديك صلاحية للوصول لهذه الصفحة');
            case 'admin':
            default:
                return redirect()->route('admin.dashboard')->with('error', 'ليس لديك صلاحية للوصول لهذه الصفحة');
        }
    }
}
