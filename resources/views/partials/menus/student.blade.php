<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle"><i class="icon-arrow-left8"></i></a>
        Navigation
        <a href="#" class="sidebar-mobile-expand"><i class="icon-screen-full"></i><i class="icon-screen-normal"></i></a>
    </div>
    <div class="sidebar-content">
        <div class="sidebar-user"><div class="card-body"><div class="media"><div class="mr-3"><a href="{{ route('my_account') }}"><img src="{{ Auth::user()->photo }}" width="38" height="38" class="rounded-circle" alt="photo"></a></div><div class="media-body"><div class="media-title font-weight-semibold">{{ Auth::user()->name }}</div><div class="font-size-xs opacity-50"><i class="icon-user font-size-sm"></i> &nbsp;Student</div></div></div></div></div>
        <div class="card card-sidebar-mobile"><ul class="nav nav-sidebar" data-nav-type="accordion">
            @include('pages.student.menu')
            <li class="nav-item"><a href="{{ route('my_account') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}"><i class="icon-user"></i><span>My Account</span></a></li>
        </ul></div>
    </div>
</div>
