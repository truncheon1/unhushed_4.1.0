<!-- Modal Body -->
<div class="modal-body">
    <form action="{{url($path.'/backend/pedagogy/edit')}}" method="POST" id="edit_entry" role="form" onsubmit="return editEntry()">
        <input type='hidden' name='id' class="id" value=''/>
        @csrf
        <div class="form-group row">
            <label for="etitle" class="col-md-3 col-form-label text-md-right">Term</label>
            <div class="col-md-9">
                <input id="etitle" type="text" class="form-control" name="title" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="eauthor" class="col-md-3 col-form-label text-md-right">Author</label>
            <div class="col-md-9">
                <input id="eauthor" type="text" class="form-control" name="author" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="ejournal" class="col-md-3 col-form-label text-md-right">Journal</label>
            <div class="col-md-9">
                <input id="ejournal" type="text" class="form-control" name="journal" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="eyear" class="col-md-3 col-form-label text-md-right">Year</label>
            <div class="col-md-9">
                <input id="eyear" type="text" class="form-control" name="year" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="emonth" class="col-md-3 col-form-label text-md-right">Month</label>
            <div class="col-md-9">
                <input id="emonth" type="text" class="form-control" name="month" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="ekeywords" class="col-md-3 col-form-label text-md-right">Keywords</label>
            <div class="col-md-9">
                <input id="ekeywords" type="text" class="form-control" name="keywords" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="eurl" class="col-md-3 col-form-label text-md-right">Url</label>
            <div class="col-md-9">
                <input id="eurl" type="text" class="form-control" name="url" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="eabstract" class="col-md-3 col-form-label text-md-right">Abstract</label>
            <div class="col-md-9">
                <textarea id="eabstract" class="form-control teditor" name="abstract" value=""></textarea>
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
        //modal draggable
        $("#editEntry").draggable({
            handle: ".modal-header"
        });
    });

    //ENTRY EDIT
    $('body').on("click", '.entry-edit-link', function(e){
        e.preventDefault();
        _id = $(this).attr('rel');
        $.ajax({
            url: '{{url($path."/backend/pedagogy/edit")}}/'+_id,
            type: 'get',
            success: function(response){
                console.log(response);
                    $(".id").val(response.entry.id);
                    $("#etitle").val(response.entry.title);
                    $("#eauthor").val(response.entry.author);
                    $("#ejournal").val(response.entry.journal);
                    $("#eyear").val(response.entry.year);
                    $("#emonth").val(response.entry.month);
                    $("#ekeywords").val(response.entry.keywords);
                    $("#eurl").val(response.entry.url);
                    $("#eabstract").val(response.entry.abstract);
                    $("#editEntry").modal('show');
                },
                fail: function(){ alert("Error"); }
        });
    });

    //edit script
    function editEntry(){
        let _url = $("#edit_entry").attr('action');
        fd = $("#edit_entry").serialize();
        $.ajax({
        url: _url,
        type: 'post',
        data: fd,
        success: function(response){
            console.log(response);
            if(response.error === true){
                alert(response.message);
            }else{
                alert("Citation updated.");
                location.reload();
            }
        },
        fail: function(){ alert("Error"); }
        });
        return false;
    }
</script>
