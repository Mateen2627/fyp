<li class="nav-item"><a href="{{ route('teacher.dashboard') }}" class="nav-link {{ Route::is('teacher.dashboard') ? 'active' : '' }}"><i class="icon-home4"></i> Dashboard</a></li>
<li class="nav-item"><a href="{{ route('teacher.courses.index') }}" class="nav-link {{ Route::is('teacher.courses.*') ? 'active' : '' }}"><i class="icon-book"></i> My Courses</a></li>
<li class="nav-item"><a href="{{ route('teacher.classes.index') }}" class="nav-link {{ Route::is('teacher.classes.*') ? 'active' : '' }}"><i class="icon-office"></i> My Classes & Timetable</a></li>
<li class="nav-item"><a href="{{ route('teacher.assignments.index') }}" class="nav-link {{ Route::is('teacher.assignments.*') ? 'active' : '' }}"><i class="icon-clipboard3"></i> Assignments</a></li>
<li class="nav-item"><a href="{{ route('teacher.quizzes.index') }}" class="nav-link {{ Route::is('teacher.quizzes.*') ? 'active' : '' }}"><i class="icon-question4"></i> Quizzes</a></li>
<li class="nav-item"><a href="{{ route('teacher.attendance.index') }}" class="nav-link {{ Route::is('teacher.attendance.*') ? 'active' : '' }}"><i class="icon-calendar5"></i> Student Attendance</a></li>
<li class="nav-item"><a href="{{ route('teacher.subject_marks.index') }}" class="nav-link {{ Route::is('teacher.subject_marks.*') ? 'active' : '' }}"><i class="icon-stats-bars2"></i> Mid & Final Marks</a></li>
