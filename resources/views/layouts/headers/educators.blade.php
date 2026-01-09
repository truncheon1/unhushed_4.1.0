<!-- row 3 educators-->
<ul class="navbar-nav ml-auto mb-2 mb-md-0 text-center">
    <!-- ABOUT -->
    <li class="nav-item dropdown px-2">
        <a id="navbarAbout" href="{{ url($path.'/about') }}" aria-haspopup="true" aria-expanded="false">{{ __('ABOUT US') }}</a>
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
        <a id="navbarService" href="{{ url($path.'/subscription-info') }}" aria-haspopup="true" aria-expanded="false">{{ __('CURRICULA') }}</a>
    </li>
    <!-- FREE CONTENT -->
    <li class="nav-item dropdown px-2">
        <a id="navbarFreeContent" href="{{ url($path.'/free-content') }}" aria-haspopup="true" aria-expanded="false">{{ __('FREE RESOURCES') }}</a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarFreeContent">
            <a class="dropdown-item" href="{{ url($path.'/free-activities') }}">ACTIVITIES</a>
            <a class="dropdown-item" href="{{ url($path.'/arcade') }}">ARCADE</a>
            <a class="dropdown-item" href="{{ url($path.'/blog') }}">BLOG</a>
            <a class="dropdown-item" href="{{ url($path.'/sex-ed-dictionary') }}">DICTIONARIES</a>
            <a class="dropdown-item" href="{{ url($path.'/research') }}">RESEARCH</a>
        </div>
    </li>
    <!-- GET TRAINED -->
    <li class="nav-item dropdown px-2">
        <a id="navbarGetTrained" href="{{ url($path.'/store/trainings') }}" aria-haspopup="false" aria-expanded="false">{{ __('GET TRAINED') }}</a>
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
    <!-- GIVE -->
    <li class="dropdown px-2">
        <a id="navbarInvolved" href="{{ url($path.'/give') }}" aria-haspopup="true" aria-expanded="false">{{ __('GIVE') }}</a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarInvolved">
            <a class="dropdown-item" href="https://www.paypal.com/donate/?hosted_button_id=KZL7UNCVMLY2Q">DONATE</a>
        </div>
    </li>
    <!-- CONTACT US -->
    <li class="dropdown px-2">
        <a id="navbarInvolved" href="{{ url($path.'/support') }}" aria-haspopup="true" aria-expanded="false">{{ __('CONTACT') }}</a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarInvolved">
            <a class="dropdown-item" href="{{ url($path.'/support') }}">CONTACT US</a>
            <a class="dropdown-item" href="{{ url($path.'/blog') }}">BLOG</a>
            <a class="dropdown-item" href="{{ url($path.'/news') }}">NEWSLETTER</a>
        </div>
    </li>
</ul>
