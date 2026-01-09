<!-- Modal Body -->
<div class="modal-body">
    <form action="{{url($path.'/backend/dictionaries/edit')}}" method="POST" id="edit_entry" role="form" onsubmit="return editEntry()">
        <input type='hidden' name='id' class="id" value=''/>
        <input type="hidden" name="file" class="file-name1" value=""/>
        @csrf
        <div class="row pb-2">
            <label for="eterm" class="col-form-label col-md-2 text-md-right pr-2">Term</label>
            <div class="col-md-8">
                <input id="eterm" type="text" class="form-control" name="term" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="ephonetic" class="col-form-label col-md-2 text-md-right pr-2">Phonetic</label>
            <div class="col-md-8">
                <input id="ephonetic" type="text" class="form-control" name="phonetic" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="eaudio" class="col-form-label col-md-2 text-md-right pr-2">Audio File</label>
            <div class="col-md-8">
                <input id="eaudio" type="file"  class="form-control" name="audio" accept="audio/*">
            </div>
        </div>
        <div class="row pb-2">
            <label for="eslug" class="col-form-label col-md-2 text-md-right pr-1">Slug</label>
            <div class="col-md-8">
                <input id="eslug" type="text" class="form-control" name="slug" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="ekeywords" class="col-form-label col-md-2 text-md-right pr-1">Keywords</label>
            <div class="col-md-8">
                <input id="ekeywords" type="text" class="form-control" name="keywords" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="ealt" class="col-form-label col-md-2 text-md-right pr-1">Alternative Spellings</label>
            <div class="col-md-8">
                <input id="ealt" type="text" class="form-control" name="alt" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="etags" class="col-form-label col-md-2 text-md-right pr-2">Tags</label>
            <div class="col-md-10">
                <select class="selectpicker" data-live-search="true" id='ecategories' multiple name='tags[]' title='Select Tags...'>
                    @foreach(\App\Models\DictTags::where('parent_id', 0)->get() as $tag_option)
                    <optgroup label="{{$tag_option->name}}">
                        @foreach(\App\Models\DictTags::where('parent_id', $tag_option->id)->get() as $tag)
                        <option value='{{$tag->id}}' style="align-content: left;">{{$tag->root_tag()}}/{{$tag->parent_tag_name()}}/{{$tag->name}}</option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row m-2">
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        //Auto generate slugs from entry
        $('body').on("change", "#eterm", function(){
            $('#eslug').val(string_to_slug($(this).val()));
        })
        function string_to_slug (str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();
        // remove accents, swap ñ for n, etc
        var from = "àáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
        var to   = "aaaaaeeeeiiiioooouuuunc------";
        for (var i=0, l=from.length ; i<l ; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }
        str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes
        return str;
    }


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
            url: '{{url($path."/backend/dictionaries/edit")}}/'+_id,
            type: 'get',
            success: function(response){
                console.log(response);
                $(".id").val(response.entry.id);
                $("#eterm").val(response.entry.term);
                $("#ephonetic").val(response.entry.phonetic);
                //$("#eaudio").val(response.entry.audio);
                $("#eslug").val(response.entry.slug);
                $("#ekeywords").val(response.entry.keywords);
                $("#ealt").val(response.entry.alt);
                //tags
                $("#ecategories option").each(function(){
                    $(this).prop('selected', false);
                })
                for(i in response.tags){
                    $("#ecategories option[value='"+response.tags[i].tag_id+"']").prop("selected", true);
                }
                $("#editEntry").modal('show');
            },
            fail: function(){ alert("Error"); }
        });
    });

    //edit script
    function editEntry(){
        let _url = $("#edit_entry").attr('action');
        fd = $("#edit_entry").serialize();
        console.log(fd);
        $.ajax({
        url: _url,
        type: 'post',
        data: fd,
        success: function(response){
            console.log(response);
            if(response.error === true){
                alert(response.message);
            }else{
                alert("Entry updated.");
                location.reload();
            }
        },
        fail: function(){ alert("Error"); }
        });
        return false;
    }
</script>
