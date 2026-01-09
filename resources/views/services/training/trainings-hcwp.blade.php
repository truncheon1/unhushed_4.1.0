@extends('layouts.app')

@section('content')

<section>
    <!-- BREADCRUMBS -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/services') }}">Services </a> |
            <a href="{{ url($path.'/trainings') }}"> Trainings</a> |
            {{$training->name}}
        </div>
    </div>

    <div class="container pt-5 pb-5">
        <!-- PRODUCT -->
        <div class="row">
            <div class="col-12">
                <!-- title -->
                <p class="diazo">{{$training->name}}</p>
                <hr>
            </div>
            <div class="col-lg-4 col-md-12">
                <!-- images -->
                <div class="text-center">
                    <div class="row">
                        <div class="col-10">
                            <img class="big" src="{{ url('uploads/products/'.$training->image) }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <!-- description -->
                {!!$training->description!!}
                <br>
                <!-- purchased trainings -->
                @if($training->price > 1)
                <form class="cart-form" action="{{url($path.'/cart')}}" method="get">
                    <input type="hidden" name="type[{{$training->id}}]" value="{{\App\Models\OrderItems::ITEM_TYPE_TRAINING}}"/>
                    <input type="hidden" name="id[]" value="{{$training->id}}" />
                    <div class="form-row">
                        <div class="col-lg-8 col-sm-12">
                            <div class="form-group">
                                <label for="curriculum">TRAINING BEING ADDED TO CART</label>
                                <input class="form-control package-caption" type="text" placeholder="{{$training->name}}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-12">
                            <div class="form-group">
                                <label for="qty1">QTY</label>
                                <input type="number" class="form-control" name="qty[{{$training->id}}]" id="qty1" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-lg-3 col-sm-12">
                            <div class="form-group">
                                <label for="donation">TOTAL</label>
                                <input type="text" class="form-control" id="donation" value="${{$training->price}}">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="w-100"></div>
                <div class="col pt-3">
                    <div class="text-center">
                        <a href="{{ url($path.'/cart') }}" class="btn btn-secondary add-cart">ADD TO CART</a>
                    </div>
                </div>
                <!-- HCWP training -->
                @elseif($path.'/'.$training->slug == '/child-welfare-providers')
                    @guest
                    But first! Please <a href="{{ url($path.'/login') }}">{{ __('LOGIN') }}</a> to your account, @if (Route::has('register')) or <a href="{{ url($path.'/register') }}">{{ __('REGISTER') }} </a> for a free account in order to gain access to the training page and materials.
                    @endif
                    @else
                    <p>Fill in this form to reserve your spot and receive a free digital copy of the handbook.</p>
                    <form method="POST" action="{{ url($path.'/reghcwp') }}" id="address-form">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="sr-only">Name</label>
                            <input type="text" name="name" id="name" class="form-control" @if(auth()->user()) value="{{auth()->user()->name}}" @endif placeholder="Full name">
                        </div>
                        <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="text" name="email" id="email" class="form-control" @if(auth()->user()) value="{{auth()->user()->email}}" @endif placeholder="Email address">
                        </div>
                        <div class="form-group">
                            <label for="street" class="sr-only">Street</label>
                            <input type="text" name="street" id="street" class="form-control" placeholder="Physical address">
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label for="city" class="sr-only">City</label>
                                <input type="text" name="city" id="city" class="form-control" placeholder="City">
                            </div>
                            <div class="form-group">
                                <label for="state" class="sr-only">State</label>
                                <select class="form-control" name="state" id="state" onchange="txChecked(this);">
                                    @include('layouts.helpers.states')
                                </select>
                            </div>
                            <div class="col-sm">
                                <label for="zip" class="sr-only">Zip</label>
                                <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip">
                            </div>
                        </div>
                        <div id="txCheck" style="display:none;">
                            <p>TX residents may select one of the following options for 4 CEUs.</p>
                            <div class="form-check form-check-inline ml-1">
                                <input class="form-check-input" type="radio" name="ceu" id="none" value="none" checked>
                                <label class="form-check-label" for="none">none</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ceu" id="LMSW" value="LMSW">
                                <label class="form-check-label" for="LMSW">LMSW</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ceu" id="LPC" value="LPC">
                                <label class="form-check-label" for="LPC">LPC</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ceu" id="LMFT" value="LMFT">
                                <label class="form-check-label" for="LMFT">LMFT</label>
                            </div>
                            <p style="font-size: 9px">
                                The Steve Hicks School of Social Work Office of Professional Development at the University of Texas at Austin grants Continuing Education Hours to the Texas Institute for Child & Family Wellbeing workshops, courses, and educational programs that meet the criteria established by Texas State Board of Social Work Examiners, Texas State Board of Professional Counselors, and Texas State Boards of Examiners of Marriage and Family Therapists. These educational hours may then be submitted by professionals to meet continuing education requirements for Social Work, Licensed Professional Counselor, and Licensed Marriage and Family Therapist license renewal.
                            </p>
                        </div>
                        <div class="form-group mt-2">
                            <input name="continue" id="continue" class="btn btn-secondary" type="submit" value="REGISTER" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
                        </div>
                    </form>
                    @endguest
                <!-- free trainings -->
                @else
                <div class="col pt-3">
                    @guest
                    <a href="{{ url($path.'/login') }}">{{ __('LOGIN') }}</a> to your account, @if (Route::has('register')) or <a href="{{ url($path.'/register') }}">{{ __('REGISTER') }} </a> for a free account to download these materials right now!
                    @endif
                    @else
                    You can visit the <a href="{{ url($path.'content/trainings') }}"> TRAININGS </a> tab from your dashboard to view these materials.
                    @endguest
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style type="text/css">
a.btn{
    color: #fff;
}
</style>

<script>
    $(".thumbs a").on('click', function(e) {
        e.preventDefault();
        $('.big').attr('src', $(this).attr('href'));
    });
    $(document).ready(function(){
        $("#qty1").on("change", function(){
            if($(this).val() < 1){
                $(this).val(1);
            }
            price = {{$training->price}};
            sum = parseInt( $("#qty1").val()) * 1;
            sum = price * sum;
            $("#donation").val("$"+sum);

        });
        $(".add-cart").on("click", function(e){
            e.preventDefault();
            $('.cart-form').submit();
        });
        $(".add-package").on("click", function(e){
            e.preventDefault();
            alert("Not implemented yet!");
        })
    });
    $(document).ready(function(){
        $("#continue").on('click', function(e){
            e.preventDefault();
            _url = $("#address-form").attr('action');
            _data = $("#address-form").serialize();
            $.ajax({
                url: _url,
                type: 'post',
                data: _data,
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        string = '';
                        for(r in response.reason){
                            console.log(r);
                            for(rsn in response.reason[r]){
                                console.log(response.reason[r][rsn]);
                                string += response.reason[r][rsn] + '\n';
                            }
                        }
                        alert(string);

                    }else{
                        document.location = _url;
                    }

                },
                fail: function(){
                    alert("Error");
                }
            });
        })
    })
    function txChecked(nameSelect)
    {
        if(nameSelect){
            admOptionValue = document.getElementById("admOption").value;
            if(admOptionValue == nameSelect.value){
                document.getElementById("txCheck").style.display = "block";
            }
            else{
                document.getElementById("txCheck").style.display = "none";
            }
        }
        else{
            document.getElementById("txCheck").style.display = "none";
        }
    }
</script>

@endsection
