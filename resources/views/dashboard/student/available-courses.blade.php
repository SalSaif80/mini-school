@extends('dashboard.layouts.master')
@section('title', 'المواد المتاحة للتسجيل')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المواد المتاحة للتسجيل</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ دراستي</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('student.my-courses') }}" class="btn btn-success btn-icon ml-2">
                    <i class="fas fa-book"></i> موادي المسجلة
                </a>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- Search and Filter -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('student.available-courses') }}">
                        <div class="row align-items-end">
                            <div class="col-lg-3">
                                <label class="form-label">البحث في المواد</label>
                                <input type="text" name="search" class="form-control" placeholder="اسم المادة أو الرمز..." value="{{ request('search') }}">
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label">المستوى</label>
                                <select name="level" class="form-control">
                                    <option value="">جميع المستويات</option>
                                    <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>مبتدئ</option>
                                    <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>متوسط</option>
                                    <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>متقدم</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label">الساعات</label>
                                <select name="credit_hours" class="form-control">
                                    <option value="">جميع الساعات</option>
                                    <option value="1" {{ request('credit_hours') == '1' ? 'selected' : '' }}>1 ساعة</option>
                                    <option value="2" {{ request('credit_hours') == '2' ? 'selected' : '' }}>2 ساعة</option>
                                    <option value="3" {{ request('credit_hours') == '3' ? 'selected' : '' }}>3 ساعات</option>
                                    <option value="4" {{ request('credit_hours') == '4' ? 'selected' : '' }}>4 ساعات</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">الأستاذ</label>
                                <input type="text" name="teacher" class="form-control" placeholder="اسم الأستاذ..." value="{{ request('teacher') }}">
                            </div>
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> بحث
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Courses -->
    <div class="row">
        @if($availableCourses->count() > 0)
            @foreach($availableCourses as $course)
            <div class="col-lg-4 col-md-6">
                <div class="card course-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge badge-primary">{{ $course->course_code }}</span>
                            <div>
                                @if($course->level == 'beginner')
                                    <span class="badge badge-success">مبتدئ</span>
                                @elseif($course->level == 'intermediate')
                                    <span class="badge badge-warning">متوسط</span>
                                @else
                                    <span class="badge badge-danger">متقدم</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->title }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($course->description, 100) }}
                        </p>

                        <div class="course-info">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> {{ $course->teacher->user->name ?? 'غير محدد' }}
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> {{ $course->credit_hours }} ساعة
                                </small>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-users"></i> {{ $course->enrollments_count ?? 0 }} طالب مسجل
                                </small>
                                @if($course->max_students)
                                    <small class="text-muted">
                                        <i class="fas fa-user-friends"></i> الحد الأقصى: {{ $course->max_students }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-eye"></i> عرض التفاصيل
                            </a>
                            <button class="btn btn-primary btn-sm" onclick="confirmRegistration({{ $course->id }}, '{{ $course->title }}')">
                                <i class="fas fa-plus"></i> تسجيل في المادة
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">لا توجد مواد متاحة</h4>
                            <p class="text-muted">لا توجد مواد متاحة للتسجيل حالياً أو أنت مسجل في جميع المواد</p>
                            <a href="{{ route('student.my-courses') }}" class="btn btn-primary">
                                <i class="fas fa-book"></i> عرض موادي المسجلة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($availableCourses->hasPages())
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-center">
                {{ $availableCourses->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif

    <!-- Registration Confirmation Modal -->
    <div class="modal fade" id="registrationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد التسجيل في المادة</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>هل أنت متأكد من التسجيل في مادة <strong id="courseTitle"></strong>؟</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>ملاحظة:</strong> بعد التسجيل ستظهر المادة في قائمة موادك المسجلة
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <form id="registrationForm" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">تأكيد التسجيل</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
.course-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: 1px solid #e9ecef;
}

.course-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.course-info {
    border-top: 1px solid #f8f9fa;
    padding-top: 15px;
}

.badge-lg {
    font-size: 0.9em;
    padding: 0.5em 0.75em;
}
</style>
@endsection

@section('scripts')
<script>
function confirmRegistration(courseId, courseTitle) {
    document.getElementById('courseTitle').textContent = courseTitle;
    const form = document.getElementById('registrationForm');
    form.action = `/student/register-course/${courseId}`;
    $('#registrationModal').modal('show');
}
</script>
@endsection
