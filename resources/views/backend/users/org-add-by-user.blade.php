@extends('layouts.app')
@section('content')
<section>
    <!-- breadcrumbs -->
    <div class="crumbBar">
        <div class="breadcrumbs">
            <span style="color:#cccccc">Store</span> |
            <span style="font-weight: bold;color:#9acd57">Create your organization</span>
        </div>
    </div>

    <!-- Centered Alert Overlay -->
    @if(session('email_domain'))
    <div id="orgAlertContainer">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>Note:</strong> No organization was found matching your email domain (<strong>{{ session('email_domain') }}</strong>). Please create your organization to complete your subscription setup.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <div class="container">
        @include('store.cart.statusbar')
        <!-- ORG ADD BY USER CODE-->
        <div class="d-flex justify-content-center mb-5">
            <div class="row">
                <div class="card" style="width: 50rem;">
                    <div class="card-body">
                        <p class="diazo text-center" style="font-size: 26px; line-height:30px;">Create Your Organization</p> 
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    <form action="{{ url($path.'/subscription/create-organization') }}" method="POST" id="createOrgForm">
                        @csrf
                        <!-- Organization Name -->
                        <div class="row mb-3">
                            <label for="name" class="col-form-label col-md-3 text-md-end">Organization Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', session('user_name')) }}" required autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <!-- Pedagogy & Grades -->
                        <div class="row mb-3">
                            <label for="pedagogy" class="col-form-label col-md-3 text-md-end">Pedagogy</label>
                            <div class="col-md-4">
                                <select class="form-control" name="pedagogy" id="pedagogy">
                                    <option value="">Select Pedagogy</option>
                                    @include('layouts.helpers.pedagogy')
                                </select>
                            </div>
                            <label for="grades" class="col-form-label col-md-2 text-md-end">Grades</label>
                            <div class="col-md-3">
                                <select class="form-control" name="grades" id="grades">
                                    <option value="">Select Grades</option>
                                    @include('layouts.helpers.grades')
                                </select>
                            </div>
                        </div>
                        <!-- Country -->
                        <div class="row mb-3">
                            <label for="country" class="col-form-label col-md-3 text-md-end">Country</label>
                            <div class="col-md-9">
                                <select class="form-control" name="country" id="country">
                                    <option value="">Select Country</option>
                                    <option value="US" selected>United States</option>
                                    <option value="CA">Canada</option>
                                    <option value="" disabled>──────────</option>
                                    @include('layouts.helpers.countries-iso')
                                </select>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="row mb-3">
                            <label for="address" class="col-form-label col-md-3 text-md-end">Address</label>
                            <div class="col-md-9">
                                <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" placeholder="Street address">
                            </div>
                        </div>

                        <!-- City -->
                        <div class="row mb-3">
                            <label for="city" class="col-form-label col-md-3 text-md-end">City</label>
                            <div class="col-md-4">
                                <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}">
                            </div>
                            <label class="col-form-label col-md-2 text-md-end" id="state-label">State</label>
                            <div class="col-md-3">
                                <select class="form-control" name="state" id="state">
                                    <option value="">Select State</option>
                                    @include('layouts.helpers.states')
                                </select>
                                <input type="text" class="form-control" name="province" id="province" value="{{ old('province') }}" placeholder="Province/Region" style="display:none;">
                            </div>
                        </div>

                        <!-- Postal Code -->
                        <div class="row mb-3">
                            <label for="zip" class="col-form-label col-md-3 text-md-end">Postal Code</label>
                            <div class="col-md-4">
                                <input id="zip" type="text" class="form-control" name="zip" value="{{ old('zip') }}" placeholder="ZIP or postal code">
                            </div>
                        </div>

                        <!-- Website URL -->
                        <div class="row mb-3">
                            <label for="url" class="col-form-label col-md-3 text-md-end">Website URL</label>
                            <div class="col-md-9">
                                <input id="url" type="text" class="form-control" name="url" value="{{ session('email_domain') }}" placeholder="https://">
                            </div>
                        </div>

                        <!-- Email Match Domain - Hidden, auto-populated -->
                        @php
                            $emailDomain = session('email_domain');
                            $publicDomains = ['gmail.com', 'yahoo.com', 'yahoo.co.uk', 'hotmail.com', 'outlook.com', 'aol.com', 'icloud.com', 'protonmail.com', 'mail.com'];
                            $isPublicDomain = $emailDomain && in_array(strtolower($emailDomain), $publicDomains);
                            $autoFillDomain = $emailDomain && !$isPublicDomain ? $emailDomain : '';
                        @endphp
                        
                        <input type="hidden" name="email_match" id="email_match" value="{{ old('email_match', $autoFillDomain) }}">

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-md-9 offset-md-3">
                                <button type="submit" class="btn btn-secondary" id="submitBtn">
                                    <span id="submitText">Create Organization</span>
                                    <span id="submitLoading" class="d-none">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Creating...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        #orgAlertContainer {
            position: fixed;
            top: 25%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1060;
            width: 90%;
            max-width: 600px;
            pointer-events: none;
        }
        #orgAlertContainer .alert {
            pointer-events: auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 768px) {
            #orgAlertContainer {
                width: 95%;
            }
        }
    </style>

    <script>
        $(document).ready(function(){
            // Country change handler
            $('#country').on('change', function(){
                const country = $(this).val();
                if(country === 'US'){
                    $('#state-label').text('State');
                    $('#state').show().prop('required', false);
                    $('#province').hide().prop('required', false).val('');
                } else {
                    $('#state-label').text('Province/Region');
                    $('#state').hide().prop('required', false).val('');
                    $('#province').show().prop('required', false);
                }
            });
            
            // Initialize on page load
            $('#country').trigger('change');
            
            // Form submission with loading state
            $('#createOrgForm').on('submit', function(){
                $('#submitText').addClass('d-none');
                $('#submitLoading').removeClass('d-none');
                $('#submitBtn').prop('disabled', true);
            });
        });
    </script>
</section>
@endsection
        