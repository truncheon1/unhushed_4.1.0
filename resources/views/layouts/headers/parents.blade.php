<!-- row 3 educators-->
<ul class="navbar-nav ml-auto mb-2 mb-md-0">
    <!-- ABOUT -->
    <li class="nav-item dropdown px-2">
        <a id="navbarAbout" href="{{ url($path.'/about') }}" aria-haspopup="true" aria-expanded="false">{{ __('ABOUT') }}</a>
            <!-- need to fade in/out this dropdown open until click off -->
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarAbout">
                <a class="dropdown-item" href="{{ url($path.'/values') }}">OUR VALUES</a>
                <a class="dropdown-item" href="{{ url($path.'/about#standards') }}">STANDARDS</a>
                <a class="dropdown-item" href="{{ url($path.'/apply') }}">APPLY</a>
                <a class="dropdown-item" href="{{ url($path.'/projects') }}">PROJECTS</a>
                <a class="dropdown-item" href="{{ url($path.'/about#social') }}">SOCIAL MEDIA</a>
            </div>
    </li>
    <!-- SERVICES -->
    <li class="nav-item dropdown px-2">
        <a id="navbarService" href="#" aria-haspopup="true" aria-expanded="false">AGES</a>
            <!-- need to fade in/out this dropdown open until click off -->
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarService">
                <a class="dropdown-item" href="{{ url($path.'/pre-k') }}">Birth to 4</a>
                <a class="dropdown-item" href="{{ url($path.'/k-thru-5') }}">5-11</a>
                <a class="dropdown-item" href="{{ url($path.'/six-thru-eight') }}">12-14</a>
                <a class="dropdown-item" href="{{ url($path.'/nine-thru-twelve') }}">15-17</a>
                <a class="dropdown-item" href="{{ url($path.'/parent-guardian') }}">PARENTS</a>
            </div>
    </li>
    <!-- FREE CONTENT -->
    <li class="nav-item dropdown px-2">
        <a id="navbarFreeContent" href="{{ url($path.'/free-content') }}" aria-haspopup="true" aria-expanded="false">{{ __('FREE CONTENT') }}</a>
            <!-- need to fade in/out this dropdown open until click off -->
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarFreeContent">
                <a class="dropdown-item" href="{{ url($path.'/free-activities') }}">ACTIVITIES</a>
                <a class="dropdown-item" href="{{ url($path.'/arcade') }}">ARCADE</a>
                <a class="dropdown-item" href="{{ url($path.'/sex-ed-dictionary') }}">DICTIONARIES</a>
                <a class="dropdown-item" href="{{ url($path.'/research') }}">RESEARCH</a>
            </div>
    </li>
    <!-- STORE -->
    <li class="dropdown px-2">
        <a id="navbarStore" href="{{ url($path.'/store') }}" aria-haspopup="true" aria-expanded="false">{{ __('STORE') }}</a>
            <!-- need to fade in/out this dropdown open until click off -->
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarStore">
                <a class="dropdown-item" href="{{ url($path.'/store/curricula') }}">CURRICULA</a>
                <a class="dropdown-item" href="{{ url($path.'/store/books') }}">BOOKS</a>
                <a class="dropdown-item" href="{{ url($path.'/store/games') }}">GAMES</a>
                <a class="dropdown-item" href="{{ url($path.'/store/swag') }}">SWAG & MORE</a>
            </div>
    </li>
    <!-- INVOLVED -->
    <li class="dropdown px-2">
        <a id="navbarInvolved" href="{{ url($path.'/give') }}" aria-haspopup="true" aria-expanded="false">{{ __('GIVE & GET INVOLVED') }}</a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarInvolved">
            <a class="dropdown-item" href="{{ url($path.'/blog') }}">BLOG</a>
            <a class="dropdown-item" href="{{ url($path.'/support') }}">CONTACT US</a>
            <a class="dropdown-item" href="https://www.paypal.com/donate/?hosted_button_id=KZL7UNCVMLY2Q">GIVE</a>
            <a class="dropdown-item" href="{{ url($path.'/news') }}">NEWS</a>
            <!-- <a class="dropdown-item" href="{{ url($path.'/other') }}">OTHER SITES</a> -->
        </div>
    </li>
</ul>
