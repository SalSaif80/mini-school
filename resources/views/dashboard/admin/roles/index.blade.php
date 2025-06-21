@extends('dashboard.layouts.master')
@section('title', 'الأدوار')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">ادارة الأدوار</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الأدوار</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mg-b-0">إدارة الأدوار</h4>
                            <div class="d-flex">
                                <a class="btn btn-primary" href="{{ route('roles.create') }}">
                                    <i class="fa fa-plus me-1"></i>
                                    إنشاء دور جديد
                                </a>
                            </div>
                        </div>
                        <p class="tx-12 tx-gray-500 mb-2">عرض وإدارة جميع الأدوار في النظام</p>
                    </div>
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success border-0 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-check-circle me-2"></i>
                                    <strong>تم بنجاح!</strong> {{ $message }}
                                </div>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover mb-0 text-md-nowrap">
                                <thead>
                                    <tr>
                                        <th>الرقم</th>
                                        <th>اسم الدور</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $key => $role)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                              
                                                    <span class="fw-semibold">{{ $role->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $role->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-sm btn-info" href="{{ route('roles.show', $role->id) }}"
                                                       title="عرض">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-primary" href="{{ route('roles.edit', $role->id) }}"
                                                       title="تعديل">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('roles.destroy', $role->id) }}"
                                                          style="display:inline"
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($roles->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $roles->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
