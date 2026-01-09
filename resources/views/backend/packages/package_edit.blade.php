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
                <a href="{{ url($path.'/backend/products') }}"> Products</a> |
                <span style="font-weight: bold;color:#9acd57">Edit Packages</span>
            </div>
        </div>

            <!--  TABLE -->
            <div class="row mt-2 mb-5">
                <div class="col-12">
                    <div class="card" style="width: 50rem">
                        <div class="card-header container-fluid">
                            <div class="row">
                                <div class="col-md-10">EDIT PACKAGES</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ url($path.'/backend/packages') }}" method="POST" id="edit_package" role="form" onsubmit="return editPackage()">
                                <input type="hidden" name="id" value="{{$package->id}}" />
                                @csrf
                                <div class="form-group row">
                                    <label for="ename" class="col-md-2 col-form-label text-md-right">Name</label>
                                    <div class="col-md-10">
                                        <input id="ename" type="text" class="form-control" name="name" value="{{$package->name}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="slug" class="col-md-2 col-form-label text-md-right">Short Link (slug)</label>
                                    <div class="col-md-10">
                                        <input id="slug" type="text" class="form-control" name="slug" value="{{$package->slug}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="ac_tags" class="col-md-2 col-form-label text-md-right">AC Tags (comma separated)</label>
                                    <div class="col-md-10">
                                        <input id="ac_tags" type="text" class="form-control" name="ac_tags" value="{{$package->ac_tags}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="ecurrencies" class="col-md-2 col-form-label text-md-right">Currency</label>
                                    <div class="col-md-10">
                                        <select class="form-control" id="ecurrencies" name="currency_id">
                                            @foreach(\App\Models\Currency::all() as $c)
                                                <option value="{{$c->id}}" @if($package->currency_id === $c->id) selected @endif)>{{$c->name}} ({{$c->iso}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="elanguages" class="col-md-2 col-form-label text-md-right">Language</label>
                                    <div class="col-md-10">
                                        <select class="form-control" id="elanguages" name="language_id">
                                            @foreach(\App\Models\Languages::all() as $c)
                                                <option value="{{$c->id}}" @if($c->id === $package->language_id) selected @endif>{{$c->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="sort" class="col-md-2 col-form-label text-md-right">Sort</label>
                                    <div class="col-md-10">
                                        <input id="sort" type="number" class="form-control" name="sort" value="{{$package->sort}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="description" class="col-md-2 col-form-label text-md-right">Description</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control teditor" name="description" id="description">{!!$package->description!!}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-md-right">Product Image</label>
                                    <div class="col-md-10">
                                        <input type="hidden" name="file" class="file-name1" value="{{$package->image}}" />
                                        <div id='imageUploadWide1' class='needsclick'>
                                            <div class='upload-img1 needsclick'>
                                                <div class="dz-image">
                                                    <img  src='{{ url('uploads/products/'.$package->image) }}'/>
                                                </div>
                                                <br/>
                                            </div>
                                            <div class="preview-container1 needsclick">
                                            </div>
                                            <span class='note'>
                                                Click or drop image to upload.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mt-5">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-2">&nbsp;</div>
            </div>
        </div>

        <!-- dropzone -->
        <style type="text/css">
            .dz-details, .dz-success-mark, .dz-error-mark, .dz-filename{
                display:none;
            }
            .dz-image img{
                max-width: 200px;
            }
            .needsclick{
                cursor: pointer;
            }
        </style>

        <script src='{{url("js/dropzone/dist/dropzone.js")}}'></script>
        <script src='{{url("js/tinymce/tinymce.min.js")}}'></script>

        <script type="text/javascript">
            $(document).ready(function(){
                tinymce.init({
                    selector: 'textarea#description',
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

                $('body').on("change", "#ename", function(){
                    $('#slug').val(string_to_slug($(this).val()));
                })
            });
            var myDropzone = new Dropzone("div#imageUploadWide1", {
                url: base_url + '/upload', // Set the url
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                uploadMultiple: false,
                method: "post",
                maxFiles: 100,
                thumbnailWidth: $("div#imageUploadWide1").width(),
                thumbnailHeight: $("div#imageUploadWide1").height(),
                //previewTemplate: '.preview-container',
                autoProcessQueue: true,
                acceptedFile: "image/*",
                autoQueue: true, // Make sure the files aren't queued until manually added
                previewsContainer: ".preview-container1", // Define the container to display the previews
                clickable: ".needsclick", // Define the element that should be used as click trigger to select files.

                complete: function (file){
                    console.log(file);
                    $('.upload-img1').hide();
                    files = $('.preview-container1').find('.dz-preview');
                    console.log(files.length);
                    if(files.length > 1){
                        $('.preview-container1').find('.dz-preview').first().remove();
                    }
                    $('.preview-container1').find('.dz-preview').first().find('img').attr('src', file.dataURL);
                    console.log("COMPLETE");
                },
                init: function() {
                    console.log("INIT");
                    this.on("success", function(file, serverResponse) {
                        if(serverResponse.success){
                            $('.actions-image1').show();
                            $('.file-name1').val(serverResponse.file);
                        }else{
                            alert(serverResponse.reason);
                    }
                });
                }
            });

            function editPackage(){
                let _url = $("#edit_package").attr('action');
                fd = $("#edit_package").serialize();
                $.ajax({
                    url: _url,
                    type: 'patch',
                    data: fd,
                    success: function(response){
                        console.log(response);
                        if(response.error === true){
                            alert(response.message);

                        }else{
                            alert("Package updated!");
                            document.location = '{{ url($path.'/backend/packages') }}';
                        }
                    },
                    fail: function(){ alert("Error"); }
                });
                return false;
            }

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
    </section>
@endsection





