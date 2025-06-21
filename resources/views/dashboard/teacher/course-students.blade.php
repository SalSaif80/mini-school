@extends('dashboard.layouts.master')
@section('title', 'طلاب مادة: ' . $course->title)

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">طلاب مادة: {{ $course->title }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عملي / موادي التدريسية</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('teacher.my-courses') }}" class="btn btn-primary btn-icon ml-2">
                    <i class="fas fa-arrow-left"></i> العودة لموادي
                </a>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- Course Info -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-primary-gradient">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center text-white">
                                <div class="ml-3">
                                    <div class="avatar-lg bg-white-transparent rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-book text-white fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-white mb-1">{{ $course->title }}</h4>
                                    <p class="text-white-50 mb-1">{{ $course->course_code }} - {{ $course->credit_hours }} ساعة معتمدة</p>
                                    <small class="text-white-50">{{ $course->description }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-left">
                            <div class="row text-center text-white">
                                <div class="col-6">
                                    <h3 class="text-white">{{ $enrollments->count() }}</h3>
                                    <small class="text-white-50">طالب مسجل</small>
                                </div>
                                <div class="col-6">
                                    <h3 class="text-white">{{ $averageGrade }}%</h3>
                                    <small class="text-white-50">متوسط الدرجات</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
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
                            <h6 class="text-muted mb-2">المكتملين</h6>
                            <h3 class="mb-0 text-primary">{{ $completedStudents }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary-transparent text-primary">
                                <i class="fas fa-graduation-cap"></i>
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
                            <h6 class="text-muted mb-2">المنسحبين</h6>
                            <h3 class="mb-0 text-warning">{{ $withdrawnStudents }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning-transparent text-warning">
                                <i class="fas fa-user-times"></i>
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
                            <h6 class="text-muted mb-2">معدل الحضور</h6>
                            <h3 class="mb-0 text-info">{{ $attendanceRate }}%</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-info-transparent text-info">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Management -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">إدارة طلاب المادة</h4>
                    <div class="card-options">
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-success" onclick="bulkGradeUpdate()">
                                <i class="fas fa-edit"></i> تحديث الدرجات
                            </button>
                            <button class="btn btn-sm btn-info" onclick="exportGrades()">
                                <i class="fas fa-download"></i> تصدير الدرجات
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="sendBulkNotification()">
                                <i class="fas fa-bell"></i> إشعار جماعي
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($enrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" id="studentsTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                        </th>
                                        <th>الطالب</th>
                                        <th>الرقم الجامعي</th>
                                        <th>حالة التسجيل</th>
                                        <th>الدرجة</th>
                                        <th>التقدير</th>
                                        <th>تاريخ التسجيل</th>
                                        <th>آخر تحديث</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollments as $enrollment)
                                    <tr id="row-{{ $enrollment->id }}">
                                        <td>
                                            <input type="checkbox" class="student-checkbox" value="{{ $enrollment->id }}">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-transparent rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <h6 class="mb-0">{{ $enrollment->student->user->name }}</h6>
                                                    <small class="text-muted">{{ $enrollment->student->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-outline-primary">{{ $enrollment->student->student_id ?? 'غير محدد' }}</span>
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
                                            <div class="grade-display" id="grade-display-{{ $enrollment->id }}">
                                                @if($enrollment->grade !== null)
                                                    <span class="font-weight-bold">{{ $enrollment->grade }}%</span>
                                                    <button class="btn btn-xs btn-outline-warning ml-2" onclick="editGradeInline({{ $enrollment->id }}, {{ $enrollment->grade }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @else
                                                    <span class="text-muted">لم يتم التقييم</span>
                                                    <button class="btn btn-xs btn-outline-success ml-2" onclick="editGradeInline({{ $enrollment->id }}, 0)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            <div class="grade-edit" id="grade-edit-{{ $enrollment->id }}" style="display: none;">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" class="form-control" id="grade-input-{{ $enrollment->id }}"
                                                           min="0" max="100" value="{{ $enrollment->grade ?? 0 }}">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-success" onclick="saveGradeInline({{ $enrollment->id }})">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button class="btn btn-secondary" onclick="cancelGradeEdit({{ $enrollment->id }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($enrollment->grade !== null)
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
                                            @else
                                                <span class="text-muted">-</span>
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
                                                <button class="btn btn-sm btn-info" onclick="viewStudentProfile({{ $enrollment->student->id }})" title="عرض الملف الشخصي">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" onclick="sendNotification({{ $enrollment->student->id }})" title="إرسال إشعار">
                                                    <i class="fas fa-bell"></i>
                                                </button>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        @if($enrollment->status == 'active')
                                                            <a class="dropdown-item text-success" href="#" onclick="completeStudent({{ $enrollment->id }})">
                                                                <i class="fas fa-check"></i> إكمال المادة
                                                            </a>
                                                            <a class="dropdown-item text-warning" href="#" onclick="withdrawStudent({{ $enrollment->id }})">
                                                                <i class="fas fa-user-times"></i> تسجيل انسحاب
                                                            </a>
                                                        @elseif($enrollment->status == 'withdrawn')
                                                            <a class="dropdown-item text-primary" href="#" onclick="reactivateStudent({{ $enrollment->id }})">
                                                                <i class="fas fa-user-check"></i> إعادة تفعيل
                                                            </a>
                                                        @endif
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#" onclick="viewProgress({{ $enrollment->id }})">
                                                            <i class="fas fa-chart-line"></i> متابعة التقدم
                                                        </a>
                                                        <a class="dropdown-item" href="#" onclick="viewAttendance({{ $enrollment->id }})">
                                                            <i class="fas fa-calendar-check"></i> سجل الحضور
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">لا يوجد طلاب مسجلين في هذه المادة</h4>
                            <p class="text-muted">لم يتم تسجيل أي طالب في هذه المادة بعد</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Statistics Chart -->
    @if($enrollments->count() > 0)
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">توزيع الدرجات</h4>
                </div>
                <div class="card-body">
                    <canvas id="gradesChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">حالات التسجيل</h4>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('styles')
<style>
.bg-primary-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-white-transparent {
    background-color: rgba(255, 255, 255, 0.1);
}

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

.avatar-lg {
    width: 80px;
    height: 80px;
}

.grade-edit .input-group {
    max-width: 150px;
}

#studentsTable tbody tr:hover {
    background-color: #f8f9fa;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Select All Functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.student-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// Inline Grade Editing
function editGradeInline(enrollmentId, currentGrade) {
    document.getElementById(`grade-display-${enrollmentId}`).style.display = 'none';
    document.getElementById(`grade-edit-${enrollmentId}`).style.display = 'block';
    document.getElementById(`grade-input-${enrollmentId}`).focus();
}

function cancelGradeEdit(enrollmentId) {
    document.getElementById(`grade-display-${enrollmentId}`).style.display = 'block';
    document.getElementById(`grade-edit-${enrollmentId}`).style.display = 'none';
}

function saveGradeInline(enrollmentId) {
    const grade = document.getElementById(`grade-input-${enrollmentId}`).value;

    if (grade < 0 || grade > 100) {
        alert('الدرجة يجب أن تكون بين 0 و 100');
        return;
    }

    fetch(`/teacher/update-grade/${enrollmentId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ grade: grade })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('حدث خطأ في حفظ الدرجة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال');
    });
}

// Student Actions
function viewStudentProfile(studentId) {
    window.open(`/student/profile/${studentId}`, '_blank');
}

function sendNotification(studentId) {
    // سيتم تطوير هذه الوظيفة لاحقاً
    alert('سيتم تطوير وظيفة إرسال الإشعارات لاحقاً');
}

function completeStudent(enrollmentId) {
    if (confirm('هل أنت متأكد من إكمال هذا الطالب للمادة؟')) {
        updateStudentStatus(enrollmentId, 'completed');
    }
}

function withdrawStudent(enrollmentId) {
    if (confirm('هل أنت متأكد من تسجيل انسحاب هذا الطالب؟')) {
        updateStudentStatus(enrollmentId, 'withdrawn');
    }
}

function reactivateStudent(enrollmentId) {
    if (confirm('هل تريد إعادة تفعيل هذا الطالب؟')) {
        updateStudentStatus(enrollmentId, 'active');
    }
}

function updateStudentStatus(enrollmentId, status) {
    fetch(`/teacher/update-enrollment-status/${enrollmentId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
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

// Bulk Actions
function bulkGradeUpdate() {
    const selected = getSelectedStudents();
    if (selected.length === 0) {
        alert('يرجى اختيار طالب واحد على الأقل');
        return;
    }

    const grade = prompt('أدخل الدرجة (0-100):');
    if (grade && grade >= 0 && grade <= 100) {
        // تحديث الدرجات للطلاب المحددين
        alert(`سيتم تحديث درجات ${selected.length} طالب إلى ${grade}%`);
    }
}

function exportGrades() {
    window.location.href = `/teacher/export-course-grades/{{ $course->id }}`;
}

function sendBulkNotification() {
    const selected = getSelectedStudents();
    if (selected.length === 0) {
        alert('يرجى اختيار طالب واحد على الأقل');
        return;
    }

    const message = prompt('أدخل نص الإشعار:');
    if (message) {
        alert(`سيتم إرسال إشعار إلى ${selected.length} طالب`);
    }
}

function getSelectedStudents() {
    const checkboxes = document.querySelectorAll('.student-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

// Charts
@if($enrollments->count() > 0)
// Grades Distribution Chart
const gradesCtx = document.getElementById('gradesChart').getContext('2d');
const gradesChart = new Chart(gradesCtx, {
    type: 'bar',
    data: {
        labels: ['90-100%', '80-89%', '70-79%', '60-69%', '0-59%', 'غير مقيم'],
        datasets: [{
            label: 'عدد الطلاب',
            data: [
                {{ $gradeDistribution['excellent'] ?? 0 }},
                {{ $gradeDistribution['very_good'] ?? 0 }},
                {{ $gradeDistribution['good'] ?? 0 }},
                {{ $gradeDistribution['acceptable'] ?? 0 }},
                {{ $gradeDistribution['fail'] ?? 0 }},
                {{ $gradeDistribution['not_graded'] ?? 0 }}
            ],
            backgroundColor: [
                '#28a745',
                '#007bff',
                '#17a2b8',
                '#ffc107',
                '#dc3545',
                '#6c757d'
            ]
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['نشط', 'مكتمل', 'منسحب'],
        datasets: [{
            data: [{{ $activeStudents }}, {{ $completedStudents }}, {{ $withdrawnStudents }}],
            backgroundColor: ['#28a745', '#007bff', '#ffc107']
        }]
    },
    options: {
        responsive: true
    }
});
@endif
</script>
@endsection
</rewritten_file>
