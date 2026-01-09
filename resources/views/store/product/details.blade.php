<!-- DETAILS -->
@if($product->category != 5){{-- exclude swag products --}}
<div class="row">
    @foreach ($details as $detail)
    <div class="col-12"><hr></div>
    <div class="col-12" style="text-align:justify;">
        <!-- CREATORS -->
        <div class="row pb-2">
        @if($detail->author != null)
            @if(in_array($product->category, [1, 2])) <!-- curricula or activities -->
            <div class="col-12 info">WRITER(S)</div>
            @elseif($product->category == 3) <!-- books -->
            <div class="col-12 info">AUTHOR(S)</div>
            @elseif($product->category == 4) <!-- games -->
            <div class="col-12 info">CREATOR(S)</div>
            @elseif($product->category == 7) <!-- trainings -->
            <div class="col-12 info">TRAINER(S)</div>          
            @endif
            <div class="col-12">{{ $detail->author }}</div>
        @endif
        </div>

        <!-- FACILITATOR -->
        <div class="row pb-2">
        @if($detail->facilitator != null)
            @if($product->category == 3) <!-- 3 books -->
            <div class="col-12 info">INTENDED READER</div>
            @elseif($product->category == 7) <!-- 7 trainings -->
            <div class="col-12 info">INTENDED TRAINEE</div>
            @else <!-- 1 curricula, 2 activities, 4 games, 5 teaching tools -->
            <div class="col-12 info">INTENDED FACILITATOR</div>
            @endif
            <div class="col-12">{{ $detail->facilitator }}</div>
        @endif
        </div>

        <!-- PARTICIPANT POPULATION  -->
        <div class="row pb-2">
        @if($detail->population != null && $product->category != 3)
            <div class="col-12 info">PARTICIPANT POPULATION</div>
            <div class="col-12">{{ $detail->population }}</div>
        @endif
        </div>

        <!-- AGE APPROPRIATE FOR  -->
         <div class="row pb-2">
        @if($detail->age != null)
            <div class="col-12 info">AGE APPROPRIATE FOR</div><div class="col-12 infoA">{{ $detail->age }}</div>
        @endif
        </div>

        <!-- PRODUCT LENGTH -->
        <div class="row pb-2">
        @if($detail->duration != null)
            @if($product->category == 1) <!-- curricula -->
            <div class="col-12 info">LENGTH</div> 
            @elseif($product->category == 3) <!-- books -->
            <div class="col-12 info">PAGE COUNT</div>
            @elseif($product->category == 4) <!-- games -->
            <div class="col-12 info">PARTICIPANTS</div>
            @elseif($product->category == 7) <!-- trainings -->
            <div class="col-12 info">DURATION</div>
            @endif
            <div class="col-12">{{ $detail->duration }}</div>
        @endif
        </div>
    </div>
    @endforeach
    </div>
@endif

<!-- review row -->
@if(empty($reviews))
    @include('store.ratings.reviews')
@endif