@extends('dashboard.layouts.master2')
@section('css')
<!-- Sidemenu-respoansive-tabs css -->
<link href="{{URL::asset('dashboard/assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css')}}" rel="stylesheet">
@endsection
@section('content')
		<div class="container-fluid">
			<div class="row no-gutter">
				<!-- The image half -->
				<div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent">
					<div class="row wd-100p mx-auto text-center">
						<div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
							<img src="{{URL::asset('assets/images/logo.png')}}" class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="مشروع مدرسة">
						</div>
					</div>
				</div>
				<!-- The content half -->
				<div class="col-md-6 col-lg-6 col-xl-5 bg-white">
					<div class="login d-flex align-items-center py-2">
						<!-- Demo content-->
						<div class="container p-0">
							<div class="row">
								<div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
									<div class="card-sigin">
										<div class="mb-5 d-flex justify-content-center align-items-center">
											<a href="{{ url('/') }}">
												<img src="{{URL::asset('assets/images/logo.png')}}" class="sign-favicon ht-40" alt="شعار المدرسة">
											</a>
											&nbsp;
											<h1 class="ml-1 mr-0 my-auto tx-28" style="color: #4e73df;">مشروع مدرسة</h1>
										</div>
										<div class="main-signup-header text-center">
											<h2 class="text-primary" style="color: #28a745 !important;">إنشاء حساب جديد</h2>
											<h5 class="font-weight-normal mb-4" style="color: #7f8c8d;">انضم إلى نظام إدارة المدرسة - التسجيل مجاني ويستغرق دقيقة واحدة</h5>

											@if($errors->any())
												<div class="alert alert-danger" role="alert" style="text-align: right; direction: rtl;">
													<ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
												</div>
											@endif

											<form method="POST" action="{{ route('register') }}">
												@csrf

												<div class="form-group text-right">
													<label>{{ __('الاسم الكامل') }}</label>
													<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="أدخل اسمك الكامل" required autocomplete="name" autofocus style="text-align: right; direction: rtl;">
												</div>

												<div class="form-group text-right">
													<label>{{ __('البريد الإلكتروني') }}</label>
													<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="أدخل بريدك الإلكتروني" required autocomplete="email" style="text-align: right; direction: rtl;">
												</div>

												<div class="form-group text-right">
													<label>{{ __('كلمة المرور') }}</label>
													<input id="password" type="password" class="form-control" name="password" placeholder="أدخل كلمة المرور" required autocomplete="new-password" style="text-align: right; direction: rtl;">
												</div>

												<div class="form-group text-right">
													<label>{{ __('تأكيد كلمة المرور') }}</label>
													<input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="أعد إدخال كلمة المرور" required autocomplete="new-password" style="text-align: right; direction: rtl;">
												</div>

												<button type="submit" class="btn btn-success btn-block" style="background: linear-gradient(45deg, #28a745, #20c997); border: none; padding: 12px; font-weight: bold;">{{ __('إنشاء الحساب') }}</button>

												<div class="row row-xs mt-3">
													<div class="col-sm-6">
														<button type="button" class="btn btn-outline-primary btn-block" style="border-color: #4e73df; color: #4e73df;"><i class="fas fa-chalkboard-teacher"></i> تسجيل معلم</button>
													</div>
													<div class="col-sm-6 mg-t-10 mg-sm-t-0">
														<button type="button" class="btn btn-outline-info btn-block" style="border-color: #17a2b8; color: #17a2b8;"><i class="fas fa-user-friends"></i> تسجيل ولي أمر</button>
													</div>
												</div>
											</form>

											<div class="main-signup-footer mt-5 text-center">
												<p style="color: #6c757d;">لديك حساب بالفعل؟ <a href="{{ route('login') }}" style="color: #4e73df;">{{ __('تسجيل الدخول') }}</a></p>
												<hr>
												<p style="color: #6c757d; font-size: 12px;">
													<i class="fas fa-graduation-cap" style="color: #28a745;"></i>
													انضم إلى مجتمع مشروع مدرسة - تعليم متميز وإدارة ذكية
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div><!-- End -->
					</div>
				</div><!-- End -->
			</div>
		</div>
@endsection
@section('js')
@endsection
