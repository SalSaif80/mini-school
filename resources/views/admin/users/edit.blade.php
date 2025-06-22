@extends('layouts.app')

@section('title', 'تعديل المستخدم')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-edit me-2"></i>تعديل المستخدم: {{ $user->name }}</h2>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>تعديل بيانات المستخدم</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم الكامل</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">اسم المستخدم</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                   id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور الجديدة (اختياري)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password">
                            <small class="form-text text-muted">اتركه فارغاً إذا لم ترد تغيير كلمة المرور</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="user_type" class="form-label">نوع المستخدم (الصلاحية)</label>
                    <select class="form-select @error('user_type') is-invalid @enderror"
                            id="user_type" name="user_type" required>
                        <option value="">اختر نوع المستخدم</option>
                        <option value="admin" {{ old('user_type', $user->user_type) == 'admin' ? 'selected' : '' }}>
                            <i class="fas fa-user-shield"></i> إدارة
                        </option>
                        <option value="teacher" {{ old('user_type', $user->user_type) == 'teacher' ? 'selected' : '' }}>
                            <i class="fas fa-chalkboard-teacher"></i> مدرس
                        </option>
                        <option value="student" {{ old('user_type', $user->user_type) == 'student' ? 'selected' : '' }}>
                            <i class="fas fa-user-graduate"></i> طالب
                        </option>
                    </select>
                    @error('user_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>ملاحظة:</strong> تغيير نوع المستخدم سيؤثر على صلاحياته في النظام.
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
