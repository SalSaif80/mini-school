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
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ التسجيلات</span>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تسجيل جديد</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <div class="pr-1 mb-3 mb-xl-0">
            <a href="{{ route('enrollments.index') }}" class="btn btn-secondary btn-icon ml-2">
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
                <h3 class="card-title">تسجيل طالب جديد</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('enrollments.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="student_id">الطالب <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('student_id') is-invalid @enderror"
                                        id="student_id" name="student_id" required>
                                    <option value="">اختر الطالب</option>
                                    @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->name }} - {{ $student->student_id }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course_id">المادة <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('course_id') is-invalid @enderror"
                                        id="course_id" name="course_id" required>
                                    <option value="">اختر المادة</option>
                                    @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }} - {{ $course->course_code }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="enrollment_date">تاريخ التسجيل <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('enrollment_date') is-invalid @enderror"
                                       id="enrollment_date" name="enrollment_date" value="{{ old('enrollment_date', date('Y-m-d')) }}" required>
                                @error('enrollment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">حالة التسجيل <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror"
                                        id="status" name="status" required>
                                    <option value="">اختر الحالة</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                    <option value="dropped" {{ old('status') == 'dropped' ? 'selected' : '' }}>منسحب</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="grade">الدرجة (اختياري)</label>
                                <input type="number" class="form-control @error('grade') is-invalid @enderror"
                                       id="grade" name="grade" value="{{ old('grade') }}"
                                       min="0" max="100" step="0.01" placeholder="0.00">
                                @error('grade')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">الدرجة من 0 إلى 100</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> ملاحظات مهمة:</h6>
                        <ul class="mb-0">
                            <li>تأكد من أن الطالب غير مسجل في هذه المادة مسبقاً</li>
                            <li>يمكن إضافة الدرجة لاحقاً إذا كانت الحالة "نشط"</li>
                            <li>الدرجة مطلوبة فقط عند تحديد الحالة كـ "مكتمل"</li>
                        </ul>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> حفظ التسجيل
                        </button>
                        <a href="{{ route('enrollments.index') }}" class="btn btn-secondary btn-lg mr-2">
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

    // Show/hide grade field based on status
    $('#status').on('change', function() {
        var status = $(this).val();
        var gradeField = $('#grade').closest('.form-group');

        if (status === 'completed') {
            gradeField.show();
            $('#grade').attr('required', true);
        } else {
            gradeField.hide();
            $('#grade').attr('required', false);
        }
    });

    // Trigger change event on page load
    $('#status').trigger('change');
});
</script>
@endsection
