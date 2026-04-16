<li class="nav-item">
    <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}"><i class="icon-home4"></i> Student Dashboard</a>
</li>
<li class="nav-item">
    <a href="{{ route('tt.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['tt.index', 'ttr.show']) ? 'active' : '' }}"><i class="icon-calendar3"></i> My Timetable</a>
</li>
<li class="nav-item">
    <a href="{{ route('marks.year_select', Qs::hash(Auth::user()->id)) }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.show', 'marks.year_selector', 'pins.enter']) ? 'active' : '' }}"><i class="icon-book"></i> My Marksheet</a>
</li>