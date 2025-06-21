@extends('dashboard.layouts.master')
@section('css')
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
        <div class="pr-1 mb-3 mb-xl-0">
            <a href="{{ route('enrollments.create') }}" class="btn btn-primary btn-icon ml-2">
                <i class="mdi mdi-plus"></i> إضافة تسجيل جديد
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

<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">التسجيلات</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 text-md-nowrap" id="example1">
                        <thead>
                            <tr>
                                <th>رقم التسجيل</th>
                                <th>الطالب</th>
                                <th>المادة</th>
                                <th>المدرس</th>
                                <th>تاريخ التسجيل</th>
                                <th>الحالة</th>
                                <th>الدرجة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $enrollment)
                            <tr>
                                <th scope="row">
                                    <span class="badge badge-info">#{{ $enrollment->id }}</span>
                                </th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $enrollment->student->user->name }}</h6>
                                            <small class="text-muted">{{ $enrollment->student->student_id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $enrollment->course->title }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $enrollment->course->course_code }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $enrollment->course->teacher->user->name }}</h6>
                                            <small class="text-muted">{{ $enrollment->course->teacher->department }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $enrollment->enrollment_date->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge badge-{{ $enrollment->status == 'active' ? 'success' : ($enrollment->status == 'completed' ? 'primary' : 'danger') }}">
                                        {{ $enrollment->status == 'active' ? 'نشط' : ($enrollment->status == 'completed' ? 'مكتمل' : 'منسحب') }}
                                    </span>
                                </td>
                                <td>
                                    @if($enrollment->grade)
                                        <span class="badge badge-secondary">{{ $enrollment->grade }}%</span>
                                    @else
                                        <span class="text-muted">لا توجد</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('enrollments.show', $enrollment) }}" class="btn btn-sm btn-info" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        &nbsp;
                                        <a href="{{ route('enrollments.edit', $enrollment) }}" class="btn btn-sm btn-warning" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        &nbsp;
                                        <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" style="display: inline;"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا التسجيل؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        &nbsp;
                                        @if($enrollment->status == 'active')
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#completeModal{{ $enrollment->id }}" title="إكمال">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            &nbsp;
                                            <form action="{{ route('enrollments.markDropped', $enrollment) }}" method="POST" style="display: inline;"
                                                  onsubmit="return confirm('هل أنت متأكد من تسجيل انسحاب الطالب؟')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-secondary" title="انسحاب">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Complete Modal -->
                            @if($enrollment->status == 'active')
                            <div class="modal fade" id="completeModal{{ $enrollment->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">إكمال المادة</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('enrollments.markCompleted', $enrollment) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="grade">الدرجة النهائية</label>
                                                    <input type="number" class="form-control" name="grade" min="0" max="100" step="0.01" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-success">حفظ الدرجة</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">لا توجد تسجيلات متاحة</h5>
                                        <a href="{{ route('enrollments.create') }}" class="btn btn-primary mt-2">
                                            إضافة أول تسجيل
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($enrollments->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $enrollments->links('pagination::bootstrap-5') }}
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
