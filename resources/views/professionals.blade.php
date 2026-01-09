@extends('layouts.app')
@section('content')
<section>
    <div class="containerNoCrumb">
        <!-- PROFESSIONALS -->
        <div class="row justify-content-center">
            <div class="col text-center pt-5">
                <p style="font-size:40px; line-height:35px;">UN|HUSHED in the work force</p>
                <p style="font-size:25px; line-height:30px;"><a href="{{ url($path.'/books') }}">Handbooks</a> on how to talk about sexuality in your field.</p>
            </div>
        </div>
        <div class="row justify-content-center cImg p-3">
            <!-- Books Carousel -->
            <div id="booksCarousel" class="carousel slide w-100">
                <div class="carousel-inner">
                    @forelse($books as $index => $book)
                        @if($index % 4 === 0)
                            <div class="carousel-item @if($index === 0) active @endif">
                                <div class="row justify-content-center">
                                    @foreach($books->slice($index, 4) as $item)
                                        <div class="col-3 col-md-2" style="text-align: center">
                                            <a href="{{ url($path.'/'.$item->slug) }}">
                                                @if($item->images->isNotEmpty())
                                                    <img class="lrgImg" src="{{ asset('uploads/products/'.$item->images->first()->image) }}" alt="{{$item->name}}" style="max-width: 100%; height: auto;">
                                                @else
                                                    <img class="lrgImg" src="{{ asset('img/placeholder.png') }}" alt="{{$item->name}}" style="max-width: 100%; height: auto;">
                                                @endif
                                                <p>{{str_replace('A Handbook for','', $item->name)}}</p>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="carousel-item active">
                            <p class="text-center">No handbooks available</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Carousel Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#booksCarousel" data-bs-slide="prev" style="left: -50px;">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#booksCarousel" data-bs-slide="next" style="right: -50px;">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <div class="row justify-content-center p-3">
            <div class="homeText">
                <p style="font-size:35px;">... and a little more?</p>
                <hr>
                <p>Explore the site, check out all that we do 
                    <br/>(it's a lot!)</p>
            </div>
            <div class="iImg3">
                Donate
                <a href="https://www.paypal.com/donate/?hosted_button_id=KZL7UNCVMLY2Q"><img src="{{ asset('img/cards-sm/w_donate.png') }}" alt="Donate"></a>
            </div>
            <div class="iImg2">
                Newsletter
                <a href="{{ url($path.'/news') }}"><img src="{{ asset('img/cards-sm/w_news.png') }}" alt="Newsletter"></a>
            </div>
        </div>
    </div>

    <style>
        .cImg{
            background: rgb(224,230,245);
            background: linear-gradient(0deg, rgba(224,230,245,1) 19%, rgba(181,195,230,1) 30%, rgba(188,196,217,1) 35%, rgba(203,213,238,1) 66%, rgba(240,243,250,1) 100%);
        }
    </style>

</section>
@endsection
