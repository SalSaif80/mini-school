<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'roles' => 'required|array|min:1',
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->route('user'))],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults(), 'min:8'],
        ];

    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'roles.required' => 'الأدوار مطلوبة',
            'roles.array' => 'الأدوار يجب أن يكون عبارة عن مصفوفة',
            'roles.min' => 'يجب أن يكون لديك على الأقل أدوار واحدة',
            'name.required' => 'خطأ في حقل الاسم',
            'name.string' => 'خطأ في حقل الاسم',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يجب أن يكون البريد الإلكتروني عبارة عن بريد إلكتروني ',
            'email.unique' => 'خطأ في البريد الإلكتروني أو كلمة المرور',
            'password.confirmed' => 'يجب أن يكون كلمة المرور وتأكيدها متطابقين',
            'password.min' => 'يجب أن يكون كلمة المرور أطول من 8 أحرف',
        ];

    }

}
