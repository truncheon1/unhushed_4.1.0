@extends('layouts.app')
@section('content')
@include('layouts.dictbar')

    <section>
        <!-- PAGE CONTENT -->
        <div class="containerStore">
        <div class="d-flex">
            <div class="row">
                <div class="col-auto mx-auto">
                    <img src="{{ asset('img/cards-med/orientation.png') }}" alt="Ollie the octopus holding a book." style="max-width:100px">
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo" style="font-size=18px">Sex Ed Orientation Dictionary</p>
                    <p>Browse the full list of terms here, or refine your search on the left of your screen.</p>
                </div>
            </div>
        </div>

    <!-- STORE CARDS -->
    <div class="d-flex">
        <div class="row">
            <div class="col-10 mb-5">
                @foreach ($entries as $entry)
                <div class="row bg-white rounded p-3 my-2" style="min-width: 400px">
                    <div class="col-4">
                    <p style="font-size:12pt"><span style="font-weight:bold">{{ $entry['term'] }}</span>
                        <br><span style="font-size:11pt"><i>{{ $entry['part'] }}</i>
                    </div>
                    <div class="col-2 ml-auto">
                        @empty($entry['symbol'])
                        @else
                        <img class="tinySymbol" src="{{ url('uploads/dictionaries/'.$entry['symbol']) }}" alt="{{ $entry['symbol'] }}"/></a>
                        @endempty
                        @empty($entry['flag'])
                        @else
                        <img class="tinyFlag" src="{{ url('uploads/dictionaries/'.$entry['flag']) }}" alt="{{ $entry['flag'] }}"/></a>
                        @endempty
                    </div>
                    <div class="col-12">
                        <span style="font-size:10pt; text-align: justify">{!!$entry['definition']!!}</span></p>
                        <span style="font-size:10pt; color: #696969">Updated on {{ $entry['updated_at']->format('m-d-Y') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<style>
</style>

@endsection
