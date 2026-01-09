<!-- Modal Body -->
<div class="modal-body">
    <form action="{{ url($path.'/backend/dictionaries') }}" method="POST" id="add_entry" role="form" onsubmit="return addEntry()">
        @csrf
        <div class="row pb-2">
            <label for="term" class="col-form-label col-md-2 text-md-right pr-2">Term</label>
            <div class="col-md-8">
                <input id="term" type="text" class="form-control" name="term" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="phonetic" class="col-form-label col-md-2 text-md-right pr-2">Phonetic</label>
            <div class="col-md-8">
                <input id="phonetic" type="text" class="form-control" name="phonetic" value="" placeholder=" [ <b>syllable</b>-syllable ] ">
            </div>
        </div>
        <div class="row pb-2">
            <label for="audio" class="col-form-label col-md-2 text-md-right pr-2">Audio File</label>
            <div class="col-md-8">
                <input id="audio" type="file"  class="form-control" name="audio" accept="audio/*">
            </div>
        </div>
        <div class="row pb-2">
            <label for="slug" class="col-form-label col-md-2 text-md-right pr-1">Slug</label>
            <div class="col-md-8">
                <input id="slug" type="text" class="form-control" name="slug" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="keywords" class="col-form-label col-md-2 text-md-right pr-1">Keywords</label>
            <div class="col-md-8">
                <input id="keywords" type="text" class="form-control" name="keywords" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="alt" class="col-form-label col-md-2 text-md-right pr-1">Alternative Spellings</label>
            <div class="col-md-8">
                <input id="alt" type="text" class="form-control" name="alt" value="">
            </div>
        </div>
        <div class="row pb-2">
            <label for="tags" class="col-form-label col-md-2 text-md-right pr-2">Tags</label>
            <div class="col-md-10">
                <select class="selectpicker" data-live-search="true" id='categories' multiple name='tags[]' title='Select Tags...'>
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
        //Auto generate slugs from name
        $('body').on("change", "#term", function(){
            $('#slug').val(string_to_slug($(this).val()));
        });
        //prevent modal from opening until clicked
        $(".add-entry").on('click', function(e){
            e.preventDefault();
            $("#addEntry").modal('show');
        });
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
                alert("Entry added.");
                document.location = '{{ url($path.'/backend/dictionaries') }}';
            }
        },
        fail: function(){ alert("Error"); }
        });
        return false;
    }

    //Auto generate and clean slug from name
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
</script>
