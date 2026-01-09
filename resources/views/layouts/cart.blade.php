<!-- CART -->
<li class="nav-item cart">
    <a href="{{ url($path.'/carts') }}">
        <i class="fa-solid fa-cart-shopping"></i>
        @php 
            $subController = new \App\Http\Controllers\Store\CartSubscriptController(); 
            $subCount = $subController->subscription_count();
            $prodController = new \App\Http\Controllers\Store\CartProductController(); 
            $prodCount = $prodController->cart_count();
            $hasItems = ($subCount + $prodCount) > 0;
        @endphp
        @if($hasItems)
            <span class="cart-count"><i class="fas fa-box fa-sm"></i></span>
        @endif
    </a>
</li>

<style>
    .cart{
        padding: 0 0.5rem;
        display: flex;
        align-items: center;
    }
    .cart-count{
        font-size: 10px;
        display: inline-block;
        position: relative;
        left: -21px;
        top: -8px;
        color: #964B00;
        width: 20px;
        height: 20px;
        text-align: center;
        font-weight: bold;
    }
</style>