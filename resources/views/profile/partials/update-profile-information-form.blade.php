<div class="card">
    <div class="card-header">{{ __('الملف الشخصي') }}</div>

    <div class="card-body">
        <form
            id="send-verification"
            class="d-none"
            method="post"
            action="{{ route('verification.send') }}"
        >
            @csrf
        </form>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="row mb-3">
                <label for="name" class="col-md-4 col-form-label text-md-end">
                    {{ __('الاسم') }}
                </label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="email" class="col-md-4 col-form-label text-md-end">
                    {{ __('البريد الإلكتروني') }}
                </label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="mb-0">
                                {{ __('البريد الإلكتروني غير مؤكد.') }}

                                <button form="send-verification" class="btn btn-link p-0">
                                    {{ __('اضغط هنا لإعادة إرسال البريد الإلكتروني للتحقق.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <div class="alert alert-success mt-3 mb-0" role="alert">
                                    {{ __('تم إرسال رابط التحقق إلى بريدك الإلكتروني.') }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('تحديث') }}
                    </button>
                    @if (session('status') === 'profile-updated')
                        <span class="m-1 fade-out badge badge-success">{{ __('تم التحديث.') }}</span>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
