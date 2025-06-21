@extends('dashboard.layouts.master')
@section('title', 'ملفي الشخصي')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">ملفي الشخصي</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ دراستي</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <button type="button" class="btn btn-primary btn-icon ml-2" onclick="enableEdit()">
                    <i class="fas fa-edit"></i> تعديل المعلومات
                </button>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- Profile Header -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-primary-gradient">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-3 text-center">
                            <img src="{{ asset('dashboard/assets/img/faces/1.jpg') }}" alt="avatar" class="avatar avatar-xxl rounded-circle border border-white">
                        </div>
                        <div class="col-lg-6">
                            <div class="text-white">
                                <h2 class="mb-1 text-white">{{ Auth::user()->name }}</h2>
                                <p class="mb-1 text-white-50">
                                    <i class="fas fa-id-card"></i> الرقم الجامعي: {{ $student->student_id }}
                                </p>
                                <p class="mb-1 text-white-50">
                                    <i class="fas fa-graduation-cap"></i> {{ $student->class_level }}
                                </p>
                                <p class="mb-0 text-white-50">
                                    <i class="fas fa-calendar"></i> طالب منذ {{ $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : 'غير محدد' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-3 text-center">
                            <div class="text-white">
                                <h3 class="text-white">{{ $student->enrollments()->count() }}</h3>
                                <p class="text-white-50">مادة مسجلة</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">المعلومات الشخصية</h4>
                </div>
                <div class="card-body">
                    <form id="profileForm" method="POST" action="{{ route('student.profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">الاسم الكامل</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                    <small class="text-muted">لتغيير الاسم، يرجى التواصل مع الإدارة</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                                    <small class="text-muted">لتغيير البريد، يرجى التواصل مع الإدارة</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">الرقم الجامعي</label>
                                    <input type="text" class="form-control" value="{{ $student->student_id }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">المستوى الدراسي</label>
                                    <input type="text" class="form-control" value="{{ $student->class_level }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">تاريخ الميلاد</label>
                                    <input type="date" name="date_of_birth" class="form-control profile-input"
                                           value="{{ $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '' }}" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">الجنس</label>
                                    <select name="gender" class="form-control profile-input" disabled>
                                        <option value="">اختر الجنس</option>
                                        <option value="male" {{ $student->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">رقم الهاتف</label>
                                    <input type="text" name="phone" class="form-control profile-input"
                                           value="{{ $student->phone }}" placeholder="رقم الهاتف" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">السنة الأكاديمية</label>
                                    <input type="text" name="academic_year" class="form-control profile-input"
                                           value="{{ $student->academic_year }}" placeholder="السنة الأكاديمية" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">العنوان</label>
                            <textarea name="address" class="form-control profile-input" rows="3"
                                      placeholder="العنوان الكامل" disabled>{{ $student->address }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">اسم ولي الأمر</label>
                                    <input type="text" name="parent_name" class="form-control profile-input"
                                           value="{{ $student->parent_name }}" placeholder="اسم ولي الأمر" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">هاتف ولي الأمر</label>
                                    <input type="text" name="parent_phone" class="form-control profile-input"
                                           value="{{ $student->parent_phone }}" placeholder="هاتف ولي الأمر" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="saveButtons" style="display: none;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ التغييرات
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="cancelEdit()">
                                <i class="fas fa-times"></i> إلغاء
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Academic Summary -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">ملخص أكاديمي</h4>
                </div>
                <div class="card-body">
                    @php
                        $totalEnrollments = $student->enrollments()->count();
                        $activeEnrollments = $student->enrollments()->where('status', 'active')->count();
                        $completedEnrollments = $student->enrollments()->where('status', 'completed')->count();
                        $gpa = $student->enrollments()->where('status', 'completed')->whereNotNull('grade')->avg('grade') ?? 0;
                    @endphp

                    <div class="d-flex justify-content-between mb-3">
                        <span>المواد المسجلة:</span>
                        <span class="badge badge-primary">{{ $totalEnrollments }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>المواد النشطة:</span>
                        <span class="badge badge-success">{{ $activeEnrollments }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>المواد المكتملة:</span>
                        <span class="badge badge-info">{{ $completedEnrollments }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>المعدل التراكمي:</span>
                        <span class="badge badge-warning">{{ number_format($gpa, 2) }}</span>
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
                        <a href="{{ route('student.my-courses') }}" class="btn btn-outline-primary">
                            <i class="fas fa-book"></i> موادي المسجلة
                        </a>
                        <a href="{{ route('student.my-grades') }}" class="btn btn-outline-success">
                            <i class="fas fa-chart-bar"></i> درجاتي ومعدلي
                        </a>
                        <a href="{{ route('student.available-courses') }}" class="btn btn-outline-info">
                            <i class="fas fa-search"></i> تصفح المواد
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-warning">
                            <i class="fas fa-cog"></i> إعدادات الحساب
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function enableEdit() {
    document.querySelectorAll('.profile-input').forEach(input => {
        input.disabled = false;
    });
    document.getElementById('saveButtons').style.display = 'block';
    document.querySelector('.btn-primary.btn-icon').style.display = 'none';
}

function cancelEdit() {
    document.querySelectorAll('.profile-input').forEach(input => {
        input.disabled = true;
    });
    document.getElementById('saveButtons').style.display = 'none';
    document.querySelector('.btn-primary.btn-icon').style.display = 'inline-block';

    // Reset form to original values
    document.getElementById('profileForm').reset();
}
</script>
@endsection
