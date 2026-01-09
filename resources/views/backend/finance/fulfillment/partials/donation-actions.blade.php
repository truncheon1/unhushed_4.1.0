<div class="btn-group" role="group">
    <button type="button" class="btn btn-sm btn-primary edit-donation" data-id="{{ $donation->id }}" title="Edit">
        <i class="fas fa-edit"></i>
    </button>
    @if($donation->status === 'completed' && !$donation->isAnonymous())
        <a href="{{ url("/{$path}/admin/donations/{$donation->id}/receipt") }}" 
           class="btn btn-sm btn-success" 
           target="_blank" 
           title="View Receipt">
            <i class="fas fa-file-pdf"></i>
        </a>
    @endif
    <button type="button" 
            class="btn btn-sm btn-danger delete-donation" 
            data-id="{{ $donation->id }}" 
            title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>
