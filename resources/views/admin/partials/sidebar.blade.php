<a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">

    <i class="fas fa-tachometer-alt me-2"></i> &nbsp; لوحة التحكم
</a>
<a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">

    <i class="fas fa-users me-2"></i> &nbsp; المستخدمون
</a>
<a class="nav-link {{ request()->routeIs('admin.courses*') ? 'active' : '' }}" href="{{ route('admin.courses') }}">

    <i class="fas fa-book me-2"></i> &nbsp; الكورسات
</a>
<a class="nav-link {{ request()->routeIs('admin.enrollments*') ? 'active' : '' }}"
    href="{{ route('admin.enrollments') }}">

    <i class="fas fa-user-graduate me-2"></i> &nbsp; التسجيلات
</a>
<a class="nav-link {{ request()->routeIs('admin.activity-log*') ? 'active' : '' }}"
    href="{{ route('admin.activity-log') }}">

    <i class="fas fa-history me-2"></i> &nbsp; سجل الأنشطة
</a>
