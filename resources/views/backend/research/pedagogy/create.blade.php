<!-- Modal Body -->
<div class="modal-body">
    <form action="{{url($path.'/backend/pedagogy')}}" method="POST" id="add_entry" role="form" onsubmit="return addEntry()">
        @csrf
        <div class="form-group row">
            <label for="title" class="col-md-3 col-form-label text-md-right">Title</label>
            <div class="col-md-9">
                <input id="title" type="text" class="form-control" name="title" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="author" class="col-md-3 col-form-label text-md-right">Author</label>
            <div class="col-md-9">
                <input id="author" type="text" class="form-control" name="author" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="journal" class="col-md-3 col-form-label text-md-right">Journal</label>
            <div class="col-md-9">
                <input id="journal" type="text" class="form-control" name="journal" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="year" class="col-md-3 col-form-label text-md-right">Year</label>
            <div class="col-md-9">
                <input id="year" type="text" class="form-control" name="year" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="month" class="col-md-3 col-form-label text-md-right">Month</label>
            <div class="col-md-9">
                <input id="month" type="text" class="form-control" name="month" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="keywords" class="col-md-3 col-form-label text-md-right">Keywords</label>
            <div class="col-md-9">
                <input id="keywords" type="text" class="form-control" name="keywords" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="url" class="col-md-3 col-form-label text-md-right">Url</label>
            <div class="col-md-9">
                <input id="url" type="text" class="form-control" name="url" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="abstract" class="col-md-3 col-form-label text-md-right">Abstract</label>
            <div class="col-md-9">
                <textarea class="form-control teditor" name="abstract" id="abstract"></textarea>
            </div>
        </div>
        <div class="form-group row mb-5 mt-5">
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        //prevent modal from opening until clicked
        $(".add-entry").on('click', function(e){
            e.preventDefault();
            $("#addEntry").modal('show');
        })
        //modal draggable
        $("#addEntry").draggable({
            handle: ".modal-header"
        });
    });

    //add script
    function addEntry(){
        let _url = $("#add_entry").attr('action');
        fd = $("#add_entry").serialize();
        $.ajax({
        url: _url,
        type: 'post',
        data: fd,
        success: function(response){
            console.log(response);
            if(response.error === true){
                alert(response.message);
            }else{
                alert("Citation added.");
                document.location = "{{ url($path.'/backend/pedagogy') }}";
            }
        },
        fail: function(){
            alert("Error");
        }
        });
        return false;
    }
</script>
