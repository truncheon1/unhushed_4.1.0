@extends('layouts.app')

@section('content')
<section>

    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/more/blog') }}">Blog |</a>
            {{$post->title}}
        </div>
    </div>

    <!-- BLOG -->
    <div class="container">
        <!-- BLOG POSTS-->
        <div class="row mt-2">
            <!-- title -->
            <div class="col-10 mx-auto mb-3">
                <h2> {{ $post->title }} </h2>
            </div>
            <!-- image -->
            <div class="col-auto mx-auto">
                <img style="max-width:600px; text-align:center" src="{{ url('uploads/blog/'.$post->image) }}" alt="{{$post->title}}"/>
            </div>
            <!-- post -->
            <div class="col-10 mx-auto" style="white-space: nowrap;">
                <!-- details -->
                <p style="color:#808080">
                    <img class="avatarCommenter" src="/uploads/avatars/{{ $post->user->avatar }}"> {{ $post->user->name }}
                </p>
                <div class="row">
                    <div class="col-auto">
                        <p style="color:#808080">
                            <i class="fad fa-calendar-alt fa-fw"></i> <span class="mr-4">{{ date('M. jS, Y', strtotime($post->updated_at)) }}</span>
                            <i class="fas fa-eye fa-fw"></i> <span class="mr-4">{{ $post->views ?? '0' }}</span>
                            <a href="#comments"><i class="fas fa-comments fa-fw"></i></a> {{ $post->comments }}
                        </p>
                    </div>
                    <div class="col-2">
                        @guest
                            <button type="submit" id="btnShowMsg" class="fabutton" onClick="showMessage()">
                                <i class="fas fa-thumbs-up fa-fw"></i>
                            </button>
                            <span style="color:#808080">{{ $post->likes }}</span>
                        @else
                            <form method="POST" action="{{ url($path.'/blog/{slug}') }}">
                                @csrf
                                <input type="hidden" id="post_id" name="post_id" value="{{$post->id}}">
                                <button type="submit" id="btnForm" class="fabutton">
                                    <i class="fas fa-thumbs-up fa-fw"></i>
                                </button>
                            </form>
                            <span style="color:#808080">{{ $post->likes }}</span>
                        @endguest
                    </div>
                </div>
            </div>
            <div class="col-10 mx-auto">
                <!-- content -->
                <p class="text-xl text-gray-700 pt-8 leading-8 font-light" style="text-align: justify">
                    {!!$post->content!!}
                </p>
                <p class="my-4 mx-2 text-justify" style="color:#808080">
                    <i class="fas fa-th-list fa-fw"></i> <span class="mr-2">{{ $post->tags }}</span>
                    <br>
                    <i class="fas fa-tag fa-fw"></i> <span class="mr-2">{{ $post->tags }}</span>
                    <br>
                </p>
                <hr>
            </div>

        </div>

        <!-- COMMENTS -->
        <div class="row my-2" id="comments">
            <div class="col-10 mx-auto mb-3">
                <h4><i class="fal fa-comments-alt"></i> Comments</h4>
                <!-- add -->
                <div class="row p-3" style='border-bottom:1px solid #ccc;'>
                    <div class="col-auto">
                        @guest
                        <img class="avatarCommenter" src="/uploads/avatars/default.jpg">
                        @else
                        <img class="avatarCommenter" src="/uploads/avatars/{{ Auth::user()->avatar }}">
                        @endguest
                    </div>
                    <div class="col-10">
                        <div id="comment-form">
                            <form method="POST" action="{{ url($path.'/blog/{slug}') }}" style="width: 700px">
                                @csrf
                                <input type="hidden" id="post_id" name="post_id" value="{{$post->id}}">
                                <textarea name="comment" id="comment" class="form-control" placeholder="What do you think?" rows="3"></textarea>
                                <div class="float-right m-2">
                                    @guest
                                    <!-- need to add a cookie here to bring back on login -->
                                    <a href="{{ url($path.'/login') }}"><div class="btn btn-secondary">Login to comment</div></a>
                                    @else
                                    <input name="submit" id="submit" class="btn btn-secondary" type="submit" value="SUBMIT" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
                                    @endguest
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- view -->
                @foreach ($entries as $entry)
                <div class="row p-3" style='border-bottom:1px solid #ccc;'>
                    <div class="col-auto">
                        <img class="avatarCommenter" src="/uploads/avatars/default.jpg">
                    </div>
                    <div class="col-10">
                        <span style="color:#808080">{{ $entry['name'] }}</span>
                        <br>{{ $entry['comment'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .fabutton {
            background: none;
            padding: 0px;
            border: none;
            color: #01a7d3;
        }
        .fabutton:hover {
            color: #265a8e;
        }
        .fabutton:focus {
            outline: none;
        }
        form { display: inline-block; }
    </style>

    <script type="text/javascript">
        function showMessage() {
            alert("Login to like this post.");
        }
    </script>

</section>
@endsection

