@extends('layouts.app')

@section('title', 'عرض التسجيل')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-eye me-2"></i>تفاصيل التسجيل #{{ $enrollment->enrollment_id }}</h2>
        <div>
            <a href="{{ route('admin.enrollments.edit', $enrollment->enrollment_id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>تعديل
            </a>
            <a href="{{ route('admin.enrollments') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-1"></i>العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row">
        <!-- معلومات التسجيل الأساسية -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات التسجيل</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">رقم التسجيل:</th>
                                    <td><strong class="text-primary">#{{ $enrollment->enrollment_id }}</strong></td>
                                </tr>
                                <tr>
                                    <th>تاريخ التسجيل:</th>
                                    <td>{{ $enrollment->enrollment_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>الفصل الدراسي:</th>
                                    <td><span class="badge bg-info">{{ $enrollment->semester }}</span></td>
                                </tr>
                                <tr>
                                    <th>الحالة:</th>
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
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">تاريخ الإكمال:</th>
                                    <td>
                                        @if($enrollment->completion_date)
                                            {{ $enrollment->completion_date->format('Y-m-d') }}
                                        @else
                                            <span class="text-muted">غير مكتمل</span>
                                        @endif
                                    </td>
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
                </div>
            </div>

            <!-- معلومات الطالب -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>معلومات الطالب</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div class="text-center">
                                <i class="fas fa-user-circle fa-4x text-primary"></i>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="20%">الاسم:</th>
                                    <td><strong>{{ $enrollment->student->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>اسم المستخدم:</th>
                                    <td>{{ $enrollment->student->username }}</td>
                                </tr>
                                <tr>
                                    <th>نوع المستخدم:</th>
                                    <td><span class="badge bg-success">طالب</span></td>
                                </tr>
                                <tr>
                                    <th>تاريخ التسجيل في النظام:</th>
                                    <td>{{ $enrollment->student->created_at->format('Y-m-d') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات الكورس -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>معلومات الكورس</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">اسم الكورس:</th>
                                    <td><strong>{{ $enrollment->course->course_name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>المدرس:</th>
                                    <td>{{ $enrollment->course->teacher->name }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">رقم القاعة:</th>
                                    <td>{{ $enrollment->course->room_number }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الجدولة:</th>
                                    <td>{{ $enrollment->course->schedule_date->format('Y-m-d') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الدرجات وملف الاختبار -->
        <div class="col-md-4">
            <!-- الدرجات -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>الدرجات</h5>
                </div>
                <div class="card-body text-center">
                    @if($enrollment->final_exam_grade)
                        <div class="mb-3">
                            <h2 class="text-primary mb-1">{{ $enrollment->final_exam_grade }}%</h2>
                            <p class="text-muted mb-0">الدرجة النهائية</p>
                        </div>

                        @if($enrollment->grade)
                            <div class="mb-3">
                                <span class="badge fs-5 px-3 py-2
                                    @if($enrollment->grade == 'F') bg-danger
                                    @elseif(in_array($enrollment->grade, ['A+', 'A'])) bg-success
                                    @else bg-info
                                    @endif">
                                    {{ $enrollment->grade }}
                                </span>
                                <p class="text-muted mt-2 mb-0">الدرجة الحرفية</p>
                            </div>
                        @endif

                        <div class="progress mb-3" style="height: 20px;">
                            <div class="progress-bar
                                @if($enrollment->final_exam_grade >= 90) bg-success
                                @elseif($enrollment->final_exam_grade >= 70) bg-info
                                @elseif($enrollment->final_exam_grade >= 50) bg-warning
                                @else bg-danger
                                @endif"
                                role="progressbar"
                                style="width: {{ $enrollment->final_exam_grade }}%">
                                {{ $enrollment->final_exam_grade }}%
                            </div>
                        </div>

                        <small class="text-muted">
                            @if($enrollment->final_exam_grade >= 50)
                                <i class="fas fa-check-circle text-success me-1"></i>ناجح
                            @else
                                <i class="fas fa-times-circle text-danger me-1"></i>راسب
                            @endif
                        </small>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <p class="text-muted">لم يتم إدخال الدرجة بعد</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ملف الاختبار -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>ملف الاختبار النهائي</h5>
                </div>
                <div class="card-body text-center">
                    @if($enrollment->exam_file_path)
                        <div class="mb-3">
                            <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                            <p class="mb-2"><strong>تم رفع الملف</strong></p>
                            <p class="text-muted small mb-3">{{ basename($enrollment->exam_file_path) }}</p>
                            <a href="{{ Storage::url($enrollment->exam_file_path) }}"
                               target="_blank"
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-download me-1"></i>تحميل الملف
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                            <p class="text-muted">لم يتم رفع ملف الاختبار</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info me-2"></i>معلومات إضافية</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-primary">{{ $enrollment->student->enrollments->count() ?? 0 }}</h6>
                                <small class="text-muted">إجمالي كورسات الطالب</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-success">{{ $enrollment->course->enrollments->count() ?? 0 }}</h6>
                            <small class="text-muted">طلاب الكورس</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إجراءات سريعة -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>الإجراءات</h5>
        </div>
        <div class="card-body">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.enrollments.edit', $enrollment->enrollment_id) }}"
                   class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>تعديل التسجيل
                </a>
                <a href="{{ route('admin.users.show', $enrollment->student->id) }}"
                   class="btn btn-info">
                    <i class="fas fa-user me-1"></i>عرض ملف الطالب
                </a>
                <a href="{{ route('admin.courses.show', $enrollment->course->course_id) }}"
                   class="btn btn-success">
                    <i class="fas fa-book me-1"></i>عرض تفاصيل الكورس
                </a>
                <button type="button"
                        class="btn btn-danger"
                        onclick="confirmDelete({{ $enrollment->enrollment_id }})">
                    <i class="fas fa-trash me-1"></i>حذف التسجيل
                </button>
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
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        سيتم حذف جميع البيانات المرتبطة بهذا التسجيل بما في ذلك ملف الاختبار.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>حذف التسجيل
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
</script>
@endsection
