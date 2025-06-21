@extends('dashboard.layouts.master')
@section('title', 'موادي التدريسية')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">موادي التدريسية</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عملي</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('teacher.browse-courses') }}" class="btn btn-info btn-icon ml-2">
                    <i class="fas fa-search"></i> تصفح جميع المواد
                </a>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- Teacher Info Card -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-teacher-gradient">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center">
                                <div class="ml-3">
                                    <img src="{{ asset('dashboard/assets/img/faces/6.jpg') }}" alt="avatar" class="avatar avatar-lg rounded-circle border border-white">
                                </div>
                                <div class="text-white">
                                    <h4 class="mb-1 text-white">أستاذ {{ Auth::user()->name }}</h4>
                                    <p class="text-white-50 mb-0">
                                        {{ $teacher->department ?? 'غير محدد' }} - {{ $teacher->specialization ?? 'غير محدد' }}
                                    </p>
                                    <small class="text-white-50">معلم منذ {{ $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : 'غير محدد' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-left">
                            <div class="row text-center text-white">
                                <div class="col-4">
                                    <h3 class="text-white">{{ $courses->total() }}</h3>
                                    <small class="text-white-50">مادة</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="text-white">{{ $courses->sum('enrollments_count') }}</h3>
                                    <small class="text-white-50">طالب</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="text-white">{{ $teacher->experience_years ?? 0 }}</h3>
                                    <small class="text-white-50">سنة خبرة</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Statistics -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-2">إجمالي موادي</h6>
                            <h3 class="mb-0 text-primary">{{ $courses->total() }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary-transparent text-primary">
                                <i class="fas fa-book"></i>
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
                            <h6 class="text-muted mb-2">إجمالي طلابي</h6>
                            <h3 class="mb-0 text-success">{{ $courses->sum('enrollments_count') }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success-transparent text-success">
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
                            <h6 class="text-muted mb-2">متوسط الطلاب</h6>
                            <h3 class="mb-0 text-warning">
                                {{ $courses->count() > 0 ? round($courses->sum('enrollments_count') / $courses->count(), 1) : 0 }}
                            </h3>
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
                            <h6 class="text-muted mb-2">إجمالي الساعات</h6>
                            <h3 class="mb-0 text-info">{{ $courses->sum('credit_hours') }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-info-transparent text-info">
                                <i class="fas fa-clock"></i>
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
                    <h4 class="card-title">موادي التدريسية</h4>
                    <div class="card-options">
                        <div class="btn-group" role="group">
                            <a href="?level=all" class="btn btn-sm {{ !request('level') || request('level') == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                                جميع المستويات
                            </a>
                            <a href="?level=beginner" class="btn btn-sm {{ request('level') == 'beginner' ? 'btn-success' : 'btn-outline-success' }}">
                                مبتدئ
                            </a>
                            <a href="?level=intermediate" class="btn btn-sm {{ request('level') == 'intermediate' ? 'btn-warning' : 'btn-outline-warning' }}">
                                متوسط
                            </a>
                            <a href="?level=advanced" class="btn btn-sm {{ request('level') == 'advanced' ? 'btn-danger' : 'btn-outline-danger' }}">
                                متقدم
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($courses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>المادة</th>
                                        <th>الرمز</th>
                                        <th>المستوى</th>
                                        <th>الساعات</th>
                                        <th>الطلاب المسجلين</th>
                                        <th>آخر نشاط</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($courses as $course)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-transparent rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-book text-primary"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <h6 class="mb-0">{{ $course->title }}</h6>
                                                    <small class="text-muted">{{ Str::limit($course->description, 50) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $course->course_code }}</span>
                                        </td>
                                        <td>
                                            @if($course->level == 'beginner')
                                                <span class="badge badge-success">مبتدئ</span>
                                            @elseif($course->level == 'intermediate')
                                                <span class="badge badge-warning">متوسط</span>
                                            @else
                                                <span class="badge badge-danger">متقدم</span>
                                            @endif
                                        </td>
                                        <td>{{ $course->credit_hours }} ساعة</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge-info mr-2">{{ $course->enrollments_count }}</span>
                                                <div class="progress flex-grow-1" style="height: 6px;">
                                                    @php
                                                        $percentage = $course->max_students ? ($course->enrollments_count / $course->max_students) * 100 : 0;
                                                    @endphp
                                                    <div class="progress-bar bg-info" style="width: {{ min($percentage, 100) }}%"></div>
                                                </div>
                                                @if($course->max_students)
                                                    <small class="text-muted ml-2">/ {{ $course->max_students }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $course->updated_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info" title="عرض تفاصيل المادة">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('teacher.course.students', $course) }}" class="btn btn-sm btn-success" title="إدارة الطلاب">
                                                    <i class="fas fa-users"></i>
                                                </a>
                                                <button class="btn btn-sm btn-warning" onclick="showGradeModal({{ $course->id }})" title="إدارة الدرجات">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </button>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">
                                                            <i class="fas fa-chart-bar"></i> إحصائيات المادة
                                                        </a>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="fas fa-download"></i> تصدير قائمة الطلاب
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="fas fa-bell"></i> إرسال إشعار للطلاب
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

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $courses->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">لا توجد مواد مُسندة إليك</h4>
                            <p class="text-muted">يرجى التواصل مع الإدارة لإسناد مواد تدريسية</p>
                            <a href="{{ route('teacher.browse-courses') }}" class="btn btn-primary">
                                <i class="fas fa-search"></i> تصفح جميع المواد
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
.bg-teacher-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.course-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.course-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
</style>
@endsection

@section('scripts')
<script>
function showGradeModal(courseId) {
    // سيتم تطوير هذه الوظيفة لاحقاً
    alert('سيتم فتح نافذة إدارة الدرجات للمادة رقم: ' + courseId);
}
</script>
@endsection
