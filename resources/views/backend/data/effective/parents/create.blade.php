@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
    <section>
        <!-- PAGE CONTENT -->
        <div class="containerLMS">

            <!-- breadcrumbs -->
            <div class="row">
                <div class="backendcrumbs">
                    <a href="{{ url($section.'backend') }}">Admin Backend</a> |
                    <a href="{{ url($section.'backend/effective') }}">Effectiveness Data</a> |
                    <a href="{{ url($section.'effective/parents') }}">Parents
                </div>
            </div>

            <!--  TABLE -->
            <div class="row mt-2 mb-5">
                <div class="col-12">
                    <div class="card" style="width: 50rem">
                        <div class="card-header container-fluid">
                            <div class="row">
                                <div class="col-md-10">ADD PARENT INFO</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{url('effective/parents')}}" method="POST" id="add_parent" role="form" onsubmit="return addParent()">
                                <input type="hidden" name="file" class="file-name" value=""/>
                                @csrf
                                <div class="form-group row">
                                    <label for="family" class="col-md-2 col-form-label text-md-right">Family</label>
                                    <div class="col-md-10">
                                        <input id="family" type="text" class="form-control" name="family" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <input id="fname" type="text" class="form-control" name="fname" value="" placeholder="First name">
                                    </div>
                                    <div class="col-md-10">
                                        <input id="lname" type="text" class="form-control" name="lname" value="" placeholder="Last name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="etags" class="col-md-2 col-form-label text-md-right">AC Tag</label>
                                    <div class="col-md-10">
                                        <input id="etags" type="text" class="form-control" name="tags" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="type" class="col-md-2 col-form-label text-md-right">Product Type</label>
                                    <div class="col-md-10">
                                        <select class="form-select" id="type" name="type">
                                            <option selected>Choose...</option>
                                            <option value="activity">activity</option>
                                            <option value="book">book</option>
                                            <option value="training">training</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="price" class="col-md-2 col-form-label text-md-right">Price</label>
                                    <div class="col-md-10">
                                        <input id="price" type="text" class="form-control" name="price" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tax" class="col-md-2 col-form-label text-md-right">Tax(%)</label>
                                    <div class="col-md-10">
                                        <input id="tax" type="text" class="form-control" name="tax" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="weight" class="col-md-2 col-form-label text-md-right">Weight</label>
                                    <div class="col-md-10">
                                        <input id="weight" type="text" class="form-control" name="weight" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="description" class="col-md-2 col-form-label text-md-right">Description</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control teditor" name="description" id="description"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="file" class="col-md-2 col-form-label text-md-right">Product Image</label>
                                    <div class="col-md-10">
                                        <input type="hidden" name="file" class="file-name1" />
                                        <div id='imageUploadWide1' class='needsclick'>
                                            <div class='upload-img1 needsclick'>
                                                <i class="fa-regular fa-cloud-arrow-up"></i><br/>
                                            </div>
                                            <div class="preview-container1 needsclick">
                                            </div>
                                            <span class='note'>
                                                Click or drop image to upload.
                                            </span>
                                        </div>
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
            <div class="row mt-2 mb-5">
                <div class="mb-5">
                    <p></p>
                </div>
            </div>
        </div>

        <script src="{{url("js/dropzone/dist/dropzone.js")}}"></script>
        <script src='{{url("js/tinymce/tinymce.min.js")}}'></script>
        <script type="text/javascript">
            function addParent(){
                let _url = $("#add_parent").attr('action');
                fd = $("#add_parent").serialize();
                $.ajax({
                url: _url,
                type: 'post',
                data: fd,
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert("Parent added.");
                        document.location = '{{url('effective/parents')}}';
                    }
                },
                fail: function(){
                    alert("Error");
                }
                });
                return false;
            }
        </script>

        <style type="text/css" media="all">
            body{
                background-color: #fff;
            }
        </style>
    </section>
@endsection





