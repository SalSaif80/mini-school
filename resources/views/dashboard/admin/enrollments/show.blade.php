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
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ التسجيلات / عرض التسجيل</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <div class="pr-1 mb-3 mb-xl-0">
            <a href="{{ route('enrollments.index') }}" class="btn btn-secondary btn-icon ml-2">
                <i class="mdi mdi-arrow-right"></i> العودة للقائمة
            </a>
            @if(Auth::user()->user_type == 'admin' || (Auth::user()->user_type == 'teacher' && $enrollment->course->teacher_id == Auth::id()))
            <a href="{{ route('enrollments.edit', $enrollment) }}" class="btn btn-warning btn-icon ml-2">
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
        <!-- Enrollment Info Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">تفاصيل التسجيل</h3>
                <div class="card-options">
                    <span class="badge badge-primary">#{{ $enrollment->enrollment_id }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">الطالب</label>
                            <div class="d-flex align-items-center">
                                <img src="{{URL::asset('dashboard/assets/img/faces/6.jpg')}}" alt="img" class="avatar avatar-md rounded-circle mr-3">
                                <div>
                                    <h6 class="mb-0">{{ $enrollment->student->name }}</h6>
                                    <small class="text-muted">{{ $enrollment->student->email }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">الدورة</label>
                            <div>
                                <h6 class="mb-0">{{ $enrollment->course->course_name }}</h6>
                                <small class="text-muted">القاعة: {{ $enrollment->course->room_number }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">المعلم</label>
                            <div class="d-flex align-items-center">
                                <img src="{{URL::asset('dashboard/assets/img/faces/6.jpg')}}" alt="img" class="avatar avatar-md rounded-circle mr-3">
                                <div>
                                    <h6 class="mb-0">{{ $enrollment->course->teacher->name }}</h6>
                                    <small class="text-muted">{{ $enrollment->course->teacher->email }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">تاريخ التسجيل</label>
                            <input type="text" class="form-control" value="{{ $enrollment->enrollment_date->format('Y-m-d') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">الفصل الدراسي</label>
                            <input type="text" class="form-control" value="{{ $enrollment->semester }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">الحالة</label>
                            <div>
                                @if($enrollment->status == 'active')
                                    <span class="badge badge-success p-2">نشط</span>
                                @elseif($enrollment->status == 'completed')
                                    <span class="badge badge-primary p-2">مكتمل</span>
                                @else
                                    <span class="badge badge-danger p-2">منسحب</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($enrollment->grade)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">الدرجة</label>
                            <div>
                                <span class="badge badge-warning p-2 h5">{{ $enrollment->grade }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($enrollment->completion_date)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">تاريخ الإكمال</label>
                            <input type="text" class="form-control" value="{{ $enrollment->completion_date->format('Y-m-d') }}" readonly>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->

@endsection

@section('js')
<script src="{{URL::asset('dashboard/assets/plugins/select2/js/select2.min.js')}}"></script>
@endsection
