@extends('dashboard.layouts.master')
@section('title', 'موادي المسجلة')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">موادي المسجلة</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ دراستي</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('student.available-courses') }}" class="btn btn-primary btn-icon ml-2">
                    <i class="fas fa-plus"></i> تسجيل مادة جديدة
                </a>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- Student Info Card -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center">
                                <div class="ml-3">
                                    <img src="{{ asset('dashboard/assets/img/faces/1.jpg') }}" alt="avatar" class="avatar avatar-md rounded-circle">
                                </div>
                                <div>
                                    <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                                    <p class="text-muted mb-0">
                                        الرقم الجامعي: {{ $student->student_id }} - {{ $student->class_level }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-left">
                            <div class="row text-center">
                                <div class="col-4">
                                    <h4 class="text-primary">{{ $enrollments->where('status', 'active')->count() }}</h4>
                                    <small class="text-muted">نشطة</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-success">{{ $enrollments->where('status', 'completed')->count() }}</h4>
                                    <small class="text-muted">مكتملة</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-info">{{ $enrollments->total() }}</h4>
                                    <small class="text-muted">الإجمالي</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Courses -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">موادي المسجلة</h4>
                    <div class="card-options">
                        <div class="btn-group" role="group">
                            <a href="?status=all" class="btn btn-sm {{ !request('status') || request('status') == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                                الكل
                            </a>
                            <a href="?status=active" class="btn btn-sm {{ request('status') == 'active' ? 'btn-success' : 'btn-outline-success' }}">
                                النشطة
                            </a>
                            <a href="?status=completed" class="btn btn-sm {{ request('status') == 'completed' ? 'btn-info' : 'btn-outline-info' }}">
                                المكتملة
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($enrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>المادة</th>
                                        <th>الرمز</th>
                                        <th>الأستاذ</th>
                                        <th>الساعات</th>
                                        <th>تاريخ التسجيل</th>
                                        <th>الحالة</th>
                                        <th>الدرجة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollments as $enrollment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-transparent rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-book text-primary"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <h6 class="mb-0">{{ $enrollment->course->title }}</h6>
                                                    <small class="text-muted">{{ Str::limit($enrollment->course->description, 50) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $enrollment->course->course_code }}</span>
                                        </td>
                                        <td>{{ $enrollment->course->teacher->user->name ?? 'غير محدد' }}</td>
                                        <td>{{ $enrollment->course->credit_hours }} ساعة</td>
                                        <td>{{ $enrollment->enrollment_date ? $enrollment->enrollment_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>
                                            @if($enrollment->status == 'active')
                                                <span class="badge badge-success">نشط</span>
                                            @elseif($enrollment->status == 'completed')
                                                <span class="badge badge-primary">مكتمل</span>
                                            @else
                                                <span class="badge badge-danger">منسحب</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($enrollment->grade)
                                                @php
                                                    $gradeClass = $enrollment->grade >= 90 ? 'success' : ($enrollment->grade >= 80 ? 'primary' : ($enrollment->grade >= 70 ? 'warning' : 'danger'));
                                                @endphp
                                                <span class="badge badge-{{ $gradeClass }}">{{ $enrollment->grade }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-sm btn-info" title="عرض تفاصيل المادة">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('enrollments.show', $enrollment) }}" class="btn btn-sm btn-success" title="عرض تفاصيل التسجيل">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                                @if($enrollment->status == 'active')
                                                    <button class="btn btn-sm btn-warning" onclick="confirmWithdraw({{ $enrollment->id }})" title="الانسحاب من المادة">
                                                        <i class="fas fa-sign-out-alt"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $enrollments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">لا توجد مواد مسجلة</h4>
                            <p class="text-muted">لم تقم بالتسجيل في أي مادة بعد</p>
                            <a href="{{ route('student.available-courses') }}" class="btn btn-primary">
                                <i class="fas fa-search"></i> تصفح المواد المتاحة
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Withdraw Confirmation Modal -->
    <div class="modal fade" id="withdrawModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد الانسحاب</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>هل أنت متأكد من الانسحاب من هذه المادة؟</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>تحذير:</strong> لن تتمكن من التراجع عن هذا الإجراء
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <form id="withdrawForm" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">تأكيد الانسحاب</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function confirmWithdraw(enrollmentId) {
    const form = document.getElementById('withdrawForm');
    form.action = `/student/withdraw-course/${enrollmentId}`;
    $('#withdrawModal').modal('show');
}
</script>
@endsection
