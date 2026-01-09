<!-- PHONE SIDEBAR -->
<div class="phone">
    <div class="scrollmenu">
        <a href="{{ url($path.'/store') }}" class="@if(!empty($section) && $section == 'store') focused @endif"> All items </a>
        <a href="{{ url($path.'/store/curricula') }}" class="@if(!empty($section) && $section == 'curricula') focused @endif"> Activities & Curricula </a>
        <a href="{{ url($path.'/store/books') }}" class="@if(!empty($section) && $section == 'books') focused @endif"> Books </a>
        <a href="{{ url($path.'/store/games') }}" class="@if(!empty($section) && $section == 'games') focused @endif"> Games </a>
        <a href="{{ url($path.'/store/swag') }}" class="@if(!empty($section) && $section == 'swag') focused @endif"> Swag, Clothes, Dontion Gifts </a>
        <a href="{{ url($path.'/store/tools') }}" class="@if(!empty($section) && $section == 'tools') focused @endif"> Teaching Tools </a>
    </div>
</div>
<!-- DESKTOP SIDEBAR -->
<div class="d-flex" id="wrapper">
    <div class="border-right desk" id="sidebar-wrapper">
        <button class="sidebar-toggle-btn" id="sidebarToggle">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div class="list-group list-group-flush pt-3">
            <a href="{{ url($path.'/store') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'store') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-shopping-cart fa-lg fa-fw "></i></div>
                    <div class="col-10">Store</div>
                </div>
            </a>
            <div class="list-group-item list-group-item-action bg-transparent">
                <a href="{{ url($path.'/store/curricula') }}" class="@if(!empty($section) && $section == 'activities') focused @endif">
                    <div class="row">
                        <div class="col-2"><i class="fas fa-file-download fa-lg fa-fw "></i></div>
                        <div class="col-10">Activities & Curricula</div>
                    </div>
                </a>
                @if($section == 'activities' && !isset($hideFilters))
                <div class="options">
                    @foreach(\App\Models\ProductStoreTags::where('root_id', 1)->where('parent_id', 0)->get() as $root_tag)
                    <b>{{strtoupper($root_tag->name)}}</b>
                    @foreach(\App\Models\ProductStoreTags::where('parent_id', $root_tag->id)->get() as $tag)
                    <div class="form-check">
                        <input class="form-check-input filter-selection" @if(in_array($tag->id, $filter)) checked @endif type="checkbox" value="{{$tag->id}}" id="flexCheckDefault-{{$tag->id}}">
                        <label class="form-check-label pt-1" for="flexCheckDefault-{{$tag->id}}">
                            {{$tag->name}}
                        </label>
                    </div>
                    @endforeach
                    @endforeach
                </div>
                @endif
            </div>
            <div class="list-group-item list-group-item-action bg-transparent">
                <a href="{{ url($path.'/store/books') }}" class="@if(!empty($section) && $section == 'books') focused @endif">
                    <div class="row">
                        <div class="col-2"><i class="fas fa-book fa-lg fa-fw "></i></div>
                        <div class="col-10">Books, Handbooks, Manuals, Etc.</div>
                    </div>
                </a>
                @if($section == 'books' && !isset($hideFilters))
                <div class="options">
                    @foreach(\App\Models\ProductStoreTags::where('root_id', 2)->where('parent_id', 0)->get() as $root_tag)
                    <b>{{strtoupper($root_tag->name)}}</b>
                    @foreach(\App\Models\ProductStoreTags::where('parent_id', $root_tag->id)->get() as $tag)
                    <div class="form-check">
                        <input class="form-check-input filter-selection" @if(in_array($tag->id, $filter)) checked @endif type="checkbox" value="{{$tag->id}}" id="flexCheckDefault-{{$tag->id}}">
                        <label class="form-check-label pt-1" for="flexCheckDefault-{{$tag->id}}">
                            {{$tag->name}}
                        </label>
                    </div>
                    @endforeach
                    @endforeach
                </div>
                @endif
            </div>
            <div class="list-group-item list-group-item-action bg-transparent">
                <a href="{{ url($path.'/store/games') }}" class="@if(!empty($section) && $section == 'games') focused @endif">
                    <div class="row">
                        <div class="col-2"><i class="fas fa-chess fa-lg fa-fw "></i></div>
                        <div class="col-10">Games</div>
                    </div>
                </a>
                @if($section == 'games' && !isset($hideFilters))
                <div class="options">
                    @foreach(\App\Models\ProductStoreTags::where('root_id', 3)->where('parent_id', 0)->get() as $root_tag)
                    <b>{{strtoupper($root_tag->name)}}</b>
                    @foreach(\App\Models\ProductStoreTags::where('parent_id', $root_tag->id)->get() as $tag)
                    <div class="form-check">
                        <input class="form-check-input filter-selection" @if(in_array($tag->id, $filter)) checked @endif type="checkbox" value="{{$tag->id}}" id="flexCheckDefault-{{$tag->id}}">
                        <label class="form-check-label pt-1" for="flexCheckDefault-{{$tag->id}}">
                            {{$tag->name}}
                        </label>
                    </div>
                    @endforeach
                    @endforeach
                </div>
                @endif
            </div>
            <div class="list-group-item list-group-item-action bg-transparent">
                <a href="{{ url($path.'/store/swag') }}" class="@if(!empty($section) && $section == 'swag') focused @endif">
                    <div class="row">
                        <div class="col-2"><i class="fad fa-tshirt fa-lg fa-fw "></i></div>
                        <div class="col-10">Swag, Clothes, Dontion Gifts</div>
                    </div>
                </a>
                @if($section == 'swag' && !isset($hideFilters))
                <div class="options">
                    @foreach(\App\Models\ProductStoreTags::where('root_id', 4)->where('parent_id', 0)->get() as $root_tag)
                    <b>{{strtoupper($root_tag->name)}}</b>
                    @foreach(\App\Models\ProductStoreTags::where('parent_id', $root_tag->id)->get() as $tag)
                    <div class="form-check">
                        <input class="form-check-input filter-selection" @if(in_array($tag->id, $filter)) checked @endif type="checkbox" value="{{$tag->id}}" id="flexCheckDefault-{{$tag->id}}">
                        <label class="form-check-label pt-1" for="flexCheckDefault-{{$tag->id}}">
                            {{$tag->name}}
                        </label>
                    </div>
                    @endforeach
                    @endforeach
                </div>
                @endif
            </div>
            <div class="list-group-item list-group-item-action bg-transparent">
                <a href="{{ url($path.'/store/tools') }}" class="@if(!empty($section) && $section == 'tools') focused @endif">
                    <div class="row">
                        <div class="col-2"><i class="fas fa-wrench fa-lg fa-fw"></i></div>
                        <div class="col-10">Teaching Tools</div>
                    </div>
                </a>
                @if($section == 'tools' && !isset($hideFilters))
                <div class="options">
                    @foreach(\App\Models\ProductStoreTags::where('root_id', 5)->where('parent_id', 0)->get() as $root_tag)
                    <b>{{strtoupper($root_tag->name)}}</b>
                    @foreach(\App\Models\ProductStoreTags::where('parent_id', $root_tag->id)->get() as $tag)
                    <div class="form-check">
                        <input class="form-check-input filter-selection" @if(in_array($tag->id, $filter)) checked @endif type="checkbox" value="{{$tag->id}}" id="flexCheckDefault-{{$tag->id}}">
                        <label class="form-check-label pt-1" for="flexCheckDefault-{{$tag->id}}">
                            {{$tag->name}}
                        </label>
                    </div>
                    @endforeach
                    @endforeach
                </div>
                @endif
            </div>
            <div class="list-group-item list-group-item-action bg-transparent">
                <a href="{{ url($path.'/store/trainings') }}" class="@if(!empty($section) && $section == 'trainings') focused @endif">
                    <div class="row">
                        <div class="col-2"><i class="fas fa-headset fa-lg fa-fw"></i></div>
                        <div class="col-10">Trainings</div>
                    </div>
                </a>
                @if($section == 'trainings' && !isset($hideFilters))
                <div class="options">
                    @foreach(\App\Models\ProductStoreTags::where('root_id', 6)->where('parent_id', 0)->get() as $root_tag)
                    <b>{{strtoupper($root_tag->name)}}</b>
                    @foreach(\App\Models\ProductStoreTags::where('parent_id', $root_tag->id)->get() as $tag)
                    <div class="form-check">
                        <input class="form-check-input filter-selection" @if(in_array($tag->id, $filter)) checked @endif type="checkbox" value="{{$tag->id}}" id="flexCheckDefault-{{$tag->id}}">
                        <label class="form-check-label pt-1" for="flexCheckDefault-{{$tag->id}}">
                            {{$tag->name}}
                        </label>
                    </div>
                    @endforeach
                    @endforeach
                </div>
                @endif
            </div>
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
    /* Prevent flash by applying state before page renders */
    #wrapper:not(.js-ready) {
        visibility: hidden;
    }
    /*iPhone*/
    @media (max-width: 767px) {
        .phone {
            /*position*/
            position:fixed;
            z-index: 1;
            top: 0;
            bottom: 0;
            width: 100%;
            height: 40px;
            margin: 51px 0 10px 0;
            /*design*/
            background: #f0f3fa;
        }
        .desk {
            visibility: hidden;
            clear: both;
            display: none;
        }
        div.scrollmenu {
            overflow: auto;
            white-space: nowrap;
        }
        div.scrollmenu a {
            display: inline-block;
            text-align: center;
            padding: 10px;
            text-decoration: none;
        }
        div.scrollmenu::-webkit-scrollbar {
            display: none;
        }
    }
    /*desktop*/
    @media (min-width: 768px) {
        .phone {
            visibility: hidden;
            clear: both;
            display: none;
        }
        
        /* Sidebar toggle button */
        .sidebar-toggle-btn {
            position: fixed;
            top: 160px;
            left: 225px;
            z-index: 1000;
            width: 30px;
            height: 30px;
            background: white;
            border: 1px solid #d8dce7;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.25s ease-out;
            transform: translateX(0);
        }
        
        .sidebar-toggle-btn:hover {
            background: #9acd57;
            color: white;
        }
        
        .sidebar-toggle-btn i {
            transition: transform 0.3s ease;
        }
        
        /* When sidebar is collapsed, move button to left edge using transform */
        #wrapper.toggled .sidebar-toggle-btn {
            transform: translateX(-210px);
        }
        
        #wrapper.toggled .sidebar-toggle-btn i {
            transform: rotate(180deg);
        }
        
        #sidebar-wrapper {
        /*position*/
        position: fixed;
        z-index: 0;
        top: 0;
        bottom: 0;
        width: 240px;
        height: 100vh;
        min-height: calc(100vh - 200px);
        margin-left: -15rem;
        padding-top: 110px;
        overflow-y: scroll;
        /*animation*/
        -webkit-transition: margin .25s ease-out;
        -moz-transition: margin .25s ease-out;
        -o-transition: margin .25s ease-out;
        transition: margin .25s ease-out;
        }
    }
    #sidebar-wrapper .sidebar-heading {
        padding: 0.875rem 1.25rem;
        text-align: center;
    }
    #sidebar-wrapper .list-group {
        text-transform: uppercase;
    }
    #sidebar-wrapper {
        margin-left: 0;
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
</style>
<script type='text/javascript'>
    $(document).ready(function(){
        // Filter selection handler
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
        
        // Restore sidebar state from localStorage BEFORE page renders
        if(localStorage.getItem('sidebarCollapsed') === 'true') {
            $("#wrapper").addClass("toggled");
        }
        // Make wrapper visible after state is applied
        $("#wrapper").addClass("js-ready");
        
        // Sidebar toggle handler - using event delegation to ensure it works
        $(document).on('click', "#sidebarToggle", function(e) {
            e.preventDefault();
            e.stopPropagation();
            $("#wrapper").toggleClass("toggled");
            
            // Save state to localStorage
            if($("#wrapper").hasClass("toggled")) {
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        });
    })
</script>
