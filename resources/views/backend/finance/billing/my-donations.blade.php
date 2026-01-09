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
                <span style="font-weight: bold;color:#9acd57">My Donations</span>
            </div>
        </div>

        <!-- USERS TABLE -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6" style="text-transform: uppercase; font-weight: bold;">MY DONATIONS ({{ $allDonations->count() }})</div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-primary" onclick="openDonationModal()">
                                    <i class="fas fa-heart"></i> Make a Donation
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="min-width:600px">
                        @if($recurringDonations->count() > 0)
                        <!-- Recurring Donations Section -->
                        <div class="mb-5">
                            <h5 class="mb-3">
                                <i class="fas fa-sync-alt"></i> Active Monthly Donations ({{ $recurringDonations->count() }})
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Started</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recurringDonations as $donation)
                                        <tr>
                                            <td>{{ $donation->created_at->format('M d, Y') }}</td>
                                            <td>${{ number_format($donation->amount, 2) }}/month</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary manage-subscription" 
                                                        data-subscription-id="{{ $donation->subscription_id }}"
                                                        data-donation-id="{{ $donation->id }}">
                                                    <i class="fas fa-cog"></i> Manage
                                                </button>
                                                @if($donation->receipt_sent)
                                                    <a href="{{ url("/{$path}/donations/{$donation->id}/receipt") }}" 
                                                       class="btn btn-sm btn-secondary" 
                                                       target="_blank">
                                                        <i class="fas fa-file-pdf"></i> Receipt
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        @if($allDonations->count() > 0)
                        <!-- All Donations History Section -->
                        <div>
                            <div class="row align-items-center mb-3">
                                <div class="col-md-4">
                                    <h5 class="mb-0">
                                        <i class="fas fa-heart"></i> Donation History
                                    </h5>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="taxYearSelect">
                                        @for ($year = date('Y'); $year >= $startYear; $year--)
                                            <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3 text-end">
                                    <strong>Total: ${{ number_format($totalDonated, 2) }}</strong>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allDonations as $donation)
                                        <tr>
                                            <td>{{ $donation->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                @if($donation->recurring)
                                                    <span class="badge bg-info">Monthly</span>
                                                @else
                                                    <span class="badge bg-secondary">One-Time</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($donation->amount, 2) }}</td>
                                            <td>
                                                @if($donation->status === 'completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($donation->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($donation->status === 'failed')
                                                    <span class="badge bg-danger">Failed</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($donation->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($donation->receipt_sent)
                                                    <a href="{{ url("/{$path}/donations/{$donation->id}/receipt") }}" 
                                                       class="btn btn-sm btn-secondary" 
                                                       target="_blank">
                                                        <i class="fas fa-file-pdf"></i> View
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        @if($allDonations->count() === 0)
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <i class="fas fa-heart fa-5x text-muted mb-3"></i>
                            <h4 class="text-muted">No Donations Yet</h4>
                            <p class="text-muted mb-4">Thank you for considering a donation to UNHUSHED!</p>
                            <button class="btn btn-primary btn-lg" onclick="openDonationModal()">
                                <i class="fas fa-heart"></i> Donate Now
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .fa-heart.fa-3x {
        opacity: 0.5;
    }
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Show success message if donation was completed successfully
    const urlParams = new URLSearchParams(window.location.search);
    const donationSuccess = urlParams.get('donation_success');
    
    if (donationSuccess) {
        const message = donationSuccess === 'recurring' 
            ? 'Thank you for donation to UN|HUSHED!' 
            : 'Thank you for donating to UN|HUSHED!';
        
        if (window.showToast) {
            window.showToast(message, 'success');
        } else {
            alert(message);
        }
        
        // Clean up URL but preserve year parameter
        const year = urlParams.get('year');
        const cleanUrl = year ? window.location.pathname + '?year=' + year : window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }
    
    // Handle tax year selection
    $('#taxYearSelect').on('change', function() {
        const selectedYear = $(this).val();
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('year', selectedYear);
        window.location.href = currentUrl.toString();
    });
    
    // Handle subscription management
    $('.manage-subscription').on('click', function() {
        const subscriptionId = $(this).data('subscription-id');
        
        // Redirect to Stripe billing portal
        window.location.href = '{{ url("/{$path}/donations/manage") }}/' + subscriptionId;
    });
});
</script>
@endpush
@endsection






