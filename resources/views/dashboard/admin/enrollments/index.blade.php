@extends('dashboard.layouts.master')
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('dashboard/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('dashboard/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('dashboard/assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('dashboard/assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('dashboard/assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('dashboard/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الأكاديمية</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ التسجيلات</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        @if(Auth::user()->user_type == 'admin')
        <div class="pr-1 mb-3 mb-xl-0">
            <a href="{{ route('enrollments.create') }}" class="btn btn-primary btn-icon ml-2">
                <i class="mdi mdi-plus"></i> تسجيل جديد
            </a>
        </div>
        @endif
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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">التسجيلات</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-md-nowrap" id="example1">
                        <thead>
                            <tr>
                                <th class="wd-10p border-bottom-0">#</th>
                                <th class="wd-15p border-bottom-0">الطالب</th>
                                <th class="wd-15p border-bottom-0">الدورة</th>
                                <th class="wd-10p border-bottom-0">المعلم</th>
                                <th class="wd-10p border-bottom-0">تاريخ التسجيل</th>
                                <th class="wd-10p border-bottom-0">الفصل الدراسي</th>
                                <th class="wd-10p border-bottom-0">الحالة</th>
                                <th class="wd-10p border-bottom-0">الدرجة</th>
                                <th class="wd-10p border-bottom-0">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->enrollment_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{URL::asset('dashboard/assets/img/faces/6.jpg')}}" alt="img" class="avatar avatar-sm rounded-circle mr-2">
                                        <div>
                                            <h6 class="mb-0">{{ $enrollment->student->name }}</h6>
                                            <small class="text-muted">{{ $enrollment->student->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $enrollment->course->course_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $enrollment->course->room_number }}</small>
                                </td>
                                <td>{{ $enrollment->course->teacher->name }}</td>
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
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('enrollments.show', $enrollment) }}" class="btn btn-sm btn-info" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if(Auth::user()->user_type == 'admin' || (Auth::user()->user_type == 'teacher' && $enrollment->course->teacher_id == Auth::id()))
                                        <a href="{{ route('enrollments.edit', $enrollment) }}" class="btn btn-sm btn-warning" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        @if($enrollment->status == 'active')
                                        <button type="button" class="btn btn-sm btn-success" title="تسجيل إكمال"
                                                data-toggle="modal" data-target="#completeModal{{ $enrollment->enrollment_id }}">
                                            <i class="fas fa-check"></i>
                                        </button>

                                        <form action="{{ route('enrollments.mark-dropped', $enrollment) }}" method="POST" style="display: inline;"
                                              onsubmit="return confirm('هل أنت متأكد من تسجيل انسحاب الطالب؟')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" title="تسجيل انسحاب">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @endif

                                        @if(Auth::user()->user_type == 'admin')
                                        <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" style="display: inline;"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا التسجيل؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-dark" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">لا توجد تسجيلات متاحة</h5>
                                        @if(Auth::user()->user_type == 'admin')
                                        <a href="{{ route('enrollments.create') }}" class="btn btn-primary mt-2">
                                            إضافة أول تسجيل
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($enrollments->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $enrollments->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End Row -->

@endsection

@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('dashboard/assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('dashboard/assets/js/table-data.js')}}"></script>

<script>
$(document).ready(function() {
    $('#example1').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
        },
        "responsive": true,
        "autoWidth": false,
    });
});
</script>
@endsection
