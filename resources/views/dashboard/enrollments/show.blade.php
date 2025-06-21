@extends('dashboard.layouts.master')
@section('css')
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الأكاديمية</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ التسجيلات</span>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تفاصيل التسجيل</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <div class="pr-1 mb-3 mb-xl-0">
            <a href="{{ route('enrollments.index') }}" class="btn btn-secondary btn-icon ml-2">
                <i class="mdi mdi-arrow-left"></i> العودة للقائمة
            </a>
        </div>
        <div class="pr-1 mb-3 mb-xl-0">
            <a href="{{ route('enrollments.edit', $enrollment) }}" class="btn btn-warning btn-icon ml-2">
                <i class="mdi mdi-pencil"></i> تعديل
            </a>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>نجح!</strong> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <!-- Student Information Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">معلومات الطالب</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">اسم الطالب</label>
                            <input type="text" class="form-control" value="{{ $enrollment->student->user->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">رقم الطالب</label>
                            <input type="text" class="form-control" value="{{ $enrollment->student->student_id }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="text" class="form-control" value="{{ $enrollment->student->user->email }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">التخصص</label>
                            <input type="text" class="form-control" value="{{ $enrollment->student->major }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Information Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">معلومات المادة</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">اسم المادة</label>
                            <input type="text" class="form-control" value="{{ $enrollment->course->title }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">رمز المادة</label>
                            <input type="text" class="form-control" value="{{ $enrollment->course->course_code }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">الساعات المعتمدة</label>
                            <input type="text" class="form-control" value="{{ $enrollment->course->credit_hours }} ساعة" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">مستوى المادة</label>
                            <input type="text" class="form-control" value="{{ $enrollment->course->level == 'beginner' ? 'مبتدئ' : ($enrollment->course->level == 'intermediate' ? 'متوسط' : 'متقدم') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">وصف المادة</label>
                            <textarea class="form-control" rows="3" readonly>{{ $enrollment->course->description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teacher Information Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">معلومات المدرس</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">اسم المدرس</label>
                            <input type="text" class="form-control" value="{{ $enrollment->course->teacher->user->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">القسم</label>
                            <input type="text" class="form-control" value="{{ $enrollment->course->teacher->department }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">التخصص</label>
                            <input type="text" class="form-control" value="{{ $enrollment->course->teacher->specialization }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollment Information Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">معلومات التسجيل</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">تاريخ التسجيل</label>
                            <input type="text" class="form-control" value="{{ $enrollment->enrollment_date->format('Y-m-d') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">حالة التسجيل</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $enrollment->status == 'active' ? 'نشط' : ($enrollment->status == 'completed' ? 'مكتمل' : 'منسحب') }}" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <span class="badge badge-{{ $enrollment->status == 'active' ? 'success' : ($enrollment->status == 'completed' ? 'primary' : 'danger') }}">
                                            {{ $enrollment->status == 'active' ? 'نشط' : ($enrollment->status == 'completed' ? 'مكتمل' : 'منسحب') }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">الدرجة</label>
                            <input type="text" class="form-control" value="{{ $enrollment->grade ? $enrollment->grade . '%' : 'لم يتم تحديد الدرجة بعد' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">تاريخ الإنشاء</label>
                            <input type="text" class="form-control" value="{{ $enrollment->created_at->format('Y-m-d H:i') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">آخر تحديث</label>
                            <input type="text" class="form-control" value="{{ $enrollment->updated_at->format('Y-m-d H:i') }}" readonly>
                        </div>
                    </div>
                </div>

                @if($enrollment->status == 'active')
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> إجراءات متاحة:</h6>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#completeModal">
                            <i class="fas fa-check"></i> إكمال المادة
                        </button>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#dropModal">
                            <i class="fas fa-times"></i> تسجيل انسحاب
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End Row -->

<!-- Complete Modal -->
@if($enrollment->status == 'active')
<div class="modal fade" id="completeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إكمال المادة</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('enrollments.markCompleted', $enrollment) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="grade">الدرجة النهائية</label>
                        <input type="number" class="form-control" name="grade" min="0" max="100" step="0.01" required>
                        <small class="form-text text-muted">أدخل الدرجة من 0 إلى 100</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">حفظ الدرجة وإكمال</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="dropModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الانسحاب</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من تسجيل انسحاب الطالب من هذه المادة؟</p>
                <p class="text-danger"><strong>تحذير:</strong> هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <form action="{{ route('enrollments.markDropped', $enrollment) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">تأكيد الانسحاب</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('js')
@endsection
