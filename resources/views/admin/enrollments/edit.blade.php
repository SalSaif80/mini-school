@extends('layouts.app')

@section('title', 'تعديل التسجيل')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit me-2"></i>تعديل التسجيل #{{ $enrollment->enrollment_id }}</h2>
        <div>
            <a href="{{ route('admin.enrollments.show', $enrollment->enrollment_id) }}" class="btn btn-info">
                <i class="fas fa-eye me-1"></i>عرض التفاصيل
            </a>
            <a href="{{ route('admin.enrollments') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-1"></i>العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>تعديل بيانات التسجيل</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.enrollments.update', $enrollment->enrollment_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">الطالب <span class="text-danger">*</span></label>
                                    <select class="form-select @error('student_id') is-invalid @enderror"
                                            id="student_id" name="student_id" required>
                                        <option value="">اختر الطالب</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}"
                                                    {{ $enrollment->student_id == $student->id ? 'selected' : '' }}>
                                                {{ $student->name }} ({{ $student->username }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="course_id" class="form-label">الكورس <span class="text-danger">*</span></label>
                                    <select class="form-select @error('course_id') is-invalid @enderror"
                                            id="course_id" name="course_id" required>
                                        <option value="">اختر الكورس</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->course_id }}"
                                                    {{ $enrollment->course_id == $course->course_id ? 'selected' : '' }}>
                                                {{ $course->course_name }} - {{ $course->teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="semester" class="form-label">الفصل الدراسي <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('semester') is-invalid @enderror"
                                           id="semester"
                                           name="semester"
                                           value="{{ old('semester', $enrollment->semester) }}"
                                           required>
                                    @error('semester')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="active" {{ $enrollment->status == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="completed" {{ $enrollment->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                        <option value="failed" {{ $enrollment->status == 'failed' ? 'selected' : '' }}>راسب</option>
                                        <option value="dropped" {{ $enrollment->status == 'dropped' ? 'selected' : '' }}>منسحب</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="final_exam_grade" class="form-label">الدرجة النهائية (0-100)</label>
                                    <input type="number"
                                           class="form-control @error('final_exam_grade') is-invalid @enderror"
                                           id="final_exam_grade"
                                           name="final_exam_grade"
                                           min="0"
                                           max="100"
                                           step="0.01"
                                           value="{{ old('final_exam_grade', $enrollment->final_exam_grade) }}"
                                           placeholder="أدخل الدرجة النهائية">
                                    @error('final_exam_grade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        سيتم حساب الدرجة الحرفية والحالة تلقائياً عند إدخال الدرجة
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">الدرجة الحرفية الحالية</label>
                                    <div class="form-control-plaintext">
                                        @if($enrollment->grade)
                                            <span class="badge fs-6 px-3 py-2
                                                @if($enrollment->grade == 'F') bg-danger
                                                @elseif(in_array($enrollment->grade, ['A+', 'A'])) bg-success
                                                @else bg-info
                                                @endif">
                                                {{ $enrollment->grade }}
                                            </span>
                                        @else
                                            <span class="text-muted">لم يتم تحديدها بعد</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>حفظ التغييرات
                                </button>
                                <a href="{{ route('admin.enrollments.show', $enrollment->enrollment_id) }}"
                                   class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>إلغاء
                                </a>
                            </div>
                            <div>
                                <button type="button"
                                        class="btn btn-danger"
                                        onclick="confirmDelete({{ $enrollment->enrollment_id }})">
                                    <i class="fas fa-trash me-1"></i>حذف التسجيل
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- معلومات التسجيل الحالية -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>المعلومات الحالية</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th width="50%">رقم التسجيل:</th>
                            <td><strong class="text-primary">#{{ $enrollment->enrollment_id }}</strong></td>
                        </tr>
                        <tr>
                            <th>تاريخ التسجيل:</th>
                            <td>{{ $enrollment->enrollment_date->format('Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الإنشاء:</th>
                            <td>{{ $enrollment->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>آخر تحديث:</th>
                            <td>{{ $enrollment->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- ملف الاختبار -->
            @if($enrollment->exam_file_path)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>ملف الاختبار</h5>
                    </div>
                    <div class="card-body text-center">
                        <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                        <p class="mb-2"><strong>تم رفع الملف</strong></p>
                        <p class="text-muted small mb-3">{{ basename($enrollment->exam_file_path) }}</p>
                        <a href="{{ Storage::url($enrollment->exam_file_path) }}"
                           target="_blank"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-download me-1"></i>تحميل الملف
                        </a>
                    </div>
                </div>
            @endif

            <!-- نصائح التعديل -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>نصائح</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i>معلومات مهمة:</h6>
                        <ul class="small mb-0">
                            <li>عند تغيير الطالب أو الكورس، تأكد من عدم وجود تسجيل مكرر</li>
                            <li>الدرجة النهائية يجب أن تكون بين 0 و 100</li>
                            <li>سيتم حساب الدرجة الحرفية تلقائياً</li>
                            <li>الدرجة أقل من 50 تعني راسب</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i>تحذير:</h6>
                        <p class="small mb-0">
                            تغيير الطالب أو الكورس قد يؤثر على الإحصائيات والتقارير.
                            تأكد من صحة البيانات قبل الحفظ.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal تأكيد الحذف -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد من حذف تسجيل الطالب <strong>{{ $enrollment->student->name }}</strong>
                    في كورس <strong>{{ $enrollment->course->course_name }}</strong>؟
                    <br><br>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>تحذير:</strong> سيتم حذف جميع البيانات المرتبطة بهذا التسجيل نهائياً.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>حذف نهائياً
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function confirmDelete(enrollmentId) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/enrollments/${enrollmentId}`;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    // تحديث معاينة الدرجة الحرفية عند تغيير الدرجة النهائية
    document.getElementById('final_exam_grade').addEventListener('input', function() {
        const grade = parseFloat(this.value);
        let letterGrade = '';
        let badgeClass = '';

        if (grade >= 95) {
            letterGrade = 'A+';
            badgeClass = 'bg-success';
        } else if (grade >= 90) {
            letterGrade = 'A';
            badgeClass = 'bg-success';
        } else if (grade >= 85) {
            letterGrade = 'B+';
            badgeClass = 'bg-info';
        } else if (grade >= 80) {
            letterGrade = 'B';
            badgeClass = 'bg-info';
        } else if (grade >= 75) {
            letterGrade = 'C+';
            badgeClass = 'bg-info';
        } else if (grade >= 70) {
            letterGrade = 'C';
            badgeClass = 'bg-info';
        } else if (grade >= 65) {
            letterGrade = 'D+';
            badgeClass = 'bg-warning';
        } else if (grade >= 60) {
            letterGrade = 'D';
            badgeClass = 'bg-warning';
        } else if (grade >= 50) {
            letterGrade = 'D-';
            badgeClass = 'bg-warning';
        } else if (grade >= 0) {
            letterGrade = 'F';
            badgeClass = 'bg-danger';
        }

        // تحديث عرض الدرجة الحرفية
        const gradeDisplay = document.querySelector('.form-control-plaintext');
        if (letterGrade) {
            gradeDisplay.innerHTML = `<span class="badge fs-6 px-3 py-2 ${badgeClass}">${letterGrade}</span>`;
        } else {
            gradeDisplay.innerHTML = '<span class="text-muted">أدخل درجة صحيحة</span>';
        }
    });
</script>
@endsection
