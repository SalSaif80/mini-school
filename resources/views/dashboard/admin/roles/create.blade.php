@extends('dashboard.layouts.master')
@section('title', 'إنشاء أدوار جديدة')
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">ادارة الأدوار</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إنشاء أدوار جديدة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fa fa-plus text-primary me-2"></i>
                            إنشاء أدوار جديدة
                        </h3>
                        <a class="btn btn-outline-primary" href="{{ route('roles.index') }}">
                            <i class="fa fa-arrow-right me-1"></i>
                            العودة
                        </a>
                    </div>
                </div>

                <div class="card-body">
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

                    <form method="POST" action="{{ route('roles.store') }}" class="needs-validation" novalidate>
                        @csrf

                        <div class="row">
                            <div class="col-12 mb-4">
                                <label for="name" class="form-label fw-bold text-dark">
                                    <i class="fa fa-tag text-primary me-1"></i>
                                    اسم الدور
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="أدخل اسم الدور" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fa fa-shield text-primary me-1"></i>
                                    الصلاحيات
                                </label>
                                <div class="row">
                                    @foreach ($permission as $value)
                                        <div class="col-md-4 col-lg-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input @error('permission') is-invalid @enderror"
                                                    type="checkbox" name="permission[]"
                                                    value="{{ $value->id }}" id="permission_{{ $value->id }}"
                                                    {{ in_array($value->id, old('permission', [])) ? 'checked' : '' }}>
                                               &nbsp;
                                               &nbsp;
                                               &nbsp;
                                                    <label class="form-check-label" for="permission_{{ $value->id }}">
                                                    {{ $value->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('permission')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fa fa-save me-2"></i>
                                    حفظ الدور
                                </button>
                                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-lg px-5 ms-2">
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
