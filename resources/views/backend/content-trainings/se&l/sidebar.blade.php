<div class="d-flex" id="wrapper">
<!-- SIDEBAR -->
<div class="border-right bg-gradient-primary" id="sidebar-wrapper">
    <div class="list-group list-group-flush py-3 mb-5">
        <a href="{{ url($path.'/dashboard') }}" class="list-group-item list-group-item-action bg-transparent">
            <i class="fas fa-tachometer-alt fa-lg fa-fw"></i> {{ __('DASHBOARD') }}
        </a>
        <a href="{{ url($path.'/dashboard/trainings') }}"  class="list-group-item list-group-item-action bg-transparent">
            <i class="fas fa-headset fa-lg fa-fw"></i> Trainings
        </a>
        <a href="{{ url($path.'/dashboard/trainings/sex-ed-law') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'se&l') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fal fa-balance-scale fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Sex Ed & the Law</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/1') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'one') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fal fa-play-circle fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">S1: Intro</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/2') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'two') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-user-tie fa-lg fa-fw align-bottom"></i></i></div>
                <div class="col-10">S2: State Law & Policies</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/3') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'three') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-people-arrows fa-lg fa-fw fa-lg fa-fw align-bottom"></i></i></div>
                <div class="col-10">S3: Age of Consent</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/4') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'four') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-notes-medical fa-lg fa-fw fa-lg fa-fw align-bottom"></i></i></div>
                <div class="col-10">S4: Medical privacy/Medical home</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/5') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'five') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-hand-paper fa-lg fa-fw fa-lg fa-fw align-bottom"></i></i></div>
                <div class="col-10">S5: Sexual Violence I</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/6') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'six') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-hand-paper fa-lg fa-fw fa-lg fa-fw align-bottom"></i></i></div>
                <div class="col-10">S6: Sexual Violence II</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/7') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'seven') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-gavel fa-lg fa-fw fa-lg fa-fw align-bottom"></i></i></div>
                <div class="col-10">S7: Title IX</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/8') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'eight') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fad fa-mobile fa-lg fa-fw fa-lg fa-fw align-bottom"></i></i></div>
                <div class="col-10">S8: Sexting</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/9') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'nine') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fal fa-camera-movie fa-lg fa-fw fa-lg fa-fw align-bottom"></i></i></div>
                <div class="col-10">S9: Revenge porn</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/10') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'ten') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-hand-holding-usd fa-lg fa-fw fa-lg fa-fw align-bottom"></i></i></div>
                <div class="col-10">S10: <span style="text-decoration: line-through">Sex work/
                    <br>Trafficking</span>
                    <br>Sex work/
                    <br>Commercial Sexual Exploitation of Minors
                </div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/11') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'eleven') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fal fa-bullhorn fa-lg fa-fw"></i></div>
                <div class="col-10">S11: Mandated reporting</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/sex-ed-law/12') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'twelve') focused @endif">
            <div class="row">
                <div class="col-2"><i class="far fa-plus fa-lg fa-fw"></i></div>
                <div class="col-10">S12: Other Topics</div>
            </div>
        </a>
        <a href="{{ url($path.'/logout') }}" class="list-group-item list-group-item-action bg-transparent" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
            <i class="fas fa-sign-out-alt fa-lg fa-fw"></i> LOGOUT
        </a><form id="frm-logout" action="{{ url($path.'/logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
        <div class="col-12">&nbsp;</div>
        <div class="col-12">&nbsp;</div>
    </div>
</div>

<style type="text/css">
    .list-group-item, #sidebar-wrapper {
        border: 1px solid #265a8e;
    }
    a.focused{
       color: #9acd57 !important;
    }
    #wrapper {
        overflow-x: hidden;
    }
    #sidebar-wrapper {
        min-height: 100vh;
        margin-left: -15rem;
        -webkit-transition: margin .25s ease-out;
        -moz-transition: margin .25s ease-out;
        -o-transition: margin .25s ease-out;
        transition: margin .25s ease-out;
        color: #fff;
        position:fixed;
        top: 0;
        bottom: 0;
        padding-top: 110px;
        overflow-y: scroll;
        background: rgb(76,113,157);
    }
    #sidebar-wrapper .sidebar-heading {
        padding: 0.875rem 1.25rem;
        text-align: center;
    }
    #sidebar-wrapper .list-group {
        width: 240px;
        text-transform: uppercase;
    }
    #page-content-wrapper {
        min-width: 100vw;
    }
    #wrapper.toggled #sidebar-wrapper {
        margin-left: 0;
    }
    @media (min-width: 768px) {
        #sidebar-wrapper {
            margin-left: 0;
        }
        #page-content-wrapper {
            min-width: 0;
            width: 100%;
        }
        #wrapper.toggled #sidebar-wrapper {
            margin-left: -15rem;
        }
    }
    #sidebar-wrapper a:link{
        color: #fff;
    }
    #sidebar-wrapper a:visited{
        color: #fff;
    }
    #sidebar-wrapper a:active {
        color: #fff;
    }
    #sidebar-wrapper a:hover {
        color: #9acd57;
    }
</style>