
<!-- Modal Body -->
<div class="modal-body">
    <form action="{{url($path.'/backend/biologies')}}" method="POST" id="add_term" role="form" onsubmit="return addTerm()">
        <input type="hidden" name="file" class="file-name" value=""/>
        @csrf
        <div class="form-group row">
            <label for="term" class="col-md-3 col-form-label text-md-right">Term</label>
            <div class="col-md-9">
                <input id="term" type="text" class="form-control" name="term" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="chromosomes" class="col-md-3 col-form-label text-md-right">Chromosomes</label>
            <div class="col-md-9">
                <input id="chromosomes" type="text"  class="form-control" name="chromosomes" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="hormones" class="col-md-3 col-form-label text-md-right">Hormones</label>
            <div class="col-md-9">
                <input id="hormones" type="text" class="form-control" name="hormones" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="gonads" class="col-md-3 col-form-label text-md-right">Gonads</label>
            <div class="col-md-9">
                <input id="gonads" type="text" class="form-control" name="gonads" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="external" class="col-md-3 col-form-label text-md-right">External</label>
            <div class="col-md-9">
                <input id="external" type="text" class="form-control" name="external" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="internal" class="col-md-3 col-form-label text-md-right">Internal</label>
            <div class="col-md-9">
                <input id="internal" type="text" class="form-control" name="internal" placeholder="">
            </div>
        </div>
        <div class="form-group row mb-5 mt-5">
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-secondary">Save</button>
            </div>
        </div>
    </form>
