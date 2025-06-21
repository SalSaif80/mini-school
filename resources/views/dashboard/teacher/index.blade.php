@extends('dashboard.layouts.master')
@section('css')
<link href="{{URL::asset('dashboard/assets/plugins/chart.js/Chart.min.css')}}" rel="stylesheet">
@endsection

@section('title', 'داشبورد المعلم')
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">لوحة تحكم المعلم</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ Auth::user()->name }}</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <span class="badge badge-success">معلم</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- Row -->
    <div class="row">
        <!-- ترحيب شخصي -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center">
                                <div class="ml-3">
                                    <img src="{{ asset('dashboard/assets/img/faces/6.jpg') }}" alt="avatar" class="avatar avatar-lg rounded-circle">
                                </div>
                                <div>
                                    <h4 class="mb-1">مرحباً أستاذ {{ Auth::user()->name }}</h4>
                                    <p class="text-muted mb-0">
                                        {{ Auth::user()->teacher ? Auth::user()->teacher->department : 'غير محدد' }} -
                                        {{ Auth::user()->teacher ? Auth::user()->teacher->specialization : 'غير محدد' }}
                                    </p>
                                    <small class="text-muted">معلم منذ {{ Auth::user()->teacher && Auth::user()->teacher->hire_date ? Auth::user()->teacher->hire_date->format('Y-m-d') : 'غير محدد' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-left">
                            <div class="mt-3 mt-lg-0">
                                <a href="{{ Auth::user()->teacher ? route('teachers.show', Auth::user()->teacher) : '#' }}" class="btn btn-primary">
                                    <i class="fas fa-user"></i> ملفي الشخصي
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الإحصائيات الشخصية -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-white mb-3">موادي التدريسية</h6>
                            <h2 class="text-white mb-0 number-font">
                                {{ Auth::user()->teacher ? Auth::user()->teacher->courses()->count() : 0 }}
                            </h2>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-primary">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-white mb-3">إجمالي طلابي</h6>
                            <h2 class="text-white mb-0 number-font">
                                {{ Auth::user()->teacher ? Auth::user()->teacher->courses()->withCount('enrollments')->get()->sum('enrollments_count') : 0 }}
                            </h2>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-danger">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-success-gradient">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-white mb-3">الطلاب النشطين</h6>
                            <h2 class="text-white mb-0 number-font">
                                {{ Auth::user()->teacher ? Auth::user()->teacher->courses()->with(['enrollments' => function($q) { $q->where('status', 'active'); }])->get()->sum(function($course) { return $course->enrollments->count(); }) : 0 }}
                            </h2>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-success">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-warning-gradient">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-white mb-3">المكتملين</h6>
                            <h2 class="text-white mb-0 number-font">
                                {{ Auth::user()->teacher ? Auth::user()->teacher->courses()->with(['enrollments' => function($q) { $q->where('status', 'completed'); }])->get()->sum(function($course) { return $course->enrollments->count(); }) : 0 }}
                            </h2>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-warning">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- موادي التدريسية -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">موادي التدريسية</h4>
                    <div class="card-options">
                        <a href="{{ route('courses.index') }}?teacher={{ Auth::user()->teacher->id ?? '' }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> عرض الكل
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(Auth::user()->teacher && Auth::user()->teacher->courses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>المادة</th>
                                        <th>الرمز</th>
                                        <th>الطلاب</th>
                                        <th>المستوى</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(Auth::user()->teacher->courses()->with('enrollments')->limit(5)->get() as $course)
                                    <tr>
                                        <td>{{ $course->title }}</td>
                                        <td><span class="badge badge-primary">{{ $course->course_code }}</span></td>
                                        <td>
                                            <span class="badge badge-info">{{ $course->enrollments->count() }} طالب</span>
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
                                        <td>
                                            <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('courses.students', $course) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-users"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-muted"></i>
                            <h5 class="mt-3">لا توجد مواد تدريسية حالياً</h5>
                            <p class="text-muted">تواصل مع الإدارة لتخصيص مواد دراسية لك</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- آخر التسجيلات -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">آخر التسجيلات</h4>
                </div>
                <div class="card-body">
                    @if(Auth::user()->teacher)
                        @php
                            $recentEnrollments = Auth::user()->teacher->courses()
                                ->with(['enrollments.student.user'])
                                ->get()
                                ->pluck('enrollments')
                                ->flatten()
                                ->sortByDesc('created_at')
                                ->take(5);
                        @endphp

                        @if($recentEnrollments->count() > 0)
                            @foreach($recentEnrollments as $enrollment)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-sm rounded-circle bg-primary text-white">
                                        {{ substr($enrollment->student->user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3 flex-grow-1">
                                        <h6 class="mb-0">{{ $enrollment->student->user->name }}</h6>
                                        <small class="text-muted">{{ $enrollment->course->title }}</small>
                                    </div>
                                    <div>
                                        @if($enrollment->status == 'active')
                                            <span class="badge badge-success">نشط</span>
                                        @elseif($enrollment->status == 'completed')
                                            <span class="badge badge-primary">مكتمل</span>
                                        @else
                                            <span class="badge badge-danger">منسحب</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center">
                                <i class="fas fa-clipboard-list fa-2x text-muted"></i>
                                <p class="text-muted mt-2">لا توجد تسجيلات</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- روابط سريعة -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">إجراءات سريعة</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('courses.index') }}?teacher={{ Auth::user()->teacher->id ?? '' }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-book"></i>
                                <br>موادي التدريسية
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('enrollments.index') }}?teacher={{ Auth::user()->teacher->id ?? '' }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-users"></i>
                                <br>طلابي
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-info btn-block">
                                <i class="fas fa-search"></i>
                                <br>تصفح المواد
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ Auth::user()->teacher ? route('teachers.show', Auth::user()->teacher) : '#' }}" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-user"></i>
                                <br>ملفي الشخصي
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="{{URL::asset('dashboard/assets/plugins/chart.js/Chart.min.js')}}"></script>

<script>
function viewStudents(courseId) {
    // يمكن تطوير هذه الوظيفة لاحقاً
    alert('عرض طلاب المقرر رقم: ' + courseId);
}

function manageGrades(courseId) {
    // يمكن تطوير هذه الوظيفة لاحقاً
    alert('إدارة درجات المقرر رقم: ' + courseId);
}

function viewAllStudents() {
    alert('عرض جميع الطلاب');
}

function manageAllGrades() {
    alert('إدارة جميع الدرجات');
}

function generateReport() {
    alert('تقرير الأداء');
}

function sendNotification() {
    alert('إرسال إشعار للطلاب');
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #28a745;
}

.card-dashboard-eight {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.avatar-sm {
    width: 40px;
    height: 40px;
}
</style>
@endsection
