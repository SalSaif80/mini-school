@extends('layouts.app')

@section('title', 'لوحة تحكم الإدارة')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tachometer-alt me-2"></i>لوحة تحكم الإدارة</h2>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $stats['total_users'] }}</h3>
                        <p class="mb-0">إجمالي المستخدمين</p>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
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
            <div class="stats-card" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $stats['total_enrollments'] }}</h3>
                        <p class="mb-0">إجمالي التسجيلات</p>
                    </div>
                    <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);">
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

    <!-- Recent Activities -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>أحدث الأنشطة</h5>
        </div>
        <div class="card-body">
            @if($recent_activities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>المستخدم</th>
                                <th>النشاط</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_activities as $activity)
                                <tr>
                                    <td>
                                        @if($activity->causer)
                                            {{ $activity->causer->name }}
                                            <small class="text-muted">({{ $activity->causer->user_type }})</small>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>{{ $activity->description }}</td>
                                    <td>{{ $activity->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center">لا توجد أنشطة حديثة</p>
            @endif
        </div>
    </div>
@endsection
