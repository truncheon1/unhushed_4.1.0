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
        @if(auth()->user()->is_assigned_training(1))
        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'tseo') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-chalkboard-teacher fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Teaching <br>Sex Ed Online</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/1') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'one') focused @endif">
            <div class="row">
                <div class="col-2"><i class="far fa-lightbulb fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Session 1<span class="text-lowercase">v</span>2 <br>This is different</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/2') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'two') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-search fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Session 2<span class="text-lowercase">v</span>2 <br>Trauma informed lens</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/3') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'three') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-exclamation-triangle fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Session 3<span class="text-lowercase">v</span>1 <br>Participant reported abuse</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/4') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'four') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-cookie-bite fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Session 4<span class="text-lowercase">v</span>1 <br>Short and Sweet</div>
                <div class="col-12 text-lowercase">or</div>
                <div class="col-2"><i class="fas fa-virus fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Session 4<span class="text-lowercase">v</span>2 <br>COVID + Communication</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/5') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'five') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fal fa-desktop fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Session 5<span class="text-lowercase">v</span>1 <br>In-person curriculum will only get you so far.</div>
                <div class="col-12 text-lowercase">or</div>
                <div class="col-2"><i class="fas fa-pencil fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Session 5<span class="text-lowercase">v</span>2 <br>Find or make a curriculum that works online!</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/6') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'six') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fal fa-desktop fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Session 6<span class="text-lowercase">v</span>1 <br>Synchronous or asynchronous?</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/7') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'seven') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-child fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Session 7<span class="text-lowercase">v</span>1 <br>Developmental appropriateness matters</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/8') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'eight') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-phone-rotary fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Session 8<span class="text-lowercase">v</span>1 <br>Talk with parents/guardians</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/9') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'nine') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-alien-monster fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Session 9<span class="text-lowercase">v</span>1 <br>This is awkward for everyone</div>
            </div>
        </a>
        <a href="{{ url($path.'/dashboard/trainings/teaching-sex-ed-online/10') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'ten') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-heart fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                <div class="col-10">Session 10<span class="text-lowercase">v</span>1 <br>Practice self compassion.</div>
            </div>
        </a>
        @else
        <a href="{{ ('/store/teaching-sex-ed-online') }}" class="list-group-item list-group-item-action bg-transparent">
            <div class="row">
                <div class="col-2"><i class="fal fa-shopping-cart"></i></div>
                <div class="col-10">Buy the full training</div>
            </div>
        </a>
        @endif
        <a href="{{ url($path.'/dashboard/trainings/bonus-tech-session') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'bonus') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fas fa-exclamation fa-lg fa-fw"></i></div>
                <div class="col-10">Bonus Tech Session</div>
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