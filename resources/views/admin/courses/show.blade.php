@extends('layouts.app')

@section('title', 'عرض الكورس')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-eye me-2"></i>تفاصيل الكورس</h2>
        <div>
            <a href="{{ route('admin.courses.edit', $course->course_id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>تعديل
            </a>
            <a href="{{ route('admin.courses') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row">
        <!-- معلومات الكورس -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات الكورس</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>اسم الكورس:</strong></td>
                            <td>{{ $course->course_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>المدرس المسؤول:</strong></td>
                            <td>
                                @if($course->teacher)
                                    <span class="badge bg-success">{{ $course->teacher->name }}</span>
                                @else
                                    <span class="badge bg-warning">غير محدد</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>موعد المحاضرة:</strong></td>
                            <td>{{ $course->schedule_date->format('l, Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>رقم الغرفة:</strong></td>
                            <td><span class="badge bg-info">{{ $course->room_number }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>تاريخ الإنشاء:</strong></td>
                            <td>{{ $course->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- إحصائيات الكورس -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>إحصائيات الطلاب</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h4 class="text-primary">{{ $stats['total_students'] }}</h4>
                            <small class="text-muted">إجمالي الطلاب</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-success">{{ $stats['active_students'] }}</h4>
                            <small class="text-muted">طلاب نشطون</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info">{{ $stats['completed_students'] }}</h4>
                            <small class="text-muted">مكملون</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-danger">{{ $stats['dropped_students'] }}</h4>
                            <small class="text-muted">منسحبون</small>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid">
                        <a href="{{ route('admin.courses.students', $course->course_id) }}" class="btn btn-primary">
                            <i class="fas fa-users me-2"></i>عرض جميع الطلاب
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- آخر الأنشطة -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>آخر الأنشطة المتعلقة بالكورس</h5>
        </div>
        <div class="card-body">
            @if($course->enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>الطالب</th>
                                <th>تاريخ التسجيل</th>
                                <th>الحالة</th>
                                <th>الدرجة</th>
                                <th>آخر تحديث</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course->enrollments->sortByDesc('updated_at')->take(10) as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->student->name }}</td>
                                    <td>{{ $enrollment->enrollment_date->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge
                                            @if($enrollment->status == 'active') bg-primary
                                            @elseif($enrollment->status == 'completed') bg-success
                                            @elseif($enrollment->status == 'failed') bg-danger
                                            @else bg-warning
                                            @endif">
                                            @if($enrollment->status == 'active') نشط
                                            @elseif($enrollment->status == 'completed') مكتمل
                                            @elseif($enrollment->status == 'failed') راسب
                                            @else مسحوب
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if($enrollment->grade)
                                            <span class="badge bg-info">{{ $enrollment->grade }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $enrollment->updated_at->diffForHumans(['locale' => 'ar']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                    <h5>لا يوجد طلاب مسجلين</h5>
                    <p class="text-muted">لم يسجل أي طالب في هذا الكورس بعد</p>
                </div>
            @endif
        </div>
    </div>
@endsection
