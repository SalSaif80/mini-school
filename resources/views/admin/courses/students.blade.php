@extends('layouts.app')

@section('title', 'طلاب الكورس')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users me-2"></i>طلاب كورس: {{ $course->course_name }}</h2>
        <a href="{{ route('admin.courses') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للكورسات
        </a>
    </div>

    <!-- معلومات الكورس -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات الكورس</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>اسم الكورس:</strong> {{ $course->course_name }}
                </div>
                <div class="col-md-3">
                    <strong>المدرس:</strong> {{ $course->teacher->name }}
                </div>
                <div class="col-md-3">
                    <strong>موعد المحاضرة:</strong> {{ $course->schedule_date->format('Y-m-d H:i') }}
                </div>
                <div class="col-md-3">
                    <strong>الغرفة:</strong> {{ $course->room_number }}
                </div>
            </div>
        </div>
    </div>

    <!-- قائمة الطلاب -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>قائمة الطلاب المسجلين ({{ $course->enrollments->count() }} طالب)</h5>
        </div>
        <div class="card-body">
            @if($course->enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>اسم الطالب</th>
                                <th>اسم المستخدم</th>
                                <th>تاريخ التسجيل</th>
                                <th>الفصل الدراسي</th>
                                <th>الحالة</th>
                                <th>درجة الاختبار النهائي</th>
                                <th>الدرجة الحرفية</th>
                                <th>تاريخ الإنجاز</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course->enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <strong>{{ $enrollment->student->name }}</strong>
                                    </td>
                                    <td>{{ $enrollment->student->username }}</td>
                                    <td>{{ $enrollment->enrollment_date->format('Y-m-d') }}</td>
                                    <td>{{ $enrollment->semester }}</td>
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
                                        @if($enrollment->final_exam_grade)
                                            <strong class="text-primary">{{ $enrollment->final_exam_grade }}%</strong>
                                        @else
                                            <span class="text-muted">لم تحدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($enrollment->grade)
                                            <span class="badge
                                                @if($enrollment->grade == 'F') bg-danger
                                                @elseif(in_array($enrollment->grade, ['A+', 'A'])) bg-success
                                                @else bg-info
                                                @endif">
                                                {{ $enrollment->grade }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($enrollment->completion_date)
                                            {{ $enrollment->completion_date->format('Y-m-d') }}
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ملخص الإحصائيات -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="text-primary">{{ $course->enrollments->where('status', 'active')->count() }}</h5>
                            <small class="text-muted">طلاب نشطون</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="text-success">{{ $course->enrollments->where('status', 'completed')->count() }}</h5>
                            <small class="text-muted">طلاب مكملون</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="text-danger">{{ $course->enrollments->where('status', 'failed')->count() }}</h5>
                            <small class="text-muted">طلاب راسبون</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="text-warning">{{ $course->enrollments->where('status', 'dropped')->count() }}</h5>
                            <small class="text-muted">طلاب منسحبون</small>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                    <h5>لا يوجد طلاب مسجلين</h5>
                    <p class="text-muted">لم يسجل أي طالب في هذا الكورس بعد</p>
                </div>
            @endif
        </div>
    </div>
@endsection
