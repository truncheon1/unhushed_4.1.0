
    <?php
    $path = $path ?? '';
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-12">
                <div class="support">
                    <a href="{{ url($path.'/support') }}">
                        <span class="hoverTxt">Click for support!</span>
                        <img src="{{ asset('img/cards-sm/w_feedback.png') }}" alt="Feedback">
                    </a>
                </div>
            </div>
        </div>
    </div>


<style>
    .support {
        position:fixed;
        bottom: 110px;
        right: 0;
        z-index: 1;
        height: 160px;
    }
    .support .hoverTxt {
        visibility: hidden;
        width: 120px;
        background-color: #fff;
        color: #01a7d3;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        /* Position the tooltip */
        position: absolute;
        z-index: 1;
        bottom: 60px;
        right: 118px;
        outline: solid 2px #265a8e;
        opacity: 0;
        transition: opacity 1s;
    }
    .support .hoverTxt::after {
        content: " ";
        position: absolute;
        bottom: 10px;
        left: 100%;
        margin-top: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent transparent #265a8e;
    }
    .support:hover .hoverTxt {
        visibility: visible;
        opacity: 1;
    }
</style>
