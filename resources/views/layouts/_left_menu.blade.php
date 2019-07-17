<ul id="menu" class="page-sidebar-menu">
    <li {!! (Request::is('dashboard') ? 'class="active"' : '') !!}>
        <a href="{{ route('dashboard') }}">
            <i class="livicon" data-name="home" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Dashboard</span>
        </a>
    </li>

    <li {!! (Request::is('groups') ? 'class="active" id="active"' : '') !!}>
        <a href="{{ URL::to('groups') }}">
            <i class="livicon" data-name="users" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Group List</span>
        </a>
    </li>
    <li {!! (Request::is('contact') ? 'class="active"' : '') !!}>
        <a href="{{ URL::to('contact') }}">
            <i class="livicon" data-name="users-ban" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Contact Us</span>
        </a>
    </li>
    
    <li {!! (Request::is('schools') || Request::is('schools/create') || Request::is('school_profile') || Request::is('schools/*') || Request::is('deleted_schools') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="list" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">School</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('shools') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('schools') }}">
                    <i class="fa fa-angle-double-right"></i>
                    School Lists
                </a>
            </li>
            <li {!! (Request::is('schools/create') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('schools/create') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Add New school
                </a>
            </li>
           
            <!-- <li {!! ((Request::is('clubs/*')) && !(Request::is('clubs/create')) ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::route('schools.show',Sentinel::getUser()->id) }}">
                    <i class="fa fa-angle-double-right"></i>
                    View Profile
                </a>
            </li> -->
            <!-- <li {!! (Request::is('deleted_schools') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('deleted_schools') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Deleted School
                </a>
            </li> -->
        </ul>
    </li>
   
    <li {!! (Request::is('trainer') || Request::is('trainer/create') || Request::is('trainer_profile') || Request::is('trainer/*') || Request::is('deleted_trainer') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="notebook" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Trainers</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('trainer') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('trainer') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Trainers
                </a>
            </li>
            <li {!! (Request::is('trainer/create') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('trainer/create') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Add New Trainer
                </a>
            </li>
            <!-- <li {!! ((Request::is('trainer/*')) && !(Request::is('trainer/create')) ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::route('users.show',Sentinel::getUser()->id) }}">
                    <i class="fa fa-angle-double-right"></i>
                    View Profile
                </a>
            </li> -->
            <!-- <li {!! (Request::is('deleted_trainer') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('deleted_trainer') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Deleted Trainer
                </a>
            </li> -->
        </ul>
    </li>


    <li {!! (Request::is('users') || Request::is('users/create') || Request::is('user_profile') || Request::is('users/*') || Request::is('deleted_users') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="user" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Athletes</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('users') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('users') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Athletes
                </a>
            </li>
            <li {!! (Request::is('users/create') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('users/create') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Add New Athlete
                </a>
            </li>
            <li {!! ((Request::is('users/*')) && !(Request::is('users/create')) ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::route('users.show',Sentinel::getUser()->id) }}">
                    <i class="fa fa-angle-double-right"></i>
                    View Profile
                </a>
            </li>
            <li {!! (Request::is('deleted_users') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('deleted_users') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Deleted Athlete
                </a>
            </li>
        </ul>
    </li>


    <li {!! (Request::is('courses') || Request::is('courses/create') || Request::is('course_profile') || Request::is('courses/*') || Request::is('deleted_courses') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="gear" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Course</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('courses') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('users') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Courses
                </a>
            </li>
            <li {!! (Request::is('courses/create') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('courses/create') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Add New course
                </a>
            </li>
            <li {!! ((Request::is('courses/*')) && !(Request::is('courses/create')) ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::route('users.show',Sentinel::getUser()->id) }}">
                    <i class="fa fa-angle-double-right"></i>
                    View Course
                </a>
            </li>
            <li {!! (Request::is('deleted_courses') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('deleted_courses') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Deleted Course
                </a>
            </li>
        </ul>
    </li>

    <li {!! (Request::is('booking') || Request::is('booking/create') || Request::is('booking_profile') || Request::is('booking/*') || Request::is('deleted_booking') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="address-book" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Booking</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('booking') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('booking') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Booking List
                </a>
            </li>
            <li {!! (Request::is('booking/create') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('users/create') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Add New Booking
                </a>
            </li>
            <li {!! ((Request::is('booking/*')) && !(Request::is('booking/create')) ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::route('users.show',Sentinel::getUser()->id) }}">
                    <i class="fa fa-angle-double-right"></i>
                    View Booking
                </a>
            </li>
            <li {!! (Request::is('deleted_booking') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('deleted_users') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Deleted Booking
                </a>
            </li>
        </ul>
    </li>
   
    <li {!! (Request::is('package') ? 'class="active"' : '') !!}>
        <a href="{{ URL::to('blank') }}">
            <i class="livicon" data-name=" fa-cart-plus" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Package</span>
        </a>
    </li>
    
    <li {!! (Request::is('mailtemplate') ? 'class="active"' : '') !!}>
        <a href="{{ URL::to('/') }}/mailtemplate">
            <i class="livicon" data-name="inbox" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Mail Templete</span>
        </a>
    </li>

    <li {!! (Request::is('report') ? 'class="active"' : '') !!}>
        <a href="{{ URL::to('blank') }}">
            <i class="livicon" data-name="report" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Report</span>
        </a>
    </li>

    <li {!! (Request::is('setting') ? 'class="active"' : '') !!}>
        <a href="{{ URL::to('blank') }}">
            <i class="livicon" data-name="setting" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">General Setting</span>
        </a>
    </li>

    <!-- Menus generated by CRUD generator -->
    @include('layouts/menu')
</ul>
