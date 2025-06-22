@extends('layouts.app')

@section('title', 'الكورسات المتاحة للتسجيل')

@section('sidebar')
    <a class="nav-link" href="{{ route('student.dashboard') }}">
        <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
    </a>
    <a class="nav-link" href="{{ route('student.courses') }}">
        <i class="fas fa-book me-2"></i>كورساتي
    </a>
    <a class="nav-link active" href="{{ route('student.available.courses') }}">
        <i class="fas fa-plus me-2"></i>كورسات متاحة
    </a>
    <a class="nav-link" href="{{ route('student.grades') }}">
        <i class="fas fa-star me-2"></i>درجاتي
    </a>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus me-2"></i>الكورسات المتاحة للتسجيل</h2>
        <a href="{{ route('student.courses') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة لكورساتي
        </a>
    </div>

    @if($availableCourses->count() > 0)
        <div class="row">
            @foreach($availableCourses as $course)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-book me-2"></i>{{ $course->course_name }}
                            </h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="mb-3">
                                <p class="card-text">
                                    <strong><i class="fas fa-user me-2"></i>المدرس:</strong>
                                    @if($course->teacher)
                                        <span class="badge bg-success">{{ $course->teacher->name }}</span>
                                    @else
                                        <span class="badge bg-warning">غير محدد</span>
                                    @endif
                                </p>

                                <p class="card-text">
                                    <strong><i class="fas fa-clock me-2"></i>موعد المحاضرة:</strong><br>
                                    <small class="text-muted">{{ $course->schedule_date->format('l, Y-m-d') }}</small><br>
                                    <small class="text-muted">{{ $course->schedule_date->format('H:i') }}</small>
                                </p>

                                <p class="card-text">
                                    <strong><i class="fas fa-door-open me-2"></i>الغرفة:</strong>
                                    <span class="badge bg-info">{{ $course->room_number }}</span>
                                </p>

                                <p class="card-text">
                                    <strong><i class="fas fa-users me-2"></i>عدد الطلاب المسجلين:</strong>
                                    <span class="badge bg-secondary">{{ $course->enrollments_count ?? 0 }}</span>
                                </p>
                            </div>

                            <div class="mt-auto">
                                <button type="button" class="btn btn-success w-100"
                                        onclick="confirmEnrollment({{ $course->course_id }}, '{{ $course->course_name }}')">
                                    <i class="fas fa-user-plus me-2"></i>تسجيل في الكورس
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                <h4>لا توجد كورسات متاحة</h4>
                <p class="text-muted">
                    لقد سجلت في جميع الكورسات المتاحة، أو لا توجد كورسات جديدة في الوقت الحالي.
                </p>
                <a href="{{ route('student.courses') }}" class="btn btn-primary">
                    <i class="fas fa-book me-2"></i>عرض كورساتي
                </a>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
<script>
    function confirmEnrollment(courseId, courseName) {
        if (confirm(`هل تريد التسجيل في كورس "${courseName}"؟\n\nسيتم إضافة الكورس لقائمة كورساتك.`)) {
            // إنشاء form للتسجيل
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/student/enroll/${courseId}`;

            // إضافة CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
