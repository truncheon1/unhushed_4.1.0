@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <a href="{{ url($path.'/parents') }}">Home </a> |
            <a href="{{ url($path.'/high-school-classes') }}"> High School Classes</a> |
            <span style="font-weight: bold;color:#9acd57">Registration</span>
        </div>
    </div>

    <!-- MS CLASS REG HEADER-->
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-auto m-auto">
                    <img class="img-s" src="{{ asset('img/octos/ollie-Qbox.png') }}" alt="Ollie the octopus holding a question box."></a>
                </div>
                <div class="col-auto px-1 mx-auto">
                    <p class="diazo">HS Registration</p>
                    <hr>
                    <p class="sketchnote-square" style="max-width:600px">
                        We're so glad you've chosen to register your youth in our up coming High School Sex Ed Classes.
                    </p>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-12 pt-3">
                    <!-- register for classes -->
                        @if('/parents/class-hs-reg')
                        @guest
                        But first! 
                        <br/>Please <a href="{{ url($path.'/login') }}">{{ __('LOGIN') }}</a> to your account, @if (Route::has('register')) or <a href="{{ url($path.'/register') }}">{{ __('REGISTER') }} </a> for a free account in order to gain access to the calendar page and other info.
                        @endif
                        @else
                        <p>Fill in this form to reserve your participant's spot. You'll be taken to the payment page next.</p>
                        <form method="POST" action="{{ url($path.'/hs-registration') }}" id="class-hs-reg-form">
                            @csrf
                            <p><b>Your info:</b></p>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="parent1_name" class="sr-only">Your full name</label>
                                    <input type="text" name="parent1_name" id="parent1_name" class="form-control" @if(auth()->user()) value="{{auth()->user()->name}}" @endif placeholder="Your full name">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6 col-sm-12">
                                    <label for="parent1_email" class="sr-only">You email</label>
                                    <input type="text" name="parent1_email" id="parent1_email" class="form-control" @if(auth()->user()) value="{{auth()->user()->email}}" @endif placeholder="Your email address">
                                </div>
                                <div class="col-md-6 col-sm-12">    
                                    <label for="parent1_phone" class="sr-only">Your Phone</label>
                                    <input type="text" name="parent1_phone" id="parent1_phone" class="form-control" placeholder="Your phone number">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="street" class="sr-only">Street</label>
                                    <input type="text" name="street" id="street" class="form-control" placeholder="Your mailing address">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6 col-sm-12">
                                    <label for="city" class="sr-only">City</label>
                                    <input type="text" name="city" id="city" class="form-control" placeholder="City">
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label for="state" class="sr-only">State</label>
                                    <select class="form-control" name="state" id="state">
                                        @include('layouts.helpers.states')
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label for="zip" class="sr-only">Zip</label>
                                    <input type="text" name="zip" id="zip" class="form-control" placeholder="Zipcode">
                                </div>
                            </div>
                            <p><b>Additonal legal guardian(s):</b></p>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="additional_guardian" class="sr-only">Additonal legal guardian</label>
                                    <select class="form-control" name="additional_guardian" id="additional_guardian" onchange="yesChecked(this);">
                                        <option value="">Select</option>
                                        <option value="no">No, there is no one else with LEGAL CUSTODY.</option>
                                        <option id="addOption" value="yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div id="yes" style="display:none;">
                                <p><b>Your child's second guardian's info:</b></p>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label for="parent2_name" class="sr-only">Their full name</label>
                                        <input type="text" name="parent2_name" id="parent2_name" class="form-control" placeholder="Their full name">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label for="parent2_email" class="sr-only">Their email</label>
                                        <input type="text" name="parent2_email" id="parent2_email" class="form-control" placeholder="Their email address">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="parent2_phone" class="sr-only">Their phone</label>
                                        <input type="text" name="parent2_phone" id="parent2_phone" class="form-control" placeholder="Their phone number">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <span style="font-size: 11px">*Please note if your child has more than two legal guardians you can email us at info@unhushed.org to add them.</span>
                                </div>
                            </div>
                            <p><b>Your child's info:</b></p>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="youth_name" class="sr-only">Their full name</label>
                                    <input type="text" name="youth_name" id="youth_name" class="form-control" placeholder="Their full name">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3 col-sm-6">
                                    <label for="age" class="sr-only">Age</label>
                                    <input type="number" name="age" id="age" class="form-control" placeholder="Age" min="14" max="17">
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label for="grade" class="sr-only">Grade</label>
                                    <input type="number" name="grade" id="grade" class="form-control" placeholder="Grade" min="9" max="12">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6 col-sm-12">
                                    <label for="gender_identity" class="sr-only">Gender Identity</label>
                                    <select class="form-control" name="gender_identity" id="gender_identity">
                                        <option value="" selected>Gender Identity    </option>
                                        <option value="girl">Girl           </option>
                                        <option value="boy">Boy             </option>
                                        <option value="nonbinary">Nonbinary </option>
                                        <option value="trans">Trans         </option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="pronouns" class="sr-only">Their pronouns</label>
                                    <input type="text" name="pronouns" id="pronouns" class="form-control" placeholder="Their pronouns">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6 col-sm-12">
                                    <label for="kid_email" class="sr-only">Their email</label>
                                    <input type="text" name="kid_email" id="kid_email" class="form-control" placeholder="Their email address">
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="kid_phone" class="sr-only">Their phone</label>
                                    <input type="text" name="kid_phone" id="kid_phone" class="form-control" placeholder="Their phone number">
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <input name="continue" id="continue" class="btn btn-secondary" type="submit" value="REGISTER" data-bs-toggle="button" aria-pressed="false" autocomplete="off">
                            </div>
                        </form>
                        @endguest
                    @endif
                </div>
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
        $("#continue").on('click', function(e){
            e.preventDefault();
            _url = $("#class-hs-reg-form").attr('action');
            _data = $("#class-hs-reg-form").serialize();
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
    function yesChecked(nameSelect)
    {
        if(nameSelect){
            addOptionValue = document.getElementById("addOption").value;
            if(addOptionValue == nameSelect.value){
                document.getElementById("yes").style.display = "block";
            }
            else{
                document.getElementById("yes").style.display = "none";
            }
        }
        else{
            document.getElementById("yes").style.display = "none";
        }
    }
</script>

@endsection