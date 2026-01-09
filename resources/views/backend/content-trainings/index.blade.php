@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">

        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Dashboard</a> |
                <span style="font-weight: bold;color:#9acd57">Trainings</span>
            </div>
        </div>

        <!-- Training Card -->
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    CURRICULA TRAININGS
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    <!-- paid shown to users with access -->
                    @forelse($trainings as $training)
                        @if(str_contains($training->name, 'Curriculum'))
                        <div class="smCard">
                            <a href="{{ url($path.'/dashboard/trainings/'.($training->assigned_variant->deliver_slug ?? $training->deliver_slug)) }}"><img class="smImg" src="{{ asset('uploads/products/'.$training->primary_image_path) }}" alt="{{ $training->name }}"></a>
                            <div class="smText">{{ $training->name }}</div>
                        </div>
                        @endif
                    @empty
                    <div class="smCard">
                        <div class="smText">Visit our store to <a href="{{ url($path.'/trainings') }}">purchase trainings</a> today!</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    HANDBOOK TRAININGS
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    @forelse($trainings as $training)
                        @if(!str_contains($training->name, 'Curriculum') && (str_contains($training->name, 'Handbook')))
                        <div class="smCard">
                            <a href="{{ url($path.'/dashboard/trainings/'.($training->assigned_variant->deliver_slug)) }}"><img class="smImg" src="{{ asset('uploads/products/'.$training->primary_image_path) }}" alt="{{ $training->name }}"></a>
                            <div class="smText">{{ $training->name }}</div>
                        </div>
                        @endif
                    @empty
                    <div class="smCard">
                        <div class="smText">Visit our store to <a href="{{ url($path.'/trainings') }}">purchase trainings</a> today!</div>
                    </div>
                    @endforelse
                    <!-- HCWP-->
                    @if(auth()->user()->can('access-hcwp'))
                    <div class="smCard">
                        <a href="{{ url($path.'/dashboard/trainings/child-welfare-providers') }}"><img class="smImg" src="{{ asset('img/products/m-HCWP.png') }}" alt="Handbook for Childwelfare Providers"></a>
                        <div class="smText">Handbook for Childwelfare Providers</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    MORE AMAZING TRAININGS
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    @forelse($trainings as $training)
                        @if(!str_contains($training->name, 'Curriculum') && (!str_contains($training->name, 'Handbook')))
                        <div class="smCard">
                            <a href="{{ url($path.'/dashboard/trainings/'.($training->assigned_variant->deliver_slug)) }}"><img class="smImg" src="{{ asset('uploads/products/'.$training->primary_image_path) }}" alt="{{ $training->name }}"></a>
                            <div class="smText">{{ $training->name }}</div>
                        </div>
                        @endif
                    @empty
                    <div class="smCard">
                        <div class="smText">Visit our store to <a href="{{ url($path.'/trainings') }}">purchase trainings</a> today!</div>
                    </div>
                    @endforelse
                    <!-- Tech Session *free to all users-->
                    @if(!auth()->user()->is_assigned_training(5) && !auth()->user()->has_completed_training(7))
                    <div class="smCard">
                        <a href="{{ url($path.'/dashboard/trainings/bonus-tech-session') }}"><img class="smImg" src="{{ asset('img/products/wt-tech.png') }}" alt="Tech Session"></a>
                        <div class="smText">Bonus Tech Session</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    WEBSITE TRAINING
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div class="d-flex align-items-md-center flex-wrap pb-2">
                    <!-- Head of School Training *free to all head users-->
                    @if(auth()->user()->can('access-master') || auth()->user()->can('modify-users'))
                    <div class="smCard">
                        <a href="{{ url($path.'/dashboard/trainings/head-school-session') }}"><img class="smImg" src="{{ asset('img/cards-med/teacher.png') }}" alt="Head of School Website Training"></a>
                        <div class="smText">Head of School Website Training</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
