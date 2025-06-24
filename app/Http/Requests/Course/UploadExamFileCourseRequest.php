<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class UploadExamFileCourseRequest extends FormRequest
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
            'exam_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'exam_file.required' => 'يجب أن يكون لديك ملف اختبار.',
            'exam_file.file' => 'يجب أن يكون ملف اختبار.',
            'exam_file.mimes' => 'يجب أن يكون ملف اختبار بصيغة pdf, doc, docx, xls, xlsx, ppt, pptx.',
            'exam_file.max' => 'يجب أن يكون ملف اختبار أقل من 2048 ميجا.',
        ];
    }
}
