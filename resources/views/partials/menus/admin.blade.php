<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle"><i class="icon-arrow-left8"></i></a>
        Navigation
        <a href="#" class="sidebar-mobile-expand"><i class="icon-screen-full"></i><i class="icon-screen-normal"></i></a>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3"><a href="{{ route('my_account') }}"><img src="{{ Auth::user()->photo }}" width="38" height="38" class="rounded-circle" alt="photo"></a></div>
                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-50"><i class="icon-user font-size-sm"></i> &nbsp;Admin</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}"><i class="icon-home4"></i><span>Dashboard</span></a></li>
                <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link {{ Route::is('admin.users.*') ? 'active' : '' }}"><i class="icon-users4"></i><span>Manage Users</span></a></li>
                <li class="nav-item"><a href="{{ route('admin.courses.index') }}" class="nav-link {{ Route::is('admin.courses.*') ? 'active' : '' }}"><i class="icon-book"></i><span>Manage Courses</span></a></li>
                <li class="nav-item"><a href="{{ route('admin.classes.index') }}" class="nav-link {{ Route::is('admin.classes.*') ? 'active' : '' }}"><i class="icon-windows2"></i><span>Manage Classes</span></a></li>
                <li class="nav-item"><a href="{{ route('admin.assign-teachers.index') }}" class="nav-link {{ Route::is('admin.assign-teachers.*') ? 'active' : '' }}"><i class="icon-user-check"></i><span>Assign Teachers</span></a></li>
                <li class="nav-item"><a href="{{ route('admin.reports.index') }}" class="nav-link {{ Route::is('admin.reports.*') ? 'active' : '' }}"><i class="icon-file-text2"></i><span>Reports</span></a></li>
                <li class="nav-item"><a href="{{ route('admin.system-settings.index') }}" class="nav-link {{ Route::is('admin.system-settings.*') ? 'active' : '' }}"><i class="icon-cog3"></i><span>System Settings</span></a></li>
            </ul>
        </div>
    </div>
</div>
