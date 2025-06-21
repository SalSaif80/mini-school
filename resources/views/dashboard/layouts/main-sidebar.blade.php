<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="{{ route('dashboard') }}">
            <img src="{{ asset('dashboard/assets/img/brand/logo.png') }}" class="main-logo" alt="logo">
        </a>
        <a class="desktop-logo logo-dark active" href="{{ route('dashboard') }}">
            <img src="{{ asset('dashboard/assets/img/brand/logo-white.png') }}" class="main-logo dark-theme" alt="logo">
        </a>
        <a class="logo-icon mobile-logo icon-light active" href="{{ route('dashboard') }}">
            <img src="{{ asset('dashboard/assets/img/brand/favicon.png') }}" class="logo-icon" alt="logo">
        </a>
        <a class="logo-icon mobile-logo icon-dark active" href="{{ route('dashboard') }}">
            <img src="{{ asset('dashboard/assets/img/brand/favicon-white.png') }}" class="logo-icon dark-theme" alt="logo">
        </a>
    </div>

    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="">
                    <img alt="user-img" class="avatar avatar-xl brround" src="{{ asset('dashboard/assets/img/faces/6.jpg') }}">
                </div>
                <div class="user-info">
                    <h4 class="font-weight-semibold mt-3 mb-0">{{ Auth::user()->name }}</h4>
                    <span class="mb-0 text-muted">
                        @if(Auth::user()->role == \App\Models\User::ADMIN_ROLE)
                            مدير النظام
                        @elseif(Auth::user()->role == \App\Models\User::TEACHER_ROLE)
                            معلم
                        @else
                            طالب
                        @endif

                    </span>
                </div>
            </div>
        </div>

        <ul class="side-menu">
            {{-- Admin Menu --}}
            @if(Auth::user()->role == \App\Models\User::ADMIN_ROLE)
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('admin.dashboard') }}">
                        <i class="side-menu__icon fe fe-home"></i>
                        <span class="side-menu__label">الرئيسية</span>
                    </a>
                </li>

                <li class="side-item side-item-category">إدارة النظام</li>
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#">
                        <i class="side-menu__icon fe fe-users"></i>
                        <span class="side-menu__label">إدارة المستخدمين</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a class="slide-item" href="{{ route('admin.users.index') }}">جميع المستخدمين</a></li>
                        <li><a class="slide-item" href="{{ route('admin.users.create') }}">إضافة مستخدم</a></li>
                        <li><a class="slide-item" href="{{ route('admin.roles.index') }}">إدارة الأدوار</a></li>
                    </ul>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#">
                        <i class="side-menu__icon fe fe-user-check"></i>
                        <span class="side-menu__label">إدارة المعلمين</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a class="slide-item" href="{{ route('admin.teachers.index') }}">جميع المعلمين</a></li>
                        <li><a class="slide-item" href="{{ route('admin.teachers.create') }}">إضافة معلم</a></li>
                    </ul>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#">
                        <i class="side-menu__icon fe fe-users"></i>
                        <span class="side-menu__label">إدارة الطلاب</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a class="slide-item" href="{{ route('admin.students.index') }}">جميع الطلاب</a></li>
                        <li><a class="slide-item" href="{{ route('admin.students.create') }}">إضافة طالب</a></li>
                    </ul>
                </li>

                <li class="side-item side-item-category">الإدارة الأكاديمية</li>
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#">
                        <i class="side-menu__icon fe fe-book"></i>
                        <span class="side-menu__label">إدارة المواد</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a class="slide-item" href="{{ route('admin.courses.index') }}">جميع المواد</a></li>
                        <li><a class="slide-item" href="{{ route('admin.courses.create') }}">إضافة مادة</a></li>
                    </ul>
                </li>

                <li class="slide">
                    <a class="side-menu__item" href="{{ route('admin.enrollments.index') }}">
                        <i class="side-menu__icon fe fe-file-text"></i>
                        <span class="side-menu__label">جميع التسجيلات</span>
                    </a>
                </li>

            {{-- Teacher Menu --}}
            @elseif(Auth::user()->role == \App\Models\User::TEACHER_ROLE)
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('teacher.dashboard') }}">
                        <i class="side-menu__icon fe fe-home"></i>
                        <span class="side-menu__label">الرئيسية</span>
                    </a>
                </li>

                <li class="side-item side-item-category">عملي</li>
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('teacher.profile') }}">
                        <i class="side-menu__icon fe fe-user"></i>
                        <span class="side-menu__label">ملفي الشخصي</span>
                    </a>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#">
                        <i class="side-menu__icon fe fe-book"></i>
                        <span class="side-menu__label">موادي التدريسية</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a class="slide-item" href="{{ route('teacher.my-courses') }}">مواد أدرسها</a></li>
                        <li><a class="slide-item" href="{{ route('teacher.browse-courses') }}">تصفح الكل</a></li>
                    </ul>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#">
                        <i class="side-menu__icon fe fe-users"></i>
                        <span class="side-menu__label">طلابي</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a class="slide-item" href="{{ route('teacher.my-students') }}">المسجلين عندي</a></li>
                        <li><a class="slide-item" href="{{ route('teacher.my-students') }}?status=active">النشطين</a></li>
                        <li><a class="slide-item" href="{{ route('teacher.my-students') }}?status=completed">المكتملين</a></li>
                    </ul>
                </li>

            {{-- Student Menu --}}
            @else
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('student.dashboard') }}">
                        <i class="side-menu__icon fe fe-home"></i>
                        <span class="side-menu__label">الرئيسية</span>
                    </a>
                </li>

                <li class="side-item side-item-category">دراستي</li>
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('student.profile') }}">
                        <i class="side-menu__icon fe fe-user"></i>
                        <span class="side-menu__label">ملفي الشخصي</span>
                    </a>
                </li>

                <li class="slide">
                    <a class="side-menu__item" href="{{ route('student.my-courses') }}">
                        <i class="side-menu__icon fe fe-book"></i>
                        <span class="side-menu__label">موادي المسجلة</span>
                    </a>
                </li>

                <li class="slide">
                    <a class="side-menu__item" href="{{ route('student.my-grades') }}">
                        <i class="side-menu__icon fe fe-bar-chart-2"></i>
                        <span class="side-menu__label">درجاتي ومعدلي</span>
                    </a>
                </li>

                <li class="slide">
                    <a class="side-menu__item" href="{{ route('student.available-courses') }}">
                        <i class="side-menu__icon fe fe-search"></i>
                        <span class="side-menu__label">تصفح المواد المتاحة</span>
                    </a>
                </li>

                <li class="slide">
                    <a class="side-menu__item" href="{{ route('student.available-courses') }}">
                        <i class="side-menu__icon fe fe-plus"></i>
                        <span class="side-menu__label">تسجيل مادة جديدة</span>
                    </a>
                </li>
            @endif

            {{-- Shared Menu Items --}}
            <li class="side-item side-item-category">الحساب</li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('profile.edit') }}">
                    <i class="side-menu__icon fe fe-settings"></i>
                    <span class="side-menu__label">إعدادات الحساب</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="side-menu__icon fe fe-log-out"></i>
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
