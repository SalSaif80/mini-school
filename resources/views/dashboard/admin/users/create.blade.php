@extends('dashboard.layouts.master')
@section('title', 'إنشاء مستخدم جديد')
@section('css')
<!-- Internal Select2 css -->
<link href="{{URL::asset('dashboard/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<!--Internal  Datetimepicker-slider css -->
<link href="{{URL::asset('assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/pickerjs/picker.min.css')}}" rel="stylesheet">
<!-- Internal Spectrum-colorpicker css -->
<link href="{{URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css')}}" rel="stylesheet">
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">ادارة المستخدمين</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إنشاء مستخدم جديد</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fa fa-user-plus me-2"></i>
                            إنشاء مستخدم جديد
                        </h3>
                        <a class="btn btn-outline-light" href="{{ route('users.index') }}">
                            <i class="fa fa-arrow-right me-1"></i>
                            العودة
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger border-0 shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-exclamation-triangle me-2"></i>
                                <strong>تنبيه!</strong> هناك بعض المشاكل في إدخالك.
                            </div>
                            <hr class="my-2">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.store') }}" class="needs-validation" novalidate>
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold text-dark">
                                    <i class="fa fa-user text-primary me-1"></i>
                                    الاسم
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}"
                                       placeholder="أدخل الاسم" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold text-dark">
                                    <i class="fa fa-envelope text-primary me-1"></i>
                                    البريد الإلكتروني
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}"
                                       placeholder="أدخل البريد الإلكتروني" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold text-dark">
                                    <i class="fa fa-lock text-primary me-1"></i>
                                    كلمة المرور
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password"
                                       placeholder="أدخل كلمة المرور" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-bold text-dark">
                                    <i class="fa fa-lock text-primary me-1"></i>
                                    تأكيد كلمة المرور
                                </label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation" name="password_confirmation"
                                       placeholder="أكد كلمة المرور" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-4">
                                <label for="roles" class="form-label fw-bold text-dark">
                                    <i class="fa fa-users text-primary me-1"></i>
                                    الأدوار
                                </label>
                                <div class="position-relative">
                                    <select class="form-control select2 @error('roles') is-invalid @enderror"
                                            id="roles" name="roles[]" multiple="multiple" required>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                    {{ in_array($role->name, old('roles', [])) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('roles')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted mt-3 d-block">
                                    <i class="fa fa-info-circle me-2 text-primary"></i>
                                    يمكنك البحث والاختيار من القائمة
                                </small>
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fa fa-save me-2"></i>
                                    إنشاء المستخدم
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-lg px-5 ms-2">
                                    <i class="fa fa-times me-2"></i>
                                    إلغاء
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<!--Internal  Datepicker js -->
<script src="{{URL::asset('dashboard/assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{URL::asset('dashboard/assets/plugins/jquery.maskedinput/jquery.maskedinput.js')}}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{URL::asset('dashboard/assets/plugins/spectrum-colorpicker/spectrum.js')}}"></script>
<!-- Internal Select2.min js -->
<script src="{{URL::asset('dashboard/assets/plugins/select2/js/select2.min.js')}}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{URL::asset('dashboard/assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{URL::asset('dashboard/assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>
<!-- Ionicons js -->
<script src="{{URL::asset('dashboard/assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js')}}"></script>
<!--Internal  pickerjs js -->
<script src="{{URL::asset('dashboard/assets/plugins/pickerjs/picker.min.js')}}"></script>
<!-- Internal form-elements js -->
<script src="{{URL::asset('dashboard/assets/js/form-elements.js')}}"></script>
@endsection