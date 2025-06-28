<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle the custom login request
     */
    public function login(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'user_type' => 'required|in:admin,teacher,student',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'user_type.required' => 'يرجى اختيار نوع المستخدم',
            'user_type.in' => 'نوع المستخدم غير صحيح',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف',
        ]);

        $userType = $request->user_type;
        $email = $request->email;
        $password = $request->password;

        // البحث عن المستخدم
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'لا يوجد مستخدم بهذا البريد الإلكتروني'
            ])->withInput();
        }

        // التحقق من كلمة المرور
        if (!Hash::check($password, $user->password)) {
            return back()->withErrors([
                'password' => 'كلمة المرور غير صحيحة'
            ])->withInput();
        }

        // التحقق من نوع المستخدم
        $actualUserType = $this->getUserType($user);

        if ($userType !== $actualUserType) {
            return back()->withErrors([
                'user_type' => 'نوع المستخدم المحدد لا يتطابق مع حسابك. حسابك مسجل كـ: ' . $this->getArabicUserType($actualUserType)
            ])->withInput();
        }

        // تسجيل الدخول
        Auth::login($user, $request->filled('remember'));

        // إعادة التوجيه حسب نوع المستخدم
        return $this->redirectToDashboard($userType);
    }

        /**
     * تحديد نوع المستخدم
     */
    private function getUserType($user)
    {
        if ($user->isStudent()) {
            return 'student';
        }

        if ($user->isTeacher()) {
            return 'teacher';
        }

        return 'admin';
    }

    /**
     * الحصول على نوع المستخدم بالعربية
     */
    private function getArabicUserType($userType)
    {
        switch ($userType) {
            case 'student':
                return User::STUDENT;
            case 'teacher':
                return User::TEACHER;
            case 'admin':
                return User::ADMIN;
            default:
                return 'غير محدد';
        }
    }

    /**
     * إعادة التوجيه للداشبورد المناسب
     */
    private function redirectToDashboard($userType)
    {
        switch ($userType) {
            case 'student':
                return redirect()->route('student.dashboard')->with('success', 'مرحباً بك في لوحة تحكم الطالب');
            case 'teacher':
                return redirect()->route('teacher.dashboard')->with('success', 'مرحباً بك في لوحة تحكم المعلم');
            case 'admin':
                return redirect()->route('admin.dashboard')->with('success', 'مرحباً بك في لوحة تحكم الإدارة');
            default:
                return redirect()->route('dashboard-school')->with('success', 'مرحباً بك');
        }
    }
}
