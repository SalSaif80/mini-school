@extends('dashboard.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2 class="my-3">{{ __('الملف الشخصي') }}</h2>
                <div class="mb-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
                <div class="mb-4">
                    @include('profile.partials.update-password-form')
                </div>
                <div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // إظهار المودال عند وجود أخطاء في حذف المستخدم
    @if($errors->getBag('userDeletion')->any())
        $('#deleteAccountModal').modal('show');
    @endif
});
</script>
@endsection
