@extends('layouts.app')

@section('content')
<section>
    <!-- BREADCRUMBS -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/arcade') }}">Arcade </a> |
            The Play's the Thing!: A Madlib Game
        </div>
    </div>


    <div class="mx-auto" style="max-width: 800px;">
        <div class="loginCard m-5" style="max-width: 100%;">
            <div class="col">
                <div class="card-body">
                    <p class="diazo">The Play's the Thing!</p>
                    <p class="text-justify">Pick parts of speech and fill them in the form below. Then click continue to head to the next page where these words will be used to fill in two stories.</p>
                        <form method="POST" action="{{ url($path.'/mad') }}" id="madlibs">
                            @csrf
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="mb-3 col-md-6">
                                        <label for="adj1" class="sr-only">adj1</label>
                                        <input type="text" name="adj1" id="adj1" class="form-control" placeholder="Adjective">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="noun1" class="sr-only">Noun</label>
                                        <input type="text" name="noun1" id="noun1" class="form-control" placeholder="Noun">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="mb-3 col-md-6">
                                        <label for="place" class="sr-only">Place</label>
                                        <input type="text" name="place" id="place" class="form-control" placeholder="Place">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="adj2" class="sr-only">Adjective</label>
                                        <input type="text" name="adj2" id="adj2" class="form-control" placeholder="Adjective">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="mb-3 col-md-6">
                                        <label for="adj3" class="sr-only">Adjective</label>
                                        <input type="text" name="adj3" id="adj3" class="form-control" placeholder="Adjective">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="noun2" class="sr-only">Liquid</label>
                                        <input type="text" name="noun2" id="noun2" class="form-control" placeholder="Noun">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="mb-3 col-md-6">
                                        <label for="verb" class="sr-only">Verb</label>
                                        <input type="text" name="verb" id="verb" class="form-control" placeholder="Verb">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="name" class="sr-only">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="mb-3 col-md-6">
                                        <label for="adj4" class="sr-only">Adjective</label>
                                        <input type="text" name="adj4" id="adj4" class="form-control" placeholder="Adjective">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="color" class="sr-only">Color</label>
                                        <input type="text" name="color" id="color" class="form-control" placeholder="Color">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="mb-3 col-md-6">
                                        <label for="food" class="sr-only">Food</label>
                                        <input type="text" name="food" id="food" class="form-control" placeholder="Food">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="veggie" class="sr-only">Vegetable</label>
                                        <input type="text" name="veggie" id="veggie" class="form-control" placeholder="Vegtable">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="mb-3 col-md-6">
                                        <label for="animal" class="sr-only">Animal</label>
                                        <input type="text" name="animal" id="animal" class="form-control" placeholder="Animal">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="adj5" class="sr-only">Adjective</label>
                                        <input type="text" name="adj5" id="adj5" class="form-control" placeholder="Adjective">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="mb-3 col-md-6">
                                        <label for="family" class="sr-only">Family</label>
                                        <input type="text" name="family" id="family" class="form-control" placeholder="Family member (mom, uncle, sister, etc.)">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="adj6" class="sr-only">Adjective</label>
                                        <input type="text" name="adj6" id="adj6" class="form-control" placeholder="Adjective">
                                    </div>
                                </div>
                                <input name="continue" id="continue" class="btn btn-secondary" type="submit" value="CONTINUE" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function(){
        $("#continue").on('click', function(e){
            e.preventDefault();
            _url = $("#madlibs").attr('action');
            _data = $("#madlibs").serialize();
            $.ajax({
                url: _url,
                type: 'POST',
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
                fail: function(){ alert("Error"); }
            });
        })
    })
</script>

@endsection
