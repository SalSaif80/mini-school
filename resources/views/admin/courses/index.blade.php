@extends('layouts.app')

@section('title', 'إدارة الكورسات')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-book me-2"></i>إدارة الكورسات</h2>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>إضافة كورس جديد
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>قائمة الكورسات</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>اسم الكورس</th>
                            <th>المدرس</th>
                            <th>موعد المحاضرة</th>
                            <th>رقم الغرفة</th>
                            <th>عدد الطلاب</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                            <tr>
                                <td>{{ $course->course_id }}</td>
                                <td>{{ $course->course_name }}</td>
                                <td>
                                    @if($course->teacher)
                                        <span class="badge bg-success">{{ $course->teacher->name }}</span>
                                    @else
                                        <span class="badge bg-warning">غير محدد</span>
                                    @endif
                                </td>
                                <td>{{ $course->schedule_date->format('Y-m-d H:i') }}</td>
                                <td>{{ $course->room_number }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $course->enrollments_count }} طالب
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.courses.show', $course->course_id) }}"
                                           class="btn btn-info btn-sm" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.courses.students', $course->course_id) }}"
                                           class="btn btn-success btn-sm" title="عرض الطلاب">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        <a href="{{ route('admin.courses.edit', $course->course_id) }}"
                                           class="btn btn-warning btn-sm" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($course->enrollments_count == 0)
                                        <button type="button" class="btn btn-danger btn-sm"
                                                title="حذف" onclick="confirmDelete({{ $course->course_id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @else
                                            <button disabled class="btn btn-danger btn-sm" title="لا يمكن حذف الكورس لأنه لديه طلاب">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $courses->links() }}
            </div>
        </div>
    </div>

    {{-- تم فصل المودال علشان ما يتكرر المودال لكل كورس--}}
    <!-- Modal لتأكيد الحذف -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد من حذف هذا الكورس؟ سيتم حذف جميع التسجيلات المرتبطة به.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">حذف</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function confirmDelete(courseId) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/courses/${courseId}`;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
@endsection
