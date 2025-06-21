@extends('dashboard.layouts.master')
@section('css')
<link href="{{URL::asset('dashboard/assets/plugins/chart.js/Chart.min.css')}}" rel="stylesheet">
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">لوحة تحكم الإدارة</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الرئيسية</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <div class="pr-1 mb-3 mb-xl-0">
            <button type="button" class="btn btn-warning  btn-icon ml-2">
                <i class="mdi mdi-filter-variant"></i> تصفية البيانات
            </button>
        </div>
        <div class="pr-1 mb-3 mb-xl-0">
            <button type="button" class="btn btn-primary btn-icon ml-2">
                <i class="mdi mdi-download"></i> تصدير التقرير
            </button>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')

<!-- Welcome Alert -->
<div class="alert alert-primary alert-dismissible fade show" role="alert">
    <strong><i class="fas fa-crown"></i> مرحباً {{ Auth::user()->name }}!</strong>
    أهلاً بك في لوحة تحكم الإدارة. يمكنك من هنا إدارة جميع أقسام المدرسة ومتابعة الإحصائيات.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card card-dashboard-eight bg-primary-gradient">
            <div class="card-header border-0">
                <h3 class="card-title text-white">إجمالي الطلاب</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="mt-0 text-left">
                            <span class="text-white">{{ \App\Models\Student::count() }}</span>
                            <h2 class="text-white mb-0">طالب</h2>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="icon1 mt-2 text-center">
                            <i class="fas fa-user-graduate text-white" style="font-size: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-dashboard-eight bg-success-gradient">
            <div class="card-header border-0">
                <h3 class="card-title text-white">إجمالي المعلمين</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="mt-0 text-left">
                            <span class="text-white">{{ \App\Models\Teacher::count() }}</span>
                            <h2 class="text-white mb-0">معلم</h2>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="icon1 mt-2 text-center">
                            <i class="fas fa-chalkboard-teacher text-white" style="font-size: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-dashboard-eight bg-warning-gradient">
            <div class="card-header border-0">
                <h3 class="card-title text-white">إجمالي المواد</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="mt-0 text-left">
                            <span class="text-white">{{ \App\Models\Course::count() }}</span>
                            <h2 class="text-white mb-0">مادة</h2>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="icon1 mt-2 text-center">
                            <i class="fas fa-book text-white" style="font-size: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-dashboard-eight bg-info-gradient">
            <div class="card-header border-0">
                <h3 class="card-title text-white">إجمالي التسجيلات</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="mt-0 text-left">
                            <span class="text-white">{{ \App\Models\Enrollment::count() }}</span>
                            <h2 class="text-white mb-0">تسجيل</h2>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="icon1 mt-2 text-center">
                            <i class="fas fa-clipboard-list text-white" style="font-size: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">الإجراءات السريعة</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('students.create') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-user-plus"></i><br>
                            إضافة طالب جديد
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('teachers.create') }}" class="btn btn-success btn-block">
                            <i class="fas fa-user-tie"></i><br>
                            إضافة معلم جديد
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('courses.create') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-book-open"></i><br>
                            إضافة مادة جديدة
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('enrollments.create') }}" class="btn btn-info btn-block">
                            <i class="fas fa-clipboard-check"></i><br>
                            تسجيل طالب في مادة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Management Sections -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">إدارة النظام</h3>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="fas fa-users text-primary"></i> إدارة المستخدمين</h5>
                            <small>{{ \App\Models\User::count() }} مستخدم</small>
                        </div>
                        <p class="mb-1">إدارة حسابات المستخدمين والصلاحيات</p>
                    </a>
                    <a href="{{ route('students.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="fas fa-user-graduate text-success"></i> إدارة الطلاب</h5>
                            <small>{{ \App\Models\Student::count() }} طالب</small>
                        </div>
                        <p class="mb-1">عرض وإدارة بيانات الطلاب</p>
                    </a>
                    <a href="{{ route('teachers.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="fas fa-chalkboard-teacher text-warning"></i> إدارة المعلمين</h5>
                            <small>{{ \App\Models\Teacher::count() }} معلم</small>
                        </div>
                        <p class="mb-1">عرض وإدارة بيانات المعلمين</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">إدارة المناهج</h3>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('courses.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="fas fa-book text-info"></i> إدارة المواد</h5>
                            <small>{{ \App\Models\Course::count() }} مادة</small>
                        </div>
                        <p class="mb-1">إضافة وإدارة المواد الدراسية</p>
                    </a>
                    <a href="{{ route('enrollments.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="fas fa-clipboard-list text-danger"></i> إدارة التسجيلات</h5>
                            <small>{{ \App\Models\Enrollment::count() }} تسجيل</small>
                        </div>
                        <p class="mb-1">متابعة تسجيل الطلاب في المواد</p>
                    </a>
                    <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="fas fa-shield-alt text-secondary"></i> إدارة الأدوار</h5>
                            <small>إدارة متقدمة</small>
                        </div>
                        <p class="mb-1">إدارة الأدوار والصلاحيات</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">الأنشطة الأخيرة</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">إضافة طالب جديد</h6>
                            <p>تم إضافة طالب جديد إلى النظام</p>
                            <small class="text-muted">منذ ساعتين</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">تسجيل في مادة</h6>
                            <p>تم تسجيل طالب في مادة الرياضيات</p>
                            <small class="text-muted">منذ 4 ساعات</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">إضافة معلم جديد</h6>
                            <p>تم إضافة معلم جديد للعلوم</p>
                            <small class="text-muted">أمس</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{URL::asset('dashboard/assets/plugins/chart.js/Chart.min.js')}}"></script>

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
    border-left: 4px solid #007bff;
}

.card-dashboard-eight {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
</style>
@endsection
