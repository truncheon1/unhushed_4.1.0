<!-- row 3 organizations-->
<ul class="navbar-nav ml-auto mb-2 mb-md-0 text-center">
    <!-- SERVICES -->
    <li class="nav-item dropdown px-2">
        <a id="navbarService" href="{{ url($path.'/team') }}" aria-haspopup="true" aria-expanded="false">{{ __('AUTHORS') }}</a>
    </li>
    <!-- ABOUT -->
    <li class="nav-item dropdown px-2">
        <a id="navbarAbout" href="{{ url($path.'/about') }}" aria-haspopup="true" aria-expanded="false">{{ __('ABOUT US') }}</a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarAbout">
                <a class="dropdown-item" href="{{ url($path.'/values') }}">MISSION & VALUES</a>
                <a class="dropdown-item" href="{{ url($path.'/about#standards') }}">STANDARDS</a>
                <a class="dropdown-item" href="{{ url($path.'/apply') }}">APPLY</a>
                <a class="dropdown-item" href="{{ url($path.'/projects') }}">PROJECTS</a>
                <a class="dropdown-item" href="{{ url($path.'/social') }}">SOCIAL MEDIA</a>
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
    <!-- STORE -->
    <li class="dropdown px-2">
        <a id="navbarStore" href="{{ url($path.'/store') }}" aria-haspopup="true" aria-expanded="false">{{ __('STORE') }}</a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarStore">
                <a class="dropdown-item" href="{{ url($path.'/store/curricula') }}">CURRICULA</a>
                <a class="dropdown-item" href="{{ url($path.'/store/books') }}">BOOKS</a>
                <a class="dropdown-item" href="{{ url($path.'/store/games') }}">GAMES</a>
                <a class="dropdown-item" href="{{ url($path.'/store/swag') }}">SWAG & MORE</a>
                <a class="dropdown-item" href="{{ url($path.'/store/tools') }}">TEACHING TOOLS</a>
                <a class="dropdown-item" href="{{ url($path.'/store/trainings') }}">TRAINING</a>
            </div>
    </li>
</ul>
