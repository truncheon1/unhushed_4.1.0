<!-- Modal Header -->
<div class="modal-header">
    <b>MASTER:&nbsp;</b> EDIT LICENSE
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
</div>
<!-- Modal Body -->
<div class="modal-body">
    <form action="{{url($path.'/backend/org-lic-update')}}" method="POST" role="form" id="licUpdate" onsubmit="return checkLicUpdate()">
        <input type="hidden" name="id" class="lic_update" value="" />
        @csrf
        <div class="row pb-2">
            <label for="total" class="col-form-label col-md-3 text-md-right pr-2">Total Users</label>
            <div class="col-md-4">
                <input class="form-control qty_update" id="qty" name="total" type="number" min="0" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="status" class="col-form-label col-md-3 text-md-right pr-2">Status</label>
            <div class="col-md-8">
                <select class="form-select status_update" id="status" name="status" value="">
                    <option value="">select status</option>
                    <option value="2">active</option>
                    <option value="3">canceled</option>
                    <option value="4">reviewing</option>
                    <option value="5">reviewed</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-secondary">UDPATE</button>
            </div>
        </div>
    </form>
</div>