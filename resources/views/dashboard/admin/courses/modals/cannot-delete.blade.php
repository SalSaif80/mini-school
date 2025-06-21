<button type="button" class="btn btn-sm btn-outline-warning"
    title="لا يمكن الحذف - يوجد {{ $course->enrollments->count() }} طالب مسجل" data-toggle="modal"
    data-target="#cannotDeleteModal{{ $course->id }}">
    <i class="fas fa-ban"></i>
</button>


<!-- Cannot Delete Modal -->
<div class="modal fade" id="cannotDeleteModal{{ $course->id }}" tabindex="-1" role="dialog"
    aria-labelledby="cannotDeleteModalLabel{{ $course->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cannotDeleteModalLabel{{ $course->id }}">لا يمكن حذف المادة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h6>لا يمكن حذف المادة <strong>"{{ $course->title }}"</strong></h6>
                    <p class="text-muted">يوجد <strong>{{ $course->enrollments->count() }}</strong> طالب مسجل في هذه
                        المادة.</p>
                    <p class="text-danger"><small>يجب إلغاء تسجيل جميع الطلاب أولاً قبل حذف المادة.</small></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <a href="{{ route('courses.students', $course) }}" class="btn btn-primary">
                    <i class="fas fa-users"></i> عرض الطلاب المسجلين
                </a>
            </div>
        </div>
    </div>
</div>
