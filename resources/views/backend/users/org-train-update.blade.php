<!-- Modal Header -->
<div class="modal-header">
    <span style="text-transform: uppercase;"><b>EDIT&nbsp;</b></span>
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
</div>
<!-- Modal Body -->
<div class="modal-body">
    <form action="{{url($path.'/backend/org-training-update')}}" method="POST" role="form" id="licUpdate" onsubmit="return checkTrainingUpdate()">
        <input type="hidden" name="id" class="training_update" value="" />
        @csrf
        <div class="row pb-2">
            <label for="total" class="col-form-label col-md-6 text-md-right pr-2">Total Users</label>
            <div class="col-md-4">
                <input id="qty" type="number" class="form-control qty_update"  name="total" min="0" value="">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-secondary">UDPATE</button>
            </div>
        </div>
    </form>
</div>