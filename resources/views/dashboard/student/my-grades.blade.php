@extends('dashboard.layouts.master')
@section('title', 'درجاتي ومعدلي')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">درجاتي ومعدلي</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ دراستي</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <span class="badge badge-info">المعدل التراكمي: {{ number_format($gpa, 2) }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- GPA Overview -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-primary-gradient">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center">
                                <div class="ml-3">
                                    <img src="{{ asset('dashboard/assets/img/faces/1.jpg') }}" alt="avatar" class="avatar avatar-lg rounded-circle">
                                </div>
                                <div class="text-white">
                                    <h4 class="mb-1 text-white">{{ Auth::user()->name }}</h4>
                                    <p class="mb-0 text-white-50">
                                        الرقم الجامعي: {{ $student->student_id }} - {{ $student->class_level }}
                                    </p>
                                    <small class="text-white-50">إجمالي الساعات المكتملة: {{ $totalCredits }} ساعة</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-left">
                            <div class="text-white text-center">
                                <h1 class="display-4 mb-0 text-white">{{ number_format($gpa, 2) }}</h1>
                                <h5 class="text-white-50">المعدل التراكمي</h5>
                                <div class="mt-2">
                                    @if($gpa >= 90)
                                        <span class="badge badge-success">ممتاز</span>
                                    @elseif($gpa >= 80)
                                        <span class="badge badge-primary">جيد جداً</span>
                                    @elseif($gpa >= 70)
                                        <span class="badge badge-warning">جيد</span>
                                    @elseif($gpa >= 60)
                                        <span class="badge badge-info">مقبول</span>
                                    @else
                                        <span class="badge badge-danger">ضعيف</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Statistics -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-2">المواد المكتملة</h6>
                            <h3 class="mb-0 text-success">{{ $completedEnrollments->count() }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success-transparent text-success">
                                <i class="fas fa-check-circle"></i>
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
                            <h6 class="text-muted mb-2">أعلى درجة</h6>
                            <h3 class="mb-0 text-primary">{{ $completedEnrollments->max('grade') ?? 0 }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary-transparent text-primary">
                                <i class="fas fa-trophy"></i>
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
                            <h6 class="text-muted mb-2">أقل درجة</h6>
                            <h3 class="mb-0 text-warning">{{ $completedEnrollments->min('grade') ?? 0 }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning-transparent text-warning">
                                <i class="fas fa-chart-line"></i>
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
                            <h6 class="text-muted mb-2">إجمالي الساعات</h6>
                            <h3 class="mb-0 text-info">{{ $totalCredits }}</h3>
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

    <!-- Grades Table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">تفاصيل الدرجات</h4>
                    <div class="card-options">
                        <button class="btn btn-sm btn-primary" onclick="printGrades()">
                            <i class="fas fa-print"></i> طباعة كشف الدرجات
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($completedEnrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>المادة</th>
                                        <th>الرمز</th>
                                        <th>الأستاذ</th>
                                        <th>الساعات</th>
                                        <th>الدرجة</th>
                                        <th>التقدير</th>
                                        <th>تاريخ الإكمال</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedEnrollments as $enrollment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-transparent rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-book text-primary"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <h6 class="mb-0">{{ $enrollment->course->title }}</h6>
                                                    <small class="text-muted">{{ $enrollment->course->level }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $enrollment->course->course_code }}</span>
                                        </td>
                                        <td>{{ $enrollment->course->teacher->user->name ?? 'غير محدد' }}</td>
                                        <td>{{ $enrollment->course->credit_hours }} ساعة</td>
                                        <td>
                                            @php
                                                $grade = $enrollment->grade;
                                                $gradeClass = $grade >= 90 ? 'success' : ($grade >= 80 ? 'primary' : ($grade >= 70 ? 'warning' : ($grade >= 60 ? 'info' : 'danger')));
                                            @endphp
                                            <span class="badge badge-{{ $gradeClass }} badge-lg">{{ $grade }}</span>
                                        </td>
                                        <td>
                                            @if($grade >= 90)
                                                <span class="text-success font-weight-bold">ممتاز</span>
                                            @elseif($grade >= 80)
                                                <span class="text-primary font-weight-bold">جيد جداً</span>
                                            @elseif($grade >= 70)
                                                <span class="text-warning font-weight-bold">جيد</span>
                                            @elseif($grade >= 60)
                                                <span class="text-info font-weight-bold">مقبول</span>
                                            @else
                                                <span class="text-danger font-weight-bold">راسب</span>
                                            @endif
                                        </td>
                                        <td>{{ $enrollment->updated_at->format('Y-m-d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">لا توجد درجات مسجلة</h4>
                            <p class="text-muted">لم تكمل أي مادة بعد</p>
                            <a href="{{ route('student.my-courses') }}" class="btn btn-primary">
                                <i class="fas fa-book"></i> عرض موادي الحالية
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Distribution Chart -->
    @if($completedEnrollments->count() > 0)
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">توزيع الدرجات</h4>
                </div>
                <div class="card-body">
                    <canvas id="gradeChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">إحصائيات التقديرات</h4>
                </div>
                <div class="card-body">
                    @php
                        $gradeStats = [
                            'ممتاز' => $completedEnrollments->where('grade', '>=', 90)->count(),
                            'جيد جداً' => $completedEnrollments->whereBetween('grade', [80, 89])->count(),
                            'جيد' => $completedEnrollments->whereBetween('grade', [70, 79])->count(),
                            'مقبول' => $completedEnrollments->whereBetween('grade', [60, 69])->count(),
                            'راسب' => $completedEnrollments->where('grade', '<', 60)->count(),
                        ];
                    @endphp

                    @foreach($gradeStats as $grade => $count)
                        @if($count > 0)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0">{{ $grade }}</h6>
                                    <small class="text-muted">{{ $count }} مادة</small>
                                </div>
                                <div>
                                    @php
                                        $percentage = ($count / $completedEnrollments->count()) * 100;
                                        $colorClass = $grade == 'ممتاز' ? 'success' : ($grade == 'جيد جداً' ? 'primary' : ($grade == 'جيد' ? 'warning' : ($grade == 'مقبول' ? 'info' : 'danger')));
                                    @endphp
                                    <span class="badge badge-{{ $colorClass }}">{{ number_format($percentage, 1) }}%</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
<script src="{{ asset('dashboard/assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
<script>
@if($completedEnrollments->count() > 0)
// Grade Distribution Chart
const ctx = document.getElementById('gradeChart').getContext('2d');
const gradeChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['ممتاز (90+)', 'جيد جداً (80-89)', 'جيد (70-79)', 'مقبول (60-69)', 'راسب (<60)'],
        datasets: [{
            data: [
                {{ $completedEnrollments->where('grade', '>=', 90)->count() }},
                {{ $completedEnrollments->whereBetween('grade', [80, 89])->count() }},
                {{ $completedEnrollments->whereBetween('grade', [70, 79])->count() }},
                {{ $completedEnrollments->whereBetween('grade', [60, 69])->count() }},
                {{ $completedEnrollments->where('grade', '<', 60)->count() }}
            ],
            backgroundColor: [
                '#28a745',
                '#007bff',
                '#ffc107',
                '#17a2b8',
                '#dc3545'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            position: 'bottom'
        }
    }
});
@endif

function printGrades() {
    window.print();
}
</script>
@endsection
