@extends('dashboard.layouts.master')

@section('css')
    <!-- Owl-carousel css -->
    <link href="{{ URL::asset('dashboard/assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <!-- Maps css -->
    <link href="{{ URL::asset('dashboard/assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('dashboard/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">نظام إدارة المدرسة</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ لوحة التحكم الرئيسية</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- Welcome Card -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                                </div>
                                <លتفاصيل

                                <div>
                                    <h3 class="mb-1">أهلاً وسهلاً، {{ Auth::user()->name }}</h3>
                                    <p class="mb-0 text-muted">
                                        مرحباً بك في نظام إدارة المدرسة
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <h5 class="text-muted">{{ now()->format('Y-m-d') }}</h5>
                            <p class="text-muted">{{ now()->locale('ar')->translatedFormat('l') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- General Statistics for All Users -->
        <div class="col-sm-6 col-md-3">
            <div class="card card-body">
                <div class="d-flex">
                    <div class="align-self-center">
                        <div class="chart-circle-value text-center">
                            <i class="fas fa-users text-primary fa-2x"></i>
                        </div>
                    </div>
                    <div class="wrapper mr-3">
                        <p class="mb-0 mt-1 text-muted">إجمالي المستخدمين</p>
                        <h3 class="mb-0 font-weight-semibold">{{ \App\Models\User::count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-body">
                <div class="d-flex">
                    <div class="align-self-center">
                        <div class="chart-circle-value text-center">
                            <i class="fas fa-chalkboard-teacher text-success fa-2x"></i>
                        </div>
                    </div>
                    <div class="wrapper mr-3">
                        <p class="mb-0 mt-1 text-muted">المعلمين</p>
                        <h3 class="mb-0 font-weight-semibold">{{ \App\Models\Teacher::count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-body">
                <div class="d-flex">
                    <div class="align-self-center">
                        <div class="chart-circle-value text-center">
                            <i class="fas fa-user-graduate text-info fa-2x"></i>
                        </div>
                    </div>
                    <div class="wrapper mr-3">
                        <p class="mb-0 mt-1 text-muted">الطلاب</p>
                        <h3 class="mb-0 font-weight-semibold">{{ \App\Models\Student::count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-body">
                <div class="d-flex">
                    <div class="align-self-center">
                        <div class="chart-circle-value text-center">
                            <i class="fas fa-book text-warning fa-2x"></i>
                        </div>
                    </div>
                    <div class="wrapper mr-3">
                        <p class="mb-0 mt-1 text-muted">المواد</p>
                        <h3 class="mb-0 font-weight-semibold">{{ \App\Models\Course::count() }}</h3>
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
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-book"></i> المواد
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('enrollments.index') }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-graduation-cap"></i> التسجيلات
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">آخر التسجيلات</h3>
                </div>
                <div class="card-body">
                    @php
                        $recentEnrollments = \App\Models\Enrollment::with(['student.user', 'course'])
                                                                 ->orderBy('created_at', 'desc')
                                                                 ->limit(5)
                                                                 ->get();
                    @endphp
                    @forelse($recentEnrollments as $enrollment)
                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3">
                                <i class="fas fa-user-graduate text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $enrollment->student->user->name ?? 'غير متوفر' }}</h6>
                                <small class="text-muted">سجل في {{ $enrollment->course->title ?? 'غير متوفر' }}</small>
                            </div>
                            <div>
                                <span class="badge badge-{{ $enrollment->status == 'active' ? 'success' : ($enrollment->status == 'completed' ? 'primary' : 'warning') }}">
                                    {{ $enrollment->status == 'active' ? 'نشط' : ($enrollment->status == 'completed' ? 'مكتمل' : 'منسحب') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">لا توجد تسجيلات حديثة</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إحصائيات التسجيلات</h3>
                </div>
                <div class="card-body">
                    @php
                        $enrollmentStats = [
                            'active' => \App\Models\Enrollment::where('status', 'active')->count(),
                            'completed' => \App\Models\Enrollment::where('status', 'completed')->count(),
                            'dropped' => \App\Models\Enrollment::where('status', 'dropped')->count(),
                        ];
                        $total = array_sum($enrollmentStats);
                    @endphp
                    @if($total > 0)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>نشط</span>
                                <span>{{ $enrollmentStats['active'] }}</span>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ ($enrollmentStats['active'] / $total) * 100 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>مكتمل</span>
                                <span>{{ $enrollmentStats['completed'] }}</span>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: {{ ($enrollmentStats['completed'] / $total) * 100 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>منسحب</span>
                                <span>{{ $enrollmentStats['dropped'] }}</span>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: {{ ($enrollmentStats['dropped'] / $total) * 100 }}%"></div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted text-center">لا توجد بيانات للعرض</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Internal Chart.bundle js -->
    <script src="{{ URL::asset('dashboard/assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Moment js -->
    <script src="{{ URL::asset('dashboard/assets/plugins/raphael/raphael.min.js') }}"></script>
    <!-- Internal Flot js -->
    <script src="{{ URL::asset('dashboard/assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/plugins/jquery.flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/plugins/jquery.flot/jquery.flot.categories.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/js/dashboard.sampledata.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/js/chart.flot.sampledata.js') }}"></script>
    <!-- Internal Apexchart js -->
    <script src="{{ URL::asset('dashboard/assets/js/apexcharts.js') }}"></script>
    <!-- Internal Map -->
    <script src="{{ URL::asset('dashboard/assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/js/modal-popup.js') }}"></script>
    <!-- Internal index js -->
    <script src="{{ URL::asset('dashboard/assets/js/index.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/js/jquery.vmap.sampledata.js') }}"></script>
    <!-- Internal Select2 js -->
    <script src="{{ URL::asset('dashboard/assets/plugins/select2/js/select2.min.js') }}"></script>
@endsection