<?php

namespace App\Http\Requests\Enrollment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
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
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,course_id',
            'semester' => 'required|string|max:50',
            'status' => 'required|in:active,completed,failed,dropped',
            'final_exam_grade' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'يجب أن يكون هناك طالب محدد',
            'student_id.exists' => 'الطالب غير موجود',
            'course_id.required' => 'يجب أن يكون هناك كورس محدد',
            'course_id.exists' => 'الكورس غير موجود',
            'semester.required' => 'يجب أن يكون هناك فصل محدد',
            'semester.string' => 'الفصل يجب أن يكون نص',
            'semester.max' => 'الفصل يجب أن يكون أقل من 50 حرف',
            'status.required' => 'يجب أن يكون هناك حالة محددة',
            'status.in' => 'الحالة يجب أن تكون من القيم المسموح بها',
            'final_exam_grade.numeric' => 'الدرجة يجب أن يكون رقم',
        ];
    }
}
