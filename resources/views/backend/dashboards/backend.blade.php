@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <span style="font-weight: bold;color:#9acd57">Backend</span>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <!-- store -->
                <div class="card mb-2">
                    <div class="card-body">
                        <b>STORE</b><br/>
                        @if(auth()->user()->can('access-master') || auth()->user()->can('modify-products'))
                            <a href="{{ url($path.'/backend/products') }}"><img class="tinyImg" src="{{ asset('img/cards-med/box.png') }}" alt="PRODUCTS">
                            Products</a>
                        @endif
                        @if(auth()->user()->can('access-master') || auth()->user()->can('modify-discounts'))
                            <br><a href="{{ url($path.'/backend/discounts') }}"><img class="tinyImg" src="{{ asset('img/cards-med/money.png') }}" alt="DISCOUNTS">
                            Discount Codes</a>
                        @endif
                        @if(auth()->user()->can('access-master') || auth()->user()->can('modify-products'))
                            <br><a href="{{ url($path.'/backend/fulfillment') }}"><img class="tinyImg" src="{{ asset('img/cards-med/box.png') }}" alt="ORDERS">
                            Orders</a>
                        @endif
                        @if(auth()->user()->can('access-master'))
                            <br><a href="{{ url($path.'/renewals') }}"><img class="tinyImg" src="{{ asset('img/cards-med/money.png') }}" alt="Renewals">
                            Renewals</a>
                        @endif
                    </div>
                </div>
                <!-- resources -->
                <div class="card">
                    <div class="card-body">
                        <b>RESOURCES</b><br/>
                        @if(auth()->user()->can('access-master') || auth()->user()->can('modify-dictionaries'))
                            <a href="{{ url($path.'/backend/biological') }}"><img class="tinyImg" src="{{ asset('img/cards-med/bio.png') }}" alt="Biological Sex">
                            Biological Sex Glossary</a>
                            <br><a href="{{ url($path.'/backend/dictionaries') }}"><img class="tinyImg" src="{{ asset('img/cards-med/book.png') }}" alt="Dictionaries">
                            Dictionaries</a>
                            <br><a href="{{ url($path.'/backend/about#standards') }}"><img class="tinyImg" src="{{ asset('img/cards-med/globe.png') }}" alt="Dictionaries">
                            Educational Standards</a>
                            <br><a href="{{ url($path.'/backend/legal') }}"><img class="tinyImg" src="{{ asset('img/cards-med/science.png') }}" alt="Legal">
                            Legal Research</a>
                            <br><a href="{{ url($path.'/backend/pedagogy') }}"><img class="tinyImg" src="{{ asset('img/cards-med/science.png') }}" alt="Research">
                            Pedagogical Research</a>
                            <br><a href="{{ url($path.'/backend/statistics') }}"><img class="tinyImg" src="{{ asset('img/cards-med/science.png') }}" alt="Research">
                            Statistical Research</a>
                        @endif
                        @if(auth()->user()->can('access-master') || auth()->user()->can('modify-blog'))
                            <br><a href="{{ url($path.'/backend/blog') }}"><img class="tinyImg" src="{{ asset('img/cards-med/blog.png') }}" alt="Update Blog">
                            Update Blog</a>
                        @endif
                        @if(auth()->user()->can('access-master') || auth()->user()->can('modify-links'))
                            <br><a href="{{ url($path.'/backend/short-links') }}"><img class="tinyImg" src="{{ asset('img/cards-sm/w_classes.png') }}" alt="Research">
                            Short Link Creator</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-4">
                <!-- site -->
                <div class="card mb-2">
                    <div class="card-body">
                        <b>USER STATS</b>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-user"></i></th>
                                    <td>Total</td>
                                    <td class="text-right">
                                        @if(auth()->user()->can('access-master') || auth()->user()->can('modify-data'))
                                            <a href="{{ url($path.'/backend/master-users') }}">{{$totalUsers}}</a>
                                        @else
                                            {{$totalUsers}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-user"></i></th>
                                    <td>Current</td>
                                    <td class="text-end">{{$validUsers}}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-user"></i></th>
                                    <td>Trained</td>
                                    <td class="text-end">{{$trainedUsers}}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-file-arrow-down"></i></th>
                                    <td>Elementary users</td>
                                    <td class="text-end">{{$es}}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-file-arrow-down"></i></th>
                                    <td>Middle School users</td>
                                    <td class="text-end">{{$ms}}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-file-arrow-down"></i></th>
                                    <td>High School users</td>
                                    <td class="text-end">{{$hs}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <b>ORG STATS</b>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-users"></i></th>
                                    <td>Total
                                    <td class="text-end">
                                        @if(auth()->user()->can('access-master') || auth()->user()->can('modify-data'))
                                            <a href="{{ url($path.'/backend/master-orgs') }}">{{$orgs}}</a>
                                        @else
                                            {{$orgs}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-users"></i></th>
                                    <td>Using paid curricula</td>
                                    <td class="text-end">{{$paidOrgs}}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-users"></i></th>
                                    <td>Trained</td>
                                    <td class="text-end">{{$trainedOrgs}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row align-items-center mb-2">
                            <label for="yearSelect" class="col-auto col-form-label fw-bold mb-0">GROWTH STATS FOR</label>
                            <div class="col-auto">
                                    <select class="form-select" id="yearSelect" data-path="{{ $path }}">
                                    @for ($year = 2017; $year <= $currentYr; $year++)
                                        <option value="{{ $year }}" {{ $year == $currentYr ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-user-plus"></i></th>
                                    <td>Users Gained in <span class="selectedYearLabel">{{ $currentYr }}</span></td>
                                    <td class="text-end" id="ytdGainValue">{{$YTDgain}}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-chalkboard-user"></i></th>
                                    <td>Users Trained in <span class="selectedYearLabel">{{ $currentYr }}</span></td>
                                    <td class="text-end" id="ytdTrainedValue">{{$YTDtrained}}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-users-medical"></i></th>
                                    <td>Orgs Gained in <span class="selectedYearLabel">{{ $currentYr }}</span></td>
                                    <td class="text-end" id="ytdOrgsValue">{{$YTDorgs}}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-cart-circle-plus"></i></th>
                                    <td>Subscriptions in <span class="selectedYearLabel">{{ $currentYr }}</span></td>
                                    <td class="text-end" id="ytdPaidValue">{{$YTDpaid}}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="fa-solid fa-cart-circle-check"></i></th>
                                    <td>Renewals in <span class="selectedYearLabel">{{ $currentYr }}</span></td>
                                    <td class="text-end">TBD</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- people
                <div class="card">
                    <div class="card-body">
                        <b>PEOPLE</b><br/>
                        @if(auth()->user()->can('access-master') || auth()->user()->can('modify-team'))
                            <a href="{{ url($path.'/backend/team') }}"><img class="tinyImg" src="{{ asset('img/cards-med/team.png') }}" alt="Team">
                            Team Page (not publicly visible right now)</a>
                        @endif
                        still building, need for this may change
                        @if(auth()->user()->can('access-master') || auth()->user()->can('modify-data'))
                            <br><a href="{{ url($path.'/backend/effective') }}"><img class="tinyImg" src="{{ asset('img/cards-med/science.png') }}" alt="Participant's Data">
                            Participant Data</a>
                        @endif
                    </div>
                </div>-->
            </div>
            <div class="col-4">
                <!-- store -->
                <div class="card mb-2">
                    <div class="card-body">
                        <b>FACILITATORS</b><br/>
                            <a href="{{ url($path.'/backend/ms-roster') }}"> 2024-25 MS Class Roster</a>
                            <!--<br><a href="{{ url($path.'/backend/hs-roster') }}"> HS Class Roster</a>-->
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <b>EXTERNAL SITE LINKS</b>
                        <div class="d-flex justify-content-center align-items-md-center flex-wrap">
                            <a href="{{ url('https://www.activecampaign.com/login/') }}" target="_blank" ><img class="imgLink" src="{{ asset('img/partners/activeCampaign.png') }}" alt="ActiveCampaign"></a>
                            <a href="{{ url('https://quickbooks.intuit.com/sign-in-offer/') }}" target="_blank" ><img class="imgLink" src="{{ asset('img/partners/quickbooks.png') }}" alt="QuickBooks"></a>
                            <a href="{{ url('https://www.paypal.com/us/signin') }}" target="_blank" ><img class="imgLink" src="{{ asset('img/partners/paypal.png') }}" alt="PayPal"></a>
                            <a href="{{ url('https://blog.unhushed.org/admin') }}" target="_blank">Wordpress</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        input[type="checkbox"] {
            line-height: normal;
            margin: 0;
        }
        .modal-header {
            background-color:#f7f7f7;
        }
        .fakelink:hover{
            color: #265a8e;
        }
    </style>

    <script type="text/javascript">
        //Add Discounts
        function addDiscount(){
            let _url = $("#add_discount").attr('action');
            fd = $("#add_discount").serialize();
            $.ajax({
                url: _url,
                type: 'post',
                data: fd,
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert("Discount added!");
                        location.reload();
                        $("#addPackage").modal('hide');
                    }
                },
                fail: function(){ alert("Error"); }
            });
            return false;
        };
        //Moveable Modals
        $("#addDiscount").draggable({
            handle: ".modal-header"
        });

            // Year dropdown AJAX update (labels + metrics in one handler)
            $('#yearSelect').on('change', function(){
                const year = $(this).val();
                // Update visible year labels immediately
                $('.selectedYearLabel').text(year);
                const path = $(this).data('path');
                const url = '/' + path + '/backend/stats/' + year;
                $.get(url, function(data){
                    if(data){
                        $('#ytdGainValue').text(data.ytdGain);
                        $('#ytdTrainedValue').text(data.ytdTrained);
                        $('#ytdOrgsValue').text(data.ytdOrgs);
                        $('#ytdPaidValue').text(data.ytdPaid);
                    }
                }).fail(function(){
                    alert('Could not load stats for ' + year);
                });
            });
    </script>
</section>
@endsection





