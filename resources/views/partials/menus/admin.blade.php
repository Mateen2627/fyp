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
                <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}"><i class="icon-home4"></i><span>Dashboard</span></a></li>
                <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['users.index','users.create','users.edit','users.show']) ? 'active' : '' }}"><i class="icon-users4"></i><span>Users</span></a></li>
                <li class="nav-item"><a href="{{ route('students.promotion') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion','students.promotion_manage']) ? 'active' : '' }}"><i class="icon-sort"></i><span>Student Promotion</span></a></li>
                <li class="nav-item"><a href="{{ route('classes.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['classes.index','classes.edit']) ? 'active' : '' }}"><i class="icon-windows2"></i><span>Classes</span></a></li>
                <li class="nav-item"><a href="{{ route('sections.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['sections.index','sections.edit']) ? 'active' : '' }}"><i class="icon-fence"></i><span>Sections</span></a></li>
                <li class="nav-item"><a href="{{ route('subjects.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['subjects.index','subjects.edit']) ? 'active' : '' }}"><i class="icon-pin"></i><span>Subjects</span></a></li>
                <li class="nav-item"><a href="{{ route('exams.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['exams.index','exams.edit']) ? 'active' : '' }}"><i class="icon-books"></i><span>Exams</span></a></li>
                <li class="nav-item"><a href="{{ route('payments.manage') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.manage','payments.invoice','payments.receipts']) ? 'active' : '' }}"><i class="icon-cash3"></i><span>Payments</span></a></li>
                <li class="nav-item"><a href="{{ route('my_account') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}"><i class="icon-user"></i><span>My Account</span></a></li>
            </ul>
        </div>
    </div>
</div>
