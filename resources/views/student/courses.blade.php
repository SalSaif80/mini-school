@extends('layouts.app')

@section('title', 'كورساتي')

@section('sidebar')
    <a class="nav-link" href="{{ route('student.dashboard') }}">
        <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
    </a>
    <a class="nav-link active" href="{{ route('student.courses') }}">
        <i class="fas fa-book me-2"></i>كورساتي
    </a>
    <a class="nav-link" href="{{ route('student.available.courses') }}">
        <i class="fas fa-plus me-2"></i>كورسات متاحة
    </a>
    <a class="nav-link" href="{{ route('student.grades') }}">
        <i class="fas fa-star me-2"></i>درجاتي
    </a>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-book me-2"></i>كورساتي</h2>
        <a href="{{ route('student.available.courses') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>تسجيل في كورس جديد
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>قائمة الكورسات المسجل بها</h5>
        </div>
        <div class="card-body">
            @if($enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>اسم الكورس</th>
                                <th>المدرس</th>
                                <th>الموعد</th>
                                <th>الغرفة</th>
                                <th>الفصل الدراسي</th>
                                <th>الحالة</th>
                                <th>الدرجة النهائية</th>
                                <th>الدرجة الحرفية</th>
                                <th>ملف الاختبار</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->course->course_name }}</td>
                                    <td>{{ $enrollment->course->teacher->name }}</td>
                                    <td>{{ $enrollment->course->schedule_date->format('Y-m-d H:i') }}</td>
                                    <td>{{ $enrollment->course->room_number }}</td>
                                    <td>{{ $enrollment->semester }}</td>
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
                                            @else مسحوب
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if($enrollment->final_exam_grade)
                                            <strong class="text-primary">{{ $enrollment->final_exam_grade }}%</strong>
                                        @else
                                            <span class="text-muted">لم تحدد</span>
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
                                    <td>
                                        @if($enrollment->exam_file_path)
                                            <span class="badge bg-success">
                                                <i class="fas fa-file-alt me-1"></i>تم الرفع
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>لم يرفع
                                            </span>
                                        @endif
                                    </td>
                                                                        <td>
                                        @if($enrollment->status == 'active')
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary btn-sm"
                                                        onclick="openUploadModal({{ $enrollment->enrollment_id }}, '{{ $enrollment->course->course_name }}', {{ $enrollment->exam_file_path ? 'true' : 'false' }})">
                                                    <i class="fas fa-upload me-1"></i>
                                                    {{ $enrollment->exam_file_path ? 'تعديل الملف' : 'رفع ملف' }}
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmWithdraw({{ $enrollment->enrollment_id }}, '{{ $enrollment->course->course_name }}')">
                                                    <i class="fas fa-sign-out-alt me-1"></i>انسحاب
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted">منتهي</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5>لا توجد كورسات</h5>
                    <p class="text-muted">لم تقم بالتسجيل في أي كورسات بعد</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal لرفع ملف الاختبار -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">رفع ملف الاختبار النهائي</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="uploadForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم المادة:</label>
                            <p id="courseName" class="fw-bold text-primary"></p>
                        </div>

                        <div class="mb-3">
                            <label for="exam_file" class="form-label">ملف الاختبار</label>
                            <input type="file" class="form-control" id="exam_file" name="exam_file"
                                   accept=".pdf,.doc,.docx" required>
                            <div class="form-text">
                                أنواع الملفات المسموحة: PDF, DOC, DOCX (حد أقصى 10 ميجابايت)
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>تنبيه:</strong> بعد رفع الملف، سيقوم المدرس بتصحيحه وإعطائك الدرجة.
                        </div>

                        <div id="hasExistingFile" class="alert alert-info d-none">
                            <i class="fas fa-info-circle me-2"></i>
                            يوجد ملف مرفوع مسبقاً. رفع ملف جديد سيحل محل الملف السابق.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-1"></i>رفع الملف
                        </button>
                        <button type="button" id="deleteFileBtn" class="btn btn-danger d-none"
                                onclick="deleteExamFile()">
                            <i class="fas fa-trash me-1"></i>حذف الملف الحالي
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let currentEnrollmentId = null;

    function openUploadModal(enrollmentId, courseName, hasFile) {
        currentEnrollmentId = enrollmentId;
        document.getElementById('courseName').textContent = courseName;

        const form = document.getElementById('uploadForm');
        form.action = `/student/enrollments/${enrollmentId}/upload-exam`;

        // إظهار/إخفاء التنبيهات والأزرار حسب وجود ملف
        const hasFileAlert = document.getElementById('hasExistingFile');
        const deleteBtn = document.getElementById('deleteFileBtn');

        if (hasFile) {
            hasFileAlert.classList.remove('d-none');
            deleteBtn.classList.remove('d-none');
        } else {
            hasFileAlert.classList.add('d-none');
            deleteBtn.classList.add('d-none');
        }

        const modal = new bootstrap.Modal(document.getElementById('uploadModal'));
        modal.show();
    }

        function deleteExamFile() {
        if (!currentEnrollmentId) return;

        if (confirm('هل أنت متأكد من حذف ملف الاختبار؟')) {
            // إنشاء form للحذف
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/student/enrollments/${currentEnrollmentId}/delete-exam`;

            // إضافة CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // إضافة method DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }
    }

    function confirmWithdraw(enrollmentId, courseName) {
        if (confirm(`هل أنت متأكد من الانسحاب من كورس "${courseName}"؟\n\nلن تتمكن من العودة للكورس مرة أخرى.`)) {
            // إنشاء form للانسحاب
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/student/withdraw/${enrollmentId}`;

            // إضافة CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // إضافة method PATCH
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PATCH';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
