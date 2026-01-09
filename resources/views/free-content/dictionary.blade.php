@extends('layouts.app')
@section('content')
@include('layouts.dictionbar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerStore">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <span style="font-weight: bold;color:#9acd57">Dictionary</span>
            </div>
        </div>
        <!-- DICTIONARY HEADER-->
        <div class="row align-items-center">
            <div class="col-auto">
                <img src="{{ asset('img/octos/ollie-book.png') }}" alt="Ollie reading." style="max-width:100px">
            </div>
            <div class="col-6 pt-2">
                <p class="diazo2">Sexuality Education Dictionary</p>
                <p style="text-align:justify">Originally created as part of our Middle School Curriculum in 2017, this dictionary is meant to provide a starting place for sexuality educators, 
                    students, and parents. This is in no way a completely comprehensive list, as language is changing and evolving on a daily basis. Our goal is to provide
                    a safe space to research terms that may be new to you.</p>
            </div>
            <div class="col-8"><hr></div>
        </div>

        <!-- DICTIONARY CONTENT-->
        <div class="row mx-auto pb-5">
            <!-- search bar -->
            <div id="search" class="search">
                <div class="pr-4 mx-auto">
                    <form action="{{ url($path.'/sex-ed-dictionary') }}" method="GET" role="search">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="word" placeholder="Search keywords" id="word">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="submit" title="Search keywords">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                                <a href="{{ url($path.'/sex-ed-dictionary') }}" button class="btn btn-secondary" type="button" title="Refresh page">
                                    <i class="fas fa-sync-alt"></i></a>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- entries -->
            <div class="col-8">
                @forelse($entries as $entry)
                <div class="row rounded p-3 my-2" style="min-width: 400px; background-color:#f7f8fb;">
                        <div class="col-auto mt-2">
                            <span style="font-weight:bold; font-size:16pt;">{{$entry['term']}}</span> &ensp; 
                            <span style="font-size:10pt;">[ {!!$entry['phonetic']!!} ]</span>
                        </div>
                        <div class="col-auto">
                            @if($entry['audio'])               
                            <audio controls controlsList="noplaybackrate nodownload" class="audio-1">
                                <source src="/uploads/audio/{{$entry['audio']}}" type="audio/mpeg">
                            </audio>
                            @endif
                        </div>
                    </span>
                    <details>
                        <summary>
                            <span id="open"></span>
                            <span id="close">close <i class="fa-solid fa-square-chevron-up"></i></span>
                        </summary>
                        <div class="col-12 ml-3">
                        @foreach(\App\Models\DictDefs::where('term_id', $entry['id'])->where('part', 1)->orderBy('sort', 'ASC')->get() as $def)
                            @if($loop->first)
                                <span style="font-weight:bold;">noun</span>
                            @endif
                            <div class="row" style="font-size:10pt; text-align: justify">
                                <div class="col-auto text-end">{{ $loop->iteration}}.</div>
                                <div class="col-11">{!! $def['def'] !!}</div>
                                <div class="col-12" style="font-size:10pt; text-align:justify; color:#808080; padding:5px 0 10px 45px;"><i>{!! $def['example'] !!}</i></div>
                                @foreach(\App\Models\DictImages::where('def_id', $def['id'])->orderBy('sort', 'ASC')->get() as $images)
                                <div class="col-12" style="padding:5px 0 10px 45px;"><img src="{{ asset('uploads/dictionaries/'.$images->image) }}" style="max-width:100px"></div>
                                @endforeach
                            </div>
                        @endforeach
                        @foreach(\App\Models\DictDefs::where('term_id', $entry['id'])->where('part', 2)->orderBy('sort', 'ASC')->get() as $def)
                            @if($loop->first)
                                <p><span style="font-weight:bold;">pronoun</span>
                            @endif
                            <div class="row" style="font-size:10pt; text-align: justify">
                                <div class="col-auto text-end">{{ $loop->iteration}}.</div>
                                <div class="col-11">{!! $def['def'] !!}</div>
                                <div class="col-12" style="font-size:10pt; text-align:justify; color:#808080; padding:5px 0 10px 45px;"><i>{!! $def['example'] !!}</i></div>
                                @foreach(\App\Models\DictImages::where('def_id', $def['id'])->orderBy('sort', 'ASC')->get() as $images)
                                <div class="col-12" style="padding:5px 0 10px 45px;"><img src="{{ asset('uploads/dictionaries/'.$images->image) }}" style="max-width:100px"></div>
                                @endforeach
                            </div>
                        @endforeach
                        @foreach(\App\Models\DictDefs::where('term_id', $entry['id'])->where('part', 3)->orderBy('sort', 'ASC')->get() as $def)
                            @if($loop->first)
                                <p><span style="font-weight:bold;">verb</span>
                            @endif
                            <div class="row" style="font-size:10pt; text-align: justify">
                                <div class="col-auto text-end">{{ $loop->iteration}}.</div>
                                <div class="col-11">{!! $def['def'] !!}</div>
                                <div class="col-12" style="font-size:10pt; text-align:justify; color:#808080; padding:5px 0 10px 45px;"><i>{!! $def['example'] !!}</i></div>
                                @foreach(\App\Models\DictImages::where('def_id', $def['id'])->orderBy('sort', 'ASC')->get() as $images)
                                <div class="col-12" style="padding:5px 0 10px 45px;"><img src="{{ asset('uploads/dictionaries/'.$images->image) }}" style="max-width:100px"></div>
                                @endforeach
                            </div>
                        @endforeach
                        @foreach(\App\Models\DictDefs::where('term_id', $entry['id'])->where('part', 4)->orderBy('sort', 'ASC')->get() as $def)
                            @if($loop->first)
                                <p><span style="font-weight:bold;">adjective</span>
                            @endif
                            <div class="row" style="font-size:10pt; text-align: justify">
                                <div class="col-auto text-end">{{ $loop->iteration}}.</div>
                                <div class="col-11">{!! $def['def'] !!}</div>
                                <div class="col-12" style="font-size:10pt; text-align:justify; color:#808080; padding:5px 0 10px 45px;"><i>{!! $def['example'] !!}</i></div>
                                @foreach(\App\Models\DictImages::where('def_id', $def['id'])->orderBy('sort', 'ASC')->get() as $images)
                                <div class="col-12" style="padding:5px 0 10px 45px;"><img src="{{ asset('uploads/dictionaries/'.$images->image) }}" style="max-width:100px"></div>
                                @endforeach
                            </div>
                        @endforeach
                        @foreach(\App\Models\DictDefs::where('term_id', $entry['id'])->where('part', 5)->orderBy('sort', 'ASC')->get() as $def)
                            @if($loop->first)
                                <p><span style="font-weight:bold;">adverb</span>
                            @endif
                            <div class="row" style="font-size:10pt; text-align: justify">
                                <div class="col-auto text-end">{{ $loop->iteration}}.</div>
                                <div class="col-11">{!! $def['def'] !!}</div>
                                <div class="col-12" style="font-size:10pt; text-align:justify; color:#808080; padding:5px 0 10px 45px;"><i>{!! $def['example'] !!}</i></div>
                                @foreach(\App\Models\DictImages::where('def_id', $def['id'])->orderBy('sort', 'ASC')->get() as $images)
                                <div class="col-12" style="padding:5px 0 10px 45px;"><img src="{{ asset('uploads/dictionaries/'.$images->image) }}" style="max-width:100px"></div>
                                @endforeach
                            </div>
                        @endforeach
                        @foreach(\App\Models\DictDefs::where('term_id', $entry['id'])->where('part', 6)->orderBy('sort', 'ASC')->get() as $def)
                            @if($loop->first)
                                <p><span style="font-weight:bold;">preposition</span>
                            @endif
                            <div class="row" style="font-size:10pt; text-align: justify">
                                <div class="col-auto text-end">{{ $loop->iteration}}.</div>
                                <div class="col-11">{!! $def['def'] !!}</div>
                                <div class="col-12" style="font-size:10pt; text-align:justify; color:#808080; padding:5px 0 10px 45px;"><i>{!! $def['example'] !!}</i></div>
                                @foreach(\App\Models\DictImages::where('def_id', $def['id'])->orderBy('sort', 'ASC')->get() as $images)
                                <div class="col-12" style="padding:5px 0 10px 45px;"><img src="{{ asset('uploads/dictionaries/'.$images->image) }}" style="max-width:100px"></div>
                                @endforeach
                            </div>
                        @endforeach
                        @foreach(\App\Models\DictDefs::where('term_id', $entry['id'])->where('part', 7)->orderBy('sort', 'ASC')->get() as $def)
                            @if($loop->first)
                                <p><span style="font-weight:bold;">conjunction</span>
                            @endif
                            <div class="row" style="font-size:10pt; text-align: justify">
                                <div class="col-auto text-end">{{ $loop->iteration}}.</div>
                                <div class="col-11">{!! $def['def'] !!}</div>
                                <div class="col-12" style="font-size:10pt; text-align:justify; color:#808080; padding:5px 0 10px 45px;"><i>{!! $def['example'] !!}</i></div>
                                @foreach(\App\Models\DictImages::where('def_id', $def['id'])->orderBy('sort', 'ASC')->get() as $images)
                                <div class="col-12" style="padding:5px 0 10px 45px;"><img src="{{ asset('uploads/dictionaries/'.$images->image) }}" style="max-width:100px"></div>
                                @endforeach
                            </div>
                        @endforeach
                        @foreach(\App\Models\DictDefs::where('term_id', $entry['id'])->where('part', 8)->orderBy('sort', 'ASC')->get() as $def)
                            @if($loop->first)
                                <p><span style="font-weight:bold;">interjection</span>
                            @endif
                            <div class="row" style="font-size:10pt; text-align: justify">
                                <div class="col-auto text-end">{{ $loop->iteration}}.</div>
                                <div class="col-11">{!! $def['def'] !!}</div>
                                <div class="col-12" style="font-size:10pt; text-align:justify; color:#808080; padding:5px 0 10px 45px;"><i>{!! $def['example'] !!}</i></div>
                                @foreach(\App\Models\DictImages::where('def_id', $def['id'])->orderBy('sort', 'ASC')->get() as $images)
                                <div class="col-12" style="padding:5px 0 10px 45px;"><img src="{{ asset('uploads/dictionaries/'.$images->image) }}" style="max-width:100px"></div>
                                @endforeach
                            </div>
                        @endforeach
                        @foreach(\App\Models\DictDefs::where('term_id', $entry['id'])->where('part', 9)->orderBy('sort', 'ASC')->get() as $def)
                            @if($loop->first)
                                <p><span style="font-weight:bold;">determiner</span>
                            @endif
                            <div class="row" style="font-size:10pt; text-align: justify">
                                <div class="col-auto text-end">{{ $loop->iteration}}.</div>
                                <div class="col-11">{!! $def['def'] !!}</div>
                                <div class="col-12" style="font-size:10pt; text-align:justify; color:#808080; padding:5px 0 10px 45px;"><i>{!! $def['example'] !!}</i></div>
                                @foreach(\App\Models\DictImages::where('def_id', $def['id'])->orderBy('sort', 'ASC')->get() as $images)
                                <div class="col-12" style="padding:5px 0 10px 45px;"><img src="{{ asset('uploads/dictionaries/'.$images->image) }}" style="max-width:100px"></div>
                                @endforeach
                            </div>
                        @endforeach
                        @foreach(\App\Models\DictDefs::where('term_id', $entry['id'])->where('part', 10)->orderBy('sort', 'ASC')->get() as $def)
                            @if($loop->first)
                                <p><span style="font-weight:bold;">article</span>
                            @endif
                            <div class="row" style="font-size:10pt; text-align: justify">
                                <div class="col-auto text-end">{{ $loop->iteration}}.</div>
                                <div class="col-11">{!! $def['def'] !!}</div>
                                <div class="col-12" style="font-size:10pt; text-align:justify; color:#808080; padding:5px 0 10px 45px;"><i>{!! $def['example'] !!}</i></div>
                                @foreach(\App\Models\DictImages::where('def_id', $def['id'])->orderBy('sort', 'ASC')->get() as $images)
                                <div class="col-12" style="padding:5px 0 10px 45px;"><img src="{{ asset('uploads/dictionaries/'.$images->image) }}" style="max-width:100px"></div>
                                @endforeach
                            </div>
                        @endforeach
                        @foreach(\App\Models\DictDefs::where('term_id', $entry['id'])->where('part', 11)->orderBy('sort', 'ASC')->get() as $def)
                            @if($loop->first)
                                <p><span style="font-weight:bold;">suffix</span>
                            @endif
                            <div class="row" style="font-size:10pt; text-align: justify">
                                <div class="col-auto text-end">{{ $loop->iteration}}.</div>
                                <div class="col-11">{!! $def['def'] !!}</div>
                                <div class="col-12" style="font-size:10pt; text-align:justify; color:#808080; padding:5px 0 10px 45px;"><i>{!! $def['example'] !!}</i></div>
                                @foreach(\App\Models\DictImages::where('def_id', $def['id'])->orderBy('sort', 'ASC')->get() as $images)
                                <div class="col-12" style="padding:5px 0 10px 45px;"><img src="{{ asset('uploads/dictionaries/'.$images->image) }}" style="max-width:100px"></div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div class="col-12">
                        <p><b>keywords:</b> {!!$entry['keywords']!!}
                        @if($entry['alt'])<br><b>alternative spellings:</b> {!!$entry['alt']!!} @else @endif
                        <br><span style="font-size:10pt; color: #808080">Updated on:  {{$entry['updated_at']->format('M, d Y')}}</span></p>
                    </div>
                </details>
            </div>
            @empty
                <p><br>&emsp;There are no entries that match your keyword(s)</p>
            @endforelse
        </div>
    </div>

    <style>
        audio::-webkit-media-controls-panel {
            background-color: #ffffff;
        }
        audio::-webkit-media-controls-play-button,
        audio::-webkit-media-controls-mute-button {
            background-color: #f7f8fb;
            border-radius: 50%;
        }
        audio::-webkit-media-controls-current-time-display,
        audio::-webkit-media-controls-time-remaining-display {        
            color: #265a8e;
        }
        .search {
            width: 100%;
            max-width: 800px;
            height: 40px;
            z-index: 1;
        }
        .sticky {
            position: fixed;
            top: 115px;
            right: 310px;
            height: 50px;
            max-width: 600px;
            background: white;
            padding: 5px 0 5px 10px;
            border-radius: 0 0 0 6px;
            border-bottom: 1px solid #d8dce7;
            border-left: 1px solid #d8dce7;
        }
        h2, details {
            display:inline;
        }
        details {
            position:relative;
        }
        details summary {
            display:block;
            cursor: pointer;
            color: #265a8e;
        }
        details summary:focus {
            outline:none;
        }
        details[open] {
            display:block;
            padding-bottom:25px;
            padding-top:10px;
            animation: open .2s linear;
        }
        details[open] summary {
            position:absolute;
            bottom: 0;
            left:0;
        }
        details #open {
            display: inline-block;
            position: relative;
            color: #01a7d3;
            
            height: auto;
        }
        details #open:after {
            content: 'definition';
            font-size: 14px;
            color: #01a7d3;
            padding: 0;
            position: relative;
        }
        details #open:hover {
            color: #265a8e;
        }
        details[open] #open{
            display:none;
        }
        details #close{
            display: none;
            content: 'close';
            font-size: 14px;
            color: #01a7d3;
            padding-left: 20px;
        }
        details[open] #close{display:block;}
        ::-webkit-details-marker {display: none;}
        @keyframes open {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .values {
            font-size: 18px;
            padding-left: 20px;
        }
    </style>

    <script>
        // When the user scrolls the page, execute myFunction
        window.onscroll = function() {
            stickySearchFunction()
        };
        // Get the header
        var search = document.getElementById("search");
        var headerBar = document.getElementById("headerBar");
        // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
        function stickySearchFunction() {
            if (window.pageYOffset > 160) {
                search.classList.add("sticky");
            } else {
                search.classList.remove("sticky");
            }
        }
    </script>
</section>
@endsection


