
<!-- THERMOMETER -->
<div class="box2">
    <div class="row p-1">
        <div class="col-12">
            <div class="tube">
                <div class="fill"></div>
            </div>
            <div class="tube-labelsG">
                <div class="arrow-leftG"></div>
                <div class="label-goal">$ 5,000</div>
            </div>
            <div class="tube-labels">
                <div class="arrow-leftR"></div>
                <div class="label-raised">$ 1,500</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="mx-auto p-1">
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input name="donate" id="donate" class="btn btn-secondary" type="submit" value="DONATE" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
                <input type="hidden" name="cmd" value="_s-xclick" >
                <input type="hidden" name="hosted_button_id" value="VFH5ZPLVX5352" >
            </form>
        </div>
    </div>
</div>

<style>
    /*iPhone*/
    @media screen and (max-width: 780px){
        .box2 {
            margin: 10px auto;
        }
        .center{
            text-align: center;
            margin: 0 auto;
        }
    }
    /*desktop*/
    @media all and (min-width: 780px) {
        .box2 {
            z-index: 1;
            position: fixed;
            top: 9em;
            right: 1em;
        }
        .right {
            float: right;
        }
        .left {
            float: left;
        }
    }
    .box2 {
            width: 160px;
            height: 500px;
            padding: 10px 5px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 0 1px #e3e7f1;
    }
    .tube {
        margin: 0 0 8px 12px;
        height: 400px;
        width: 60px;
        float: left;
        border-radius: 60px;
        text-align: center;
        -moz-box-shadow:    inset 0 0 10px #ccc;
        -webkit-box-shadow: inset 0 0 10px #ccc;
        box-shadow:         inset 0 0 10px #ccc;
        overflow: hidden;
        transform:rotate(180deg);
        padding-bottom: 280px; /* padding from 390-0 indicated funding goal */
    }
    .tube-labels {
        margin: 0 auto;
        height: 390px;
        width: 70px;
        float: left;
        padding-top: 260px; /* padding above -10 */
        background-repeat: no-repeat;
    }
    .tube-labelsG {
        margin: 0 auto;
        height: 10px;
        width: 70px;
        float: left;
        padding-top: 10px; /* padding above -10 */
        background-repeat: no-repeat;
    }
    .label-goal{
        height: 20px;
        width: 60px;
        background-color: #265a8e;
        color: #fff;
        font-weight: bold;
        float: left;
    }
    .label-raised{
        height: 20px;
        width: 60px;
        background-color: #265a8e;
        color: #fff;
        font-weight: bold;
        float: left;
    }
    .arrow-leftG {
        width: 0;
        height: 0;
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
        border-right:10px solid #265a8e;
        float: left;
    }
    .arrow-leftR {
        width: 0;
        height: 0;
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
        border-right:10px solid #265a8e;
        float: left;
    }
    .fill {
        /* fallback */
        background-color: #9acd57;

        /* Safari 4-5, Chrome 1-9 */
        background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#c2ec8b), to(#9acd57));

        /* Safari 5.1, Chrome 10+ */
        background: -webkit-linear-gradient(bottom, #c2ec8b, #9acd57);

        /* Firefox 3.6+ */
        background: -moz-linear-gradient(bottom, #c2ec8b, #9acd57);

        /* IE 10 */
        background: -ms-linear-gradient(bottom, #c2ec8b, #9acd57);

        /* Opera 11.10+ */
        background: -o-linear-gradient(bottom, #c2ec8b, #9acd57);
    }

    .tube h3.goal, .tube h3.raised {
        font-family: Arial,sans-serif;
        font-size: 2em;
        font-weight: 600;
        line-height: 50px;
        margin: 0;
        padding: 0;
        text-align: center;
        display: inline;
    }

    .tube h3.raised {
        color: #fff;
        margin: 14px 25px 0 50px;
        padding: 0 25px 0 0;
    }

    .tube h3.goal {
        color: #b2b2b2;
        text-align: center;
    }

    .label-raised, .arrow-leftR {
        -webkit-animation: fadein 4s; /* Safari and Chrome */
        -moz-animation: fadein 4s; /* Firefox */
        -ms-animation: fadein 4s; /* Internet Explorer */
        -o-animation: fadein 4s; /* Opera */
        animation: fadein 4s;
    }

    @keyframes fadein {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    /* Firefox */
    @-moz-keyframes fadein {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    /* Safari and Chrome */
    @-webkit-keyframes fadein {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    /* Internet Explorer */
    @-ms-keyframes fadein {
        from { opacity: 0; }
        to   { opacity: 1; }
    }​

    /* Opera */
    @-o-keyframes fadein {
        from { opacity: 0; }
        to   { opacity: 1; }
    }​

    .tube h3.goal {
        float: right;
        display: inline;
        padding: 0 25px 0 0;
        text-align: center;
    }

    .tube div {
        -webkit-animation: progress-bar 2s ease forwards;
        -moz-animation: progress-bar 2s ease forwards;
        -o-animation: progress-bar 2s ease forwards;
        animation: progress-bar 2s ease forwards;
    }

    @-webkit-keyframes progress-bar {
        from { height: 0%; }
        to { height: 100%; }
    }

    @-moz-keyframes progress-bar {
        from { height: 0%; }
        to { height: 100%; }
    }

    @-o-keyframes progress-bar {
        from { height: 0%; }
        to { height: 100%; }
    }

    @keyframes progress-bar {
        from { height: 0%; }
        to { height: 100%; }
    }

</style>
