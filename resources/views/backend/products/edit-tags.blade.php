<!-- Modal Body -->
<div class="modal-body">
    <form action="{{ url($path.'/backend/products/tags') }}" method="POST" id="save_tags" role="form" onsubmit="return saveTags()">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="table-scroll-container" style="max-height: 500px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px;">
                <table class="table table-striped tags-list mb-0" style="width: 100%">
                    <thead>
                        <tr>
                            <th  colspan="1" style="text-align:left;">ROOT</th>
                            <th  colspan="1" style="text-align:left;">Parent Tag</th>
                            <th  colspan="1" style="text-align:left">Store Tag</th>
                            <th  colspan="1" style="text-align:center;"><i class="fa fa-trash"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\ProductStoreTags::all() as $tag)
                        <tr>
                            <td>
                                <select class="form-control root_tag" name="r[{{$tag->id}}]">
                                    <option value="{{\App\Models\ProductStoreTags::ACTIVITIES}}">Activites...</option>
                                    <option value="{{\App\Models\ProductStoreTags::BOOKS}}" @if($tag->root_id == \App\Models\ProductStoreTags::BOOKS) selected @endif>Books...</option>
                                    <option value="{{\App\Models\ProductStoreTags::GAMES}}" @if($tag->root_id == \App\Models\ProductStoreTags::GAMES) selected @endif>Games...</option>
                                    <option value="{{\App\Models\ProductStoreTags::SWAG}}" @if($tag->root_id == \App\Models\ProductStoreTags::SWAG) selected @endif>Swag...</option>
                                    <option value="{{\App\Models\ProductStoreTags::TEACH}}" @if($tag->root_id == \App\Models\ProductStoreTags::TEACH) selected @endif>Teach...</option>
                                    <option value="{{\App\Models\ProductStoreTags::TRAINING}}" @if($tag->root_id == \App\Models\ProductStoreTags::TRAINING) selected @endif>Training...</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="c[{{$tag->id}}]">
                                    <option value="0">No Parent</option>
                                    @foreach(\App\Models\ProductStoreTags::where('parent_id', 0)->where('root_id', $tag->root_id)->get() as $ptag)
                                    <option value="{{$ptag->id}}" @if($tag->parent_id == $ptag->id) selected @endif>{{$ptag->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                             <td>
                                <input type="text" name="n[{{$tag->id}}]" placeholder="tag name" value="{{$tag->name}}" class="form-control"/>
                            </td>
                            <td style="text-align:center;">
                                <a class="text-danger delete-existing-tag" href="#" data-tag-id="{{$tag->id}}" title="Delete tag"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>
        <div class="form-group row m-4">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-secondary add-tag">Add Tags</button>
                <button type="submit" class="btn btn-secondary save-tags">Save Tags</button>
            </div>
        </div>
    </form>
</div>

<style>
    .table-scroll-container {
        position: relative;
    }
    
    .table-scroll-container .tags-list thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 10;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
    }
    
    .table-scroll-container::-webkit-scrollbar {
        width: 8px;
    }
    
    .table-scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .table-scroll-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    
    .table-scroll-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

@php
    $tagsData = [];
    for($x = 1; $x < 7; $x++) {
        $tagsData[$x] = \App\Models\ProductStoreTags::where('root_id', $x)
            ->where('parent_id', 0)
            ->select('id', 'name')
            ->get()
            ->map(function($tt) {
                return ['id' => $tt->id, 'name' => $tt->name];
            })
            ->toArray();
    }
@endphp

<script>
    $('.edit-tags').on('click', function(){
        $("#editTags").modal('show');
    })

    $("#editTags").draggable({
        handle: ".modal-header"
    });

    const tags = @json($tagsData);
    
    function getTags(root_id){
        return tags[root_id] || [];
    }

    $(document).ready(function(){
        $('.add-tag').on('click', function(){
            _newTagRow = getBlankTag();
            $('.tags-list tbody').append(_newTagRow);
            
            // Auto-scroll to show the new row
            const container = $('.table-scroll-container');
            container.animate({
                scrollTop: container[0].scrollHeight
            }, 300);
        });

        $('body').on('click', '.rem-tag', function(e){
            e.preventDefault();
            $(this).parent().parent().remove();
        })

        $('body').on('click', '.delete-existing-tag', function(e){
            e.preventDefault();
            
            if(!confirm('Are you sure you want to delete this tag? This action cannot be undone.')){
                return;
            }
            
            const tagId = $(this).data('tag-id');
            const row = $(this).closest('tr');
            
            $.ajax({
                url: '{{ url($path."/backend/products/tags/delete") }}',
                type: 'POST',
                data: {
                    id: tagId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response){
                    if(response.error === true){
                        alert(response.message);
                    } else {
                        alert('Tag deleted successfully!');
                        row.fadeOut(300, function(){
                            $(this).remove();
                        });
                    }
                },
                error: function(xhr){
                    alert('Error deleting tag. Please try again.');
                }
            });
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
                        <option value="{{\App\Models\ProductStoreTags::ACTIVITIES}}">Activites...</option>
                        <option value="{{\App\Models\ProductStoreTags::BOOKS}}">Books...</option>
                        <option value="{{\App\Models\ProductStoreTags::GAMES}}">Games...</option>
                        <option value="{{\App\Models\ProductStoreTags::SWAG}}">Swag...</option>
                        <option value="{{\App\Models\ProductStoreTags::TEACH}}">Teach...</option>
                        <option value="{{\App\Models\ProductStoreTags::TRAINING}}">Training...</option>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="category[]">
                        <option value="0">No Parent</option>
                        @foreach(\App\Models\ProductStoreTags::where('parent_id', 0)->where('root_id', 1)->get() as $tag)
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

