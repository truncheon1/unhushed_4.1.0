<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/app.css') }}?version={{ config('app.version') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{url('js/jquery-ui/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{url('css/games/lifespan.css')}}">
    <link rel="stylesheet" href="https://use.typekit.net/blx5ubk.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{url('js/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="{{url('js/g-timeline/timeline.js')}}"></script>
</head>

<body>
    <span style="font-family: agency-fb, sans-serif;">
        <div class="description">
            <p class="text-center" style="font-size:24px">Sort the "Life Events" on the right to match the age at which you think it happens on average in the United States.</p>
        </div>

        <div id="gameTimeline">
            <div id="gameTitle1">AGES</div>
            <div id="gameTitle2">LIFE EVENTS <i class="fas fa-sort-alt"></i></div>
            <div id="ageBoxes"></div>
            <div id="answerBoxes"></div>
        </div>
    </span>
    <div id="checkAnswers">
        <button class="btn btn-secondary" onClick="getSubmited()">CHECK ANSWERS</button>
    </div>
    <div id="successMessage">
        You answered  <span class="fcount"></span> out of 16 correctly! Want to play again?<br>
        <button class="btn btn-secondary" onClick="gameInit()">RESET</button>
    </div>

</body>
</html>
