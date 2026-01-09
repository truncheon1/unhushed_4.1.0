<!-- RATE IT -->
<section class="my-5 py-1 rounded"><!--bg-white-->
    <div class="text-center">
        @guest
        <h4>Login to review this product!</h4>
        <!-- need to add a cookie here to bring back on login -->
        <a href="{{ url($path.'/login') }}"><div class="btn btn-secondary">LOGIN</div></a>
        <a href="{{ url($path.'/register') }}"><div class="btn btn-secondary">REGISTER</div></a>
        @else
        <form>
            <p></p>
        </form>
        @endguest
    </div>
</section>
