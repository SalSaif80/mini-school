@extends('layouts.app')

@section('title', 'تفاصيل المستخدم')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user me-2"></i>تفاصيل المستخدم: {{ $user->name }}</h2>
        <div>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>تعديل
            </a>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row">
        <!-- بيانات المستخدم الأساسية -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>البيانات الأساسية</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>الرقم:</strong></td>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>الاسم الكامل:</strong></td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>اسم المستخدم:</strong></td>
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <td><strong>نوع المستخدم:</strong></td>
                            <td>
                                <span class="badge
                                    @if($user->user_type == 'admin') bg-danger
                                    @elseif($user->user_type == 'teacher') bg-warning
                                    @else bg-success
                                    @endif">
                                    @if($user->user_type == 'admin')
                                        <i class="fas fa-user-shield me-1"></i>إدارة
                                    @elseif($user->user_type == 'teacher')
                                        <i class="fas fa-chalkboard-teacher me-1"></i>مدرس
                                    @else
                                        <i class="fas fa-user-graduate me-1"></i>طالب
                                    @endif
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>تاريخ الإنشاء:</strong></td>
                            <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>آخر تحديث:</strong></td>
                            <td>{{ $user->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- إحصائيات المستخدم -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>الإحصائيات</h5>
                </div>
                <div class="card-body">
                    @if($user->user_type === 'teacher')
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stats-card bg-primary text-white p-3 rounded">
                                    <h4>{{ $stats['courses'] ?? 0 }}</h4>
                                    <small>الكورسات</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stats-card bg-success text-white p-3 rounded">
                                    <h4>{{ $stats['students'] ?? 0 }}</h4>
                                    <small>الطلاب</small>
                                </div>
                            </div>
                        </div>
                    @elseif($user->user_type === 'student')
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stats-card bg-info text-white p-3 rounded">
                                    <h4>{{ $stats['enrollments'] ?? 0 }}</h4>
                                    <small>التسجيلات</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stats-card bg-success text-white p-3 rounded">
                                    <h4>{{ $stats['completed'] ?? 0 }}</h4>
                                    <small>مكتملة</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                            <h5>مستخدم إدارة</h5>
                            <p class="text-muted">لديه صلاحيات كاملة في النظام</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
