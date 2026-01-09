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
                    Short Link Creator
                </div>
            </div>

            <!--  TABLE -->
            <div class="row mt-2 mb-5">
                <div class="col-auto">
                    <div class="card">
                        <div class="card-header container-fluid">
                            <div class="row">
                                <div class="col-md-10">SHORT LINKS</div>
                                <div class="col-md-2 text-right">
                                    @if(auth()->user()->can('access-master'))
                                    <a class="btn btn-secondary btn-sm" data-bs-toggle="modal"  data-bs-backdrop="static" data-bs-keyboard="false" data-bs-target="#addLink">ADD</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table" id="linksTable">
                                <thead>
                                    <tr>
                                        <th id="bulk">Bulk</th>
                                        <th id="code" style="text-align:center">Short Link</th>
                                        <th id="url" style="text-align:center">URL</th>
                                        <th id="hits" style="text-align:center">Hits</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="row_">
                                        <td style="text-align:center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="bulkSelect"/>
                                            </div>
                                        </td>
                                        <td style="text-align:center"><a href="" to edit>link</a></td>
                                        <td style="text-align:right"><a href="" to edit>https://wwww.unhushed.org/somelink</a></td>
                                        <td style="text-align:center">01</td>
                                        <td style="text-align:center">
                                            <div class="btn-group">
                                                <div class="btn-group">
                                                    <a class="discount-delete" rel="" href="{{url($path.'/backend/short-links/') }}">
                                                        <i class="fa fa-trash" style="color:red" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style="text-align:right"><i class="fas fa-trash"></i></td>
                                        <td colspan="2" style="text-align:left"> Bulk Delete</td>
                                        <td colspan="5"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 my-1">&nbsp;</div>
            </div>
        </div>

        <div class="modal fade" id="addLink" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        ADD SHORT LINK
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form action="{{url($path.'/backend/short-links')}}" method="POST" id="add_link" role="form" onsubmit="return addLink()" aria-label="add_link">
                            @csrf
                            <div class="form-group row">
                                <label for="code" class="col-md-4 col-form-label text-md-right">Link</label>
                                <div class="col-md-6">
                                    <input id="code" type="text" class="form-control" name="link" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="value" class="col-md-4 col-form-label text-md-right">URL</label>
                                <div class="col-md-6">
                                    <input id="value" type="text" class="form-control" name="value" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-auto mx-auto">
                                    <button type="submit" class="btn btn-secondary">SAVE</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <style>
            input[type="checkbox"] {
                line-height: normal;
                margin: 0;
            }
            .modal-header {
                background-color:#f7f7f7;
            }
        </style>

        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript">
            //Stay on table selection
            var t = $('#linksTable').DataTable({
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
            $("#linksTable").on('page.dt', function(){
            var info = t.page.info();
            currentPage = info;
            console.log(currentPage.page);
            urlParams.set('page', currentPage.page);
            history.pushState(null, null, "?"+urlParams.toString());
            });

            //Moveable Modals
            $("#addLink").draggable({
                handle: ".modal-header"
            });
        </script>
    </section>
@endsection





