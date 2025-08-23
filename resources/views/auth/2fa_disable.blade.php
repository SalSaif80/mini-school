@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row py-5 justify-content-center">
            <div class="col-md-6">
                <div class="card p-4 bg-white">
                    <h2>Disable Two-Factor Authentication</h2>

                    <form method="POST" action="{{ route('2fa.disable') }}">
                        @csrf
                        <div class="form-group">
                            <p>Please enter the <strong>OTP</strong> generated on your Authenticator App. <br> Ensure you
                                submit the current one because it refreshes every 30 seconds.</p>
                            <label for="one_time_password">Enter OTP to disable 2FA:</label>
                            <input type="number" name="otp" class="form-control" required>
                        </div>
                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                        <button class="btn btn-danger mt-3" type="submit">Disable 2FA</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection