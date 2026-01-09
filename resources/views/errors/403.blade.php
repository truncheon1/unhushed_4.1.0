@extends('layouts.app')
@section('content')
@php(
    $tenant = function(){
        $candidate = request()->route('path') ?? request()->segment(1);
        if(function_exists('get_path')){ return get_path($candidate); }
        return in_array($candidate, ['educators','organizations','professionals','parents','youth']) ? $candidate : 'educators';
    }()
)
<section>
    <div class="containerNoCrumb">
        <div class="mx-auto" style="max-width: 640px;">
            <div class="loginCard" style="max-width: 100%;">
                <div class="col">
                    <div class="card-body text-center">
                        <img src="{{ asset('img/octos/ollie-403.png') }}" alt="UN|HUSHED" class="mb-3">
                        <p class="diazo">403</p>
                        <p class="sketchnote-square" style="line-height:30px;">You don't have access to this curriculum resource.</p>
                        <p class="login-card-description" style="line-height:26px;">If you have a license, please <a href="{{ url($tenant.'/login') }}">log in</a> with the correct account, or renew an expired subscription.</p>
                        <div class="mt-3 d-flex flex-wrap justify-content-center gap-2">
                            <a class="btn btn-primary" href="{{ url($tenant.'/store') }}">View Store</a>
                            <a class="btn btn-outline-primary" href="{{ url($tenant.'/subscription-info') }}">Subscription Info</a>
                            <a class="btn btn-outline-secondary" href="{{ url($tenant.'/dashboard/curricula') }}">My Curricula</a>
                        </div>
                        @php($names = config('curricula.names'))
                        @if(is_array($names))
                            <div class="mt-4">
                                <p class="mb-1"><strong>Available Curricula:</strong></p>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    @foreach($names as $key => $val)
                                        <span class="badge bg-info text-dark">{{ $val }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <hr>
                        <p class="small text-muted mb-0">Need help? <a href="{{ url($tenant.'/support') }}">Contact Support</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
