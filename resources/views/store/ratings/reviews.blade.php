<!-- REVIEWS -->
<section class="bg-white py-5">
    <div class="row px-5">
        <div class="col-8 m-auto text-center">
            <h4>PRODUCT REVIEWS</h4>
        </div>
        <div class="col-8 mx-auto">
            @forelse ($reviews as $review)
            <!-- each review -->
            <div class="col">
                    <div class="col">
                        <a href="{{ '@' . $review->user->name }}"></a>
                    </div>
                    <div class="col">
                        <!-- user info -->
                        <div class="col">
                            <!-- down the road this link will go to user's review page <a href="{{ '@' . $review->user->username }}"></a> -->
                            <img class="avatarreviewer" src="/uploads/avatars/{{ $review->user->avatar }}" style="width:35px;height:35px;"> {{  $review->user->name }}
                        </div>
                        <!-- stars -->
                        <div class="col" style="color:#ffcc33">
                            @foreach(range(1,5) as $i)
                                <span class="fa-stack" style="width:1em">
                                    <i class="far fa-star fa-stack-1x"></i>
                                    @if($review->rating >0)
                                        @if($review->rating >0.5)
                                            <i class="fas fa-star fa-stack-1x"></i>
                                        @else
                                            <i class="fas fa-star-half fa-stack-1x"></i>
                                        @endif
                                    @endif
                                    @php $review->rating--; @endphp
                                </span>
                            @endforeach
                        </div>
                        <!-- date -->
                        <div class="col" style="color:#ccc">
                            Reviwed on {{ date('m-d-Y', strtotime($review->created_at)) }}
                        </div>
                        <!-- review -->
                        <div class="col">
                            {{ $review->review }}
                            <hr>
                        </div>
                    </div>
                </div>
            @empty
            <div class="col">
            </div>
            @endforelse
        </div>
    </div>
</section>
