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
                    </div>
                    <div class="form-row">
                        <div class="col-lg-2 col-sm-12">
                            <div class="form-group">
                                <label for="qty1">QTY</label>
                                <input type="number" class="form-control" name="qty[{{$training->id}}]" id="qty1" value="1">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="form-group">
                                <label for="donation">TOTAL</label>
                                <input type="text" class="form-control" id="donation" value="${{$training->price}}">
                            </div>
                        </div>
                        <div class="col" style="padding-top:30px">
                            <div class="form-group">
                                <a href="{{ url($path.'/cart') }}" class="btn btn-secondary add-cart">ADD TO CART</a>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- description -->
                {!!$training->description!!}
                <!-- HCWP training -->
                @elseif($training->slug == '/child-welfare-providers')
                    Thank you for your interest in this training. Our free May 17th training has come to an end. If you would like to schedule a training for your organization please contact us at <a href="mailto:info@unhushed.org">info@unhushed.org</a>
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
