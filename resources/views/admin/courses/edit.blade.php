@extends('layouts.app')

@section('title', 'تعديل الكورس')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit me-2"></i>تعديل الكورس</h2>
        <a href="{{ route('admin.courses') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-book me-2"></i>تعديل بيانات الكورس</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.courses.update', $course->course_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="course_name" class="form-label">اسم الكورس</label>
                            <input type="text" class="form-control @error('course_name') is-invalid @enderror"
                                   id="course_name" name="course_name" value="{{ old('course_name', $course->course_name) }}" required>
                            @error('course_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="teacher_id" class="form-label">المدرس المسؤول</label>
                            <select class="form-select @error('teacher_id') is-invalid @enderror"
                                    id="teacher_id" name="teacher_id" required>
                                <option value="">اختر المدرس</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                            {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="schedule_date" class="form-label">موعد المحاضرة</label>
                            <input type="datetime-local" class="form-control @error('schedule_date') is-invalid @enderror"
                                   id="schedule_date" name="schedule_date"
                                   value="{{ old('schedule_date', $course->schedule_date->format('Y-m-d\TH:i')) }}" required>
                            @error('schedule_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="room_number" class="form-label">رقم الغرفة</label>
                            <input type="text" class="form-control @error('room_number') is-invalid @enderror"
                                   id="room_number" name="room_number"
                                   value="{{ old('room_number', $course->room_number) }}"
                                   placeholder="مثال: A101" required>
                            @error('room_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.courses') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
