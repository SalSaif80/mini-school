@extends('dashboard.layouts.master')

@section('title', 'داشبورد الطالب')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">لوحة تحكم الطالب</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ Auth::user()->name }}</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <span class="badge badge-primary">طالب</span>
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
                                    <img src="{{ asset('dashboard/assets/img/faces/1.jpg') }}" alt="avatar" class="avatar avatar-lg rounded-circle">
                                </div>
                                <div>
                                    <h4 class="mb-1">مرحباً {{ Auth::user()->name }}</h4>
                                    <p class="text-muted mb-0">
                                        الرقم الجامعي: {{ Auth::user()->student ? Auth::user()->student->student_id : 'غير محدد' }} -
                                        {{ Auth::user()->student ? Auth::user()->student->class_level : 'غير محدد' }}
                                    </p>
                                    <small class="text-muted">طالب منذ {{ Auth::user()->student && Auth::user()->student->enrollment_date ? Auth::user()->student->enrollment_date->format('Y-m-d') : 'غير محدد' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-left">
                            <div class="mt-3 mt-lg-0">
                                <a href="{{ Auth::user()->student ? route('students.show', Auth::user()->student) : '#' }}" class="btn btn-primary">
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
                            <h6 class="text-white mb-3">المواد المسجلة</h6>
                            <h2 class="text-white mb-0 number-font">
                                {{ Auth::user()->student ? Auth::user()->student->enrollments()->count() : 0 }}
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
            <div class="card overflow-hidden sales-card bg-success-gradient">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-white mb-3">المواد النشطة</h6>
                            <h2 class="text-white mb-0 number-font">
                                {{ Auth::user()->student ? Auth::user()->student->enrollments()->where('status', 'active')->count() : 0 }}
                            </h2>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-success">
                                <i class="fas fa-play-circle"></i>
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
                            <h6 class="text-white mb-3">المواد المكتملة</h6>
                            <h2 class="text-white mb-0 number-font">
                                {{ Auth::user()->student ? Auth::user()->student->enrollments()->where('status', 'completed')->count() : 0 }}
                            </h2>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-warning">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-info-gradient">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-white mb-3">معدلي التراكمي</h6>
                            <h2 class="text-white mb-0 number-font">
                                @php
                                    $completedEnrollments = Auth::user()->student ? Auth::user()->student->enrollments()->where('status', 'completed')->whereNotNull('grade')->get() : collect();
                                    $gpa = $completedEnrollments->count() > 0 ? $completedEnrollments->avg('grade') : 0;
                                @endphp
                                {{ number_format($gpa, 1) }}
                            </h2>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-info">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- موادي الحالية -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">موادي الحالية</h4>
                    <div class="card-options">
                        <a href="{{ route('enrollments.index') }}?student={{ Auth::user()->student->id ?? '' }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> عرض الكل
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(Auth::user()->student && Auth::user()->student->enrollments()->where('status', 'active')->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>المادة</th>
                                        <th>الرمز</th>
                                        <th>الأستاذ</th>
                                        <th>الساعات</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(Auth::user()->student->enrollments()->with(['course.teacher.user'])->where('status', 'active')->limit(5)->get() as $enrollment)
                                    <tr>
                                        <td>{{ $enrollment->course->title }}</td>
                                        <td><span class="badge badge-primary">{{ $enrollment->course->course_code }}</span></td>
                                        <td>{{ $enrollment->course->teacher->user->name ?? 'غير محدد' }}</td>
                                        <td>{{ $enrollment->course->credit_hours }} ساعة</td>
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
                                            <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('enrollments.show', $enrollment) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted"></i>
                            <h5 class="mt-3">لا توجد مواد مسجلة حالياً</h5>
                            <p class="text-muted">يمكنك التسجيل في مواد جديدة من قائمة المواد المتاحة</p>
                            <a href="{{ route('courses.index') }}" class="btn btn-primary">
                                <i class="fas fa-search"></i> تصفح المواد المتاحة
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- درجاتي الأخيرة -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">درجاتي الأخيرة</h4>
                </div>
                <div class="card-body">
                    @if(Auth::user()->student)
                        @php
                            $recentGrades = Auth::user()->student->enrollments()
                                ->with(['course'])
                                ->where('status', 'completed')
                                ->whereNotNull('grade')
                                ->orderBy('updated_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp

                        @if($recentGrades->count() > 0)
                            @foreach($recentGrades as $enrollment)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $enrollment->course->title }}</h6>
                                        <small class="text-muted">{{ $enrollment->course->course_code }}</small>
                                    </div>
                                    <div>
                                        @php
                                            $grade = $enrollment->grade;
                                            $gradeClass = $grade >= 90 ? 'success' : ($grade >= 80 ? 'primary' : ($grade >= 70 ? 'warning' : 'danger'));
                                        @endphp
                                        <span class="badge badge-{{ $gradeClass }}">{{ $grade }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center">
                                <i class="fas fa-chart-bar fa-2x text-muted"></i>
                                <p class="text-muted mt-2">لا توجد درجات</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- تقدمي الأكاديمي -->
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">تقدمي الأكاديمي</h4>
                </div>
                <div class="card-body">
                    @if(Auth::user()->student)
                        @php
                            $totalEnrollments = Auth::user()->student->enrollments()->count();
                            $completedCount = Auth::user()->student->enrollments()->where('status', 'completed')->count();
                            $activeCount = Auth::user()->student->enrollments()->where('status', 'active')->count();
                            $progress = $totalEnrollments > 0 ? ($completedCount / $totalEnrollments) * 100 : 0;
                        @endphp

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>التقدم الإجمالي</span>
                                <span>{{ number_format($progress, 1) }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-4">
                                <h5 class="text-success">{{ $completedCount }}</h5>
                                <small class="text-muted">مكتملة</small>
                            </div>
                            <div class="col-4">
                                <h5 class="text-primary">{{ $activeCount }}</h5>
                                <small class="text-muted">حالية</small>
                            </div>
                            <div class="col-4">
                                <h5 class="text-info">{{ number_format($gpa, 1) }}</h5>
                                <small class="text-muted">المعدل</small>
                            </div>
                        </div>
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
                            <a href="{{ route('enrollments.index') }}?student={{ Auth::user()->student->id ?? '' }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-book"></i>
                                <br>موادي المسجلة
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-search"></i>
                                <br>تصفح المواد
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('enrollments.create') }}" class="btn btn-outline-info btn-block">
                                <i class="fas fa-plus"></i>
                                <br>تسجيل مادة جديدة
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ Auth::user()->student ? route('students.show', Auth::user()->student) : '#' }}" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-user"></i>
                                <br>ملفي الشخصي
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- آخر الأنشطة -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">آخر أنشطتي</h4>
                </div>
                <div class="card-body">
                    @if(Auth::user()->student)
                        @php
                            $recentActivities = Auth::user()->student->enrollments()
                                ->with(['course'])
                                ->orderBy('updated_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp

                        @if($recentActivities->count() > 0)
                            <div class="timeline timeline-one-side">
                                @foreach($recentActivities as $enrollment)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-{{ $enrollment->status == 'completed' ? 'success' : ($enrollment->status == 'active' ? 'primary' : 'warning') }}"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">{{ $enrollment->course->title }}</h6>
                                            <p>
                                                @if($enrollment->status == 'completed')
                                                    اكتملت المادة بدرجة {{ $enrollment->grade ?? 'غير محددة' }}
                                                @elseif($enrollment->status == 'active')
                                                    مسجل في المادة
                                                @else
                                                    انسحبت من المادة
                                                @endif
                                            </p>
                                            <small class="text-muted">{{ $enrollment->updated_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-3x text-muted"></i>
                                <h5 class="mt-3">لا توجد أنشطة حديثة</h5>
                                <p class="text-muted">ستظهر هنا أحدث أنشطتك الأكاديمية</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
