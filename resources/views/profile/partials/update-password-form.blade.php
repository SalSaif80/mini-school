<div class="card">
    <div class="card-header">{{ __('تحديث كلمة المرور') }}</div>

    <div class="card-body">
        <div class="mb-3">
            {{ __('تأكد من أن حسابك يستخدم كلمة مرور طويلة وعشوائية للبقاء آمنًا.') }}
        </div>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="row mb-3">
                <label for="password" class="col-md-4 col-form-label text-md-end">
                    {{ __('كلمة المرور الحالية') }}
                </label>

                <div class="col-md-6">
                    <input id="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" name="current_password" required autocomplete="current-password">

                    @error('current_password', 'updatePassword')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="password" class="col-md-4 col-form-label text-md-end">
                    {{ __('كلمة المرور الجديدة') }}
                </label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password', 'updatePassword')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="password_confirmation" class="col-md-4 col-form-label text-md-end">
                    {{ __('تأكيد كلمة المرور') }}
                </label>

                <div class="col-md-6">
                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" name="password_confirmation" required>

                    @error('password_confirmation', 'updatePassword')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('تحديث') }}
                    </button>
                    @if (session('status') === 'password-updated')
                        <span class="m-1 fade-out badge badge-success">{{ __('تم التحديث.') }}</span>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>