@extends('dashboard.layouts.master')
@section('title', 'ملفي الشخصي')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">ملفي الشخصي</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عملي</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <button class="btn btn-primary btn-icon ml-2" onclick="toggleEditMode()">
                    <i class="fas fa-edit"></i> تعديل الملف الشخصي
                </button>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Profile Header -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-teacher-gradient">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-3 text-center">
                            <div class="position-relative d-inline-block">
                                <img src="{{ asset('dashboard/assets/img/faces/6.jpg') }}" alt="avatar"
                                     class="avatar avatar-xxl rounded-circle border border-white shadow">
                                <div class="avatar-status bg-success"></div>
                            </div>
                            <div class="mt-3 text-white">
                                <h4 class="text-white mb-1">{{ Auth::user()->name }}</h4>
                                <p class="text-white-50 mb-0">معلم</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="text-white">
                                <h5 class="text-white mb-3">معلومات أساسية</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <i class="fas fa-envelope ml-2"></i>
                                            <span>{{ Auth::user()->email }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-phone ml-2"></i>
                                            <span>{{ $teacher->phone ?? 'غير محدد' }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-building ml-2"></i>
                                            <span>{{ $teacher->department ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <i class="fas fa-graduation-cap ml-2"></i>
                                            <span>{{ $teacher->specialization ?? 'غير محدد' }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-calendar ml-2"></i>
                                            <span>{{ $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : 'غير محدد' }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-star ml-2"></i>
                                            <span>{{ $teacher->experience_years ?? 0 }} سنة خبرة</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row text-center text-white">
                                <div class="col-12 mb-3">
                                    <h3 class="text-white">{{ $teacherStats['courses'] }}</h3>
                                    <small class="text-white-50">مادة أدرسها</small>
                                </div>
                                <div class="col-12 mb-3">
                                    <h3 class="text-white">{{ $teacherStats['students'] }}</h3>
                                    <small class="text-white-50">طالب</small>
                                </div>
                                <div class="col-12">
                                    <h3 class="text-white">{{ $teacherStats['average_grade'] }}%</h3>
                                    <small class="text-white-50">متوسط درجات طلابي</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Personal Information -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">المعلومات الشخصية</h4>
                    <div class="card-options">
                        <button class="btn btn-sm btn-primary" onclick="toggleEditMode()" id="editBtn">
                            <i class="fas fa-edit"></i> تعديل
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- View Mode -->
                    <div id="viewMode">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">الاسم الكامل</label>
                                    <p class="form-control-plaintext">{{ Auth::user()->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">البريد الإلكتروني</label>
                                    <p class="form-control-plaintext">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">رقم الهاتف</label>
                                    <p class="form-control-plaintext">{{ $teacher->phone ?? 'غير محدد' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">القسم</label>
                                    <p class="form-control-plaintext">{{ $teacher->department ?? 'غير محدد' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">التخصص</label>
                                    <p class="form-control-plaintext">{{ $teacher->specialization ?? 'غير محدد' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">المؤهل العلمي</label>
                                    <p class="form-control-plaintext">{{ $teacher->qualification ?? 'غير محدد' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">تاريخ التوظيف</label>
                                    <p class="form-control-plaintext">{{ $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : 'غير محدد' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">سنوات الخبرة</label>
                                    <p class="form-control-plaintext">{{ $teacher->experience_years ?? 0 }} سنة</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">نبذة تعريفية</label>
                                    <p class="form-control-plaintext">{{ $teacher->bio ?? 'لم يتم إضافة نبذة تعريفية بعد' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Mode -->
                    <div id="editMode" style="display: none;">
                        <form method="POST" action="{{ route('teacher.profile.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">الاسم الكامل</label>
                                        <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">البريد الإلكتروني</label>
                                        <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">رقم الهاتف</label>
                                        <input type="text" class="form-control" name="phone" value="{{ $teacher->phone }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">القسم</label>
                                        <input type="text" class="form-control" name="department" value="{{ $teacher->department }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">التخصص</label>
                                        <input type="text" class="form-control" name="specialization" value="{{ $teacher->specialization }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">المؤهل العلمي</label>
                                        <select class="form-control" name="qualification">
                                            <option value="">اختر المؤهل</option>
                                            <option value="بكالوريوس" {{ $teacher->qualification == 'بكالوريوس' ? 'selected' : '' }}>بكالوريوس</option>
                                            <option value="ماجستير" {{ $teacher->qualification == 'ماجستير' ? 'selected' : '' }}>ماجستير</option>
                                            <option value="دكتوراه" {{ $teacher->qualification == 'دكتوراه' ? 'selected' : '' }}>دكتوراه</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">تاريخ التوظيف</label>
                                        <input type="date" class="form-control" name="hire_date"
                                               value="{{ $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">سنوات الخبرة</label>
                                        <input type="number" class="form-control" name="experience_years"
                                               value="{{ $teacher->experience_years }}" min="0">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">نبذة تعريفية</label>
                                        <textarea class="form-control" name="bio" rows="4">{{ $teacher->bio }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> حفظ التغييرات
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">
                                    <i class="fas fa-times"></i> إلغاء
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">تغيير كلمة المرور</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.password.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">كلمة المرور الحالية</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">كلمة المرور الجديدة</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key"></i> تغيير كلمة المرور
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics & Quick Actions -->
        <div class="col-lg-4">
            <!-- Teaching Statistics -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">إحصائيات التدريس</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="bg-primary-transparent p-3 rounded">
                                <h4 class="text-primary mb-1">{{ $teacherStats['courses'] }}</h4>
                                <small class="text-muted">مادة أدرسها</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-success-transparent p-3 rounded">
                                <h4 class="text-success mb-1">{{ $teacherStats['students'] }}</h4>
                                <small class="text-muted">طالب</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-warning-transparent p-3 rounded">
                                <h4 class="text-warning mb-1">{{ $teacherStats['average_grade'] }}%</h4>
                                <small class="text-muted">متوسط الدرجات</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-info-transparent p-3 rounded">
                                <h4 class="text-info mb-1">{{ $teacherStats['total_hours'] }}</h4>
                                <small class="text-muted">ساعة تدريسية</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">إجراءات سريعة</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.my-courses') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-book"></i> موادي التدريسية
                        </a>
                        <a href="{{ route('teacher.my-students') }}" class="btn btn-success btn-block">
                            <i class="fas fa-users"></i> طلابي المسجلين
                        </a>
                        <a href="{{ route('teacher.browse-courses') }}" class="btn btn-info btn-block">
                            <i class="fas fa-search"></i> تصفح جميع المواد
                        </a>
                        <button class="btn btn-warning btn-block" onclick="generateReport()">
                            <i class="fas fa-chart-bar"></i> تقرير أدائي
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">النشاط الأخير</h4>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @if(isset($recentActivities) && $recentActivities->count() > 0)
                            @foreach($recentActivities as $activity)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">{{ $activity->description }}</h6>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <p>لا يوجد نشاط حديث</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
.bg-teacher-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.avatar-xxl {
    width: 120px;
    height: 120px;
}

.avatar-status {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
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

.timeline {
    position: relative;
}

.timeline-item {
    position: relative;
    padding-left: 30px;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 5px;
    top: 17px;
    width: 2px;
    height: calc(100% + 5px);
    background-color: #e9ecef;
}
</style>
@endsection

@section('scripts')
<script>
function toggleEditMode() {
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');
    const editBtn = document.getElementById('editBtn');

    if (viewMode.style.display === 'none') {
        viewMode.style.display = 'block';
        editMode.style.display = 'none';
        editBtn.innerHTML = '<i class="fas fa-edit"></i> تعديل';
    } else {
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
        editBtn.innerHTML = '<i class="fas fa-times"></i> إلغاء';
    }
}

function generateReport() {
    // سيتم تطوير وظيفة إنتاج التقارير لاحقاً
    alert('سيتم تطوير وظيفة إنتاج تقرير الأداء لاحقاً');
}
</script>
@endsection
