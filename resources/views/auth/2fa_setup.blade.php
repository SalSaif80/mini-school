@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Enable Two-Factor Authentication</h2>
                <p>Set up your two factor authentication by scanning the barcode below. Alternatively, you can use the code
                    <strong>{{ $secret }}</strong></p>
                <p>Ensure you submit the current one because it refreshes every 30 seconds.</p>
                <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="QR Code">

                <form method="POST" action="{{ route('2fa.enable') }}" class="mt-4">
                    @csrf
                    <div class="form-group">
                        <label for="otp">Enter OTP from app:</label>
                        <input type="number" name="otp" id="otp" class="form-control" required>
                    </div>
                    @error('otp')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    @if (session('error') && !$errors->has('otp'))
                        <span class="invalid-feedback" role="alert" style="display: block;">
                            <strong>{{ session('error') }}</strong>
                        </span>
                    @endif
                    <button class="btn btn-primary mt-2" type="submit">Enable 2FA</button>
                </form>
            </div>
        </div>
    </div>
@endsection