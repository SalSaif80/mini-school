@extends('dashboard.layouts.master')
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('dashboard/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('dashboard/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('dashboard/assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('dashboard/assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('dashboard/assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الأكاديمية</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ المواد الدراسية / {{ $course->title }} / الطلاب المسجلين</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <div class="pr-1 mb-3 mb-xl-0">
            <a href="{{ route('courses.index') }}" class="btn btn-secondary btn-icon ml-2">
                <i class="mdi mdi-arrow-right"></i> العودة للمواد
            </a>
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

<!-- Course Info Card -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h5 class="font-weight-bold text-primary">{{ $course->title }}</h5>
                        <p class="text-muted mb-1">رمز المادة: <span class="badge badge-primary">{{ $course->course_code }}</span></p>
                        <p class="text-muted mb-1">الساعات المعتمدة: <span class="badge badge-info">{{ $course->credit_hours }}</span></p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-muted mb-1">المدرس: <strong>{{ $course->teacher->user->name }}</strong></p>
                        <p class="text-muted mb-1">القسم: {{ $course->teacher->department }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-muted mb-1">عدد المسجلين: <span class="badge badge-success">{{ $course->enrollments->count() }}</span></p>
                        <p class="text-muted mb-1">المستوى:
                            <span class="badge badge-{{ $course->level == 'beginner' ? 'success' : ($course->level == 'intermediate' ? 'warning' : 'danger') }}">
                                {{ $course->level == 'beginner' ? 'مبتدئ' : ($course->level == 'intermediate' ? 'متوسط' : 'متقدم') }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-muted mb-1">تاريخ الإنشاء: {{ $course->created_at->format('Y-m-d') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Students Table -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">الطلاب المسجلين في المادة</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-md-nowrap" id="studentsTable">
                        <thead>
                            <tr>
                                <th class="wd-10p border-bottom-0">#</th>
                                <th class="wd-15p border-bottom-0">الطالب</th>
                                <th class="wd-15p border-bottom-0">البريد الإلكتروني</th>
                                <th class="wd-10p border-bottom-0">رقم الطالب</th>
                                <th class="wd-10p border-bottom-0">المستوى الدراسي</th>
                                <th class="wd-10p border-bottom-0">تاريخ التسجيل</th>
                                <th class="wd-10p border-bottom-0">حالة التسجيل</th>
                                <th class="wd-10p border-bottom-0">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($course->enrollments as $enrollment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{URL::asset('dashboard/assets/img/faces/6.jpg')}}" alt="img" class="avatar avatar-sm rounded-circle mr-2">
                                        <div>
                                            <h6 class="mb-0">{{ $enrollment->student->user->name }}</h6>
                                            <small class="text-muted">{{ $enrollment->student->major }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $enrollment->student->user->email }}</td>
                                <td>
                                    <span class="badge badge-secondary">{{ $enrollment->student->student_id }}</span>
                                </td>
                                <td>{{ $enrollment->student->class_level }}</td>
                                <td>{{ $enrollment->enrollment_date->format('Y-m-d') }}</td>
                                <td>
                                    @if($enrollment->status == 'active')
                                        <span class="badge badge-success">نشط</span>
                                    @elseif($enrollment->status == 'completed')
                                        <span class="badge badge-primary">مكتمل</span>
                                    @elseif($enrollment->status == 'dropped')
                                        <span class="badge badge-danger">منسحب</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $enrollment->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @can('تعديل التسجيلات')
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('enrollments.edit', $enrollment) }}" class="btn btn-sm btn-warning" title="تعديل التسجيل">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        @if($enrollment->status == 'active')
                                        <form action="{{ route('enrollments.mark-completed', $enrollment) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="تمت المادة">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('enrollments.mark-dropped', $enrollment) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" title="انسحاب من المادة">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">لا يوجد طلاب مسجلين في هذه المادة</h5>
                                        @can('إدارة تسجيل الطلاب')
                                        <a href="{{ route('enrollments.create') }}?course_id={{ $course->id }}" class="btn btn-primary mt-2">
                                            تسجيل طالب جديد
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>

<script>
$(document).ready(function() {
    $('#studentsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
        },
        "responsive": true,
        "autoWidth": false,
        "order": [[ 0, "asc" ]],
        "pageLength": 25
    });
});
</script>
@endsection
