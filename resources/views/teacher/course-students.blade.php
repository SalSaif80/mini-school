@extends('layouts.app')

@section('title', 'طلاب الكورس')

@section('sidebar')
    <a class="nav-link" href="{{ route('teacher.dashboard') }}">
        <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
    </a>
    <a class="nav-link active" href="{{ route('teacher.courses') }}">
        <i class="fas fa-book me-2"></i>كورساتي
    </a>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users me-2"></i>طلاب كورس: {{ $course->course_name }}</h2>
        <a href="{{ route('teacher.courses') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للكورسات
        </a>
    </div>

    <!-- معلومات الكورس -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات الكورس</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <strong>اسم الكورس:</strong> {{ $course->course_name }}
                </div>
                <div class="col-md-2">
                    <strong>موعد المحاضرة:</strong> {{ $course->schedule_date->format('Y-m-d H:i') }}
                </div>
                <div class="col-md-2">
                    <strong>الغرفة:</strong> {{ $course->room_number }}
                </div>
                <div class="col-md-2">
                    <strong>عدد الطلاب:</strong> {{ $course->enrollments->count() }}
                </div>
                <div class="col-md-2">
                    <strong>الملفات المرفوعة:</strong>
                    <span class="badge bg-info">{{ $course->enrollments->whereNotNull('exam_file_path')->count() }}</span>
                </div>
                <div class="col-md-2">
                    <strong>تم التقييم:</strong>
                    <span class="badge bg-success">{{ $course->enrollments->whereNotNull('final_exam_grade')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- قائمة الطلاب -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>قائمة الطلاب والدرجات</h5>
        </div>
        <div class="card-body">
            @if($course->enrollments->count() > 0)
                <div class="table">
                    <table class="table table-bordered table-striped table-hover ">
                        <thead>
                            <tr>
                                <th>اسم الطالب</th>
                                <th>تاريخ التسجيل</th>
                                <th>الحالة</th>
                                <th>ملف الاختبار</th>
                                <th>درجة الاختبار النهائي</th>
                                <th>الدرجة الحرفية</th>
                                {{-- <th>الإجراءات</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course->enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <strong>{{ $enrollment->student->name }}</strong>
                                        <br><small class="text-muted">{{ $enrollment->student->username }}</small>
                                    </td>
                                    <td>{{ $enrollment->enrollment_date->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge
                                            @if($enrollment->status == 'active') bg-primary
                                            @elseif($enrollment->status == 'completed') bg-success
                                            @elseif($enrollment->status == 'failed') bg-danger
                                            @else bg-warning
                                            @endif">
                                            @if($enrollment->status == 'active') نشط
                                            @elseif($enrollment->status == 'completed') مكتمل
                                            @elseif($enrollment->status == 'failed') راسب
                                            @else منسحب
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if($enrollment->status == 'active' || $enrollment->final_exam_grade === null)
                                                <button type="button" class="btn btn-primary btn-sm"
                                                        onclick="openGradeModal({{ $enrollment->enrollment_id }}, '{{ $enrollment->student->name }}', {{ $enrollment->final_exam_grade ?? 0 }})">
                                                    <i class="fas fa-star me-1"></i>
                                                    {{ $enrollment->final_exam_grade ? 'تعديل' : 'إدخال' }}
                                                </button>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>تم التقييم
                                                </span>
                                            @endif

                                            @if($enrollment->exam_file_path)
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-outline-info btn-sm dropdown-toggle"
                                                            data-bs-toggle="dropdown" title="خيارات الملف">
                                                        <i class="fas fa-file-alt"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item"
                                                               href="{{ route('teacher.view.exam', $enrollment->enrollment_id) }}"
                                                               target="_blank">
                                                                <i class="fas fa-eye me-2"></i>عرض الملف
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                               href="{{ route('teacher.download.exam', $enrollment->enrollment_id) }}">
                                                                <i class="fas fa-download me-2"></i>تحميل الملف
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($enrollment->final_exam_grade)
                                            <strong class="text-primary">{{ $enrollment->final_exam_grade }}%</strong>
                                        @else
                                            <span class="text-muted">لم تدخل بعد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($enrollment->grade)
                                            <span class="badge
                                                @if($enrollment->grade == 'F') bg-danger
                                                @elseif(in_array($enrollment->grade, ['A+', 'A'])) bg-success
                                                @else bg-info
                                                @endif">
                                                {{ $enrollment->grade }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                    <h5>لا يوجد طلاب مسجلين</h5>
                    <p class="text-muted">لم يسجل أي طالب في هذا الكورس بعد</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal لإدخال الدرجة -->
    <div class="modal fade" id="gradeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إدخال درجة الاختبار النهائي</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="gradeForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم الطالب:</label>
                            <p id="studentName" class="fw-bold text-primary"></p>
                        </div>

                        <div class="mb-3">
                            <label for="final_exam_grade" class="form-label">درجة الاختبار النهائي (من 100)</label>
                            <input type="number" class="form-control" id="final_exam_grade"
                                   name="final_exam_grade" min="0" max="100" step="0.5" required>
                            <div class="form-text">أدخل الدرجة من 0 إلى 100</div>
                        </div>

                        <div class="alert alert-info">
                            <h6>سلم التقديرات:</h6>
                            <div class="row small">
                                <div class="col-4">95-100: A+</div>
                                <div class="col-4">90-94: A</div>
                                <div class="col-4">85-89: B+</div>
                                <div class="col-4">80-84: B</div>
                                <div class="col-4">75-79: C+</div>
                                <div class="col-4">70-74: C</div>
                                <div class="col-4">65-69: D+</div>
                                <div class="col-4">60-64: D</div>
                                <div class="col-4">أقل من 60: F</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>حفظ الدرجة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function openGradeModal(enrollmentId, studentName, currentGrade) {
        document.getElementById('studentName').textContent = studentName;
        document.getElementById('final_exam_grade').value = currentGrade || '';

        const form = document.getElementById('gradeForm');
        form.action = `/teacher/enrollments/${enrollmentId}/grade`;

        const modal = new bootstrap.Modal(document.getElementById('gradeModal'));
        modal.show();
    }
</script>
@endsection
