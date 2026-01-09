<div class="d-flex" id="wrapper">
<!-- SIDEBAR -->
<div class="border-right bg-gradient-primary" id="sidebar-wrapper">
    <div class="list-group list-group-flush py-3 mb-5">
        <a href="{{ url($path.'/dashboard') }}" class="list-group-item list-group-item-action bg-transparent">
            <i class="fas fa-tachometer-alt fa-lg fa-fw"></i> {{ __('DASHBOARD') }}
        </a>
        <a href="{{ url($path.'/dashboard/curricula') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'curricula') focused @endif">
            <i class="fas fa-file fa-lg fa-fw"></i> curricula
        </a>
        <a href="{{ url($path.'/dashboard/trainings') }}"  class="list-group-item list-group-item-action bg-transparent">
            <i class="fas fa-headset fa-lg fa-fw"></i> Trainings
        </a>
        <a href="{{ url($path.'/dashboard/trainings/high-school-curriculum-training-2025') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'hs') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-chalkboard-teacher fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">The High School <br>Curriculum Training</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/1') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'one') focused @endif">
            <div class="row">
                <div class="col-1"></div>
                <div class="col-11">Day 1: <br>Introduction</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/2') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'two') focused @endif">
            <div class="row">
                <div class="col-1"></div>
                <div class="col-11">Day 2: <br>Facilitation</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/3') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'three') focused @endif">
            <div class="row">
                <div class="col-1"></div>
                <div class="col-11">Day 3: <br>How to design an UN|HUSHED program</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/4') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'four') focused @endif">
            <div class="row">
                <div class="col-1"></div>
                <div class="col-11">Day 4: <br>Managment and policy</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/hs-curriculum-training/5') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'five') focused @endif">
            <div class="row">
                <div class="col-1"></div>
                <div class="col-11">Day 5: <br>Practise</div>
            </div>
        </a>
        <a href="{{ url($path.'/logout') }}" class="list-group-item list-group-item-action bg-transparent" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
            <div class="col"><i class="fas fa-sign-out-alt fa-lg fa-fw"></i> LOGOUT</div>
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