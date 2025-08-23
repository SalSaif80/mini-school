@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row py-5 justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 bg-white">
                <h2>Two-Factor Authentication</h2>

                <form action="{{ route('2fa.verify') }}" method="POST">

                    @csrf
                    <div class="form-group">
                        <p>Please enter the <strong>OTP</strong> generated on your Authenticator App. <br> Ensure you submit the current one because it refreshes every 30 seconds.</p>
                        <label for="one_time_password">Enter the 6-digit OTP from your app:</label>
                        <input type="number" name="one_time_password" class="form-control" required>
                    </div>
                    @if (count($errors) > 0)
                    @foreach ($errors->all() as $error)
                    <p class="text-danger">{{ $error }}</p>
                    @endforeach
                    @endif
                    <button class="btn btn-primary mt-3" type="submit">Verify</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection