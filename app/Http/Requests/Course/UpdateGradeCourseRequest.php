<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeCourseRequest extends FormRequest
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
            'final_exam_grade' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'final_exam_grade.required' => 'يجب أن يكون لديك درجة اختبار نهائية.',
            'final_exam_grade.numeric' => 'يجب أن تكون درجة الاختبار النهائية رقمية.',
            'final_exam_grade.min' => 'يجب أن تكون درجة الاختبار النهائية أكبر من 0.',
            'final_exam_grade.max' => 'يجب أن تكون درجة الاختبار النهائية أقل من 100.',
        ];
    }
}
