@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/store') }}">Store </a> |
            <span style="font-weight: bold;color:#9acd57">Subscription Cart</span> |
            <a href="{{ url($path.'/cart_products') }}">Product Cart </a> 
        </div>
    </div>

    <!-- CART-SUBSCRIPTION HEADER-->
    <div class="container">
        @include('store.cart.statusbar')
        <!-- CART CODE-->
        <div class="d-flex justify-content-center mb-5">
            <div class="row">
                <div class="card" style="width: 50rem;">
                    <div class="card-body">
                        <p class="diazo text-center" style="font-size: 26px; line-height:30px;">Subscription Cart</p> 
                            <table id="cart" class="table table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th style="width:54%">Product</th>
                                    <th style="width:16%">Price</th>
                                    <th style="width:12%">Quantity</th>
                                    <th style="width:18%" class="text-right">Subtotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($rows as $row)
                                    <tr class="row_{{$row['id']}}">
                                        <td class="product_{{$row['id']}}"><a href="{{ url($path.'/store/'.$row['slug']) }}">{{$row['product']}}</a></td>
                                        <td class="price_{{$row['id']}}">@if($row['standard'] == 0 || $row['standard'] == $row['price']) ${{$row['price']}} @else <span style="text-decoration: line-through;">${{$row['standard']}}</span> ${{$row['price']}} @endif</td>
                                        <td><input type="number" class="form-control qty-change" rel="{{$row['id']}}" value="{{$row['qty']}}"/></td>
                                        <td class="text-right tprice_{{$row['id']}}">${{$row['price']*$row['qty']}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="hidden-xs"></td>
                                        <td class="hidden-xs text-right text-sm discount-applied"></span>
                                    </tr>
                                    <tr class="table-borderless">
                                        <td colspan="3" class="hidden-xs"></td>
                                        <td class="hidden-xs text-right total-amount"><strong>Total: ${{$total}}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- <div class="ml-auto pb-2" style="width:16rem">
                                <div class="input-group">
                                    <input type="text" class="form-control discount-code" name="discount-code" placeholder="Discount Code">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary btn-sm apply-discount">Apply</button>
                                    </div>
                                </div>
                            </div> -->
                            <div class="text-right">
                                    <a href="{{ url($path.'/store') }}"><div class="btn btn-secondary"><i class="fa fa-angle-left"></i> Continue Shopping</div></a>
                                    <a href="#"><div class="btn btn-green checkout-link"> Continue to Payment <i class="fa fa-angle-right"></i></div></a>
                            </div>
                            <form action="{{url($path.'/checkout_subscriptions')}}" method="post" id="checkout-form">
                                <input type="hidden" name="discount" class="dcode" />
                                @csrf
                                @foreach($rows as $row)
                                <input type="hidden" name="id[{{$row['id']}}]" value="{{$row['id']}}" />
                                <input type="hidden" name="qty[{{$row['id']}}]" value="{{$row['qty']}}" />
                                <input type="hidden" name="type[{{$row['id']}}]" value="{{$row['type']}}" />
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var total_amount = {{$total}};
        $(document).ready(function(){
            $(".checkout-link").on("click", function(e){
                e.preventDefault();
                $("#checkout-form").submit();
            });

            $(document).on('click', '.qty-change', function(e){
                if($(this).val() < 0){
                    $(this).val(0);
                }
                if($(this).val() == 0){
                    if(!confirm('Delete this item from the cart?')){
                        $(this).val(1);
                        return;
                    }
                }
                e.preventDefault();
                updateRows();
            })

            $(".apply-discount").on('click', function(e){
                e.preventDefault();
                //check code and apply new price
                applyDiscount();
            });
        });

        function updateRows(){
            params = '';
            $('.qty-change').each(function(){
                params += 'qty['+$(this).attr('rel')+']='+$(this).val()+"&";
                if($(this).val() == 0){
                    $('.row_'+$(this).attr('rel')).remove();
                }
            });


            $.ajax({
                url: '{{url($path.'/update_subs_list')}}?'+params,
                type: 'get',
                success: function(rsp){
                    //update total
                    console.log('Update response:', rsp);
                    total_amount = rsp.total;
                    
                    //apply discount if it was entered
                    let discount = $('.discount-code').val();
                    if(discount && discount.length > 3) {
                        applyDiscount();
                    } else {
                        $('.total-amount').html(`<strong>Total: $${total_amount}</strong>`);
                    }
                    
                    //update all the rows
                    for(x = 0; x < rsp.data.length; x++){
                        $('.product_'+rsp.data[x].id).text(rsp.data[x].product);

                        let price = '$'+rsp.data[x].price;
                        if(rsp.data[x].standard != rsp.data[x].price){
                            price = `<span style="text-decoration: line-through;">$${rsp.data[x].standard}</span> $${rsp.data[x].price}`;
                        }
                        $('.price_'+rsp.data[x].id).html(price);
                        total_p = rsp.data[x].price * rsp.data[x].qty;
                        $('.tprice_'+rsp.data[x].id).text('$'+total_p);
                    }

                },
                fail: function(){
                    alert("Error");
                }
            });
        }

        function applyDiscount(){
            $.ajax({
                url: '{{url($path.'/check_discount')}}?current='+total_amount+'&code='+$('.discount-code').val(),
                type: 'get',
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                        $('.discount-applied').text('');
                        $('.total-amount').html(`<strong>Total: ${total_amount}</strong>`);
                    }else{
                        $('.dcode').val(response.discount.code);
                        if(response.discount.value.length){
                            $('.discount-applied').text('Discount: ' + response.discount.value);
                            $('.total-amount').html(`<strong>Total: <span style="text-decoration: line-through">${total_amount}</span> $${response.discount.new_total}</strong>`);
                        }else{
                            $('.discount-applied').text('');
                            $('.total-amount').html(`<strong>Total: ${total_amount}</strong>`);
                        }
                    }
                },
                fail: function(){
                    alert("Error");
                }
            });

        }
    </script>
</section>
@endsection
