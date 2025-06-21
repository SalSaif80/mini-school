@extends('dashboard.layouts.master')
@section('title', 'طلابي المسجلين')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">طلابي المسجلين</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عملي</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('teacher.my-courses') }}" class="btn btn-primary btn-icon ml-2">
                    <i class="fas fa-book"></i> موادي التدريسية
                </a>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- Students Statistics -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-2">إجمالي طلابي</h6>
                            <h3 class="mb-0 text-primary">{{ $totalStudents }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary-transparent text-primary">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-2">الطلاب النشطين</h6>
                            <h3 class="mb-0 text-success">{{ $activeStudents }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success-transparent text-success">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-2">متوسط الدرجات</h6>
                            <h3 class="mb-0 text-warning">{{ $averageGrade }}%</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning-transparent text-warning">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-2">المواد المُدرسة</h6>
                            <h3 class="mb-0 text-info">{{ $totalCourses }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-info-transparent text-info">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-3">
                            <label for="course_filter" class="form-label">فلترة حسب المادة</label>
                            <select name="course_id" id="course_filter" class="form-control">
                                <option value="">جميع المواد</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status_filter" class="form-label">حالة التسجيل</label>
                            <select name="status" id="status_filter" class="form-control">
                                <option value="">جميع الحالات</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                <option value="withdrawn" {{ request('status') == 'withdrawn' ? 'selected' : '' }}>منسحب</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="form-label">البحث</label>
                            <input type="text" name="search" id="search" class="form-control"
                                   placeholder="اسم الطالب أو الرقم الجامعي" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> بحث
                            </button>
                            <a href="{{ route('teacher.my-students') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">طلابي المسجلين</h4>
                    <div class="card-options">
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-success" onclick="exportStudents()">
                                <i class="fas fa-download"></i> تصدير Excel
                            </button>
                            <button class="btn btn-sm btn-info" onclick="printStudents()">
                                <i class="fas fa-print"></i> طباعة
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($enrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>الطالب</th>
                                        <th>المادة</th>
                                        <th>حالة التسجيل</th>
                                        <th>الدرجة</th>
                                        <th>تاريخ التسجيل</th>
                                        <th>آخر تحديث</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollments as $enrollment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-transparent rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <h6 class="mb-0">{{ $enrollment->student->user->name }}</h6>
                                                    <small class="text-muted">{{ $enrollment->student->student_id ?? 'غير محدد' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="font-weight-bold">{{ $enrollment->course->title }}</span>
                                                <br>
                                                <small class="text-muted">{{ $enrollment->course->course_code }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($enrollment->status == 'active')
                                                <span class="badge badge-success">نشط</span>
                                            @elseif($enrollment->status == 'completed')
                                                <span class="badge badge-primary">مكتمل</span>
                                            @elseif($enrollment->status == 'withdrawn')
                                                <span class="badge badge-danger">منسحب</span>
                                            @else
                                                <span class="badge badge-secondary">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($enrollment->grade !== null)
                                                <div class="d-flex align-items-center">
                                                    <span class="font-weight-bold mr-2">{{ $enrollment->grade }}%</span>
                                                    @if($enrollment->grade >= 90)
                                                        <span class="badge badge-success">ممتاز</span>
                                                    @elseif($enrollment->grade >= 80)
                                                        <span class="badge badge-primary">جيد جداً</span>
                                                    @elseif($enrollment->grade >= 70)
                                                        <span class="badge badge-info">جيد</span>
                                                    @elseif($enrollment->grade >= 60)
                                                        <span class="badge badge-warning">مقبول</span>
                                                    @else
                                                        <span class="badge badge-danger">راسب</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">لم يتم التقييم</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $enrollment->enrollment_date->format('Y-m-d') }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $enrollment->updated_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-warning" onclick="editGrade({{ $enrollment->id }}, {{ $enrollment->grade ?? 0 }})" title="تعديل الدرجة">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info" onclick="viewStudent({{ $enrollment->student->id }})" title="عرض ملف الطالب">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" onclick="sendNotification({{ $enrollment->student->id }})">
                                                            <i class="fas fa-bell"></i> إرسال إشعار
                                                        </a>
                                                        <a class="dropdown-item" href="#" onclick="viewProgress({{ $enrollment->id }})">
                                                            <i class="fas fa-chart-line"></i> متابعة التقدم
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        @if($enrollment->status == 'active')
                                                            <a class="dropdown-item text-success" href="#" onclick="completeEnrollment({{ $enrollment->id }})">
                                                                <i class="fas fa-check"></i> إكمال المادة
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $enrollments->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-graduate fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">لا يوجد طلاب مسجلين</h4>
                            <p class="text-muted">لم يتم تسجيل أي طالب في موادك بعد</p>
                            <a href="{{ route('teacher.my-courses') }}" class="btn btn-primary">
                                <i class="fas fa-book"></i> عرض موادي التدريسية
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Edit Modal -->
    <div class="modal fade" id="gradeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل الدرجة</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="gradeForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="grade">الدرجة (من 0 إلى 100)</label>
                            <input type="number" class="form-control" id="grade" name="grade" min="0" max="100" required>
                        </div>
                        <div class="form-group">
                            <label for="notes">ملاحظات (اختياري)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ الدرجة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
.icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.bg-primary-transparent {
    background-color: rgba(0, 123, 255, 0.1);
}

.bg-success-transparent {
    background-color: rgba(40, 167, 69, 0.1);
}

.bg-warning-transparent {
    background-color: rgba(255, 193, 7, 0.1);
}

.bg-info-transparent {
    background-color: rgba(23, 162, 184, 0.1);
}

.avatar-sm {
    width: 40px;
    height: 40px;
}
</style>
@endsection

@section('scripts')
<script>
let currentEnrollmentId = null;

function editGrade(enrollmentId, currentGrade) {
    currentEnrollmentId = enrollmentId;
    document.getElementById('grade').value = currentGrade;
    $('#gradeModal').modal('show');
}

document.getElementById('gradeForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const grade = document.getElementById('grade').value;
    const notes = document.getElementById('notes').value;

    // هنا يمكن إضافة AJAX call لحفظ الدرجة
    fetch(`/teacher/update-grade/${currentEnrollmentId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            grade: grade,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#gradeModal').modal('hide');
            location.reload();
        } else {
            alert('حدث خطأ في حفظ الدرجة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال');
    });
});

function viewStudent(studentId) {
    // فتح صفحة ملف الطالب
    window.open(`/student/profile/${studentId}`, '_blank');
}

function sendNotification(studentId) {
    // إرسال إشعار للطالب
    alert('سيتم تطوير وظيفة إرسال الإشعارات لاحقاً');
}

function viewProgress(enrollmentId) {
    // عرض تقدم الطالب
    alert('سيتم تطوير وظيفة متابعة التقدم لاحقاً');
}

function completeEnrollment(enrollmentId) {
    if (confirm('هل أنت متأكد من إكمال هذا الطالب للمادة؟')) {
        // تحديث حالة التسجيل إلى مكتمل
        fetch(`/teacher/complete-enrollment/${enrollmentId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ في تحديث الحالة');
            }
        });
    }
}

function exportStudents() {
    // تصدير قائمة الطلاب
    window.location.href = '/teacher/export-students?' + new URLSearchParams(window.location.search);
}

function printStudents() {
    // طباعة قائمة الطلاب
    window.print();
}
</script>
@endsection
