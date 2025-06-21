<!-- main-sidebar -->
		<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
		<aside class="app-sidebar sidebar-scroll">
			<div class="main-sidebar-header active">
				<a class="desktop-logo logo-light active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/images/logo.png')}}" class="main-logo" alt="logo"></a>
				<a class="desktop-logo logo-dark active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/images/logo.png')}}" class="main-logo dark-theme" alt="logo"></a>
				<a class="logo-icon mobile-logo icon-light active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/images/logo.png')}}" class="logo-icon" alt="logo"></a>
				<a class="logo-icon mobile-logo icon-dark active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/images/logo.png')}}" class="logo-icon dark-theme" alt="logo"></a>
			</div>
			<div class="main-sidemenu">
				<div class="app-sidebar__user clearfix">
					<div class="dropdown user-pro-body">
						<div class="">
							<img alt="user-img" class="avatar avatar-xl brround" src="{{URL::asset('assets/images/logo.png')}}"><span class="avatar-status profile-status bg-green"></span>
						</div>
						<div class="user-info">
							<h4 class="font-weight-semibold mt-3 mb-0">{{ Auth::user()->name }}</h4>
							<span class="mb-0 text-muted">{{ Auth::user()->isAdmin() ? 'مدير' : (Auth::user()->isTeacher() ? 'معلم' : 'طالب') }}</span>
						</div>
					</div>
				</div>
				<ul class="side-menu">
					<li class="side-item side-item-category">الرئيسية</li>
					<li class="slide">
						<a class="side-menu__item" href="{{ route('dashboard-school') }}">
							<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0V0z" fill="none"/>
								<path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/>
								<path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/>
							</svg>
							<span class="side-menu__label">لوحة التحكم</span>
						</a>
					</li>


					<li class="side-item side-item-category">إدارة النظام</li>
					<li class="slide">
						<a class="side-menu__item" data-toggle="slide" href="#">
							<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0V0z" fill="none"/>
								<path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H5C3.89 1 3 1.89 3 3V7H21V9ZM3 19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V11H3V19Z"/>
							</svg>
							<span class="side-menu__label">إدارة المستخدمين</span>
							<i class="angle fe fe-chevron-down"></i>
						</a>
						<ul class="slide-menu">
							<li><a class="slide-item" href="{{ route('users.index') }}">جميع المستخدمين</a></li>
							<li><a class="slide-item" href="{{ route('roles.index') }}">إدارة الأدوار</a></li>

						</ul>
					</li>
				


					<li class="side-item side-item-category">الأكاديمية</li>
					<li class="slide">
						<a class="side-menu__item" data-toggle="slide" href="#">
							<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0V0z" fill="none"/>
								<path d="M12 3L1 9L5 11.18V17.18L12 21L19 17.18V11.18L21 10.09V17H23V9L12 3Z" opacity=".3"/>
								<path d="M12 3L1 9L12 15L21 10.09V17H23V9L12 3ZM18.82 9L12 12.72L5.18 9L12 5.28L18.82 9ZM17 15.99L12 18.72L7 15.99V12.27L12 15L17 12.27V15.99Z"/>
							</svg>
							<span class="side-menu__label">المواد الدراسية</span>
							<i class="angle fe fe-chevron-down"></i>
						</a>
						<ul class="slide-menu">
							<li><a class="slide-item" href="{{ route('courses.index') }}">جميع المواد</a></li>

							<li><a class="slide-item" href="{{ route('courses.create') }}">إضافة مادة جديدة</a></li>

						</ul>
					</li>

					<li class="slide">
						<a class="side-menu__item" data-toggle="slide" href="#">
							<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0V0z" fill="none"/>
								<path d="M9 11h6V9H9V11ZM24 6V18C24 19.1 23.1 20 22 20H2C0.9 20 0 19.1 0 18V6C0 4.9 0.9 4 2 4H22C23.1 4 24 4.9 24 6ZM22 6H2V18H22V6Z"/>
							</svg>
							<span class="side-menu__label">التسجيلات</span>
							<i class="angle fe fe-chevron-down"></i>
						</a>
						<ul class="slide-menu">
							<li><a class="slide-item" href="{{ route('enrollments.index') }}">جميع التسجيلات</a></li>

							<li><a class="slide-item" href="{{ route('enrollments.create') }}">تسجيل جديد</a></li>

						</ul>
					</li>



					<li class="side-item side-item-category">خدمات الطلاب</li>
					<li class="slide">
						<a class="side-menu__item" href="{{ route('enrollments.index') }}">
							<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0V0z" fill="none"/>
								<path d="M9 11h6V9H9V11ZM24 6V18C24 19.1 23.1 20 22 20H2C0.9 20 0 19.1 0 18V6C0 4.9 0.9 4 2 4H22C23.1 4 24 4.9 24 6ZM22 6H2V18H22V6Z"/>
							</svg>
							<span class="side-menu__label">موادي المسجلة</span>
						</a>
					</li>
					<li class="slide">
						<a class="side-menu__item" href="{{ route('courses.index') }}">
							<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0V0z" fill="none"/>
								<path d="M19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM19 19H5V5H19V19Z"/>
							</svg>
							<span class="side-menu__label">تصفح المواد</span>
						</a>
					</li>


					<li class="side-item side-item-category">خدمات المعلمين</li>
					<li class="slide">
						<a class="side-menu__item" data-toggle="slide" href="#">
							<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0V0z" fill="none"/>
								<path d="M12 3L1 9L5 11.18V17.18L12 21L19 17.18V11.18L21 10.09V17H23V9L12 3Z"/>
							</svg>
							<span class="side-menu__label">موادي التدريسية</span>
							<i class="angle fe fe-chevron-down"></i>
						</a>
						<ul class="slide-menu">
							<li><a class="slide-item" href="{{ route('courses.index') }}">جميع موادي</a></li>
						</ul>
					</li>
					<li class="slide">
						<a class="side-menu__item" data-toggle="slide" href="#">
							<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0V0z" fill="none"/>
								<path d="M16 4C16.55 4 17 4.45 17 5S16.55 6 16 6 15 5.55 15 5 15.45 4 16 4M13 1.07C13.15 1.07 13.3 1.09 13.44 1.12L17.24 2.1C18.33 2.33 19.05 3.39 18.82 4.47L17.17 11.93C16.94 13.01 15.88 13.73 14.8 13.5L13 13.08V19C13 20.1 12.1 21 11 21H4C2.9 21 2 20.1 2 19V9C2 7.9 2.9 7 4 7H6C6.55 7 7 7.45 7 8S6.55 9 6 9H4V19H11V7.96L8.15 7.35C7.07 7.12 6.35 6.06 6.58 4.98L7.08 3.21C7.31 2.13 8.37 1.41 9.45 1.64L13 2.5C13 1.78 13.22 1.12 13.63 0.59C13.83 0.36 14.16 0.15 14.5 0.05C14.66 0.02 14.83 0 15 0C15.69 0 16.39 0.28 16.89 0.78S18 2.31 18 3C18 3.69 17.72 4.39 17.22 4.89L13 1.07Z"/>
							</svg>
							<span class="side-menu__label">طلابي</span>
							<i class="angle fe fe-chevron-down"></i>
						</a>
						<ul class="slide-menu">
							<li><a class="slide-item" href="{{ route('enrollments.index') }}">جميع طلابي</a></li>
						</ul>
					</li>

					<li class="side-item side-item-category">الحساب الشخصي</li>
					<li class="slide">
						<a class="side-menu__item" href="{{ route('profile.edit') }}">
							<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0V0z" fill="none"/>
								<path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H5C3.89 1 3 1.89 3 3V7H21V9ZM3 19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V11H3V19Z"/>
							</svg>
							<span class="side-menu__label">الملف الشخصي</span>
						</a>
					</li>

					<li class="slide">
						{{-- <a class="side-menu__item" href="{{ route('students.show', Auth::user()->student) }}">
							<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0V0z" fill="none"/>
								<path d="M19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM19 19H5V5H19V19Z"/>
							</svg>
							<span class="side-menu__label">ملفي الأكاديمي</span>
						</a> --}}
					</li>

					<li class="slide">
						{{-- <a class="side-menu__item" href="{{ route('teachers.show', Auth::user()->teacher) }}">
							<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0V0z" fill="none"/>
								<path d="M19 3H18V1H16V3H8V1H6V3H5C3.89 3 3.01 3.9 3.01 5L3 19C3 20.1 3.89 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM19 19H5V8H19V19Z"/>
							</svg>
							<span class="side-menu__label">ملفي التدريسي</span>
						</a> --}}
					</li>

					<li class="slide">
						<a class="side-menu__item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
							<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0V0z" fill="none"/>
								<path d="M17 7L15.59 8.41 18.17 11H8V13H18.17L15.59 15.59 17 17L22 12 17 7ZM4 5H12V3H4C2.9 3 2 3.9 2 5V19C2 20.1 2.9 21 4 21H12V19H4V5Z"/>
							</svg>
							<span class="side-menu__label">تسجيل الخروج</span>
						</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>
					</li>
				</ul>
			</div>
		</aside>
<!-- main-sidebar -->
