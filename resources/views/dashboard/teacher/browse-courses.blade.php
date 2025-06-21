@extends('dashboard.layouts.master')
@section('title', 'تصفح جميع المواد')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">تصفح جميع المواد</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عملي</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('teacher.my-courses') }}" class="btn btn-primary btn-icon ml-2">
                    <i class="fas fa-chalkboard-teacher"></i> موادي التدريسية
                </a>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- Search & Filters -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">البحث والفلترة</h4>
                </div>
                <div class="card-body">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-3">
                            <label for="search" class="form-label">البحث</label>
                            <input type="text" name="search" id="search" class="form-control"
                                   placeholder="اسم المادة أو الرمز" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="level_filter" class="form-label">المستوى</label>
                            <select name="level" id="level_filter" class="form-control">
                                <option value="">جميع المستويات</option>
                                <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>مبتدئ</option>
                                <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>متوسط</option>
                                <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>متقدم</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="hours_filter" class="form-label">الساعات</label>
                            <select name="credit_hours" id="hours_filter" class="form-control">
                                <option value="">جميع الساعات</option>
                                <option value="1" {{ request('credit_hours') == '1' ? 'selected' : '' }}>ساعة واحدة</option>
                                <option value="2" {{ request('credit_hours') == '2' ? 'selected' : '' }}>ساعتان</option>
                                <option value="3" {{ request('credit_hours') == '3' ? 'selected' : '' }}>3 ساعات</option>
                                <option value="4" {{ request('credit_hours') == '4' ? 'selected' : '' }}>4 ساعات أو أكثر</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="teacher_filter" class="form-label">المعلم</label>
                            <select name="teacher_id" id="teacher_filter" class="form-control">
                                <option value="">جميع المعلمين</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> بحث
                            </button>
                            <a href="{{ route('teacher.browse-courses') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                            <button type="button" class="btn btn-info" onclick="toggleView()">
                                <i class="fas fa-th" id="viewToggleIcon"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Statistics -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-2">إجمالي المواد</h6>
                            <h3 class="mb-0 text-primary">{{ $courses->total() }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary-transparent text-primary">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-2">موادي</h6>
                            <h3 class="mb-0 text-success">{{ $myCourses }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success-transparent text-success">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-2">المتاحة للتدريس</h6>
                            <h3 class="mb-0 text-warning">{{ $availableCourses }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning-transparent text-warning">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-2">متوسط الساعات</h6>
                            <h3 class="mb-0 text-info">{{ $averageHours }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-info-transparent text-info">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Display -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">جميع المواد الدراسية</h4>
                    <div class="card-options">
                        <div class="btn-group" role="group">
                            <a href="?view=table" class="btn btn-sm {{ !request('view') || request('view') == 'table' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-list"></i> قائمة
                            </a>
                            <a href="?view=cards" class="btn btn-sm {{ request('view') == 'cards' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-th"></i> بطاقات
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($courses->count() > 0)
                        @if(!request('view') || request('view') == 'table')
                            <!-- Table View -->
                            <div class="table-responsive" id="tableView">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>المادة</th>
                                            <th>الرمز</th>
                                            <th>المعلم</th>
                                            <th>المستوى</th>
                                            <th>الساعات</th>
                                            <th>الطلاب</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($courses as $course)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary-transparent rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-book text-primary"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <h6 class="mb-0">{{ $course->title }}</h6>
                                                        <small class="text-muted">{{ Str::limit($course->description, 40) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">{{ $course->course_code }}</span>
                                            </td>
                                            <td>
                                                @if($course->teacher)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs bg-success-transparent rounded-circle d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-user text-success"></i>
                                                        </div>
                                                        <div class="ml-2">
                                                            <span class="font-weight-bold">{{ $course->teacher->user->name }}</span>
                                                            @if($course->teacher->id == auth()->user()->teacher->id)
                                                                <br><small class="text-success">أنت المعلم</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-warning">
                                                        <i class="fas fa-exclamation-triangle"></i> غير مُسند
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($course->level == 'beginner')
                                                    <span class="badge badge-success">مبتدئ</span>
                                                @elseif($course->level == 'intermediate')
                                                    <span class="badge badge-warning">متوسط</span>
                                                @else
                                                    <span class="badge badge-danger">متقدم</span>
                                                @endif
                                            </td>
                                            <td>{{ $course->credit_hours }} ساعة</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge badge-info mr-2">{{ $course->enrollments_count ?? 0 }}</span>
                                                    @if($course->max_students)
                                                        <small class="text-muted">/ {{ $course->max_students }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if(!$course->teacher)
                                                    <span class="badge badge-warning">متاح للتدريس</span>
                                                @elseif($course->teacher->id == auth()->user()->teacher->id)
                                                    <span class="badge badge-success">أدرسها</span>
                                                @else
                                                    <span class="badge badge-info">مُسندة لمعلم آخر</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info" title="عرض التفاصيل">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($course->teacher && $course->teacher->id == auth()->user()->teacher->id)
                                                        <a href="{{ route('teacher.course.students', $course) }}" class="btn btn-sm btn-success" title="إدارة الطلاب">
                                                            <i class="fas fa-users"></i>
                                                        </a>
                                                    @endif
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="{{ route('courses.show', $course) }}">
                                                                <i class="fas fa-info-circle"></i> تفاصيل المادة
                                                            </a>
                                                            @if(!$course->teacher)
                                                                <a class="dropdown-item text-success" href="#" onclick="requestToTeach({{ $course->id }})">
                                                                    <i class="fas fa-hand-paper"></i> طلب تدريس المادة
                                                                </a>
                                                            @endif
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item" href="#" onclick="addToFavorites({{ $course->id }})">
                                                                <i class="fas fa-star"></i> إضافة للمفضلة
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <!-- Cards View -->
                            <div class="row" id="cardsView">
                                @foreach($courses as $course)
                                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                                    <div class="card course-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="course-icon">
                                                    <div class="avatar-md bg-primary-transparent rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-book text-primary fa-lg"></i>
                                                    </div>
                                                </div>
                                                <div class="course-status">
                                                    @if(!$course->teacher)
                                                        <span class="badge badge-warning">متاح</span>
                                                    @elseif($course->teacher->id == auth()->user()->teacher->id)
                                                        <span class="badge badge-success">أدرسها</span>
                                                    @else
                                                        <span class="badge badge-info">مُسندة</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <h5 class="card-title mb-2">{{ $course->title }}</h5>
                                            <p class="card-text text-muted mb-3">{{ Str::limit($course->description, 80) }}</p>

                                            <div class="course-details mb-3">
                                                <div class="row text-center">
                                                    <div class="col-4">
                                                        <div class="detail-item">
                                                            <i class="fas fa-code text-primary"></i>
                                                            <small class="d-block text-muted">{{ $course->course_code }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="detail-item">
                                                            <i class="fas fa-clock text-warning"></i>
                                                            <small class="d-block text-muted">{{ $course->credit_hours }} ساعة</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="detail-item">
                                                            <i class="fas fa-users text-success"></i>
                                                            <small class="d-block text-muted">{{ $course->enrollments_count ?? 0 }} طالب</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($course->teacher)
                                                <div class="teacher-info mb-3 p-2 bg-light rounded">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-chalkboard-teacher text-info mr-2"></i>
                                                        <div>
                                                            <small class="text-muted">المعلم:</small>
                                                            <div class="font-weight-bold">{{ $course->teacher->user->name }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="course-level mb-3">
                                                @if($course->level == 'beginner')
                                                    <span class="badge badge-success">مستوى مبتدئ</span>
                                                @elseif($course->level == 'intermediate')
                                                    <span class="badge badge-warning">مستوى متوسط</span>
                                                @else
                                                    <span class="badge badge-danger">مستوى متقدم</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="card-footer bg-transparent">
                                            <div class="btn-group w-100" role="group">
                                                <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i> عرض
                                                </a>
                                                @if($course->teacher && $course->teacher->id == auth()->user()->teacher->id)
                                                    <a href="{{ route('teacher.course.students', $course) }}" class="btn btn-outline-success btn-sm">
                                                        <i class="fas fa-users"></i> الطلاب
                                                    </a>
                                                @elseif(!$course->teacher)
                                                    <button class="btn btn-outline-warning btn-sm" onclick="requestToTeach({{ $course->id }})">
                                                        <i class="fas fa-hand-paper"></i> طلب تدريس
                                                    </button>
                                                @endif
                                                <button class="btn btn-outline-secondary btn-sm" onclick="addToFavorites({{ $course->id }})">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $courses->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">لا توجد مواد تطابق البحث</h4>
                            <p class="text-muted">جرب تعديل معايير البحث أو الفلترة</p>
                            <a href="{{ route('teacher.browse-courses') }}" class="btn btn-primary">
                                <i class="fas fa-refresh"></i> عرض جميع المواد
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
.icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.bg-primary-transparent {
    background-color: rgba(0, 123, 255, 0.1);
}

.bg-success-transparent {
    background-color: rgba(40, 167, 69, 0.1);
}

.bg-warning-transparent {
    background-color: rgba(255, 193, 7, 0.1);
}

.bg-info-transparent {
    background-color: rgba(23, 162, 184, 0.1);
}

.course-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: 1px solid #e9ecef;
}

.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.avatar-sm {
    width: 40px;
    height: 40px;
}

.avatar-xs {
    width: 30px;
    height: 30px;
}

.avatar-md {
    width: 60px;
    height: 60px;
}

.detail-item {
    text-align: center;
}

.detail-item i {
    font-size: 1.2em;
    margin-bottom: 5px;
}

.teacher-info {
    border-left: 3px solid #17a2b8;
}
</style>
@endsection

@section('scripts')
<script>
function toggleView() {
    const tableView = document.getElementById('tableView');
    const cardsView = document.getElementById('cardsView');
    const icon = document.getElementById('viewToggleIcon');

    if (tableView && tableView.style.display !== 'none') {
        // Switch to cards view
        window.location.href = '{{ request()->fullUrlWithQuery(['view' => 'cards']) }}';
    } else {
        // Switch to table view
        window.location.href = '{{ request()->fullUrlWithQuery(['view' => 'table']) }}';
    }
}

function requestToTeach(courseId) {
    if (confirm('هل تريد إرسال طلب لتدريس هذه المادة؟')) {
        // سيتم تطوير هذه الوظيفة لاحقاً
        alert('تم إرسال طلبك. سيتم مراجعته من قبل الإدارة.');
    }
}

function addToFavorites(courseId) {
    // إضافة المادة للمفضلة
    fetch(`/teacher/favorites/${courseId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم إضافة المادة للمفضلة');
        } else {
            alert('حدث خطأ في إضافة المادة للمفضلة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال');
    });
}
</script>
@endsection
