@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">

        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Backend</a> |
                <span style="font-weight: bold;color:#9acd57">Packages</span>
            </div>
        </div>

            <!--  TABLE -->
            <div class="row mt-2 mb-5">
                <div class="col-10">
                    <div class="card">
                        <div class="card-header container-fluid">
                            <div class="row">
                                <div class="col-md-10">PACKAGES</div>
                                <div class="col-md-2 text-right">
                                    @if(auth()->user()->can('access-master') || auth()->user()->can('modify-products'))
                                    <a href="{{ url($path.'/backend/packages/add') }}">
                                        <button class="btn btn-secondary btn-sm">ADD</button>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table" id="packagesTable">
                                <thead>
                                    <tr>
                                        <th id="image" colspan="1" style="text-align:left">Image</th>
                                        <th id="name" colspan="1" style="text-align:left">Name</th>
                                        <th id="sort" colspan="1" style="text-align:left">Sort</th>
                                        <th id="price" colspan="1" style="text-align:center">Price</th>
                                        <th id="variants" colspan="1" style="text-align:center">Variants</th>
                                        <th id="options" colspan="1" style="text-align:center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Products::where('category','1')->orderBy('sort','ASC')->get() as $product)
                                    <tr id="row_{{$product->id}}">
                                        <td colspan="1"><img class="tinyImg" src="{{ url('uploads/products/'.($product->primary_image_path ?? $product->image)) }}" alt="{{$product->name}}"/></td>
                                        <td colspan="1">{{$product->name}}</td>
                                        <td colspan="1">{{$product->sort}}</td>
                                        <td colspan="1" style="text-align:center">{{$product->display_price ?? '$ 0.00'}}</td>
                                        <td colspan="1" style="text-align:center">{{ $product->availableVars()->count() }}</td>
                                        <td colspan="1" style="text-align:center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item text-success product-edit-link" rel="{{$product->id}}" href="{{ url($path.'/backend/products/'.$product->id.'/edit')}}">Edit</a>
                                                    <a class="dropdown-item text-success product-content-link" rel="{{$product->id}}" href="{{ url($path.'/backend/products/'.$product->id.'/dashboard')}}">Edit Content</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger product-delete" rel="{{$product->id}}" href="#">Delete</a>
                                                </div>
                                            </div>
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
                <div class="col-12 mt-2">&nbsp;</div>
            </div>
        </div>

        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript">
            //TABLE
            var t = $('#packagesTable').DataTable({
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
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
            $("#packagesTable").on('page.dt', function(){
            var info = t.page.info();
            currentPage = info;
            console.log(currentPage.page);
            urlParams.set('page', currentPage.page);
            history.pushState(null, null, "?"+urlParams.toString());
            });
            //DELETE PACKAGE
            $('body').on('click', '.package-delete', function(e){
                e.preventDefault();
                if(!confirm(`Make sure you have no subscribers to this package before deleting it!`)){
                    return false;
                }
                let _id = $(this).attr('rel');
                _url = '{{ url($path.'/backend/packages/') }}/'+_id;
                $.ajax({
                url: _url,
                type: 'delete',
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);

                    }else{
                        alert('Package deleted');
                        location.reload();
                    }
                },
                fail: function(){
                    alert("Error");
                }
                });
            })
        </script>

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
        </style>
    </section>
@endsection





