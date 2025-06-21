<button type="button" class="btn btn-sm btn-danger" title="حذف"
                                                    data-toggle="modal" data-target="#deleteModal{{ $course->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $course->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $course->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $course->id }}">تأكيد الحذف</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>هل أنت متأكد من حذف المادة <strong>"{{ $course->title }}"</strong>؟</p>
                                                            <p class="text-danger"><small>هذا الإجراء لا يمكن التراجع عنه.</small></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                            <form action="{{ route('courses.destroy', $course) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">حذف</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
