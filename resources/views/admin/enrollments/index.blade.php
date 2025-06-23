@extends('layouts.app')

@section('title', 'إدارة التسجيلات')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-graduate me-2"></i>إدارة التسجيلات</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEnrollmentModal">
            <i class="fas fa-plus me-2"></i>إضافة تسجيل جديد
        </button>
    </div>

    <!-- فلاتر البحث -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>فلاتر البحث</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.enrollments') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="course_filter" class="form-label">الكورس</label>
                    <select class="form-select" id="course_filter" name="course_id">
                        <option value="">جميع الكورسات</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->course_id }}" {{ request('course_id') == $course->course_id ? 'selected' : '' }}>
                                {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status_filter" class="form-label">الحالة</label>
                    <select class="form-select" id="status_filter" name="status">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>راسب</option>
                        <option value="dropped" {{ request('status') == 'dropped' ? 'selected' : '' }}>منسحب</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="semester_filter" class="form-label">الفصل الدراسي</label>
                    <select class="form-select" id="semester_filter" name="semester">
                        <option value="">جميع الفصول</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                                {{ $semester }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">البحث</label>
                    <div class="input-group">
                        <input type="text" class="form-control rounded-0" id="search" name="search"
                               placeholder="اسم الطالب..." value="{{ request('search') }}">
                        <button class="btn btn-outline-primary rounded-0" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['total'] }}</h4>
                            <p class="mb-0">إجمالي التسجيلات</p>
                        </div>
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['active'] }}</h4>
                            <p class="mb-0">تسجيلات نشطة</p>
                        </div>
                        <i class="fas fa-user-check fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['completed'] }}</h4>
                            <p class="mb-0">مكتملة</p>
                        </div>
                        <i class="fas fa-graduation-cap fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['dropped'] + $stats['failed'] }}</h4>
                            <p class="mb-0">غير مكتملة</p>
                        </div>
                        <i class="fas fa-user-times fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول التسجيلات -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>قائمة التسجيلات ({{ $enrollments->total() }} تسجيل)</h5>
        </div>
        <div class="card-body">
            @if($enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>الطالب</th>
                                <th>الكورس</th>
                                <th>المدرس</th>
                                <th>تاريخ التسجيل</th>
                                <th>الفصل الدراسي</th>
                                <th>الحالة</th>
                                <th>الدرجة النهائية</th>
                                <th>الدرجة الحرفية</th>
                                <th>ملف الاختبار</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->enrollment_id }}</td>
                                    <td>
                                        <strong>{{ $enrollment->student->name }}</strong>
                                        <br><small class="text-muted">{{ $enrollment->student->username }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $enrollment->course->course_name }}</strong>
                                        <br><small class="text-muted">{{ $enrollment->course->room_number }}</small>
                                    </td>
                                    <td>{{ $enrollment->course->teacher->name }}</td>
                                    <td>{{ $enrollment->enrollment_date->format('Y-m-d') }}</td>
                                    <td>{{ $enrollment->semester }}</td>
                                    <td>
                                        <span class="badge
                                            @if($enrollment->status == 'active') bg-primary
                                            @elseif($enrollment->status == 'completed') bg-success
                                            @elseif($enrollment->status == 'failed') bg-danger
                                            @else bg-warning
                                            @endif">
                                            @if($enrollment->status == 'active') نشط
                                            @elseif($enrollment->status == 'completed') مكتمل
                                            @elseif($enrollment->status == 'failed') راسب
                                            @else منسحب
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if($enrollment->final_exam_grade)
                                            <strong class="text-primary">{{ $enrollment->final_exam_grade }}%</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($enrollment->grade)
                                            <span class="badge
                                                @if($enrollment->grade == 'F') bg-danger
                                                @elseif(in_array($enrollment->grade, ['A+', 'A'])) bg-success
                                                @else bg-info
                                                @endif">
                                                {{ $enrollment->grade }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($enrollment->exam_file_path)
                                            <span class="badge bg-success">
                                                <i class="fas fa-file-alt me-1"></i>مرفوع
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-minus me-1"></i>غير مرفوع
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-info btn-sm"
                                                    onclick="showEnrollmentDetails({{ $enrollment->enrollment_id }})"
                                                    title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm"
                                                    onclick="editEnrollment({{ $enrollment->enrollment_id }})"
                                                    title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete({{ $enrollment->enrollment_id }})"
                                                    title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $enrollments->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                    <h5>لا توجد تسجيلات</h5>
                    <p class="text-muted">لم يتم العثور على تسجيلات تطابق معايير البحث</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal إضافة تسجيل جديد -->
    <div class="modal fade" id="createEnrollmentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة تسجيل جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.enrollments.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">الطالب</label>
                            <select class="form-select" id="student_id" name="student_id" required>
                                <option value="">اختر الطالب</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->username }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="course_id" class="form-label">الكورس</label>
                            <select class="form-select" id="course_id" name="course_id" required>
                                <option value="">اختر الكورس</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->course_id }}">{{ $course->course_name }} - {{ $course->teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">الفصل الدراسي</label>
                            <input type="text" class="form-control" id="semester" name="semester" value="2025-1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>حفظ التسجيل
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal تأكيد الحذف -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد من حذف هذا التسجيل؟ سيتم حذف جميع البيانات المرتبطة به.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">حذف</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function confirmDelete(enrollmentId) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/enrollments/${enrollmentId}`;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    function showEnrollmentDetails(enrollmentId) {
        // يمكن إضافة modal لعرض التفاصيل
        window.location.href = `/admin/enrollments/${enrollmentId}`;
    }

    function editEnrollment(enrollmentId) {
        // يمكن إضافة modal للتعديل
        window.location.href = `/admin/enrollments/${enrollmentId}/edit`;
    }
</script>
@endsection
