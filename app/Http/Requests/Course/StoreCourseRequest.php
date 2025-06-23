<?php

namespace App\Http\Requests\Course;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
            'course_name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id,user_type,' . User::TEACHER,
            'schedule_date' => 'required|date',
            'room_number' => 'required|string|max:50',
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
            'course_name.required' => 'اسم الكورس مطلوب',
            'course_name.string' => 'اسم الكورس يجب أن يكون نصاً',
            'course_name.max' => 'اسم الكورس لا يمكن أن يزيد عن 255 حرفاً',
            'teacher_id.required' => 'المدرس مطلوب',
            'teacher_id.exists' => 'المدرس المحدد غير موجود',
            'teacher_id.user_type' => 'المستخدم المحدد يجب أن يكون معلماً',
            'schedule_date.required' => 'موعد المحاضرة مطلوب',
            'schedule_date.date' => 'موعد المحاضرة يجب أن يكون تاريخاً صحيحاً',
            'room_number.required' => 'رقم الغرفة مطلوب',
            'room_number.string' => 'رقم الغرفة يجب أن يكون نصاً',
            'room_number.max' => 'رقم الغرفة لا يمكن أن يزيد عن 50 حرفاً',
        ];
    }
}
