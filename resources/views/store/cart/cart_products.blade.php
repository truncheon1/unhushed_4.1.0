@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/store') }}">Store </a> |
            <a href="{{ url($path.'/cart_subscriptions') }}">Subscription Cart </a> |
            <span style="font-weight: bold;color:#9acd57">Product Cart</span>
        </div>
    </div>

    <!-- CART-PRODUCT HEADER-->
    <div class="container">
        @include('store.cart.statusbar')
        <!-- CART CODE-->
        <div class="d-flex justify-content-center mb-5">
            <div class="row">
                <div class="card" style="width: 50rem;">
                    <div class="card-body">
                        <p class="diazo text-center" style="font-size: 26px; line-height:30px;">Product Cart</p>  
                            <form action="{{url($path.'/cart/update')}}" method="post" id="updateCart">
                                @csrf
                                <input type="hidden" name="checkout" class="checkout" value="0" />
                                <table id="cart" class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th style="width:50%">Product</th>
                                        <th style="width:16%">Price</th>
                                        <th style="width:16%">Quantity</th>
                                        <th style="width:18%" class="text-right">Subtotal</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rows as $row)
                                        <tr class="row_{{$row['id']}}">
                                            <td class="product_{{$row['id']}}"><a href="{{ url($path.'/store/'.$row['slug']) }}">{!! nl2br(e($row['product'])) !!}</a></td>
                                            <td class="price_{{$row['id']}} text-center">${{number_format($row['price'], 2)}}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <input type="number" value="{{$row['qty']}}" name="qty[{{$row['id']}}]" min="0" class="form-control qty" rel="{{$row['id']}}" @if(in_array($row['category'], [0])) data-bs-rel="{{$row['reference_id']}}" @endif style="max-width:65px;">
                                                    <a href="#" class="text-danger ms-2 delete-item" data-id="{{$row['id']}}" title="Remove item"><i class="fa fa-trash"></i></a>
                                                </div>
                                            </td>
                                            <td class="text-right cost_{{$row['id']}}">${{number_format($row['cost'], 2)}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-borderless">
                                            <td colspan="3" class="hidden-xs"></td>
                                            <td class="hidden-xs text-right text-sm discount-applied"></td>
                                        </tr>
                                        <tr class="table-borderless">
                                            <td colspan="3" class="hidden-xs"></td>
                                            <td class="hidden-xs text-right total-amount"><strong>Total: ${{$total}}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="ml-auto pb-2" style="width:16rem">
                                    <div class="input-group">
                                        <input type="text" class="form-control discount-code" name="discount-code" placeholder="Discount Code">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary btn-sm apply-discount">Apply</button>
                                        </div>
                                    </div>
                                    <div class="error-text" style="color: #f00"></div>
                                </div>
                            </form>
                        <div class="text-right">
                            <a href="{{ url($path.'/store') }}"><div class="btn btn-secondary"><i class="fa fa-angle-left"></i> Continue Shopping</div></a>
                            @if(count($rows))
                            <!--<a href="#" class="btn btn-secondary update-cart"><i class="fa fa-sync"></i> Update Cart</a>-->
                            <a href="#"><div class="btn btn-green checkout-cart"> Continue to Payment <i class="fa fa-angle-right"></i></div></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css">
        a.btn{
            color: #fff;
        }
        .product {
            background-color: none !important;
        }
    </style>

    <script type="text/javascript">
        total_amount = {{$total}};
        discount = '{{$discount}}';

        $(document).ready(function(){
            $('.discount-code').val(discount);
            if(discount.length){
                applyDiscount();
            }
            $('.delete-item').on('click', function(e){
                e.preventDefault();
                if(confirm('Do you want to delete this product from the shopping cart?')){
                    let itemId = $(this).data('id');
                    console.log('Deleting item:', itemId);
                    // Set quantity to 0 to trigger deletion on server
                    $('.qty[rel="' + itemId + '"]').val(0);
                    console.log('Qty set to 0, updating cart...');
                    // Update cart on server first, then remove from display
                    update_cart().then(function(response){
                        console.log('Cart update response:', response);
                        // Remove the row from display
                        $('.row_' + itemId).remove();
                        // Recalculate total
                        update_total();
                    }).catch(function(error){
                        console.error('Delete failed:', error);
                        alert('Failed to delete item. Please try again.');
                        // Restore the quantity if delete failed
                        location.reload();
                    });
                }
            });
            $('.rem').on('click', function(){
                if(confirm('Remove this product?')){
                    _id = $(this).attr('rel');
                    $('.rems').val($('.rems').val()+","+_id);
                    $("#row_"+_id).remove();
                }
            });
            $('.qty').on('change', function(){
                if($(this).val() <= 0){
                    if(confirm('Do you want to delete this product from the shopping cart?')){
                        update_cart();
                        $(this).parent().parent().remove();
                        update_total();
                        return;
                    }else{
                        $(this).val(1);
                    }
                }
                if($(this).attr('data-bs-rel')){
                    get_price($(this));
                }else{
                    _id = $(this).attr('rel');
                    for (i in prices){
                        if (prices[i].id == _id) {
                            _price = prices[i].price;
                        }
                    }
                    new_price = _price * $(this).val();
                    $(".cost_" + _id).html("$" + new_price.toFixed(2));
                    update_total();
                }
                update_cart();
            });
            $('.update-cart').on('click', function(){
                $("#updateCart").submit();
            });
            $(".checkout-cart").on('click', function(e){
                e.preventDefault();
                // Update cart first, then redirect to Stripe checkout
                update_cart().then(function(){
                    window.location.href = '{{url($path.'/checkout')}}';
                }).catch(function(error){
                    console.error('Checkout failed:', error);
                    alert('Failed to update cart. Please try again.');
                });
            });
            $(".apply-discount").on('click', function(e){
              e.preventDefault();
               applyDiscount();
            });
        });

        prices = [];
        @foreach($rows as $row)
            prices.push({
                id: {{$row['id']}},
                price: {{$row['price']}}
            });
        @endforeach

        function get_price(obj){
            collection = $(obj).attr('data-bs-rel');
            qty = $(obj).val();
            let _id = $(obj).attr('rel');

            $.get({
                url: '{{url($path."/store/")}}' + `/${collection}/calc_total_products`,
                data: 'qty=' + qty,
                success: function (response) {
                    console.log(response);
                    if (response.error == true) {
                        alert(response.message);
                        return;
                    }
                    console.log(_id, $(".price_" + _id).html());
                    $(".price_" + _id).html("$" + response.price);
                    $(".cost_" + _id).html("$" + response.total);
                    for(p in prices){
                        if(prices[p].id == _id){
                            prices[p].price = response.price;
                        }
                    }
                    update_total();
                },
                fail: function () {
                    alert("Error");
                }
            });
        }

        async function update_cart(){
            form = $("#updateCart").serialize();
            _url = $("#updateCart").attr('action');
            try {
                rsp = await $.post({
                    url: _url,
                    data: form
                });
                console.log('Cart updated:', rsp);
                return rsp;
            } catch(error) {
                console.error('Cart update failed:', error);
                throw error;
            }
        }

        function update_total(){
            qty = {};
            total = 0;
            $('.qty').each(function(){
                _id = $(this).attr('rel');
                qty[_id] = $(this).val();
            })
            for(i in prices){
                total += prices[i].price * (qty[prices[i].id] ?? 0);
            }
            $('.total-amount').html(`<strong>Total: ${total.toFixed(2)}</strong>`);
            total_amount = total.toFixed(2);
            discount = $('.discount-code').val();
            if(discount && discount.length){
                applyDiscount();
            }
        }

        function applyDiscount(){
            $('.error-text').text("");
            $.ajax({
                url: '{{url($path.'/check_discount')}}?current='+total_amount+'&code='+$('.discount-code').val(),
                type: 'get',
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        $('.error-text').text(response.message);
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
