@extends('layouts.app')

@section('title', 'لوحة تحكم الطالب')

@section('sidebar')
    <a class="nav-link active" href="{{ route('student.dashboard') }}">
        <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
    </a>
    <a class="nav-link" href="{{ route('student.courses') }}">
        <i class="fas fa-book me-2"></i>كورساتي
    </a>
    <a class="nav-link" href="{{ route('student.grades') }}">
        <i class="fas fa-star me-2"></i>درجاتي
    </a>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-graduate me-2"></i>لوحة تحكم الطالب</h2>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $stats['total_courses'] }}</h3>
                        <p class="mb-0">إجمالي الكورسات</p>
                    </div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $stats['active_courses'] }}</h3>
                        <p class="mb-0">الكورسات النشطة</p>
                    </div>
                    <i class="fas fa-play-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $stats['completed_courses'] }}</h3>
                        <p class="mb-0">الكورسات المكتملة</p>
                    </div>
                    <i class="fas fa-check-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $stats['dropped_courses'] }}</h3>
                        <p class="mb-0">الكورسات المسحوبة</p>
                    </div>
                    <i class="fas fa-times-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- My Courses -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-book me-2"></i>كورساتي الحالية</h5>
        </div>
        <div class="card-body">
            @if($enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>اسم الكورس</th>
                                <th>المدرس</th>
                                <th>الموعد</th>
                                <th>الغرفة</th>
                                <th>الحالة</th>
                                <th>الدرجة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->course->course_name }}</td>
                                    <td>{{ $enrollment->course->teacher->name }}</td>
                                    <td>{{ $enrollment->course->schedule_date->format('Y-m-d H:i') }}</td>
                                    <td>{{ $enrollment->course->room_number }}</td>
                                    <td>
                                        <span class="badge
                                            @if($enrollment->status == 'active') bg-success
                                            @elseif($enrollment->status == 'completed') bg-primary
                                            @else bg-danger
                                            @endif">
                                            @if($enrollment->status == 'active') نشط
                                            @elseif($enrollment->status == 'completed') مكتمل
                                            @else منسحب
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if($enrollment->grade)
                                            <strong class="text-primary">{{ $enrollment->grade }}</strong>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center">لم تقم بالتسجيل في أي كورسات بعد</p>
            @endif
        </div>
    </div>
@endsection
