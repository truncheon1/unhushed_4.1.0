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
                    <a href="{{ url($path.'/dashboard/users') }}">Users</a> |
                    <span style="font-weight: bold;color:#9acd57">Assign Access</span>
                </div>
            </div>
            
            <!-- Success Alert -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <!-- Error Alert -->
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <!-- Dynamic Success Alert (from sessionStorage) -->
            <div id="purchase-success-alert" class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
                <i class="fas fa-check-circle"></i> <span id="purchase-success-message"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!--  TABLE -->
            <div class="row mt-2 mb-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-7">CURRICULA SUBSCRIPTIONS</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col mx-auto">
                                    <table class="table table-striped" style="width: 100%" id="curriculumTable">
                                        <thead>
                                            <tr>
                                                <th id="email" colspan="1" style="text-align:left">Curriculum</th>
                                                <th id="name" colspan="1" style="text-align:center">Total</th>
                                                <th id="role" colspan="1" style="text-align:center">Used</th>
                                                <th id="options" colspan="1" style="text-align:center">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($subscriptions as $subscription)
                                            <tr id="row_{{$active_subscriptions[$subscription->id]->id}}">
                                                <td colspan="1">{{$subscription->name}}</td>
                                                <td colspan="1" style="text-align:center">{{$active_subscriptions[$subscription->id]->total}} </td>
                                                <td colspan="1" style="text-align:center">{{$active_subscriptions[$subscription->id]->used}}</td>
                                                <td colspan="1" style="text-align:center">
                                                    <button type="button" class="btn btn-sm btn-primary assign-curriculum" 
                                                        data-subscription-id="{{$active_subscriptions[$subscription->id]->id}}"
                                                        data-product-id="{{$subscription->id}}"
                                                        data-product-name="{{$subscription->name}}"
                                                        data-total="{{$active_subscriptions[$subscription->id]->total}}"
                                                        data-used="{{$active_subscriptions[$subscription->id]->used}}">
                                                        <i class="fas fa-user-plus"></i> Assign To Users
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--  TABLE -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-7">TRAININGS</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col mx-auto">
                                    <table class="table table-striped" style="width: 100%" id="trainingTable">
                                        <thead>
                                            <tr>
                                                <th id="email" colspan="1" style="text-alignleft">Training</th>
                                                <th id="name" colspan="1" style="text-align:center">Total</th>
                                                <th id="role" colspan="1" style="text-align:center">Used</th>
                                                <th id="options" colspan="1" style="text-align:center">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trainings as $training)
                                            <tr id="row_{{$active_trainings[$training->id]->id}}">
                                                <td colspan="1">{{$training->name }}</td>
                                                <td colspan="1" style="text-align:center">{{$active_trainings[$training->id]->total}} </td>
                                                <td colspan="1" style="text-align:center">{{$active_trainings[$training->id]->used}}</td>
                                                <td colspan="1" style="text-align:center">
                                                    <button type="button" class="btn btn-sm btn-primary assign-training" 
                                                        data-subscription-id="{{$active_trainings[$training->id]->id}}"
                                                        data-product-id="{{$training->id}}"
                                                        data-product-name="{{$training->name}}"
                                                        data-total="{{$active_trainings[$training->id]->total}}"
                                                        data-used="{{$active_trainings[$training->id]->used}}">
                                                        <i class="fas fa-user-plus"></i> Assign To Users
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2 mb-5">
                <div class="col-12"></div>
            </div>
        </div>

        <!-- Curriculum Assignment Modal -->
        <div class="modal fade" id="curriculumModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <strong style="font-size: 1rem;" class="modal-title">Assign Curriculum Access: <i><span id="curriculum-name" class="text-primary"></span></i></strong>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <p>Available: <span id="curriculum-available" class="badge bg-success">0</span>
                            Used: <span id="curriculum-used" class="badge bg-secondary">0</span></p>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-success" id="bulk-add-curriculum">
                                <i class="fas fa-user-plus"></i> Bulk Add (<span class="selected-count">0</span>)
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" id="bulk-remove-curriculum">
                                <i class="fas fa-user-minus"></i> Bulk Remove (<span class="selected-count">0</span>)
                            </button>
                        </div>
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover table-sm" id="curriculum-users-table">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th style="width: 40px; text-align: center;">
                                            <input type="checkbox" id="curriculum-select-all" style="cursor: pointer;">
                                        </th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th class="text-center">Access</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                            Loading users...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Training Assignment Modal -->
        <div class="modal fade" id="trainingModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <strong style="font-size: 1rem;" class="modal-title">Assign Training Access: <i><span id="training-name" class="text-primary"></span></i></strong>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <p>Available: <span id="training-available" class="badge bg-success">0</span>
                            Used: <span id="training-used" class="badge bg-secondary">0</span></p>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-success" id="bulk-add-training">
                                <i class="fas fa-user-plus"></i> Bulk Add (<span class="selected-count">0</span>)
                            </button>
                            <span class="text-muted ms-3"><i class="fas fa-info-circle"></i> Trainings are one-time use and cannot be reassigned</span>
                        </div>
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover table-sm" id="training-users-table">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th style="width: 40px; text-align: center;">
                                            <input type="checkbox" id="training-select-all" style="cursor: pointer;">
                                        </th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th class="text-center">Access</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                            Loading users...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $(document).ready(function() {
                // Check for success message from purchase
                const successMessage = sessionStorage.getItem('purchase_success_message');
                if (successMessage) {
                    $('#purchase-success-message').text(successMessage);
                    $('#purchase-success-alert').show();
                    // Clear the message from sessionStorage
                    sessionStorage.removeItem('purchase_success_message');
                    
                    // Auto-dismiss after 8 seconds
                    setTimeout(function() {
                        $('#purchase-success-alert').fadeOut();
                    }, 8000);
                }
                
                $('#curriculumTable').DataTable({
                    lengthMenu: [[5, 10, -1], [5, 10, "All"]],
                    pagingType: 'full_numbers',
                    "aaSorting": []
                });
            });

            $(document).ready(function() {
                $('#trainingTable').DataTable({
                    lengthMenu: [[5, 10, -1], [5, 10, "All"]],
                    pagingType: 'full_numbers',
                    "aaSorting": []
                });
            });

            // Handle curriculum assignment modal
            $('.assign-curriculum').on('click', function() {
                const subscriptionId = $(this).data('subscription-id');
                const productId = $(this).data('product-id');
                const productName = $(this).data('product-name');
                const total = $(this).data('total');
                const used = $(this).data('used');
                const available = total - used;
                
                $('#curriculum-name').text(productName);
                $('#curriculum-available').text(available);
                $('#curriculum-used').text(used);
                
                // Store subscription ID on table for bulk operations
                $('#curriculum-users-table').data('subscription-id', subscriptionId);
                
                // Load users for this organization
                loadCurriculumUsers(subscriptionId, productId);
                
                $('#curriculumModal').modal('show');
            });

            // Handle training assignment modal
            $('.assign-training').on('click', function() {
                const subscriptionId = $(this).data('subscription-id');
                const productId = $(this).data('product-id');
                const productName = $(this).data('product-name');
                const total = $(this).data('total');
                const used = $(this).data('used');
                const available = total - used;
                
                $('#training-name').text(productName);
                $('#training-available').text(available);
                $('#training-used').text(used);
                
                // Store subscription ID on table for bulk operations
                $('#training-users-table').data('subscription-id', subscriptionId);
                
                // Load users for this organization
                loadTrainingUsers(subscriptionId, productId);
                
                $('#trainingModal').modal('show');
            });

            // Function to load curriculum users with access status
            function loadCurriculumUsers(subscriptionId, productId) {
                const $tbody = $('#curriculum-users-table tbody');
                $tbody.html('<tr><td colspan="4" class="text-center"><span class="spinner-border spinner-border-sm"></span> Loading users...</td></tr>');
                
                $.ajax({
                    url: '{{ url($path."/api/organization-users") }}',
                    method: 'GET',
                    data: { product_id: productId, category: 1 },
                    success: function(response) {
                        $tbody.empty();
                        
                        if (response.length === 0) {
                            $tbody.html('<tr><td colspan="5" class="text-center text-muted">No users in organization</td></tr>');
                            return;
                        }
                        
                        response.forEach(function(user) {
                            const hasAccess = user.has_access || false;
                            const accessBadge = hasAccess 
                                ? '<span class="badge bg-success">Yes</span>'
                                : '<span class="badge bg-secondary">No</span>';
                            
                            const actionBtn = hasAccess
                                ? `<button class="btn btn-sm btn-danger remove-curriculum-access" data-user-id="${user.id}" data-subscription-id="${subscriptionId}">
                                    <i class="fas fa-times"></i> Remove
                                   </button>`
                                : `<button class="btn btn-sm btn-success add-curriculum-access" data-user-id="${user.id}" data-subscription-id="${subscriptionId}">
                                    <i class="fas fa-plus"></i> Add
                                   </button>`;
                            
                            $tbody.append(`
                                <tr data-user-id="${user.id}" data-has-access="${hasAccess}">
                                    <td style="text-align: center;"><input type="checkbox" class="curriculum-user-checkbox" value="${user.id}" style="cursor: pointer;"></td>
                                    <td>${user.name}</td>
                                    <td>${user.email}</td>
                                    <td class="text-center access-status">${accessBadge}</td>
                                    <td class="text-center action-cell">${actionBtn}</td>
                                </tr>
                            `);
                        });
                        
                        attachCurriculumHandlers();
                        attachCurriculumCheckboxHandlers();
                    },
                    error: function() {
                        $tbody.html('<tr><td colspan="5" class="text-center text-danger">Error loading users</td></tr>');
                    }
                });
            }

            // Function to load training users with access status
            function loadTrainingUsers(subscriptionId, productId) {
                const $tbody = $('#training-users-table tbody');
                $tbody.html('<tr><td colspan="4" class="text-center"><span class="spinner-border spinner-border-sm"></span> Loading users...</td></tr>');
                
                $.ajax({
                    url: '{{ url($path."/api/organization-users") }}',
                    method: 'GET',
                    data: { product_id: productId, category: 7 },
                    success: function(response) {
                        $tbody.empty();
                        
                        if (response.length === 0) {
                            $tbody.html('<tr><td colspan="5" class="text-center text-muted">No users in organization</td></tr>');
                            return;
                        }
                        
                        response.forEach(function(user) {
                            const hasAccess = user.has_access || false;
                            const accessBadge = hasAccess 
                                ? '<span class="badge bg-success">Yes</span>'
                                : '<span class="badge bg-secondary">No</span>';
                            
                            const actionBtn = hasAccess
                                ? `<span class="badge bg-info"><i class="fas fa-check"></i> Assigned</span>`
                                : `<button class="btn btn-sm btn-success add-training-access" data-user-id="${user.id}" data-subscription-id="${subscriptionId}">
                                    <i class="fas fa-plus"></i> Add
                                   </button>`;
                            
                            // Disable checkbox for users who already have access
                            const checkboxHtml = hasAccess 
                                ? `<input type="checkbox" class="training-user-checkbox" value="${user.id}" disabled style="cursor: not-allowed;">`
                                : `<input type="checkbox" class="training-user-checkbox" value="${user.id}" style="cursor: pointer;">`;
                            
                            $tbody.append(`
                                <tr data-user-id="${user.id}" data-has-access="${hasAccess}">
                                    <td style="text-align: center;">${checkboxHtml}</td>
                                    <td>${user.name}</td>
                                    <td>${user.email}</td>
                                    <td class="text-center access-status">${accessBadge}</td>
                                    <td class="text-center action-cell">${actionBtn}</td>
                                </tr>
                            `);
                        });
                        
                        attachTrainingHandlers();
                        attachTrainingCheckboxHandlers();
                    },
                    error: function() {
                        $tbody.html('<tr><td colspan="5" class="text-center text-danger">Error loading users</td></tr>');
                    }
                });
            }

            // Attach curriculum add/remove handlers
            function attachCurriculumHandlers() {
                // Add access
                $('.add-curriculum-access').off('click').on('click', function() {
                    const $btn = $(this);
                    const userId = $btn.data('user-id');
                    const subscriptionId = $btn.data('subscription-id');
                    const $row = $btn.closest('tr');
                    
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                    
                    $.ajax({
                        url: '{{ url($path."/dashboard/assign-access") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            subscription_id: subscriptionId,
                            user_id: userId
                        },
                        success: function(response) {
                            // Update UI
                            $row.find('.access-status').html('<span class="badge bg-success">Yes</span>');
                            $row.find('.action-cell').html(`
                                <button class="btn btn-sm btn-danger remove-curriculum-access" data-user-id="${userId}" data-subscription-id="${subscriptionId}">
                                    <i class="fas fa-times"></i> Remove
                                </button>
                            `);
                            attachCurriculumHandlers();
                            
                            // Update counts
                            const used = parseInt($('#curriculum-used').text()) + 1;
                            const total = parseInt($('#curriculum-available').text()) + used;
                            $('#curriculum-used').text(used);
                            $('#curriculum-available').text(total - used);
                            
                            // Update main table
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Error: ' + (xhr.responseJSON?.message || 'Failed to assign access'));
                            $btn.prop('disabled', false).html('<i class="fas fa-plus"></i> Add');
                        }
                    });
                });
                
                // Remove access
                $('.remove-curriculum-access').off('click').on('click', function() {
                    const $btn = $(this);
                    const userId = $btn.data('user-id');
                    const subscriptionId = $btn.data('subscription-id');
                    const $row = $btn.closest('tr');
                    
                    if (!confirm('Are you sure you want to remove access for this user?')) {
                        return;
                    }
                    
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                    
                    $.ajax({
                        url: '{{ url($path."/dashboard/remove-access") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            subscription_id: subscriptionId,
                            user_id: userId,
                            category: 1
                        },
                        success: function(response) {
                            // Update UI
                            $row.find('.access-status').html('<span class="badge bg-secondary">No</span>');
                            $row.find('.action-cell').html(`
                                <button class="btn btn-sm btn-success add-curriculum-access" data-user-id="${userId}" data-subscription-id="${subscriptionId}">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            `);
                            attachCurriculumHandlers();
                            
                            // Update counts
                            const used = parseInt($('#curriculum-used').text()) - 1;
                            const total = parseInt($('#curriculum-available').text()) + used + 1;
                            $('#curriculum-used').text(used);
                            $('#curriculum-available').text(total - used);
                            
                            // Update main table
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Error: ' + (xhr.responseJSON?.message || 'Failed to remove access'));
                            $btn.prop('disabled', false).html('<i class="fas fa-times"></i> Remove');
                        }
                    });
                });
            }

            // Attach training add/remove handlers
            function attachTrainingHandlers() {
                // Add access
                $('.add-training-access').off('click').on('click', function() {
                    const $btn = $(this);
                    const userId = $btn.data('user-id');
                    const subscriptionId = $btn.data('subscription-id');
                    const $row = $btn.closest('tr');
                    
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                    
                    $.ajax({
                        url: '{{ url($path."/dashboard/assign-training") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            subscription_id: subscriptionId,
                            user_id: userId
                        },
                        success: function(response) {
                            // Update UI
                            $row.find('.access-status').html('<span class="badge bg-success">Yes</span>');
                            $row.find('.action-cell').html(`<span class="badge bg-info"><i class="fas fa-check"></i> Assigned</span>`);
                            $row.find('.training-user-checkbox').prop('disabled', true).css('cursor', 'not-allowed');
                            attachTrainingHandlers();
                            
                            // Update counts
                            const used = parseInt($('#training-used').text()) + 1;
                            const total = parseInt($('#training-available').text()) + used;
                            $('#training-used').text(used);
                            $('#training-available').text(total - used);
                            
                            // Update main table
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Error: ' + (xhr.responseJSON?.message || 'Failed to assign access'));
                            $btn.prop('disabled', false).html('<i class="fas fa-plus"></i> Add');
                        }
                    });
                });
                
                // Remove access
                $('.remove-training-access').off('click').on('click', function() {
                    const $btn = $(this);
                    const userId = $btn.data('user-id');
                    const subscriptionId = $btn.data('subscription-id');
                    const $row = $btn.closest('tr');
                    
                    if (!confirm('Are you sure you want to remove access for this user?')) {
                        return;
                    }
                    
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                    
                    $.ajax({
                        url: '{{ url($path."/dashboard/remove-access") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            subscription_id: subscriptionId,
                            user_id: userId,
                            category: 7
                        },
                        success: function(response) {
                            // Update UI
                            $row.find('.access-status').html('<span class="badge bg-secondary">No</span>');
                            $row.find('.action-cell').html(`
                                <button class="btn btn-sm btn-success add-training-access" data-user-id="${userId}" data-subscription-id="${subscriptionId}">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            `);
                            attachTrainingHandlers();
                            
                            // Update counts
                            const used = parseInt($('#training-used').text()) - 1;
                            const total = parseInt($('#training-available').text()) + used + 1;
                            $('#training-used').text(used);
                            $('#training-available').text(total - used);
                            
                            // Update main table
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Error: ' + (xhr.responseJSON?.message || 'Failed to remove access'));
                            $btn.prop('disabled', false).html('<i class="fas fa-times"></i> Remove');
                        }
                    });
                });
            }

            // Attach curriculum checkbox handlers
            function attachCurriculumCheckboxHandlers() {
                // Select all checkbox
                $('#curriculum-select-all').off('change').on('change', function() {
                    $('.curriculum-user-checkbox').prop('checked', $(this).prop('checked'));
                    updateCurriculumSelectedCount();
                });
                
                // Individual checkboxes
                $('.curriculum-user-checkbox').off('change').on('change', function() {
                    updateCurriculumSelectedCount();
                    // Update select-all based on individual selections
                    const total = $('.curriculum-user-checkbox').length;
                    const checked = $('.curriculum-user-checkbox:checked').length;
                    $('#curriculum-select-all').prop('checked', total === checked);
                });
                
                // Bulk add button
                $('#bulk-add-curriculum').off('click').on('click', function() {
                    const $btn = $(this);
                    const subscriptionId = $('#curriculum-users-table').data('subscription-id');
                    const userIds = [];
                    
                    $('.curriculum-user-checkbox:checked').each(function() {
                        const $row = $(this).closest('tr');
                        if ($row.data('has-access') === false) {
                            userIds.push($(this).val());
                        }
                    });
                    
                    if (userIds.length === 0) {
                        alert('Please select users without access to add.');
                        return;
                    }
                    
                    $btn.prop('disabled', true);
                    
                    $.ajax({
                        url: '{{ url($path."/dashboard/bulk-assign-access") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            subscription_id: subscriptionId,
                            user_ids: userIds,
                            category: 1
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Error: ' + (xhr.responseJSON?.message || 'Failed to assign access'));
                            $btn.prop('disabled', false);
                        }
                    });
                });
                
                // Bulk remove button
                $('#bulk-remove-curriculum').off('click').on('click', function() {
                    const $btn = $(this);
                    const subscriptionId = $('#curriculum-users-table').data('subscription-id');
                    const userIds = [];
                    
                    $('.curriculum-user-checkbox:checked').each(function() {
                        const $row = $(this).closest('tr');
                        if ($row.data('has-access') === true) {
                            userIds.push($(this).val());
                        }
                    });
                    
                    if (userIds.length === 0) {
                        alert('Please select users with access to remove.');
                        return;
                    }
                    
                    if (!confirm(`Are you sure you want to remove access for ${userIds.length} user(s)?`)) {
                        return;
                    }
                    
                    $btn.prop('disabled', true);
                    
                    $.ajax({
                        url: '{{ url($path."/dashboard/bulk-remove-access") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            subscription_id: subscriptionId,
                            user_ids: userIds,
                            category: 1
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Error: ' + (xhr.responseJSON?.message || 'Failed to remove access'));
                            $btn.prop('disabled', false);
                        }
                    });
                });
            }
            
            function updateCurriculumSelectedCount() {
                const count = $('.curriculum-user-checkbox:checked').length;
                $('#bulk-add-curriculum .selected-count').text(count);
                $('#bulk-remove-curriculum .selected-count').text(count);
            }
            
            // Attach training checkbox handlers
            function attachTrainingCheckboxHandlers() {
                // Select all checkbox
                $('#training-select-all').off('change').on('change', function() {
                    $('.training-user-checkbox').prop('checked', $(this).prop('checked'));
                    updateTrainingSelectedCount();
                });
                
                // Individual checkboxes
                $('.training-user-checkbox').off('change').on('change', function() {
                    updateTrainingSelectedCount();
                    // Update select-all based on individual selections
                    const total = $('.training-user-checkbox').length;
                    const checked = $('.training-user-checkbox:checked').length;
                    $('#training-select-all').prop('checked', total === checked);
                });
                
                // Bulk add button
                $('#bulk-add-training').off('click').on('click', function() {
                    const $btn = $(this);
                    const subscriptionId = $('#training-users-table').data('subscription-id');
                    const userIds = [];
                    
                    $('.training-user-checkbox:checked').each(function() {
                        const $row = $(this).closest('tr');
                        if ($row.data('has-access') === false) {
                            userIds.push($(this).val());
                        }
                    });
                    
                    if (userIds.length === 0) {
                        alert('Please select users without access to add.');
                        return;
                    }
                    
                    $btn.prop('disabled', true);
                    
                    $.ajax({
                        url: '{{ url($path."/dashboard/bulk-assign-training") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            subscription_id: subscriptionId,
                            user_ids: userIds,
                            category: 7
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Error: ' + (xhr.responseJSON?.message || 'Failed to assign access'));
                            $btn.prop('disabled', false);
                        }
                    });
                });
            }
            
            function updateTrainingSelectedCount() {
                const count = $('.training-user-checkbox:checked').length;
                $('#bulk-add-training .selected-count').text(count);
                $('#bulk-remove-training .selected-count').text(count);
            }

            // Function to load organization users
            function loadOrgUsers(selectId) {
                const $select = $(selectId);
                $select.html('<option value="">Loading users...</option>');
                
                $.ajax({
                    url: '{{ url($path."/api/organization-users") }}',
                    method: 'GET',
                    success: function(users) {
                        $select.html('<option value="">Choose a user...</option>');
                        users.forEach(function(user) {
                            $select.append(`<option value="${user.id}">${user.name} (${user.email})</option>`);
                        });
                    },
                    error: function() {
                        $select.html('<option value="">Error loading users</option>');
                    }
                });
            }
        </script>
    </section>
@endsection





