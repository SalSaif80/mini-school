@extends('layouts.app')

@section('title', 'لوحة تحكم المدرس')

@section('sidebar')
    <a class="nav-link active" href="{{ route('teacher.dashboard') }}">
        <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
    </a>
    <a class="nav-link" href="{{ route('teacher.courses') }}">
        <i class="fas fa-book me-2"></i>كورساتي
    </a>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-chalkboard-teacher me-2"></i>لوحة تحكم المدرس</h2>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $stats['total_courses'] }}</h3>
                        <p class="mb-0">كورساتي</p>
                    </div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stats-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $stats['total_students'] }}</h3>
                        <p class="mb-0">إجمالي الطلاب</p>
                    </div>
                    <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stats-card" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $stats['active_enrollments'] }}</h3>
                        <p class="mb-0">التسجيلات النشطة</p>
                    </div>
                    <i class="fas fa-chart-line fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- My Courses -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-book me-2"></i>كورساتي</h5>
        </div>
        <div class="card-body">
            @if($courses->count() > 0)
                <div class="row">
                    @foreach($courses as $course)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $course->course_name }}</h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>{{ $course->schedule_date->format('Y-m-d H:i') }}<br>
                                            <i class="fas fa-door-open me-1"></i>غرفة: {{ $course->room_number }}<br>
                                            <i class="fas fa-users me-1"></i>الطلاب: {{ $course->enrollments->count() }}
                                        </small>
                                    </p>
                                    <a href="{{ route('teacher.course.students', $course->course_id) }}" class="btn btn-primary btn-sm">
                                        عرض الطلاب
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted text-center">لا توجد كورسات مخصصة لك حالياً</p>
            @endif
        </div>
    </div>
@endsection
