@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/backend') }}">Backend</a> > 
                <span style="font-weight: bold;color:#9acd57">Tax Exemptions</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Pending Approvals -->
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Pending Tax Exemption Requests ({{ $pending->count() }})</h5>
            </div>
            <div class="card-body">
                @if($pending->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Organization</th>
                                    <th>Type</th>
                                    <th>Tax ID</th>
                                    <th>Expiry</th>
                                    <th>Head Contact</th>
                                    <th>Certificate</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pending as $org)
                                    <tr>
                                        <td>
                                            <a href="{{ url($path.'/backend/organizations/'.$org->id) }}">
                                                {{ $org->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucfirst($org->tax_exempt_type ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td>{{ $org->tax_exempt_id ?? 'N/A' }}</td>
                                        <td>
                                            @if($org->tax_exempt_expiry)
                                                {{ \Carbon\Carbon::parse($org->tax_exempt_expiry)->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">No expiry</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($org->head)
                                                {{ $org->head->first_name }} {{ $org->head->last_name }}<br>
                                                <small class="text-muted">{{ $org->head->email }}</small>
                                            @else
                                                <span class="text-muted">No head assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($org->tax_exempt_certificate)
                                                <a href="{{ url($path.'/backend/tax-exemptions/'.$org->id.'/certificate') }}" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            @else
                                                <span class="text-muted">No certificate</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form method="POST" 
                                                  action="{{ url($path.'/backend/tax-exemptions/'.$org->id.'/approve') }}" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                        onclick="return confirm('Approve this tax exemption?')">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal{{ $org->id }}">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                            
                                            <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal{{ $org->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Tax Exemption</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="POST" 
                                                              action="{{ url($path.'/backend/tax-exemptions/'.$org->id.'/reject') }}">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="reason{{ $org->id }}" class="form-label">
                                                                        Rejection Reason
                                                                    </label>
                                                                    <textarea class="form-control" 
                                                                              id="reason{{ $org->id }}" 
                                                                              name="reason" 
                                                                              rows="3" 
                                                                              required></textarea>
                                                                    <small class="form-text text-muted">
                                                                        This will be sent to the organization
                                                                    </small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" 
                                                                        data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">
                                                                    Reject Exemption
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No pending tax exemption requests.</p>
                @endif
            </div>
        </div>

        <!-- Approved Exemptions -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Approved Tax Exemptions (Last 50)</h5>
            </div>
            <div class="card-body">
                @if($approved->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Organization</th>
                                    <th>Type</th>
                                    <th>Tax ID</th>
                                    <th>Expiry</th>
                                    <th>Approved By</th>
                                    <th>Approved Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($approved as $org)
                                    <tr class="{{ $org->tax_exempt_expiry && $org->tax_exempt_expiry < now() ? 'table-danger' : '' }}">
                                        <td>
                                            <a href="{{ url($path.'/backend/organizations/'.$org->id) }}">
                                                {{ $org->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucfirst($org->tax_exempt_type ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td>{{ $org->tax_exempt_id ?? 'N/A' }}</td>
                                        <td>
                                            @if($org->tax_exempt_expiry)
                                                {{ \Carbon\Carbon::parse($org->tax_exempt_expiry)->format('M d, Y') }}
                                                @if($org->tax_exempt_expiry < now())
                                                    <br><span class="badge bg-danger">EXPIRED</span>
                                                @endif
                                            @else
                                                <span class="text-muted">No expiry</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($org->verifiedBy)
                                                {{ $org->verifiedBy->first_name }} {{ $org->verifiedBy->last_name }}
                                            @else
                                                <span class="text-muted">Unknown</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($org->tax_exempt_verified_at)
                                                {{ \Carbon\Carbon::parse($org->tax_exempt_verified_at)->format('M d, Y') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($org->tax_exempt_certificate)
                                                <a href="{{ url($path.'/backend/tax-exemptions/'.$org->id.'/certificate') }}" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No approved tax exemptions yet.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
