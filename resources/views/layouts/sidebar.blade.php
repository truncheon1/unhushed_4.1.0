<div class="d-flex" id="wrapper">
<!-- SIDEBAR -->
<div class="border-right border-color-primary" id="sidebar-wrapper" >
    <div class="list-group list-group-flush pt-3">
        @if(auth()->user() && (auth()->user()->can('access-master') || auth()->user()->can('access-backend')))
        <a href="{{ url($path.'/backend') }}" class="text-dark list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'backend') focused @endif">
            <i class="fas fa-tools fa-lg fa-fw"></i> {{ __('BACKEND') }}
        </a>
        <a href="{{ url($path.'/backend/master-orgs') }}" class="text-dark list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'master') focused @endif">
            <i class="fas fa-users-cog fa-lg fa-fw"></i> master
        </a>
            @if(!empty($section) && $section == 'master')
                <a href="{{ url($path.'/backend/master-orgs') }}" class="text-dark list-group-item list-group-item-action bg-transparent ps-5 @if(!empty($sect2) && $sect2 == 'master-orgs') focused @endif" style="font-size: 0.9rem;">
                    <i class="fa-solid fa-sitemap fa-lg fa-fw"></i> Master Orgs
                </a>
                <a href="{{ url($path.'/backend/master-users') }}" class="text-dark list-group-item list-group-item-action bg-transparent ps-5 @if(!empty($sect2) && $sect2 == 'master-users') focused @endif" style="font-size: 0.9rem;">
                    <i class="fa-solid fa-users fa-lg fa-fw"></i> Master Users
                </a>
            @endif
        <a href="{{ url($path.'/backend/fulfillment') }}" class="text-dark list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'fulfillment') focused @endif">
            <i class="fas fa-truck fa-lg fa-fw"></i> fulfillment
        </a>
            @if(!empty($section) && $section == 'fulfillment')
            <a href="{{ url($path.'/backend/donations-fulfillment') }}" class="list-group-item list-group-item-action bg-transparent ps-5 @if(!empty($sect2) && $sect2 == 'donations-fulfillment') focused @endif" style="font-size: 0.9rem;">
                <i class="fas fa-hand-holding-heart fa-fw"></i> donations
            </a>
            <a href="{{ url($path.'/backend/tax-exemptions') }}" class="list-group-item list-group-item-action bg-transparent ps-5 @if(!empty($sect2) && $sect2 == 'tax-exemptions') focused @endif" style="font-size: 0.9rem;">
                <i class="fa-solid fa-calculator"></i> Tax-exemptions
            </a>
            <a href="#" class="list-group-item list-group-item-action bg-transparent ps-5 @if(!empty($sect2) && $sect2 == 'renewals') focused @endif" style="font-size: 0.9rem;">
                <i class="fas fa-sync-alt fa-fw"></i> subscription renewals
            </a>
            @endif
        @endif
        <a href="{{ url($path.'/dashboard') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'dashboard') focused @endif">
            <i class="fas fa-tachometer-alt fa-lg fa-fw"></i> {{ __('DASHBOARD') }}
        </a>
            @if(!empty($section) && $section == 'dashboard')
                <a href="{{ url($path.'/dashboard/address-book') }}" class="list-group-item list-group-item-action bg-transparent ps-5 @if(!empty($sect2) && $sect2 == 'address-book') focused @endif" style="font-size: 0.9rem;">
                    <i class="fa-solid fa-address-book fa-lg fa-fw"></i> Address Book
                </a>
            @endif
        <a href="{{ url($path.'/dashboard/curricula') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'curricula') focused @endif">
            <i class="fas fa-file fa-lg fa-fw"></i> curricula
        </a>
        <a href="{{ url($path.'/dashboard/books') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'books') focused @endif">
        <i class="fa-solid fa-book fa-lg fa-fw"></i> handbooks
        </a>
        <a href="{{ url($path.'/dashboard/trainings') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'training') focused @endif">
            <i class="fas fa-headset fa-lg fa-fw"></i> trainings
        </a>
        @if(auth()->user() && (auth()->user()->can('access-master') || auth()->user()->can('modify-users')))
        <a href="{{ url($path.'/dashboard/users') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'users') focused @endif">
            <i class="fas fa-user-friends fa-lg fa-fw"></i> users
        </a>
            @if(!empty($section) && $section == 'users')
            <a href="{{ url($path.'/dashboard/assign-access') }}" class="list-group-item list-group-item-action bg-transparent ps-5 @if(!empty($sect2) && $sect2 == 'assign-access') focused @endif" style="font-size: 0.9rem;">
                <i class="fa-solid fa-list fa-fw"></i> Assign Access
            </a>
            @endif
        @endif
        <a href="{{ url($path.'/dashboard/billing') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'billing') focused @endif">
            <i class="fas fa-receipt fa-lg fa-fw"></i> billing
        </a>
            @if(!empty($section) && $section == 'billing')
            <a href="{{ url($path.'/dashboard/my-subscriptions') }}" class="list-group-item list-group-item-action bg-transparent ps-5 @if(!empty($sect2) && $sect2 == 'my-subscriptions') focused @endif" style="font-size: 0.9rem;">
                <i class="fas fa-sync-alt fa-fw"></i> subscriptions
            </a>
            <a href="{{ url($path.'/dashboard/my-donations') }}" class="list-group-item list-group-item-action bg-transparent ps-5 @if(!empty($sect2) && $sect2 == 'my-donations') focused @endif" style="font-size: 0.9rem;">
                <i class="fas fa-hand-holding-heart fa-fw"></i> donations
            </a>
            @endif
        <a href="{{ url($path.'/logout') }}" class="list-group-item list-group-item-action bg-transparent" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
            <i class="fas fa-sign-out-alt fa-lg fa-fw"></i> LOGOUT
        </a>
            <form id="frm-logout" action="{{ url($path.'/logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
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
        /*position*/
        position:fixed;
        top: 0;
        bottom: 0;
        width: 240px;
        min-height: 100vh;
        margin-left: -15rem;
        padding-top: 110px;
        overflow-y: scroll;
        /*design*/
        background: rgb(76,113,157);
        /*text*/
        color: #fff;
        /*animation*/
        -webkit-transition: margin .25s ease-out;
        -moz-transition: margin .25s ease-out;
        -o-transition: margin .25s ease-out;
        transition: margin .25s ease-out;
    }
    #sidebar-wrapper .sidebar-heading {
        padding: 0.875rem 1.25rem;
        text-align: center;
    }
    #sidebar-wrapper .list-group {
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
