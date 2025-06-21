@extends('dashboard.layouts.master')
@section('css')
<link href="{{URL::asset('dashboard/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الأكاديمية</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ المواد الدراسية</span>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل مادة </span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <div class="pr-1 mb-3 mb-xl-0">
            <a href="{{ route('courses.index') }}" class="btn btn-secondary btn-icon ml-2">
                <i class="mdi mdi-arrow-left"></i> العودة للقائمة
            </a>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>خطأ!</strong> يرجى تصحيح الأخطاء التالية:
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- Row -->
<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">تعديل مادة دراسية</h3>
                {{-- <div class="d-flex my-xl-auto right-content"> --}}
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary ml-2">
                        <i class="mdi mdi-arrow-left"></i> العودة للقائمة
                    </a>
                {{-- </div> --}}
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('courses.update',$course->id) }}">
                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course_code">رمز المادة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('course_code') is-invalid @enderror"
                                       id="course_code" name="course_code" value="{{$course->course_code}}"
                                       placeholder="مثال: MATH101" required>
                                @error('course_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">رمز فريد للمادة (حروف وأرقام)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">اسم المادة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{$course->title}}"
                                       placeholder="مثال: الرياضيات المتقدمة" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">وصف المادة <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="4"
                                          placeholder="وصف تفصيلي عن محتوى المادة وأهدافها..." required>{{$course->description}}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="credit_hours">الساعات المعتمدة <span class="text-danger">*</span></label>
                                <select class="form-control @error('credit_hours') is-invalid @enderror"
                                        id="credit_hours" name="credit_hours" required>
                                    <option value="">اختر عدد الساعات</option>
                                    <option value="1" {{ $course->credit_hours == '1' ? 'selected' : '' }}>1 ساعة</option>
                                    <option value="2" {{ $course->credit_hours == '2' ? 'selected' : '' }}>2 ساعة</option>
                                    <option value="3" {{ $course->credit_hours == '3' ? 'selected' : '' }}>3 ساعات</option>
                                    <option value="4" {{ $course->credit_hours == '4' ? 'selected' : '' }}>4 ساعات</option>
                                    <option value="5" {{ $course->credit_hours == '5' ? 'selected' : '' }}>5 ساعات</option>
                                    <option value="6" {{ $course->credit_hours == '6' ? 'selected' : '' }}>6 ساعات</option>
                                </select>
                                @error('credit_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="level">مستوى المادة <span class="text-danger">*</span></label>
                                <select class="form-control @error('level') is-invalid @enderror"
                                        id="level" name="level" required>
                                    <option value="">اختر المستوى</option>
                                    <option value="beginner" {{ $course->level == 'beginner' ? 'selected' : '' }}>مبتدئ</option>
                                    <option value="intermediate" {{ $course->level == 'intermediate' ? 'selected' : '' }}>متوسط</option>
                                    <option value="advanced" {{ $course->level == 'advanced' ? 'selected' : '' }}>متقدم</option>
                                </select>
                                @error('level')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="teacher_id">المعلم المسؤول <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('teacher_id') is-invalid @enderror"
                                        id="teacher_id" name="teacher_id" required>
                                    <option value="">اختر المعلم</option>
                                    @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $course->teacher_id == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->name }} - {{ $teacher->department }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> ملاحظات مهمة:</h6>
                        <ul class="mb-0">
                            <li>رمز المادة يجب أن يكون فريداً ولا يمكن تغييره لاحقاً</li>
                            <li>يمكن للطلاب التسجيل في المادة بعد إنشائها</li>
                            <li>يمكن تغيير المعلم المسؤول لاحقاً من قبل المدير</li>
                            <li>الساعات المعتمدة تؤثر على حساب المعدل التراكمي</li>
                        </ul>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> تحديث المادة
                        </button>
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary btn-lg mr-2">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->

@endsection

@section('js')
<script src="{{URL::asset('dashboard/assets/plugins/select2/js/select2.min.js')}}"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2();

    // Auto-generate course code based on title
    $('#title').on('input', function() {
        var title = $(this).val();
        var courseCode = $('#course_code');

        if (courseCode.val() === '' && title.length > 0) {
            // Simple auto-generation logic
            var words = title.split(' ');
            var code = '';

            words.forEach(function(word, index) {
                if (index < 2 && word.length > 0) {
                    code += word.substring(0, 3).toUpperCase();
                }
            });

            code += '101'; // Default level
            courseCode.val(code);
        }
    });
});
</script>
@endsection
