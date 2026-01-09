@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">

        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Dashboard</a> |
                <span style="font-weight: bold;color:#9acd57">Handbooks</span>
            </div>
        </div>

        <!-- Ebook Card -->
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    EBOOKS AND HANDOUT DOWNLOADS
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    @forelse($ebooks as $ebook)
                    <div class="smCard">
                        @php($eimg = $ebook->primary_image_path ?? $ebook->image)
                        <a href="{{ url($path.'/dashboard/books/'.$ebook->digital_slug) }}"><img class="smImg" src="{{ asset('uploads/products/'.$eimg) }}" alt="{{ $ebook->name }}"></a>
                        <div class="smText">{{ $ebook->name }}</div>
                    </div>
                    @empty
                    <div class="smCard">
                        <div class="smText">Visit our store to <a href="{{ url($path.'/books?s[]=8') }}">add another e-book to your account</a> today!</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        <!-- Physical Book Card -->
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    PHYSICAL BOOK HANDOUT DOWNLOADS
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    @forelse($books as $book)
                        @if(!empty($book->digital_slug))
                            <div class="smCard">
                                @php($img = $book->primary_image_path ?? $book->image)
                                <a href="{{ url($path.'/dashboard/books/'.$book->digital_slug) }}"><img class="smImg" src="{{ asset('uploads/products/'.$img) }}" alt="{{ $book->name }}"></a>
                                <div class="smText">{{ $book->name }}</div>
                            </div>
                        @endif
                    @empty
                    <div class="smCard">
                        <div class="smText">Visit our store to <a href="{{ url($path.'/books?s[]=9') }}">add handbook to your account</a> today!</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
