<li class="nav-item">
    <a href="{{ route('student.dashboard') }}" class="nav-link {{ Route::is('student.dashboard') ? 'active' : '' }}"><i class="icon-home4"></i> Dashboard</a>
</li>
<li class="nav-item"><a href="{{ route('student.courses.index') }}" class="nav-link {{ Route::is('student.courses.*') ? 'active' : '' }}"><i class="icon-books"></i> Courses</a></li>
<li class="nav-item"><a href="{{ route('student.assignments.index') }}" class="nav-link {{ Route::is('student.assignments.*') ? 'active' : '' }}"><i class="icon-task"></i> Assignments</a></li>
<li class="nav-item"><a href="{{ route('student.quizzes.index') }}" class="nav-link {{ Route::is('student.quizzes.*') ? 'active' : '' }}"><i class="icon-pencil6"></i> Quizzes</a></li>
<li class="nav-item"><a href="{{ route('student.attendance.index') }}" class="nav-link {{ Route::is('student.attendance.*') ? 'active' : '' }}"><i class="icon-calendar3"></i> Attendance</a></li>
<li class="nav-item"><a href="{{ route('student.reports.index') }}" class="nav-link {{ Route::is('student.reports.*') ? 'active' : '' }}"><i class="icon-file-text2"></i> Reports</a></li>
<li class="nav-item"><a href="{{ route('student.extra_details.index') }}" class="nav-link {{ Route::is('student.extra_details.*') ? 'active' : '' }}"><i class="icon-info22"></i> Extra Details</a></li>
<li class="nav-item"><a href="{{ route('student.help_support.index') }}" class="nav-link {{ Route::is('student.help_support.*') ? 'active' : '' }}"><i class="icon-lifebuoy"></i> Help & Support</a></li>
