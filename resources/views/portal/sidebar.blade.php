<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">            
            @if (in_array($user['group_id'], [1, 2 , 3]))
            <li>
                <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Applications<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('applications.index') }}">Browse Applications</a>
                    </li>
                    <li>
                        <a href="{{ route('applications.create') }}">New Application</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            @endif

            <li>
                <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Courses<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('portal.courses.grid') }}">Browse Courses</a>
                    </li>
                    @if (in_array($user['group_id'], [1, 2 , 3]))
                    <li>
                        <a href="{{ route('portal.courses.create') }}">New Course</a>
                    </li>
                    @endif
                </ul>
                <!-- /.nav-second-level -->
            </li>

            @if (in_array($user['group_id'], [1, 2 , 3]))
            <li>
                <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Schools & Memberships<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('portal.schools.index') }}">Browse Schools</a>
                        <a href="{{ route('portal.memberships.index') }}">Browse Memberships</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>

            <li>
                <a href="{{ route('portal.courseTitle.index') }}">Courses Titles</a>
            </li>


            <li>
                 <a href="{{ route('portal.finance.index') }}">Finance</a>
            </li>
            @endif

            @if (in_array($user['group_id'], [1]))
            <li>
                <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> User<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('portal.users.browse') }}">Browse User</a>
                    </li>
                    <li>
                        <a href="{{ route('portal.users.insert') }}">New User</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            @endif

            @if (in_array($user['group_id'], [1]))
            <li>
                <a href="#"><i class="fa fa-bus fa-fw"></i> Buses<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('portal.bus.index') }}">Browse Buses</a>
                    </li>
                    <li>
                        <a href="{{ route('portal.bus.student') }}">Bus Students</a>
                    </li>
                    <li>
                        <a href="{{ route('portal.bus.journeys') }}">Bus Journeys</a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
    </div>
</div>
