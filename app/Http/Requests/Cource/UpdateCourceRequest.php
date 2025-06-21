<?php

namespace App\Http\Requests\Cource;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourceRequest extends FormRequest
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
        $courseId = $this->route('course')->id;
        return [
            'course_code' => 'required|string|max:10|unique:courses,course_code,' . $courseId,
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'credit_hours' => 'required|integer|min:1|max:6',
            'level' => 'required|in:beginner,intermediate,advanced',
            'teacher_id' => 'required|exists:teachers,id',
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
            'course_code.required' => 'رمز المادة مطلوب',
            'course_code.unique' => 'رمز المادة موجود مسبقاً',
            'title.required' => 'عنوان المادة مطلوب',
            'description.required' => 'وصف المادة مطلوب',
            'credit_hours.required' => 'عدد الساعات المعتمدة مطلوب',
            'credit_hours.integer' => 'عدد الساعات يجب أن يكون رقماً صحيحاً',
            'credit_hours.min' => 'عدد الساعات يجب أن يكون على الأقل 1',
            'credit_hours.max' => 'عدد الساعات لا يمكن أن يزيد عن 6',
            'teacher_id.required' => 'المدرس مطلوب',
            'teacher_id.exists' => 'المدرس المحدد غير موجود',
            'level.required' => 'مستوى المادة مطلوب',
            'level.in' => 'مستوى المادة يجب أن يكون مبتدئ أو متوسط أو متقدم',
        ];
    }
}
