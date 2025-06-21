@extends('dashboard.layouts.master')
@section('title', 'المستخدمين')
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">ادارة المستخدمين</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ المستخدمين</span>
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
                            <h4 class="card-title mg-b-0">إدارة المستخدمين</h4>
                            <div class="d-flex">
                                <a class="btn btn-primary" href="{{ route('users.create') }}">
                                    <i class="fa fa-plus me-1"></i>
                                    إنشاء مستخدم جديد
                                </a>
                            </div>
                        </div>
                        <p class="tx-12 tx-gray-500 mb-2">عرض وإدارة جميع المستخدمين في النظام</p>
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
                                        <th>الاسم</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الأدوار</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $user)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    
                                                    <span class="fw-semibold">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if (!empty($user->getRoleNames()))
                                                    @foreach ($user->getRoleNames() as $v)
                                                        <span class="badge bg-success">{{ $v }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-sm btn-info" href="{{ route('users.show', $user->id) }}"
                                                       title="عرض">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-primary" href="{{ route('users.edit', $user->id) }}"
                                                       title="تعديل">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                                                          style="display:inline"
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
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

                        @if($data->hasPages())
                            {{-- <div class="d-flex justify-content-center mt-4"> --}}
                                {{ $data->links('pagination::bootstrap-5') }}
                            {{-- </div> --}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
