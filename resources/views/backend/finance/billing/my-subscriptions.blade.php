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
                <a href="{{ url($path.'/billing') }}"> Billing</a> |
                <span style="font-weight: bold;color:#9acd57">My Subscriptions</span>
            </div>
        </div>

        <!-- USERS TABLE -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6" style="text-transform: uppercase; font-weight: bold;"> MY SUBSCRIPTIONS</div>
                            <div class="col-md-6 text-right">
                                @php
                                    $hasActiveSubscription = !empty($details) && collect($details)->contains('s_status', 'active');
                                @endphp
                                @if($hasActiveSubscription)
                                    <a href="{{ url($path.'/billing-portal') }}" class="btn btn-primary">
                                        <i class="fa fa-credit-card"></i> Manage Payment Methods
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(empty($details))
                                <div class="text-center" style="width:600px">
                                    <p class="text-muted">You don't have any active subscriptions.
                                    <br/><a href="{{ url($path.'/store/curricula') }}" class="btn btn-primary mt-3">Browse Curriculum</a>
                                    </p>
                                </div>
                            @else
                            <table class="table table-hover" id="mySubTable">
                                <thead>
                                    <tr>
                                        <th>Curriculum</th>
                                        <th class="text-center">Since</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Used</th>
                                        <th class="text-center" colspan="2">Next Billing Cycle</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $detail)
                                        <tr>
                                            <td class="align-middle">
                                                @php
                                                    $words = explode(' ', $detail['name']);
                                                    $shortName = implode(' ', array_slice($words, 0, 2));
                                                @endphp
                                                <strong>{{ $shortName }}</strong>
                                            </td>
                                            <td class="text-center align-middle" style="white-space: nowrap;">
                                                {{ $detail['since'] }}
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($detail['s_status'] == 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($detail['s_status'] == 'canceled')
                                                    <span class="badge bg-pink">Canceled</span>
                                                @elseif($detail['s_status'] == 'reviewing')
                                                    <span class="badge bg-info">Reviewing</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($detail['s_status']) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-secondary">{{ $detail['total'] ?? 0 }}</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-primary">{{ $detail['used'] ?? 0 }}</span>
                                            </td>
                                            <td class="text-center align-middle" style="white-space: nowrap;">
                                                {{ $detail['renew'] }}
                                            </td>
                                            <td class="text-center align-middle">
                                                ${{ number_format($detail['due'] > 0 ? $detail['due'] : 99, 2) }}
                                            </td>
                                            <td class="text-center align-middle" style="white-space: nowrap;">
                                                @if($detail['s_status'] == 'canceled')
                                                    <a href="{{ url($path.'/billing-portal') }}" class="btn btn-sm btn-success">
                                                        <i class="fas fa-redo"></i> Resume
                                                    </a>
                                                @else
                                                    <a href="{{ url($path.'/billing-portal') }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-cog"></i> Manage
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .table td {
        vertical-align: middle;
    }
    .tinyImg {
        border-radius: 4px;
    }
    /* Multiple badges spacing */
    .badge + .badge {
        margin-left: 0.25rem;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $('#mySubTable').DataTable({
            lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
            pagingType: 'full_numbers',
            order: [[ 1, "desc" ]], // Order by "Since" column descending
            pageLength: 5,
            paging: true,
            info: true,
            searching: true,
            autoWidth: false,
            language: {
                search: "Search subscriptions:",
                lengthMenu: "Show _MENU_ subscriptions",
                info: "Showing _START_ to _END_ of _TOTAL_ subscriptions",
                infoEmpty: "No subscriptions to show",
                infoFiltered: "(filtered from _MAX_ total subscriptions)"
            }
        });
    });
</script>
@endsection






