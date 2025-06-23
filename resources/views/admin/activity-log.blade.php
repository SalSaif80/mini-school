@extends('layouts.app')

@section('title', 'سجل الأنشطة')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/activity-log.css') }}">
@endsection

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid activity-log-container">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">سجل الأنشطة</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                            <li class="breadcrumb-item active">سجل الأنشطة</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center stats-card">
                <div class="card-body">
                    <h3 class="text-light">{{ $stats['total'] }}</h3>
                    <p class="text-light mb-0">إجمالي الأنشطة</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center stats-card">
                <div class="card-body">
                    <h3 class="text-light">{{ $stats['today'] }}</h3>
                    <p class="text-light mb-0">أنشطة اليوم</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center stats-card">
                <div class="card-body">
                    <h3 class="text-light">{{ $stats['this_week'] }}</h3>
                    <p class="text-light mb-0">أنشطة الأسبوع</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center stats-card">
                <div class="card-body">
                    <h3 class="text-light">{{ $stats['this_month'] }}</h3>
                    <p class="text-light mb-0">أنشطة الشهر</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-0">سجل الأنشطة</h4>
                            <p class="card-subtitle">فلترة ومراقبة جميع الأنشطة في النظام</p>
                        </div>
                        <div class="btn-group">

                            <div class="btn-group" role="group">
                                <button id="exportDropdown" type="button" class="btn btn-export dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-download"></i> تصدير
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="window.print()"><i class="fas fa-print"></i> طباعة</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="exportActivities()"><i class="fas fa-file-excel"></i> تصدير Excel</a></li>
                                </ul>
                            </div>
                            <button class="btn btn-filters" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                                <i class="fas fa-filter"></i> فلاتر البحث
                            </button>
                        </div>
                    </div>

                    <!-- نموذج الفلاتر -->
                    <div class="collapse mb-4" id="filtersCollapse">
                        <div class="filter-form">
                            <form method="GET" action="{{ route('admin.activity-log') }}">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="causer_id" class="form-label">المستخدم</label>
                                        <select name="causer_id" id="causer_id" class="form-select">
                                            <option value="">جميع المستخدمين</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ request('causer_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="operation_type" class="form-label">نوع العملية</label>
                                        <select name="operation_type" id="operation_type" class="form-select">
                                            <option value="">جميع العمليات</option>
                                            <option value="create" {{ request('operation_type') == 'create' ? 'selected' : '' }}>إنشاء</option>
                                            <option value="update" {{ request('operation_type') == 'update' ? 'selected' : '' }}>تحديث</option>
                                            <option value="delete" {{ request('operation_type') == 'delete' ? 'selected' : '' }}>حذف</option>
                                            <option value="login" {{ request('operation_type') == 'login' ? 'selected' : '' }}>تسجيل دخول</option>
                                            <option value="logout" {{ request('operation_type') == 'logout' ? 'selected' : '' }}>تسجيل خروج</option>
                                            <option value="enroll" {{ request('operation_type') == 'enroll' ? 'selected' : '' }}>تسجيل في كورس</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="subject_type" class="form-label">نوع النموذج</label>
                                        <select name="subject_type" id="subject_type" class="form-select">
                                            <option value="">جميع الأنواع</option>
                                            @foreach($subjectTypes as $type)
                                                <option value="{{ $type['value'] }}" {{ request('subject_type') == $type['value'] ? 'selected' : '' }}>
                                                    {{ $type['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="search" class="form-label">البحث في النص</label>
                                        <input type="text" name="search" id="search" class="form-control"
                                               placeholder="ابحث في وصف النشاط..." value="{{ request('search') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="date_from" class="form-label">من تاريخ</label>
                                        <input type="date" name="date_from" id="date_from" class="form-control"
                                               value="{{ request('date_from') }}">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="date_to" class="form-label">إلى تاريخ</label>
                                        <input type="date" name="date_to" id="date_to" class="form-control"
                                               value="{{ request('date_to') }}">
                                    </div>

                                    <div class="col-md-6 mb-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fas fa-search"></i> تطبيق الفلاتر
                                        </button>
                                        <a href="{{ route('admin.activity-log') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i> مسح الفلاتر
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                                        @if(request()->hasAny(['causer_id', 'operation_type', 'subject_type', 'search', 'date_from', 'date_to']))
                        <div class="alert alert-info mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-info-circle"></i>
                                    يتم عرض النتائج المفلترة:

                                    @if(request('causer_id'))
                                        @php $selectedUser = $users->find(request('causer_id')); @endphp
                                        @if($selectedUser)
                                            <span class="badge bg-primary filter-badge">المستخدم: {{ $selectedUser->name }}</span>
                                        @endif
                                    @endif

                                    @if(request('operation_type'))
                                        <span class="badge bg-success filter-badge">العملية:
                                            @switch(request('operation_type'))
                                                @case('create') إنشاء @break
                                                @case('update') تحديث @break
                                                @case('delete') حذف @break
                                                @case('login') تسجيل دخول @break
                                                @case('logout') تسجيل خروج @break
                                                @case('enroll') تسجيل في كورس @break
                                                @default {{ request('operation_type') }}
                                            @endswitch
                                        </span>
                                    @endif

                                    @if(request('subject_type'))
                                        @php $selectedType = collect($subjectTypes)->firstWhere('value', request('subject_type')); @endphp
                                        @if($selectedType)
                                            <span class="badge bg-info filter-badge">النوع: {{ $selectedType['label'] }}</span>
                                        @endif
                                    @endif

                                    @if(request('search'))
                                        <span class="badge bg-warning text-dark filter-badge">البحث: "{{ request('search') }}"</span>
                                    @endif

                                    @if(request('date_from'))
                                        <span class="badge bg-secondary filter-badge">من: {{ request('date_from') }}</span>
                                    @endif

                                    @if(request('date_to'))
                                        <span class="badge bg-secondary filter-badge">إلى: {{ request('date_to') }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('admin.activity-log') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-times"></i> مسح جميع الفلاتر
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table activity-table">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">من قام بالنشاط</th>
                                    <th width="15%">من وقع عليه النشاط	</th>
                                    <th width="15%">النوع</th>
                                    <th width="25%">النشاط</th>
                                    <th width="15%">التاريخ</th>
                                    <th width="15%">الوقت</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activities as $activity)
                                    <tr>
                                        <td>{{ ($activities->currentPage() - 1) * $activities->perPage() + $loop->iteration }}</td>
                                        <td>
                                            @if($activity->causer)
                                                <span class="badge user-badge">{{ $activity->causer->name }}</span>
                                            @else
                                                <span class="badge bg-secondary">نظام</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($activity->subject)
                                                <span class="badge user-badge">{{ $activity->subject->name }}</span>
                                            @else
                                                <span class="badge bg-secondary">نظام</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($activity->subject_type)
                                                @php
                                                    $subjectTypeLabel = collect($subjectTypes)->firstWhere('value', $activity->subject_type)['label'] ?? $activity->subject_type;
                                                @endphp
                                                <span class="badge type-badge bg-info">{{ $subjectTypeLabel }}</span>
                                            @else
                                                <span class="badge type-badge bg-light text-dark">عام</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="activity-description">
                                                {{ $activity->description }}
                                                @if($activity->subject && method_exists($activity->subject, 'getRouteKey'))
                                                    <small class="text-muted d-block">
                                                        ID: {{ $activity->subject->getRouteKey() }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <small class="time-info">
                                                {{ $activity->created_at->format('Y-m-d') }}
                                            </small>
                                        </td>
                                        <td>
                                            <small class="time-info">
                                                {{ $activity->created_at->format('H:i') }}
                                            </small>
                                            <div class="text-xs text-muted">
                                                {{ $activity->created_at->diffForHumans(['locale' => 'ar']) }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="no-data-container">
                                                <i class="fas fa-inbox no-data-icon"></i>
                                                <p>لا توجد أنشطة مطابقة للفلاتر المحددة.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($activities->hasPages())
                        <div class="d-flex justify-content-center pagination-container">
                            {{ $activities->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // فتح الفلاتر تلقائياً إذا كانت هناك فلاتر مطبقة
    @if(request()->hasAny(['causer_id', 'operation_type', 'subject_type', 'search', 'date_from', 'date_to']))
        const filtersCollapse = new bootstrap.Collapse(document.getElementById('filtersCollapse'), {
            show: true
        });
    @endif

    // إعادة تعيين التواريخ
    const dateFromInput = document.getElementById('date_from');
    const dateToInput = document.getElementById('date_to');

    if (dateFromInput && dateToInput) {
        dateFromInput.addEventListener('change', function() {
            dateToInput.min = this.value;
        });

        dateToInput.addEventListener('change', function() {
            dateFromInput.max = this.value;
        });
    }

    // تطبيق الفلاتر تلقائياً عند التغيير (اختياري)
    const autoSubmitElements = ['causer_id', 'operation_type', 'subject_type'];
    autoSubmitElements.forEach(function(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.addEventListener('change', function() {
                // يمكن تفعيل هذا للتطبيق التلقائي
                // this.form.submit();
            });
        }
    });
});

// وظيفة لمسح فلتر محدد
function clearFilter(filterName) {
    const url = new URL(window.location);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

// وظيفة لتصدير البيانات (اختيارية)
function exportActivities() {
    const url = new URL(window.location);
    url.searchParams.set('export', 'excel');
    window.open(url.toString(), '_blank');
}
</script>
@endsection
