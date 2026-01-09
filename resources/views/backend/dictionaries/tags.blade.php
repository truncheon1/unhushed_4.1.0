<!-- Modal Body -->
<div class="modal-body">
    <form action="{{ url($path.'/backend/dictionaries/tags') }}" method="POST" id="save_tags" role="form" onsubmit="return saveTags()">
        @csrf
        <div class="row">
            <div class="col-12">
                <table class="table table-striped tags-list" style="width: 100%">
                    <thead>
                        <tr>
                            <th  colspan="1" style="text-alignleft">ROOT</th>
                            <th  colspan="1" style="text-align:left">Parent Tag</th>
                            <th  colspan="1" style="text-align:left">Dictionary Tag</th>
                            <th  colspan="1">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\DictTags::all() as $tag)
                        <tr>
                            <td>
                                <select class="form-control root_tag" name="r[{{$tag->id}}]">
                                    <option value="{{\App\Models\DictTags::GENDER}}">Gender...</option>
                                    <option value="{{\App\Models\DictTags::ORIENTATION}}"   @if($tag->root_id == \App\Models\DictTags::ORIENTATION)     selected @endif>Orientation...</option>
                                    <option value="{{\App\Models\DictTags::MEDICAL}}"       @if($tag->root_id == \App\Models\DictTags::MEDICAL)         selected @endif>Medical...</option>
                                    <option value="{{\App\Models\DictTags::FACILITATION}}"  @if($tag->root_id == \App\Models\DictTags::FACILITATION)    selected @endif>Facilitation...</option>
                                    <option value="{{\App\Models\DictTags::SLANG}}"         @if($tag->root_id == \App\Models\DictTags::SLANG)           selected @endif>Slang...</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="c[{{$tag->id}}]">
                                    <option value="0">No Parent</option>
                                    @foreach(\App\Models\DictTags::where('parent_id', 0)->where('root_id', $tag->root_id)->get() as $ptag)
                                    <option value="{{$ptag->id}}" @if($tag->parent_id == $ptag->id) selected @endif>{{$ptag->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                             <td>
                                <input type="text" name="n[{{$tag->id}}]" placeholder="tag name" value="{{$tag->name}}" class="form-control"/>
                            </td>
                            <td>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6">
                            <a href="#" class="add-tag">Add Tag</a>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="form-group row m-2">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-primary save-tags">Save Tags</button>
            </div>
        </div>
    </form>
</div>

<script>
    //modal draggable
    $("#editTags").draggable({
        handle: ".modal-header"
    });
    //add tags
    $('.edit-tags').on('click', function(){
        $("#editTags").modal('show');
    })
    var tags = {};
    @for($x = 1; $x < 7; $x++)
        tmp = [];
        @foreach(\App\Models\DictTags::where('root_id', $x)->where('parent_id', 0)->get() as $tt)
            tmp.push({
                'id': {{$tt->id}},
                'name': `{{$tt->name}}`
            })
        @endforeach
        tags[{{$x}}] = tmp;
    @endfor
    function getTags(root_id){
        return tags[root_id];
    }

    $(document).ready(function(){
        $('.add-tag').on('click', function(){
            _newTagRow = getBlankTag();
            $('.tags-list tbody').append(_newTagRow);
        });
        $('body').on('click', '.rem-tag', function(e){
            e.preventDefault();
            $(this).parent().parent().remove();
        })
        $('body').on('change', '.root_tag', function(e){
            e.preventDefault();
            _options = `<option value="0">No Parent</option>`;
            _tags = getTags($(this).val());
            for(_t in _tags){
                _options += `<option value="${_tags[_t].id}">${_tags[_t].name}</option>`
            }
            $(this).parent().parent().find('td').eq(1).find('select').first().html(_options);
        })
    })

    function getBlankTag(){
        let _row = `
            <tr>
                <td>
                    <select class="form-control root_tag" name="root[]">
                        <option value="{{\App\Models\DictTags::GENDER}}">Gender...</option>
                        <option value="{{\App\Models\DictTags::ORIENTATION}}">Orientation...</option>
                        <option value="{{\App\Models\DictTags::MEDICAL}}">Medical...</option>
                        <option value="{{\App\Models\DictTags::FACILITATION}}">Facilitation...</option>
                        <option value="{{\App\Models\DictTags::SLANG}}">Slang...</option>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="category[]">
                        <option value="0">No Parent</option>
                        @foreach(\App\Models\DictTags::where('parent_id', 0)->where('root_id', 1)->get() as $tag)
                            <option value="{{$tag->id}}">{{$tag->name}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="name[]" placeholder="tag name" class="form-control"/>
                </td>
                <td>
                    <a class="text-danger rem-tag" href="#"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            `;
        return _row;
    }

    function saveTags(){
        _data = $("#save_tags").serialize();
        _url = $("#save_tags").attr('action');
        $.ajax({
            url: _url,
            type: 'post',
            data: _data,
            success: function(response){
                console.log(response);
                if(response.error === true){
                    alert(response.message);
                }else{
                    alert("Tags Saved!");
                    location.reload();
                }
            },
            fail: function(){ alert("Error"); }
        });
        return false;
    }
</script>

