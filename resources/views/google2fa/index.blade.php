@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">التحقق الثنائي (Google Authenticator)</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first('one_time_password') }}
        </div>
    @endif

    <form method="POST" action="{{ url('google2fa/authenticate') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">ادخل رمز التحقق</label>
            <input type="text" name="one_time_password" class="form-control" inputmode="numeric" pattern="\d{6}" required autofocus>
        </div>

        <button type="submit" class="btn btn-primary">تأكيد</button>
    </form>
</div>
@endsection
