@extends('layouts.app')

@section('title', 'كورساتي')

@section('sidebar')
    <a class="nav-link" href="{{ route('teacher.dashboard') }}">
        <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
    </a>
    <a class="nav-link active" href="{{ route('teacher.courses') }}">
        <i class="fas fa-book me-2"></i>كورساتي
    </a>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-book me-2"></i>كورساتي</h2>
    </div>

    @if($courses->count() > 0)
        <div class="row">
            @foreach($courses as $course)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $course->course_name }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <i class="fas fa-calendar me-2"></i><strong>الموعد:</strong> {{ $course->schedule_date->format('Y-m-d H:i') }}<br>
                                <i class="fas fa-door-open me-2"></i><strong>الغرفة:</strong> {{ $course->room_number }}<br>
                                <i class="fas fa-users me-2"></i><strong>عدد الطلاب:</strong> {{ $course->enrollments->count() }}
                            </p>

                            <div class="mt-3">
                                <a href="{{ route('teacher.course.students', $course->course_id) }}"
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-users me-1"></i>إدارة الطلاب
                                </a>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">
                                طلاب نشطين: {{ $course->enrollments->where('status', 'active')->count() }} |
                                مكتملين: {{ $course->enrollments->where('status', 'completed')->count() }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5>لا توجد كورسات</h5>
                <p class="text-muted">لم يتم تعيين أي كورسات لك حتى الآن</p>
            </div>
        </div>
    @endif
@endsection
