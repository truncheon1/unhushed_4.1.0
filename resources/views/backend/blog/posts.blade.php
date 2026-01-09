@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
    <section>
        <!-- PAGE CONTENT -->
        <div class="containerLMS">

            <!-- breadcrumbs -->
            <div class="row">
                <div class="backendcrumbs">
                    <a href="{{ url($path.'/backend') }}"> Admin Backend</a> |
                    <span style="font-weight: bold;color:#9acd57">posts</span>
                </div>
            </div>

            <!--  TABLE -->
            <div class="row mt-2 mb-5">
                <div class="col-auto">
                    <div class="card">
                        <div class="card-header container-fluid">
                            <div class="row">
                                <div class="col-md-8">Blog Posts</div>
                                <div class="col-md-4 text-right">
                                    @if(auth()->user()->can('access-master') || auth()->user()->can('modify-blog'))
                                    <a href="{{ url($path.'/backend/blog/new-post') }}">
                                        <button class="btn btn-secondary btn-sm">ADD</button>
                                    </a>
                                    @endif
                                    | <a href="{{ url($path.'/more/blog') }}"><button class="btn btn-secondary btn-sm">VIEW</button></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 px-4">
                                    <div class="table-responsive">
                                        <table class="table" id="postsTable">
                                            <thead>
                                                <tr>
                                                    <th id="title" colspan="1" style="text-align:left">Title</th>
                                                    <th id="author" colspan="1" style="text-align:left">Author</th>
                                                    <th id="status" colspan="1" style="text-align:center">Status</th>
                                                    <th id="tags" colspan="1" style="text-align:center">Tags</th>
                                                    <th id="comments" colspan="1" style="text-align:center">Comments</th>
                                                    <th id="date" colspan="1" style="text-align:center">Date Posted</th>
                                                    <th id="options" colspan="1" style="text-align:center"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($posts as $post)
                                                <tr id="row_{{$post->id}}}">
                                                    <td colspan="1"><a rel="{{$post->id}}" href="{{ url($path.'/backend/blog/post-'.$post->id.'/edit')}}">{{$post->title}}</a></td>
                                                    <td colspan="1">{{ $post->user->name }}</td>
                                                    <td colspan="1" style="text-align:center">{{ $post->status }}</td>
                                                    <td style="word-wrap: break-word;min-width: 160px;max-width: 160px;">{{$post->tags}}</td>
                                                    <td colspan="1" style="text-align:center">{{$post->comments}}</td>
                                                    <td colspan="1" style="text-align:center">{{ date('Y/m/d', strtotime($post->updated_at)) }}</td>
                                                    <td colspan="1" style="text-align:center">
                                                        <a class="text-danger post-delete" rel="{{$post->id}}" href="#"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="7"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-2">&nbsp;</div>
            </div>
        </div>

        <style type="text/css" media="all">
            .blue{
                background-color: #265a8e;
                color: white;
                font-size: small;
            }
            .tinyImg{
                width: 45px;
                height: 45px;
                overflow: hidden;
            }
            .modal-header {
                background-color:#f7f7f7;
            }
            .modal-dialog{
                overflow-y: initial !important
            }
            .modal-body{
                height: 80vh;
                overflow-y: auto;
            }
        </style>

        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript">
            var t = $('#postsTable').DataTable({
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                pagingType: 'full_numbers'
            });
            var currentPage = 0;
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            if(urlParams.has('page')){
                currentPage = urlParams.get('page') * 1; //force number
            }
            $('document').ready(function(){
                    t.page(currentPage).draw(false);
            })
            $("#postsTable").on('page.dt', function(){
            var info = t.page.info();
            currentPage = info;
            console.log(currentPage.page);
            urlParams.set('page', currentPage.page);
            history.pushState(null, null, "?"+urlParams.toString());
            });

            //POST DELETE
            $('body').on('click', '.post-delete', function(e){
                e.preventDefault();
                if(!confirm(`Are you sure you want to delete this post?`)){
                    return false;
                }
                let _id = $(this).attr('rel');
                _url = '{{ url($path.'/backend/blog/') }}/'+_id;
                $.ajax({
                url: _url,
                type: 'delete',
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);

                    }else{
                        alert('Post deleted');
                        location.reload();
                    }
                },
                fail: function(){ alert("Error"); }
                });
            });
        </script>

    </section>
@endsection





