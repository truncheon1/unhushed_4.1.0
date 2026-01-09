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
                <a href="{{ url($path.'/backend') }}"> Backend</a> |
                <span style="font-weight: bold;color:#9acd57"> Fulfillment</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- BILLING TABLE -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-5" style="text-transform: uppercase; font-weight: bold;"> DONATION MANAGMENT</div>
                            <div class="col text-right">
                                <button class="btn btn-sm btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#addDonationModal">
                                    <i class="fas fa-plus"></i> Add Manual Donation
                                </button>
                                <button class="btn btn-sm btn-secondary" id="export-donations">
                                    <i class="fas fa-download"></i> Export to CSV
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                <!-- Donations Table -->
                <div class="table-responsive">
                    <table id="donations-table" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Donor</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Reference</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donations as $donation)
                            <tr>
                                <td>{{ $donation->id }}</td>
                                <td>{{ $donation->created_at->format('Y-m-d') }}</td>
                                <td>{{ $donation->user ? $donation->user->name : 'Anonymous' }}</td>
                                <td>${{ number_format($donation->amount, 2) }}</td>
                                <td>
                                    @if($donation->recurring)
                                        <span class="badge bg-info">Recurring</span>
                                    @else
                                        <span class="badge bg-secondary">One-Time</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $isStripe = $donation->payment_intent_id 
                                                    || $donation->subscription_id 
                                                    || $donation->payment_method_id 
                                                    || (isset($donation->notes) && str_contains(strtolower($donation->notes), 'stripe'));
                                    @endphp
                                    @if($isStripe)
                                        <span class="badge bg-primary">Stripe</span>
                                    @else
                                        <span class="badge bg-dark">Manual</span>
                                    @endif
                                </td>
                                <td>
                                    @if($donation->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($donation->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($donation->status == 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($donation->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $donation->payment_method ?? '-' }}</td>
                                <td>{{ $donation->reference_number ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-donation" data-id="{{ $donation->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @php
                                        $canDelete = !$donation->payment_intent_id 
                                                    && !$donation->subscription_id 
                                                    && !$donation->payment_method_id 
                                                    && !(isset($donation->notes) && str_contains(strtolower($donation->notes), 'stripe'));
                                    @endphp
                                    @if($canDelete)
                                        <button class="btn btn-sm btn-danger delete-donation" data-id="{{ $donation->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Manual Donation Modal -->
    <div class="modal fade" id="addDonationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Manual Donation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('/'.$path.'/backend/donations-fulfillment') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Donor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="donor-search" placeholder="Search by name or email..." autocomplete="off">
                            <input type="hidden" name="user_id" id="selected-user-id" required>
                            <div id="donor-results" class="list-group mt-2" style="display: none; position: absolute; z-index: 1000; max-height: 200px; overflow-y: auto; width: calc(100% - 30px);"></div>
                            <span style="color: #696969;">Search for existing user or create new</span>
                        </div>
                        
                        <!-- Create New User Section (hidden by default) -->
                        <div id="create-user-section" style="display: none;">
                            <div class="alert alert-info">
                                <strong>Create New User:</strong> This user doesn't exist yet.
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="new-user-name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="new-user-email">
                            </div>
                            <button type="button" class="btn btn-sm btn-success" id="create-user-btn">
                                <i class="fas fa-user-plus"></i> Create User & Continue
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" id="cancel-create-user-btn">Cancel</button>
                            <div id="create-user-message" class="mt-2"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="amount" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Donation Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="donation_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-select" name="payment_method" required>
                                <option value="">Select method...</option>
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="paypal">PayPal</option>
                                <option value="stripe">Stripe</option>
                                <option value="wire">Wire Transfer</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Reference Number</label>
                            <input type="text" class="form-control" name="reference_number" placeholder="Check #, transaction ID, etc.">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Donation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Donation Modal -->
    <div class="modal fade" id="editDonationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Donation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-donation-form" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-donation-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Donor</label>
                            <input type="text" class="form-control" id="edit-donor-name" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" class="form-control" name="amount" id="edit-amount" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Donation Date</label>
                            <input type="date" class="form-control" name="donation_date" id="edit-donation-date" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select" name="payment_method" id="edit-payment-method">
                                <option value="">Select method...</option>
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="paypal">PayPal</option>
                                <option value="stripe">Stripe</option>
                                <option value="wire">Wire Transfer</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Reference Number</label>
                            <input type="text" class="form-control" name="reference_number" id="edit-reference-number">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" id="edit-notes" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Source</label>
                            <input type="text" class="form-control" id="edit-source" readonly>
                        </div>
                        
                        <div class="alert alert-info" id="edit-stripe-notice" style="display: none;">
                            <i class="fas fa-info-circle"></i> This is a Stripe donation. Only manual donations can be fully edited.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="save-donation-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        #donor-results {
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        #donor-results .list-group-item {
            cursor: pointer;
        }
        #donor-results .list-group-item:hover {
            background-color: #f8f9fa;
        }
        .create-new-user-option {
            background-color: #e3f2fd !important;
            border-top: 2px solid #2196F3;
        }
    </style>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#donations-table').DataTable({
            order: [[0, 'desc']],
            pageLength: 25
        });
        
        // Donor search
        let searchTimeout;
        $('#donor-search').on('keyup', function() {
            clearTimeout(searchTimeout);
            const query = $(this).val();
            
            // Hide create user section when searching
            $('#create-user-section').hide();
            
            if (query.length < 2) {
                $('#donor-results').hide();
                return;
            }
            
            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: '{{ url("/".$path."/backend/api/users/search") }}',
                    data: { q: query },
                    success: function(users) {
                        const results = $('#donor-results');
                        results.empty();
                        
                        if (users.length === 0) {
                            results.html(`
                                <div class="list-group-item">No users found</div>
                                <a href="#" class="list-group-item list-group-item-action create-new-user-option" data-query="${query}">
                                    <i class="fas fa-user-plus"></i> <strong>Create New User</strong><br>
                                    <small class="text-muted">Add "${query}" as a new donor</small>
                                </a>
                            `);
                        } else {
                            users.forEach(function(user) {
                                results.append(
                                    `<a href="#" class="list-group-item list-group-item-action donor-select" data-id="${user.id}" data-name="${user.name}">
                                        <strong>${user.name}</strong><br>
                                        <small class="text-muted">${user.email}</small>
                                    </a>`
                                );
                            });
                            // Add create new option at the end
                            results.append(`
                                <a href="#" class="list-group-item list-group-item-action create-new-user-option" data-query="${query}">
                                    <i class="fas fa-user-plus"></i> <strong>Create New User</strong>
                                </a>
                            `);
                        }
                        results.show();
                    },
                    error: function() {
                        const results = $('#donor-results');
                        results.html('<div class="list-group-item text-danger">Error loading users</div>');
                        results.show();
                    }
                });
            }, 300);
        });
        
        // Show create new user form
        $(document).on('click', '.create-new-user-option', function(e) {
            e.preventDefault();
            const query = $(this).data('query');
            
            $('#donor-results').hide();
            $('#create-user-section').show();
            
            // Pre-fill with search query if it looks like a name
            if (query && !query.includes('@')) {
                $('#new-user-name').val(query);
            } else if (query && query.includes('@')) {
                $('#new-user-email').val(query);
            }
        });
        
        // Cancel create user
        $(document).on('click', '#cancel-create-user-btn', function() {
            $('#create-user-section').hide();
            $('#new-user-name').val('');
            $('#new-user-email').val('');
            $('#create-user-message').html('');
        });
        
        // Create new user
        $(document).on('click', '#create-user-btn', function() {
            const name = $('#new-user-name').val().trim();
            const email = $('#new-user-email').val().trim();
            
            if (!name || !email) {
                $('#create-user-message').html('<div class="alert alert-warning alert-dismissible fade show">Please enter both name and email.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                return;
            }
            
            $('#create-user-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');
            
            $.ajax({
                url: '{{ url("/".$path."/backend/api/users/create-donor") }}',
                method: 'POST',
                data: {
                    name: name,
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#selected-user-id').val(response.user.id);
                        $('#donor-search').val(response.user.name);
                        $('#create-user-section').hide();
                        $('#create-user-message').html('');
                        $('#new-user-name').val('');
                        $('#new-user-email').val('');
                        
                        alert('User created successfully!\nTemporary Password: ' + response.temp_password + '\n\nPlease share this with the donor.');
                    }
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || 'Failed to create user. Email may already exist.';
                    $('#create-user-message').html('<div class="alert alert-danger alert-dismissible fade show">' + error + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                },
                complete: function() {
                    $('#create-user-btn').prop('disabled', false).html('<i class="fas fa-user-plus"></i> Create User & Continue');
                }
            });
        });
        
        // Select donor
        $(document).on('click', '.donor-select', function(e) {
            e.preventDefault();
            const userId = $(this).data('id');
            const userName = $(this).data('name');
            
            $('#selected-user-id').val(userId);
            $('#donor-search').val(userName);
            $('#donor-results').hide();
            $('#create-user-section').hide();
        });
        
        // Hide results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#donor-search, #donor-results').length) {
                $('#donor-results').hide();
            }
        });
        
        // Edit donation
        $(document).on('click', '.edit-donation', function() {
            const donationId = $(this).data('id');
            
            $.ajax({
                url: '{{ url("/".$path."/backend/donations-fulfillment") }}/' + donationId + '/edit',
                success: function(donation) {
                    $('#edit-donation-id').val(donation.id);
                    $('#edit-donor-name').val(donation.user ? donation.user.name : 'Anonymous');
                    $('#edit-amount').val(donation.amount);
                    $('#edit-donation-date').val(donation.created_at.split('T')[0]);
                    $('#edit-payment-method').val(donation.payment_method || '');
                    $('#edit-reference-number').val(donation.reference_number || '');
                    $('#edit-notes').val(donation.notes || '');
                    
                    const isStripe = donation.payment_intent_id 
                                    || donation.subscription_id 
                                    || donation.payment_method_id
                                    || (donation.notes && donation.notes.toLowerCase().includes('stripe'));
                    $('#edit-source').val(isStripe ? 'Stripe' : 'Manual');
                    
                    if (isStripe) {
                        $('#edit-stripe-notice').show();
                        $('#edit-amount').prop('readonly', true);
                        $('#edit-donation-date').prop('readonly', true);
                        $('#edit-payment-method').prop('disabled', true);
                    } else {
                        $('#edit-stripe-notice').hide();
                        $('#edit-amount').prop('readonly', false);
                        $('#edit-donation-date').prop('readonly', false);
                        $('#edit-payment-method').prop('disabled', false);
                    }
                    
                    $('#edit-donation-form').attr('action', '{{ url("/".$path."/backend/donations-fulfillment") }}/' + donationId);
                    $('#editDonationModal').modal('show');
                }
            });
        });
        
        // Delete donation
        $(document).on('click', '.delete-donation', function() {
            if (!confirm('Are you sure you want to delete this manual donation?')) {
                return;
            }
            
            const donationId = $(this).data('id');
            const form = $('<form>', {
                method: 'POST',
                action: '{{ url("/".$path."/backend/donations-fulfillment") }}/' + donationId
            });
            
            form.append('{{ csrf_field() }}');
            form.append($('<input>', {
                type: 'hidden',
                name: '_method',
                value: 'DELETE'
            }));
            
            $('body').append(form);
            form.submit();
        });
        
        // Export to CSV
        $('#export-donations').on('click', function() {
            alert('Export functionality will be implemented in a future update.');
        });
    });
    </script>
</section>
@endsection
