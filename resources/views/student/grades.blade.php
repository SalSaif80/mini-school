@extends('layouts.app')

@section('title', 'درجاتي')

@section('sidebar')
    <a class="nav-link" href="{{ route('student.dashboard') }}">
        <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
    </a>
    <a class="nav-link" href="{{ route('student.courses') }}">
        <i class="fas fa-book me-2"></i>كورساتي
    </a>
    <a class="nav-link active" href="{{ route('student.grades') }}">
        <i class="fas fa-star me-2"></i>درجاتي
    </a>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-star me-2"></i>درجاتي</h2>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>نتائج الكورسات</h5>
        </div>
        <div class="card-body">
            @if($enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>اسم الكورس</th>
                                <th>المدرس</th>
                                <th>الفصل الدراسي</th>
                                <th>الدرجة</th>
                                <th>تاريخ الإنجاز</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->course->course_name }}</td>
                                    <td>{{ $enrollment->course->teacher->name }}</td>
                                    <td>{{ $enrollment->semester }}</td>
                                    <td>
                                        @if($enrollment->grade)
                                            <span class="badge bg-success fs-6">{{ $enrollment->grade }}</span>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($enrollment->completion_date)
                                            {{ $enrollment->completion_date->format('Y-m-d') }}
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge
                                            @if($enrollment->status == 'completed') bg-primary
                                            @elseif($enrollment->status == 'active') bg-warning
                                            @else bg-danger
                                            @endif">
                                            @if($enrollment->status == 'completed') مكتمل
                                            @elseif($enrollment->status == 'active') قيد الدراسة
                                            @else مسحوب
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Grade Statistics -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h6>إحصائيات الدرجات:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @php
                                $gradeStats = $enrollments->whereNotNull('grade')->groupBy('grade')->map->count();
                            @endphp
                            @foreach($gradeStats as $grade => $count)
                                <span class="badge bg-info">{{ $grade }}: {{ $count }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد درجات متاحة حالياً</p>
                    <a href="{{ route('student.courses') }}" class="btn btn-primary">
                        <i class="fas fa-book me-1"></i>عرض كورساتي
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
