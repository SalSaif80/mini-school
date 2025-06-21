@extends('dashboard.layouts.master')
@section('css')
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأكاديمية</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ المواد الدراسية</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            {{-- @can('إنشاء مواد دراسية') --}}
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('courses.create') }}" class="btn btn-primary btn-icon ml-2">
                    <i class="mdi mdi-plus"></i> إضافة مادة جديدة
                </a>
            </div>
            {{-- @endcan --}}
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>نجح!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
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
                    <h3 class="card-title">المواد الدراسية</h3>
                    <div class="d-flex my-xl-auto right-content">
                        {{-- @can('إنشاء مواد دراسية') --}}
                        {{-- <div class="pr-1 mb-3 mb-xl-0"> --}}
                        <a href="{{ route('courses.create') }}" class="btn btn-primary ml-2">
                            <i class="mdi mdi-plus"></i> إضافة مادة جديدة
                        </a>
                        {{-- </div> --}}
                        {{-- @endcan --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th>رمز المادة</th>
                                    <th>اسم المادة</th>
                                    <th>المدرس</th>
                                    <th>الساعات المعتمدة</th>
                                    <th>المستوى</th>
                                    <th>عدد المسجلين</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courses as $course)
                                    <tr>
                                        <th scope="row">
                                            <span class="badge badge-primary">{{ $course->course_code }}</span>
                                        </th>
                                        <td>
                                            <strong>{{ $course->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($course->description, 50) }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">

                                                <div>
                                                    <h6 class="mb-0">{{ $course->teacher->user->name }}</h6>
                                                    <small class="text-muted">{{ $course->teacher->department }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $course->credit_hours }} ساعة</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $course->level == 'beginner' ? 'success' : ($course->level == 'intermediate' ? 'warning' : 'danger') }}">
                                                {{ $course->level == 'beginner' ? 'مبتدئ' : ($course->level == 'intermediate' ? 'متوسط' : 'متقدم') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">{{ $course->enrollments->count() }}</span>
                                        </td>
                                        <td>{{ $course->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                {{-- @can('عرض تفاصيل المادة') --}}
                                                <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info"
                                                    title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                {{-- @endcan --}}
                                                &nbsp;
                                                {{-- @can('تعديل المواد الدراسية') --}}
                                                <a href="{{ route('courses.edit', $course) }}"
                                                    class="btn btn-sm btn-warning" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                {{-- @endcan --}}
                                                &nbsp;
                                                {{-- @can('حذف المواد الدراسية') --}}
                                                @if ($course->enrollments()->count() > 0)
                                                    @include('dashboard.admin.courses.modals.cannot-delete')
                                                @else
                                                    @include('dashboard.admin.courses.modals.delete')
                                                @endif
                                                {{-- @endcan --}}
                                                &nbsp;
                                                {{-- @can('عرض قائمة الطلاب المسجلين') --}}
                                                <a href="{{ route('courses.students', $course) }}"
                                                    class="btn btn-sm btn-success" title="عرض الطلاب">
                                                    <i class="fas fa-users"></i>
                                                    <span
                                                        class="badge badge-light">{{ $course->enrollments->count() }}</span>
                                                </a>
                                                {{-- @endcan --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">لا توجد مواد متاحة</h5>
                                                {{-- @can('إنشاء مواد دراسية') --}}
                                                <a href="{{ route('courses.create') }}" class="btn btn-primary mt-2">
                                                    إضافة أول مادة
                                                </a>
                                                {{-- @endcan --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($courses->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $courses->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
@endsection

@section('js')
@endsection
