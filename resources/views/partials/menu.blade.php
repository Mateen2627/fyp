<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="fas fa-arrow-left"></i>
        </a>
        <span class="sidebar-brand">SPAMS</span>
        <a href="#" class="sidebar-mobile-expand">
            <i class="fas fa-expand"></i>
            <i class="fas fa-compress"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="{{ route('my_account') }}">
                            <img src="{{ Qs::getUserPhoto(Auth::user()) }}" width="48" height="48" class="rounded-circle" alt="photo" style="border: 2px solid rgba(255,255,255,0.2);">
                        </a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold text-white">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-75">
                            <i class="fas fa-user-graduate font-size-sm"></i> &nbsp;{{ ucwords(str_replace('_', ' ', Auth::user()->user_type)) }}
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ route('my_account') }}" class="text-white-50 hover-text-white">
                            <i class="fas fa-cog"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ (Route::is('dashboard')) ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{--Academics--}}
                @if(Qs::userIsAcademic())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['tt.index', 'ttr.edit', 'ttr.show', 'ttr.manage']) ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-graduation-cap"></i> 
                            <span>Academics</span>
                        </a>

                        <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">
                            {{--Timetables--}}
                            <li class="nav-item">
                                <a href="{{ route('tt.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['tt.index']) ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt"></i> Timetables
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{--Administrative--}}
                @if(Qs::userIsAdministrative())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.invoice', 'payments.receipts', 'payments.edit', 'payments.manage', 'payments.show',]) ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-building"></i> 
                            <span>Administrative</span>
                        </a>

                        <ul class="nav nav-group-sub" data-submenu-title="Administrative">
                            {{--Payments--}}
                            @if(Qs::userIsTeamAccount())
                                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.edit', 'payments.manage', 'payments.show', 'payments.invoice']) ? 'nav-item-expanded' : '' }}">
                                    <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.create', 'payments.manage', 'payments.show', 'payments.invoice']) ? 'active' : '' }}">
                                        <i class="fas fa-money-bill-wave"></i> Payments
                                    </a>
                                    <ul class="nav nav-group-sub">
                                        <li class="nav-item">
                                            <a href="{{ route('payments.create') }}" class="nav-link {{ Route::is('payments.create') ? 'active' : '' }}">
                                                <i class="fas fa-plus-circle"></i> Create Payment
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('payments.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.show']) ? 'active' : '' }}">
                                                <i class="fas fa-list"></i> Manage Payments
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('payments.manage') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.manage', 'payments.invoice', 'payments.receipts']) ? 'active' : '' }}">
                                                <i class="fas fa-users"></i> Student Payments
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{--Manage Students--}}
                @if(Qs::userIsTeamSAT())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.create', 'students.list', 'students.edit', 'students.show', 'students.promotion', 'students.promotion_manage', 'students.graduated']) ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-users"></i> 
                            <span>Students</span>
                        </a>

                        <ul class="nav nav-group-sub" data-submenu-title="Manage Students">
                            {{--Admit Student--}}
                            @if(Qs::userIsTeamSA())
                                <li class="nav-item">
                                    <a href="{{ route('students.create') }}" class="nav-link {{ (Route::is('students.create')) ? 'active' : '' }}">
                                        <i class="fas fa-user-plus"></i> Admit Student
                                    </a>
                                </li>
                            @endif

                            {{--Student Information--}}
                            <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.list', 'students.edit', 'students.show']) ? 'nav-item-expanded' : '' }}">
                                <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['students.list', 'students.edit', 'students.show']) ? 'active' : '' }}">
                                    <i class="fas fa-info-circle"></i> Student Information
                                </a>
                                <ul class="nav nav-group-sub">
                                    @foreach(App\Models\MyClass::orderBy('name')->get() as $c)
                                        <li class="nav-item">
                                            <a href="{{ route('students.list', $c->id) }}" class="nav-link">
                                                <i class="fas fa-chalkboard"></i> {{ $c->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>

                            @if(Qs::userIsTeamSA())
                                {{--Student Promotion--}}
                                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.promotion', 'students.promotion_manage']) ? 'nav-item-expanded' : '' }}">
                                    <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion', 'students.promotion_manage' ]) ? 'active' : '' }}">
                                        <i class="fas fa-arrow-up"></i> Student Promotion
                                    </a>
                                    <ul class="nav nav-group-sub">
                                        <li class="nav-item">
                                            <a href="{{ route('students.promotion') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion']) ? 'active' : '' }}">
                                                <i class="fas fa-rocket"></i> Promote Students
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('students.promotion_manage') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion_manage']) ? 'active' : '' }}">
                                                <i class="fas fa-tasks"></i> Manage Promotions
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                {{--Student Graduated--}}
                                <li class="nav-item">
                                    <a href="{{ route('students.graduated') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.graduated' ]) ? 'active' : '' }}">
                                        <i class="fas fa-award"></i> Students Graduated
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(Qs::userIsTeamSA())
                    {{--Manage Users--}}
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['users.index', 'users.show', 'users.edit']) ? 'active' : '' }}">
                            <i class="fas fa-user-shield"></i> 
                            <span>Users</span>
                        </a>
                    </li>

                    {{--Manage Classes--}}
                    <li class="nav-item">
                        <a href="{{ route('classes.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['classes.index','classes.edit']) ? 'active' : '' }}">
                            <i class="fas fa-chalkboard"></i> 
                            <span>Classes</span>
                        </a>
                    </li>

                    {{--Manage Dorms--}}
                    <li class="nav-item">
                        <a href="{{ route('dorms.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['dorms.index','dorms.edit']) ? 'active' : '' }}">
                            <i class="fas fa-bed"></i> 
                            <span>Dormitories</span>
                        </a>
                    </li>

                    {{--Manage Sections--}}
                    <li class="nav-item">
                        <a href="{{ route('sections.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['sections.index','sections.edit',]) ? 'active' : '' }}">
                            <i class="fas fa-layer-group"></i> 
                            <span>Sections</span>
                        </a>
                    </li>

                    {{--Manage Subjects--}}
                    <li class="nav-item">
                        <a href="{{ route('subjects.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['subjects.index','subjects.edit',]) ? 'active' : '' }}">
                            <i class="fas fa-book"></i> 
                            <span>Subjects</span>
                        </a>
                    </li>
                @endif

                {{--Exam--}}
                @if(Qs::userIsTeamSAT())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['exams.index', 'exams.edit', 'grades.index', 'grades.edit', 'marks.index', 'marks.manage', 'marks.bulk', 'marks.tabulation', 'marks.show', 'marks.batch_fix',]) ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-file-alt"></i> 
                            <span>Exams</span>
                        </a>

                        <ul class="nav nav-group-sub" data-submenu-title="Manage Exams">
                            @if(Qs::userIsTeamSA())
                                {{--Exam list--}}
                                <li class="nav-item">
                                    <a href="{{ route('exams.index') }}" class="nav-link {{ (Route::is('exams.index')) ? 'active' : '' }}">
                                        <i class="fas fa-list"></i> Exam List
                                    </a>
                                </li>

                                {{--Grades list--}}
                                <li class="nav-item">
                                    <a href="{{ route('grades.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['grades.index', 'grades.edit']) ? 'active' : '' }}">
                                        <i class="fas fa-star"></i> Grades
                                    </a>
                                </li>

                                {{--Tabulation Sheet--}}
                                <li class="nav-item">
                                    <a href="{{ route('marks.tabulation') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.tabulation']) ? 'active' : '' }}">
                                        <i class="fas fa-table"></i> Tabulation Sheet
                                    </a>
                                </li>

                                {{--Marks Batch Fix--}}
                                <li class="nav-item">
                                    <a href="{{ route('marks.batch_fix') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.batch_fix']) ? 'active' : '' }}">
                                        <i class="fas fa-wrench"></i> Batch Fix
                                    </a>
                                </li>
                            @endif

                            @if(Qs::userIsTeamSAT())
                                {{--Marks Manage--}}
                                <li class="nav-item">
                                    <a href="{{ route('marks.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.index']) ? 'active' : '' }}">
                                        <i class="fas fa-edit"></i> Marks
                                    </a>
                                </li>

                                {{--Marksheet--}}
                                <li class="nav-item">
                                    <a href="{{ route('marks.bulk') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.bulk', 'marks.show']) ? 'active' : '' }}">
                                        <i class="fas fa-print"></i> Marksheet
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{--End Exam--}}

                @include('pages.'.Qs::getUserType().'.menu')

                {{--Manage Account--}}
                <li class="nav-item">
                    <a href="{{ route('my_account') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i> 
                        <span>My Account</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>

