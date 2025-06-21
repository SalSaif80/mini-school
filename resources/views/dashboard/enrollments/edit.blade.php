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
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل التسجيل</span>
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
                <h3 class="card-title">تعديل التسجيل #{{ $enrollment->id }}</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('enrollments.update', $enrollment) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="student_id">الطالب <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('student_id') is-invalid @enderror"
                                        id="student_id" name="student_id" required>
                                    <option value="">اختر الطالب</option>
                                    @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ (old('student_id', $enrollment->student_id) == $student->id) ? 'selected' : '' }}>
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
                                    <option value="{{ $course->id }}" {{ (old('course_id', $enrollment->course_id) == $course->id) ? 'selected' : '' }}>
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
                                       id="enrollment_date" name="enrollment_date"
                                       value="{{ old('enrollment_date', $enrollment->enrollment_date->format('Y-m-d')) }}" required>
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
                                    <option value="active" {{ (old('status', $enrollment->status) == 'active') ? 'selected' : '' }}>نشط</option>
                                    <option value="completed" {{ (old('status', $enrollment->status) == 'completed') ? 'selected' : '' }}>مكتمل</option>
                                    <option value="dropped" {{ (old('status', $enrollment->status) == 'dropped') ? 'selected' : '' }}>منسحب</option>
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
                                <label for="grade">الدرجة</label>
                                <input type="number" class="form-control @error('grade') is-invalid @enderror"
                                       id="grade" name="grade" value="{{ old('grade', $enrollment->grade) }}"
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
                            <li>يمكن تغيير جميع البيانات حسب الحاجة</li>
                            <li>الدرجة مطلوبة إذا كانت الحالة "مكتمل"</li>
                            <li>تأكد من صحة البيانات قبل الحفظ</li>
                        </ul>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> تحديث التسجيل
                        </button>
                        <a href="{{ route('enrollments.index') }}" class="btn btn-secondary btn-lg mr-2">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Current Information Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">المعلومات الحالية</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">الطالب الحالي</label>
                            <input type="text" class="form-control" value="{{ $enrollment->student->user->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">المادة الحالية</label>
                            <input type="text" class="form-control" value="{{ $enrollment->course->title }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">الحالة الحالية</label>
                            <input type="text" class="form-control" value="{{ $enrollment->status == 'active' ? 'نشط' : ($enrollment->status == 'completed' ? 'مكتمل' : 'منسحب') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">الدرجة الحالية</label>
                            <input type="text" class="form-control" value="{{ $enrollment->grade ? $enrollment->grade . '%' : 'لا توجد' }}" readonly>
                        </div>
                    </div>
                </div>
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
            gradeField.show(); // Keep it visible for editing
            $('#grade').attr('required', false);
        }
    });

    // Trigger change event on page load
    $('#status').trigger('change');
});
</script>
@endsection
