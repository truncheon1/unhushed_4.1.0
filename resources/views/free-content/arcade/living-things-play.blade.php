<!DOCTYPE html>
<head>
    <title>Living Things: A Memory Game</title>
    <meta name=viewport content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="{{url('css/games/living.css')}}">
</head>

<body>
    <section class="memory-game">
        <div class="memory-card" data-bs-framework="birth">
            <img class="front-face" src="{{ asset('img/games/living/live-birth.png') }}" alt="Live Birth" />
            <img class="back-face" src="{{ asset('img/games/living/back.png') }}" alt="Card Back" />
        </div>
        <div class="memory-card" data-bs-framework="birth">
            <img class="front-face" src="{{ asset('img/games/living/human.png') }}" alt="Live Birth" />
            <img class="back-face" src="{{ asset('img/games/living/back.png') }}" alt="Card Back" />
        </div>

        <div class="memory-card" data-bs-framework="binary">
            <img class="front-face" src="{{ asset('img/games/living/binary.png') }}" alt="Binary Fission" />
            <img class="back-face" src="{{ asset('img/games/living/back.png') }}" alt="Card Back" />
        </div>
        <div class="memory-card" data-bs-framework="binary">
            <img class="front-face" src="{{ asset('img/games/living/bacillus.png') }}" alt="Vue" />
            <img class="back-face" src="{{ asset('img/games/living/back.png') }}" alt="Card Back" />
        </div>

        <div class="memory-card" data-bs-framework="budding">
            <img class="front-face" src="{{ asset('img/games/living/budding.png') }}" alt="Budding" />
            <img class="back-face" src="{{ asset('img/games/living/back.png') }}" alt="Card Back" />
        </div>
        <div class="memory-card" data-bs-framework="budding">
            <img class="front-face" src="{{ asset('img/games/living/jellies.png') }}" alt="Jellies" />
            <img class="back-face" src="{{ asset('img/games/living/back.png') }}" alt="Card Back" />
        </div>

        <div class="memory-card" data-bs-framework="fragmentation">
            <img class="front-face" src="{{ asset('img/games/living/fragmentation.png') }}" alt="Fragmentation" />
            <img class="back-face" src="{{ asset('img/games/living/back.png') }}" alt="Card Back" />
        </div>
        <div class="memory-card" data-bs-framework="fragmentation">
            <img class="front-face" src="{{ asset('img/games/living/flatworm.png') }}" alt="Flatworm" />
            <img class="back-face" src="{{ asset('img/games/living/back.png') }}" alt="Card Back" />
        </div>

        <div class="memory-card" data-bs-framework="seeds">
            <img class="front-face" src="{{ asset('img/games/living/seeds.png') }}" alt="Seeds & Cones" />
            <img class="back-face" src="{{ asset('img/games/living/back.png') }}" alt="Card Back" />
        </div>
        <div class="memory-card" data-bs-framework="seeds">
            <img class="front-face" src="{{ asset('img/games/living/flower.png') }}" alt="Flower" />
            <img class="back-face" src="{{ asset('img/games/living/back.png') }}" alt="Card Back" />
        </div>

        <div class="memory-card" data-bs-framework="eggs">
            <img class="front-face" src="{{ asset('img/games/living/egg.png') }}" alt="Egg" />
            <img class="back-face" src="{{ asset('img/games/living/back.png') }}" alt="Card Back" />
        </div>
        <div class="memory-card" data-bs-framework="eggs">
            <img class="front-face" src="{{ asset('img/games/living/bee.png') }}" alt="Bee" />
            <img class="back-face" src="{{ asset('img/games/living/back.png') }}" alt="Card Back" />
        </div>
    </section>

    <div class="reset">
        <button class="btn btn-secondary btn-replay" onClick="refreshPage()">REPLAY</button></div>
    </div>


    <script src="{{url('js/g-living/living.js')}}"></script>
    <script>
        function refreshPage(){
            window.location.reload();
        }
    </script>
</body>
</html>
