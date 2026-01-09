
<form action="{{url($path.'/backend/org-train-add')}}" method="POST" role="form">
<input type="hidden" name="id" value="{{$org->id}}" />
    @csrf
    <div class="row pb-2">
        <label for="package" class="col-form-label col-md-3 text-md-right pr-2">Curriculum</label>
        <div class="col-md-9">
            <select class="form-select" id="package" name="package">
                <option selected>select one</option>
                <!-- don't include any packages the org already has -->
                @foreach($trainings as $pack)
                    <option value="{{ $pack->id }}"> {{ $pack->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row pb-2">
        <label for="total" class="col-form-label col-md-3 text-md-right pr-2">Total Users</label>
        <div class="col-md-2">
            <input id="qty" type="number" class="form-control" name="total" min="1" value="1">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-auto mx-auto">
            <button type="submit" class="btn btn-secondary">ADD</button>
        </div>
    </div>
</form>