<style>
    /* Modern Sidebar Styling */
    .sidebar-dark {
        background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    }
    
    /* Sidebar Brand */
    .sidebar-mobile-toggler .sidebar-brand {
        font-weight: 800;
        font-size: 18px;
        background: linear-gradient(135deg, #a78bfa 0%, #f472b6 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        letter-spacing: 1px;
    }
    
    /* User Profile Section */
    .sidebar-user {
        background: rgba(99, 102, 241, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        margin-bottom: 16px;
    }
    
    .sidebar-user .card-body {
        padding: 20px;
    }
    
    /* Navigation Items */
    .nav-sidebar .nav-link {
        padding: 12px 16px;
        margin: 4px 8px;
        border-radius: 12px;
        transition: all 0.2s ease;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
    }
    
    .nav-sidebar .nav-link i {
        font-size: 1.1rem;
        width: 28px;
        text-align: center;
        transition: all 0.2s ease;
    }
    
    .nav-sidebar .nav-link:hover {
        background: rgba(99, 102, 241, 0.2);
        color: white;
        transform: translateX(4px);
    }
    
    .nav-sidebar .nav-link:hover i {
        transform: scale(1.1);
        color: #a78bfa;
    }
    
    /* Active Link */
    .nav-sidebar .nav-link.active {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    
    .nav-sidebar .nav-link.active i {
        color: white;
    }
    
    /* Submenu Styling */
    .nav-sidebar .nav-group-sub {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        margin: 4px 8px 8px 8px;
        padding: 4px 0;
    }
    
    .nav-sidebar .nav-group-sub .nav-link {
        padding: 10px 16px 10px 52px;
        margin: 2px 0;
        font-size: 0.85rem;
    }
    
    .nav-sidebar .nav-group-sub .nav-link i {
        font-size: 0.9rem;
        width: 24px;
    }
    
    /* Submenu Level 3 */
    .nav-sidebar .nav-group-sub .nav-group-sub {
        margin-left: 24px;
        background: rgba(0, 0, 0, 0.15);
    }
    
    .nav-sidebar .nav-group-sub .nav-group-sub .nav-link {
        padding-left: 68px;
    }
    
    /* Expand/Collapse Indicators */
    .nav-item-submenu > .nav-link {
        position: relative;
    }
    
    .nav-item-submenu > .nav-link:after {
        content: '\f054';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        transition: transform 0.2s ease;
        font-size: 10px;
        opacity: 0.6;
    }
    
    .nav-item-submenu.nav-item-expanded > .nav-link:after {
        transform: translateY(-50%) rotate(90deg);
    }
    
    /* Sidebar Toggler */
    .sidebar-mobile-toggler {
        background: rgba(0, 0, 0, 0.3);
        padding: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .sidebar-mobile-toggler a {
        color: rgba(255, 255, 255, 0.7);
        transition: all 0.2s;
    }
    
    .sidebar-mobile-toggler a:hover {
        color: white;
    }
    
    /* Scrollbar */
    .sidebar-content::-webkit-scrollbar {
        width: 4px;
    }
    
    .sidebar-content::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 4px;
    }
    
    .sidebar-content::-webkit-scrollbar-thumb {
        background: rgba(99, 102, 241, 0.5);
        border-radius: 4px;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .sidebar-dark {
            background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
        }
        
        .nav-sidebar .nav-link {
            margin: 2px 8px;
        }
        
        .nav-sidebar .nav-group-sub {
            margin: 2px 8px 6px 8px;
        }
    }
    
    /* Hover Effects */
    .sidebar-user .media-body .media-title {
        font-weight: 600;
        font-size: 14px;
    }
    
    .hover-text-white:hover {
        color: white !important;
    }
    
    .text-white-50 {
        color: rgba(255, 255, 255, 0.5);
    }
    
    /* Icon Animations */
    .nav-sidebar .nav-link i {
        transition: transform 0.2s ease;
    }
    
    .nav-sidebar .nav-link:hover i {
        transform: translateX(2px);
    }
</style>