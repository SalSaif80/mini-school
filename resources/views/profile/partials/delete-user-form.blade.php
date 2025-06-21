<div class="card">
    <div class="card-header">{{ __('حذف الحساب') }}</div>

    <div class="card-body">
        <div class="mb-3">
            {{ __('بمجرد حذف حسابك، سيتم حذف جميع الموارد والبيانات الخاصة به بشكل دائم. قبل حذف حسابك، يرجى تحميل أي بيانات أو معلومات ترغب في الاحتفاظ بها.') }}
        </div>

        <div class="row mb-0">
            <div class="col-md-6">
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteAccountModal">
                    {{ __('حذف الحساب') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteAccountModalLabel">
            {{ __('هل أنت متأكد من أنك تريد حذف حسابك؟') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            {{ __('بمجرد حذف حسابك، سيتم حذف جميع الموارد والبيانات الخاصة به بشكل دائم. يرجى إدخال كلمة المرور للتأكد من أنك تريد حذف حسابك بشكل دائم.') }}
        </div>

        @if($errors->getBag('userDeletion')->any())
            <div class="alert alert-danger" role="alert" style="text-align: right; direction: rtl;">
                <strong>{{ $errors->getBag('userDeletion')->first('password') }}</strong>
            </div>
        @endif

        <form id="deleteAccountForm" method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="form-group">
                <input
                    type="password"
                    class="form-control @if($errors->getBag('userDeletion')->has('password')) is-invalid @endif"
                    name="password"
                    placeholder="{{ __('كلمة المرور') }}"
                    required
                    style="text-align: right; direction: rtl;">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            {{ __('إلغاء') }}
        </button>
        <button type="submit" class="btn btn-danger" form="deleteAccountForm">
            {{ __('حذف الحساب') }}
        </button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // إظهار المودال عند وجود أخطاء في التحقق
    @if($errors->getBag('userDeletion')->any())
        $('#deleteAccountModal').modal('show');
    @endif
});
</script>
