@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
    <section>
        <!-- PAGE CONTENT -->
        <div class="containerLMS">

            <!-- breadcrumbs -->
            <div class="row">
                <div class="backendcrumbs">
                    <a href="{{ url($section.'backend') }}"> Admin Backend</a> |
                    <a href="{{ url($section.'backend/statistics') }}">Statistics</a>
                </div>
            </div>

            <!--  TABLE -->
            <div class="row mt-2 mb-5">
                <div class="col-10">
                    <div class="card" >
                        <div class="card-header">ADD STATISTICAL RESEARCH CITATION</div>
                        <div class="card-body">
                            <form action="{{url('backend/statistics')}}" method="POST" id="add_entry" role="form" onsubmit="return addEntry()">
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
                    </div>
                </div>
            </div>
        </div>

        <script src='{{url("js/dropzone/dist/dropzone.js")}}'></script>
        <script src='{{url("js/tinymce/tinymce.min.js")}}'></script>

        <script type="text/javascript">
            $(document).ready(function(){
                tinymce.init({
                    selector: 'textarea#abstract',
                    license_key: 'gpl',
                    height: 500,
                    plugins: 'importcss searchreplace autolink code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help charmap',
                    paste_as_text: true,
                    menubar: false,
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                    contextmenu: 'paste | link image inserttable | cell row column deletetable'
                });
                // Prevent Bootstrap dialog from blocking focusin
                document.addEventListener('focusin', function (e) { 
                    if (e.target.closest('.tox-tinymce-aux, .moxman-window, .tam-assetmanager-root') !== null) { 
                        e.stopImmediatePropagation();
                    } 
                });
            });
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
                        document.location = '{{url('backend/statistics')}}';

                    }
                },
                fail: function(){
                    alert("Error");
                }
                });
                return false;
            }
        </script>
    </section>
@endsection
