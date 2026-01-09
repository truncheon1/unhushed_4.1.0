<div class="d-flex" id="wrapper">
<!-- SIDEBAR -->
<div class="border-right" id="sidebar-wrapper">
    <div class="list-group list-group-flush pt-3">
        <a href="{{ url($path.'') }}" class="list-group-item list-group-item-action bg-transparent @if(!empty($section) && $section == 'dictionary') focused @endif">
            <div class="row">
                <div class="col-2"><i class="fa-solid fa-book fa-lg fa-fw "></i></div>
                <div class="col-10">Dictionaries</div>
            </div>
        </a>
        <div class="list-group-item list-group-item-action bg-transparent">
            <a href="{{ url($path.'/gender') }}" class="@if(!empty($section) && $section == 'gender') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-solid fa-brain fa-lg fa-fw "></i></div>
                    <div class="col-10">Gender</div>
                </div>
            </a>
            @if($section == 'gender')
            <div class="options">
                @foreach(\App\Models\DictTags::where('root_id', 1)->where('parent_id', 0)->get() as $root_tag)
                <b>{{strtoupper($root_tag->name)}}</b>
                @foreach(\App\Models\DictTags::where('parent_id', $root_tag->id)->get() as $tag)
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
            <a href="{{ url($path.'/orientation') }}" class="@if(!empty($section) && $section == 'orientation') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fa-solid fa-heart fa-lg fa-fw "></i></div>
                    <div class="col-10">Orientation</div>
                </div>
            </a>
            @if($section == 'orientation')
            <div class="options">
                @foreach(\App\Models\DictTags::where('root_id', 2)->where('parent_id', 0)->get() as $root_tag)
                <b>{{strtoupper($root_tag->name)}}</b>
                @foreach(\App\Models\DictTags::where('parent_id', $root_tag->id)->get() as $tag)
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
            <a href="{{ url($path.'/stis') }}" class="@if(!empty($section) && $section == 'stis') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-solid fa-viruses fa-lg fa-fw "></i></div>
                    <div class="col-10">STIs</div>
                </div>
            </a>
            @if($section == 'stis')
            <div class="options">
                @foreach(\App\Models\DictTags::where('root_id', 3)->where('parent_id', 0)->get() as $root_tag)
                <b>{{strtoupper($root_tag->name)}}</b>
                @foreach(\App\Models\DictTags::where('parent_id', $root_tag->id)->get() as $tag)
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
            <a href="{{ url($path.'/onlineTerms') }}" class="@if(!empty($section) && $section == 'online') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fas fa-headset fa-lg fa-fw "></i></div>
                    <div class="col-10">Online teaching terms</div>
                </div>
            </a>
            @if($section == 'online')
            <div class="options">
                @foreach(\App\Models\DictTags::where('root_id', 4)->where('parent_id', 0)->get() as $root_tag)
                <b>{{strtoupper($root_tag->name)}}</b>
                @foreach(\App\Models\DictTags::where('parent_id', $root_tag->id)->get() as $tag)
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
            <a href="{{ url($path.'/slang') }}" class="@if(!empty($section) && $section == 'slang') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fa-solid fa-mobile-screen-button fa-lg fa-fw"></i></div>
                    <div class="col-10">Slang words</div>
                </div>
            </a>
            @if($section == 'slang')
            <div class="options">
                @foreach(\App\Models\DictTags::where('root_id', 5)->where('parent_id', 0)->get() as $root_tag)
                <b>{{strtoupper($root_tag->name)}}</b>
                @foreach(\App\Models\DictTags::where('parent_id', $root_tag->id)->get() as $tag)
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
            <a href="{{ url($path.'/periods') }}" class="@if(!empty($section) && $section == 'periods') focused @endif">
                <div class="row">
                    <div class="col-2"><i class="fa-solid fa-distribute-spacing-horizontal fa-lg fa-fw"></i></div>
                    <div class="col-10">Periods</div>
                </div>
            </a>
            @if($section == 'periods')
            <div class="options">
                @foreach(\App\Models\DictTags::where('root_id', 6)->where('parent_id', 0)->get() as $root_tag)
                <b>{{strtoupper($root_tag->name)}}</b>
                @foreach(\App\Models\DictTags::where('parent_id', $root_tag->id)->get() as $tag)
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
