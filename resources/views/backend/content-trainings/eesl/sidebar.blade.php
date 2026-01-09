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
            @if(auth()->user()->is_assigned_training(3))
            <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'eesl') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-chalkboard-teacher fa-lg fa-fw align-bottom"></i></div>
                    <div class="col-10">Enseñando Educación Sexual en Línea</div>
                </div>
            </a>
            <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/1') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'una') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="far fa-lightbulb fa-lg fa-fw align-bottom"></i></div>
                    <div class="col-10">Sesión 1 <br>Esto es difícil</div>
                </div>
            </a>
            <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/2') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'dos') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-search fa-lg fa-fw align-bottom"></i></div>
                    <div class="col-10">Sesión 2 <br>¿Como mantener un enfoque informado respecto al trauma?</div>
                </div>
            </a>
            <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/3') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'tres') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-exclamation-triangle fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                    <div class="col-10">Sesión 3 <br>Participantes que reportan abuso</div>
                </div>
            </a>
            <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/4') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'cuatro') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fa-solid fa-scissors fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                    <div class="col-10">Sesión 4 <br>Cortas y adecuadas</div>
                </div>
            </a>
            <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/5') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'cinco') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fal fa-desktop fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                    <div class="col-10">Sesión 5 <br>Sincrónico y asincrónico, es la pregunta</div>
                </div>
            </a>
            <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/6') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'seis') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fal fa-desktop fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                    <div class="col-10">Sesión 6 <br>Tratar de mantener nuestras lecciones tal cual no nos llevará lejos</div>
                </div>
            </a>
            <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/7') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'siete') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-child fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                    <div class="col-10">Sesión 7 <br>Hable con los padres, cuidadores y/o tutores cuando estén disponibles</div>
                </div>
            </a>
            <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/8') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'ocho') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-phone-rotary fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                    <div class="col-10">Sesión 8 <br>Programa de acuerdo al nivel de desarrollo</div>
                </div>
            </a>
            <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/9') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'nueve') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-alien-monster fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                    <div class="col-10">Sesión 9 <br>Practica autocompasión</div>
                </div>
            </a>
            <a href="{{ url($path.'/dashboard/trainings/ensenando-educacion-sexual-en-linea/10') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'diez') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-heart fa-lg fa-fw fa-lg fa-fw align-bottom"></i></div>
                    <div class="col-10">Sesión 10 <br>Hay que recordar que este cambio es incómodo para todos</div>
                </div>
            </a>
            @else
            <a href="{{ ('/store/dashboard/trainings/ensenando-educacion-sexual-en-linea') }}" class="list-group-item list-group-item-action bg-transparent">
                <div class="row">
                    <div class="col-2"><i class="fal fa-shopping-cart"></i></div>
                    <div class="col-10">Compra la formación completa</div>
                </div>
            </a>
            @endif
            <a href="{{ url($path.'/logout') }}" class="list-group-item list-group-item-action bg-transparent" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                <div class="col"><i class="fas fa-sign-out-alt fa-lg fa-fw"></i> Cerrar sesión</div>
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