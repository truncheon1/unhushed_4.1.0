<div class="d-flex" id="wrapper">
<!-- SIDEBAR -->
<div class="border-right" id="sidebar-wrapper">
    <div class="list-group list-group-flush pt-3">
        <a href="{{ url($path.'/sex-ed-dictionary') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'dictionary') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fa-solid fa-book fa-lg fa-fw "></i></div>
                <div class="col-10">Dictionary</div>
            </div>
        </a>
        <div class="list-group-item list-group-item-action bg-transparent">
            <a href="{{ url($path.'/sex-ed-dictionary?word=gender') }}" class="@if(!empty($section) && $section == 'gender') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-solid fa-brain fa-lg fa-fw "></i></div>
                    <div class="col-10">Gender</div>
                </div>
            </a>
        </div>
        <div class="list-group-item list-group-item-action bg-transparent">
            <a href="{{ url($path.'/sex-ed-dictionary?word=orientation') }}" class="@if(!empty($section) && $section == 'orientation') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fa-solid fa-heart fa-lg fa-fw "></i></div>
                    <div class="col-10">Orientation</div>
                </div>
            </a>
        </div>
        <div class="list-group-item list-group-item-action bg-transparent">
            <a href="{{ url($path.'/sex-ed-dictionary?word=medical') }}" class="@if(!empty($section) && $section == 'stis') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-solid fa-viruses fa-lg fa-fw "></i></div>
                    <div class="col-10">Medical</div>
                </div>
            </a>
        </div>
        <div class="list-group-item list-group-item-action bg-transparent">
            <a href="{{ url($path.'/sex-ed-dictionary?word=facilitation') }}" class="@if(!empty($section) && $section == 'online') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-headset fa-lg fa-fw "></i></div>
                    <div class="col-10">Facilitation</div>
                </div>
            </a>
        </div>
        <div class="list-group-item list-group-item-action bg-transparent">
            <a href="{{ url($path.'/sex-ed-dictionary?word=slang') }}" class="@if(!empty($section) && $section == 'slang') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fa-solid fa-mobile-screen-button fa-lg fa-fw"></i></div>
                    <div class="col-10">Slang words</div>
                </div>
            </a>
        </div>
        <div class="list-group-item list-group-item-action bg-transparent">
            <a href="{{ url($path.'/biological-sex') }}" class="@if(!empty($section) && $section == 'periods') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-solid fa-venus-mars fa-lg fa-fw "></i></div>
                    <div class="col-10">Biological sex</div>
                </div>
            </a>
            @if($section == 'biological')
            @endif
        </div>
    </div>
</div>

<style type="text/css">
    a.focused{
       color: #9acd57 !important;
    }
    #wrapper {
        overflow-x: hidden;
    }
    /*phone*/
    #sidebar-wrapper {
        width: 140px;
        min-height: 100vh;
        margin-left: -15rem;
        -webkit-transition: margin .25s ease-out;
        -moz-transition: margin .25s ease-out;
        -o-transition: margin .25s ease-out;
        transition: margin .25s ease-out;
        position:fixed;
        z-index: 1;
        margin-right: 20px;
        top: 0;
        bottom: 0;
        padding: 80px 0;
        font-size: 12px;
    }
    /*desktop*/
    @media (min-width: 768px) {
        #sidebar-wrapper {
        width: 240px;
        padding-top: 110px;
        font-size: 14px;
        overflow-y: scroll;
        }
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
    #sidebar-wrapper a:hover {
        color: #9acd57;
    }
    .options {
        margin: 10px 0 0 30px;
        font-size: 10px;
    }
    @media (max-width: 768px) {
        .options {
            visibility: hidden;
            clear: both;
            display: none;
        }
    }

</style>


<script type='text/javascript'>

$(document).ready(function(){
    $('.filter-selection').on('change', function(){
       let items = [];
       $('.filter-selection').each(function(){
           if($(this).prop('checked')){
               items.push($(this).val());
           }
       })
       query = '';
       if(items.length){
           query += '?s[]=';
           query += items.join("&s[]=");
       }
       let _loc = new URL(document.location);
       url = _loc.origin+_loc.pathname + query;
       document.location = url;

    });
})
</script>
