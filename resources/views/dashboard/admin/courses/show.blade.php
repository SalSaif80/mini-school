@extends('dashboard.layouts.master')
@section('css')
<link href="{{URL::asset('dashboard/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الأكاديمية</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الدورات التدريبية / عرض الدورة</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <div class="pr-1 mb-3 mb-xl-0">
            <a href="{{ route('courses.index') }}" class="btn btn-secondary btn-icon ml-2">
                <i class="mdi mdi-arrow-right"></i> العودة للقائمة
            </a>
            @if(Auth::user()->user_type == 'admin' || $course->teacher_id == Auth::id())
            <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning btn-icon ml-2">
                <i class="mdi mdi-pencil"></i> تعديل
            </a>
            @endif
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

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>خطأ!</strong> {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <!-- Course Info Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $course->course_name }}</h3>
                <div class="card-options">
                    <span class="badge badge-primary">{{ $course->course_id }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">اسم الدورة</label>
                            <input type="text" class="form-control" value="{{ $course->course_name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">رقم القاعة</label>
                            <input type="text" class="form-control" value="{{ $course->room_number }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">المعلم</label>
                            <div class="d-flex align-items-center">
                                <img src="{{URL::asset('dashboard/assets/img/faces/6.jpg')}}" alt="img" class="avatar avatar-md rounded-circle mr-3">
                                <div>
                                    <h6 class="mb-0">{{ $course->teacher->name }}</h6>
                                    <small class="text-muted">{{ $course->teacher->email }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">تاريخ الإنشاء</label>
                            <input type="text" class="form-control" value="{{ $course->created_at->format('Y-m-d H:i') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">آخر تحديث</label>
                            <input type="text" class="form-control" value="{{ $course->updated_at->format('Y-m-d H:i') }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollments Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">الطلاب المسجلين ({{ $course->enrollments->count() }})</h3>
            </div>
            <div class="card-body">
                @if($course->enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom">
                        <thead>
                            <tr>
                                <th>الطالب</th>
                                <th>تاريخ التسجيل</th>
                                <th>الفصل الدراسي</th>
                                <th>الحالة</th>
                                <th>الدرجة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course->enrollments as $enrollment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{URL::asset('dashboard/assets/img/faces/6.jpg')}}" alt="img" class="avatar avatar-sm rounded-circle mr-2">
                                        <div>
                                            <h6 class="mb-0">{{ $enrollment->student->name }}</h6>
                                            <small class="text-muted">{{ $enrollment->student->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $enrollment->enrollment_date->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge badge-secondary">{{ $enrollment->semester }}</span>
                                </td>
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
                                        <span class="badge badge-warning">{{ $enrollment->grade }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا يوجد طلاب مسجلين في هذه الدورة</h5>
                </div>
                @endif
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">إحصائيات الدورة</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-body">
                            <div class="d-flex">
                                <div class="align-self-center">
                                    <div class="chart-circle-value text-center">
                                        <i class="fas fa-users text-primary fa-2x"></i>
                                    </div>
                                </div>
                                <div class="wrapper mr-3">
                                    <p class="mb-0 mt-1 text-muted">إجمالي المسجلين</p>
                                    <h3 class="mb-0 font-weight-semibold">{{ $course->enrollments->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-body">
                            <div class="d-flex">
                                <div class="align-self-center">
                                    <div class="chart-circle-value text-center">
                                        <i class="fas fa-play text-success fa-2x"></i>
                                    </div>
                                </div>
                                <div class="wrapper mr-3">
                                    <p class="mb-0 mt-1 text-muted">النشطين</p>
                                    <h3 class="mb-0 font-weight-semibold">{{ $course->enrollments->where('status', 'active')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-body">
                            <div class="d-flex">
                                <div class="align-self-center">
                                    <div class="chart-circle-value text-center">
                                        <i class="fas fa-check text-primary fa-2x"></i>
                                    </div>
                                </div>
                                <div class="wrapper mr-3">
                                    <p class="mb-0 mt-1 text-muted">المكتملين</p>
                                    <h3 class="mb-0 font-weight-semibold">{{ $course->enrollments->where('status', 'completed')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-body">
                            <div class="d-flex">
                                <div class="align-self-center">
                                    <div class="chart-circle-value text-center">
                                        <i class="fas fa-times text-danger fa-2x"></i>
                                    </div>
                                </div>
                                <div class="wrapper mr-3">
                                    <p class="mb-0 mt-1 text-muted">المنسحبين</p>
                                    <h3 class="mb-0 font-weight-semibold">{{ $course->enrollments->where('status', 'dropped')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($course->enrollments->whereNotNull('grade')->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>توزيع الدرجات</h5>
                        <div class="row">
                            @php
                                $grades = $course->enrollments->whereNotNull('grade')->groupBy('grade');
                            @endphp
                            @foreach(['A', 'B', 'C', 'D', 'F'] as $grade)
                                <div class="col-sm-6 col-md-2">
                                    <div class="card card-body text-center">
                                        <h4 class="mb-0 font-weight-semibold">{{ $grades->get($grade, collect())->count() }}</h4>
                                        <p class="mb-0 text-muted">درجة {{ $grade }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End Row -->

@endsection

@section('js')
<script src="{{URL::asset('dashboard/assets/plugins/select2/js/select2.min.js')}}"></script>
@endsection
