<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'user_type' => 'required|string|in:' . implode(',', User::availableRoles()),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'name.string' => 'الاسم يجب أن يكون نص',
            'name.max' => 'الاسم يجب أن يكون أقل من 255 حرف',
            'username.required' => 'اسم المستخدم مطلوب',
            'username.string' => 'اسم المستخدم يجب أن يكون نص',
            'username.max' => 'اسم المستخدم يجب أن يكون أقل من 255 حرف',
            'username.unique' => 'اسم المستخدم يجب أن يكون فريد',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.string' => 'كلمة المرور يجب أن يكون نص',
            'password.min' => 'كلمة المرور يجب أن يكون أكثر من 8 حرف',
            'user_type.required' => 'نوع المستخدم مطلوب',
            'user_type.string' => 'نوع المستخدم يجب أن يكون نص',
            'user_type.in' => 'نوع المستخدم يجب أن يكون من القيم المتاحة',
        ];
    }
}
