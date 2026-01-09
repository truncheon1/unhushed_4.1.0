<div class="modal-body">
    <form action="{{ url($path.'/backend/dictionaries/term/definitions') }}" method="POST" id="save_definitions" role="form" onsubmit="return saveDefinitions()">
        <input type="hidden" name="id" class="term_id" value=""/>
        <input type="hidden" name="delete_definitions" class="deleted_definitions" value="" />
        @csrf
        <div class="row">
            <div class="col-12">
                <table class="table table-striped" style="width: 100%" id="definitionsTable">
                    <thead>
                        <tr>
                            <th id="part"       colspan="1" style="text-alignleft">Part of Speech</th>
                            <th id="sort"       colspan="1" style="text-align:left">Sort Order</th>
                            <th id="def"        colspan="1" style="text-align:left">Definition</th>
                            <th id="example"    colspan="1" style="text-align:left">Example Sentance</th>
                            <th id="options"    colspan="1">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">
                            <a href="#" class="add-definition">Add Definition</a>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="form-group row m-2">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-primary save-definitions">SAVE</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        //definitionsTable
        _d = $('#definitionsTable').DataTable({});
        //Modals draggable feature
        $("#editDefinitions").draggable({
            handle: ".modal-header"
        });
    });

    //add ability to reorder definitions with draggable sort.
    $("body").on('click', '.definition-edit-link', function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            type: 'get',
            success: function(response){
                console.log(response);
                if(response.error === true){
                    alert(response.message);
                }else{
                    $(".term_id").val(response.data.term.id);
                    _d.clear().draw();
                    if(response.data.definitions.length){
                        for(x = 0; x < response.data.definitions.length; x++){
                            drawRow(
                            response.data.definitions[x].id,
                            response.data.definitions[x].part,
                            response.data.definitions[x].sort,
                            response.data.definitions[x].def,
                            response.data.definitions[x].example
                            );
                        }
                    }
                    $("#editDefinitions").modal('show');
                }
            },
            fail: function(res){ alert('Failed to fetch definitions!'); }
        })
    })

    $("body").on('click', '.add-definition', function(e){
        drawRow();
    });

    $("body").on("click", '.save-definitions', function(e){
        e.preventDefault();
        _data = $("#save_definitions").serialize();
        _url = $("#save_definitions").attr('action');
        console.log(_url);
        $.ajax({
            url: _url,
            type: 'post',
            data: _data,
            success: function(response){
                console.log(response);
                if(response.error === true){
                    alert(response.message);
                }else{
                    alert("Definitions Saved!");
                    //close the modal
                }
            },
            fail: function(){ alert("Error"); }
        });
    });

    $("body").on("click", '.rem-definition', function(e){
        e.preventDefault();
        deleted = $('.deleted_definitions').val();
        if(deleted.length){
            deleted += "|";
        }
        deleted += $(this).attr('data-bs-rel');
        $('.deleted_definitions').val(deleted);
        _d.row($(this).parent().parent()).remove().draw('full-hold');
    });

    function partSpeech(part){
        let option = "article"
        switch(part){
            case 1 :
                option = "noun"
            break;
            case 2 :
                option = "pronoun"
            break;
            case 3 :
                option = "verb"
            break;
            case 4 :
                option = "adjective"
            break;
            case 5 :
                option = "adverb"
            break;
            case 6 :
                option = "preposition"
            break;
            case 7 :
                option = "conjunction"
            break;
            case 8 :
                option = "interjection"
            break;
            case 9 :
                option = "determiner"
            break;
        }
        return `<option value="${part}">${option}</option>`
    };

    function drawRow(id = "", part = "", sort = "", def = "", example = ""){
        let _suffix = "";
        if(id == "")
            _suffix = "_new";
        _d.row.add([
            `<select name="part${_suffix}[${id}]" style="width: 150px;">
                ${partSpeech(part)}
                <option value="1">noun</option>
                <option value="2">pronoun</option>
                <option value="3">verb</option>
                <option value="4">adjective</option>
                <option value="5">adverb</option>
                <option value="6">preposition</option>
                <option value="7">conjunction</option>
                <option value="8">interjection</option>
                <option value="9">determiner</option>
                <option value="10">article</option>
            </select>`, // defaults to 1 = noun on save and update
            `<input type="number" style="width: 50px;" class="form-input" name="sort${_suffix}[${id}]" value="${sort}" />`,
            `<input type="text" class="form-input" name="def${_suffix}[${id}]" value="${def}" />`,
            `<input type="text" style="width: 150px;" class="form-input" name="example${_suffix}[${id}]" value="${example}" />`,
            `<a href="#" data-rel="${id}" class="rem-definition">delete</a>`
        ]).draw( false );
    }
</script>


