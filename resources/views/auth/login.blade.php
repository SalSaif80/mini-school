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
							<img src="{{URL::asset('assets/images/logo.png')}}" class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="مدرسة صغيرة">
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
											<h1 class=" ml-1 mr-0 my-auto tx-28" style="color: #4e73df;">مشروع مدرسة</h1>
										</div>
										<div class="card-sigin">
											<div class="main-signup-header text-center">
												{{-- <h2 style="color: #2c3e50;">أهلاً وسهلاً بك!</h2> --}}
												<h5 class="font-weight-semibold mb-4" style="color: #7f8c8d;">يرجى تسجيل الدخول للوصول إلى نظام إدارة المدرسة</h5>

												@if (session('status'))
													<div class="alert alert-success" role="alert">
														{{ session('status') }}
													</div>
												@endif

												@if($errors->any())
													<div class="alert alert-danger" role="alert" style="text-align: right; direction: rtl;">
														<ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
													</div>
												@endif

												<form method="POST" action="{{ route('custom.login') }}">
													@csrf

													<div class="form-group text-right">
														<label><i class="fas fa-users" style="margin-left: 8px; color: #4e73df;"></i>{{ __('نوع المستخدم') }}</label>
														<select id="user_type" name="user_type" class="form-control" required style="text-align: right; direction: rtl;">
															<option value="">اختر نوع المستخدم</option>
															<option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>
																🏢 إدارة / موظف
															</option>
															<option value="teacher" {{ old('user_type') == 'teacher' ? 'selected' : '' }}>
																👨‍🏫 معلم
															</option>
															<option value="student" {{ old('user_type') == 'student' ? 'selected' : '' }}>
																🎓 طالب
															</option>
														</select>
													</div>

													<div class="form-group text-right">
														<label><i class="fas fa-envelope" style="margin-left: 8px; color: #4e73df;"></i>{{ __('البريد الإلكتروني') }}</label>
														<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="أدخل بريدك الإلكتروني" required autocomplete="email" autofocus style="text-align: right; direction: rtl;">
													</div>

													<div class="form-group text-right">
														<label><i class="fas fa-lock" style="margin-left: 8px; color: #4e73df;"></i>{{ __('كلمة المرور') }}</label>
														<input id="password" type="password" class="form-control" name="password" placeholder="أدخل كلمة المرور" required autocomplete="current-password" style="text-align: right; direction: rtl;">
													</div>

													<div class="form-group text-right">
														<div class="form-check">
															<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
															<label class="form-check-label" for="remember" style="margin-right: 25px;">
																{{ __('تذكرني') }}
															</label>
														</div>
													</div>

													<button type="submit" class="btn btn-primary btn-block" id="loginBtn" style="background: linear-gradient(45deg, #4e73df, #224abe); border: none; padding: 12px; font-weight: bold;">
														<i class="fas fa-sign-in-alt"></i> {{ __('دخول إلى النظام') }}
													</button>

													<div class="text-center mt-3">
														<small style="color: #6c757d;">
															<i class="fas fa-shield-alt" style="color: #28a745;"></i>
															تسجيل دخول آمن ومحمي
														</small>
													</div>
												</form>

												<div class="main-signin-footer mt-5 text-center">
													@if (Route::has('password.request'))
														<p><a href="{{ route('password.request') }}" style="color: #4e73df;">{{ __('نسيت كلمة المرور؟') }}</a></p>
													@endif
													@if (Route::has('register'))
														<p style="color: #6c757d;">ليس لديك حساب؟ <a href="{{ route('register') }}" style="color: #28a745;">{{ __('إنشاء حساب جديد') }}</a></p>
													@endif
													<hr>
													<p style="color: #6c757d; font-size: 12px;">
														<i class="fas fa-school" style="color: #4e73df;"></i>
														نظام إدارة مدرستي الصغيرة - تعليم متميز لمستقبل أفضل
													</p>
												</div>
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
<script>
$(document).ready(function() {
    // تغيير لون الزر حسب نوع المستخدم
    $('#user_type').on('change', function() {
        var userType = $(this).val();
        var loginBtn = $('#loginBtn');
        var form = $('form');

        // إزالة الكلاسات السابقة
        loginBtn.removeClass('btn-primary btn-success btn-warning');

        if (userType === 'admin') {
            loginBtn.addClass('btn-primary');
            loginBtn.html('<i class="fas fa-shield-alt"></i> دخول الإدارة');
            loginBtn.css('background', 'linear-gradient(45deg, #4e73df, #224abe)');
        } else if (userType === 'teacher') {
            loginBtn.addClass('btn-warning');
            loginBtn.html('<i class="fas fa-graduation-cap"></i> دخول المعلم');
            loginBtn.css('background', 'linear-gradient(45deg, #f6c23e, #dda20a)');
        } else if (userType === 'student') {
            loginBtn.addClass('btn-success');
            loginBtn.html('<i class="fas fa-user-graduate"></i> دخول الطالب');
            loginBtn.css('background', 'linear-gradient(45deg, #1cc88a, #13855c)');
        } else {
            loginBtn.addClass('btn-primary');
            loginBtn.html('<i class="fas fa-sign-in-alt"></i> دخول إلى النظام');
            loginBtn.css('background', 'linear-gradient(45deg, #4e73df, #224abe)');
        }
    });

    // التحقق من صحة البيانات قبل الإرسال
    $('form').on('submit', function(e) {
        var userType = $('#user_type').val();
        var email = $('#email').val();
        var password = $('#password').val();

        if (!userType) {
            alert('يرجى اختيار نوع المستخدم');
            e.preventDefault();
            return false;
        }

        if (!email || !password) {
            alert('يرجى ملء جميع الحقول المطلوبة');
            e.preventDefault();
            return false;
        }

        // إظهار loader
        $('#loginBtn').html('<i class="fas fa-spinner fa-spin"></i> جاري تسجيل الدخول...');
        $('#loginBtn').prop('disabled', true);
    });
});
</script>
@endsection
