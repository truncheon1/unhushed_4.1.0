@extends('layouts.app')
@section('content')
    <!-- PAGE CONTENT -->
    <div class="containerBlog">
        <!--  POST CREATOR -->
        <form action="{{url($path.'/backend/blog')}}" method="POST" id="add_post" role="form" onsubmit="return addPost()">
            <input type="hidden" name="file" class="file-name" value=""/>
            @csrf
            <div class="row">
                <!-- LEFT POST -->
                <div class="col-10">
                    <input class="form-control shadow-none bg-transparent outline-none title" id="title" type="text" name="title" value="" placeholder="Title...">
                    <hr>
                    <textarea class="form-control shadow-none bg-transparent outline-none post teditor" id="content" name="content" value="" placeholder="Type post here..." style="min-height: 300px"></textarea>
                    <hr>
                </div>
                <!-- RIGHT SIDEBAR -->
                <div class="col-2 mt-2" style="min-width: 210px">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Preview</button> |
                        <button type="submit" class="btn btn-secondary">Publish</button>
                    </div>
                    <div class="text-center mt-2">
                        <a href="{{url($path.'/backend/blog')}}">Back to Posts</a>
                    </div>
                    <hr>
                    <div class="panel-group" id="accordion">
                        <!-- First Panel -->
                        <div class="panel panel-default">
                            <div class="panel-heading panel-heading-full col-auto">
                                <span class="panel-title pr-5" data-bs-toggle="collapse" data-bs-target="#collapseOne"><b>Status & Visibility</b></span>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <label for="status">Status</label>
                                        </div>
                                        <div class="col-7">
                                            <select class="dropbtn" name="status" id="status">
                                                <option value="draft" selected>Draft</option>
                                                <option value="published" >Published</option>
                                            </select>
                                        </div>
                                        <div class="col-5">
                                            <label for="visiblity">Visibility</label>
                                        </div>
                                        <div class="col-7">
                                            <select class="dropbtn" name="" id="">
                                                <option selected>Public</option>
                                                <option>Private</option>
                                                <option>Password Protected</option>
                                            </select>
                                        </div>
                                        <div class="col-5">
                                            <label for="created_at">Publish</label>
                                        </div>
                                        <div class="col-7">
                                            <select class="dropbtn" name="created_at" id="created_at">
                                                <option value="now" selected>Immediately</option>
                                                <option value="future date" >Calendar Widget</option>
                                            </select>
                                        </div>
                                        <div class="col-5">
                                            <label for="user_id">Author</label>
                                        </div>
                                        <div class="col-7">
                                            <select class="dropbtn" name="user_id" id="user_id">
                                                @foreach($authors as $author)
                                                    <option value="{{ $author['id'] }}">{{ $author['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="slug">Post Slug</label>
                                            <input id="slug" type="text" class="form-control" name="slug" value="" placeholder="url-slug">
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label for="tags">Tags</label>
                                            <input id="tags" type="text" class="form-control" name="tags" value="" placeholder="#unhushed, #sexed">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <!-- Second Panel -->
                        <div class="panel panel-default">
                            <div class="panel-heading panel-heading-full col-auto">
                                <span class="panel-title pr-5" data-bs-toggle="collapse" data-bs-target="#collapseTwo"><b>Excerpt</b></span>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-12">
                                        <input id="description" type="text" class="form-control textarea" name="description" value="" placeholder="description">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <!-- Third Panel -->
                    <div class="panel panel-default">
                        <div class="panel-heading panel-heading-full col-auto">
                            <span class="panel-title pr-5" data-bs-toggle="collapse" data-bs-target="#collapseThree"><b>Featured Image</b></span>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                        <input type="hidden" name="file" class="file-name" />
                                        <div id='imageUploadWide'>
                                            <div class="preview-container"></div>
                                        </div>
                                        <div class="upload-img needsclick text-center">
                                            <i class="fal fa-images"></i> Set featured image
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- IMAGE PREVIEW BUILDER -->
    <div class="img-template">
        <div class="dz-preview dz-file-preview">
            <div class="dz-details">
                <img data-bs-dz-thumbnail class="data-bs-dz-thumbnail" />
                <div class="dz-span">
                    <img src="{{url('img/products/move-alt.png')}}" style="max-width: 19px;" class="drag-handler" /><br/>
                    <img src="{{url('img/products/delete.png')}}" class="delete-img" style="max-width: 19px;" />
                </div>
                <div class="dz-filename"><span data-bs-dz-name></span></div>
            </div>
        </div>
    </div>
    <!-- END -->

    <style type="text/css" media="all">
        .dropbtn {
        background-color: #f0f3fa;
        color: #01a7d3;
        border: none;
        cursor: pointer;
        border: solid #f0f3fa;
        }
        .dropbtn:hover, .dropbtn:focus {
        border: solid #2980B9;
        }
        .dropdown {
        position: relative;
        display: inline-block;
        }
        .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        overflow: auto;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        }
        .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        }
        .dropdown a:hover {background-color: #ddd;}

        .show {display: block;}

        .panel-body{
            padding: 20px;
        }
        .panel-title {
            position: relative;
        }
        .panel-title::after {
            content: "\f107";
            color: #333;
            top: -2px;
            right: 0px;
            position: absolute;
            font-family: "FontAwesome";
        }
        .panel-title[aria-expanded="true"]::after {
            content: "\f106";
        }
        .panel-heading-full.panel-heading {
            padding: 0;
        }
        .panel-heading-full .panel-title {
            padding: 10px 15px;
        }
        .panel-heading-full .panel-title::after {
            top: 10px;
            right: 15px;
        }
        .dz-details, .dz-success-mark, .dz-error-mark, .dz-filename{
            display:none;
        }
        .dz-image img{
            max-width: 200px;
        }
        .needsclick{
            background-color: #f0f0f0;
            cursor: pointer;
            margin: auto;
            width: 200px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-content: center;
            flex-direction: column;
        }
        .needsclick:hover{
            background-color: #e0dcdc;
        }
        .form-control {
            border: none;
        }
        .title{
            font-size: 35px;
            font-weight: bold;
        }
        .post{
            font-size: 25px;
        }
    </style>

    <script src="{{url("js/dropzone/dist/dropzone.js")}}"></script>
    <script src='{{url("js/tinymce/tinymce.min.js")}}'></script>
    <script type="text/javascript">
        $(document).ready(function(){
            //Auto generate slug from name
            $('body').on("change", "#title", function(){
                    $('#slug').val(string_to_slug($(this).val()));
            })
            //Content WYSIWYG
            tinymce.init({
                selector: 'textarea#content',
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

        //Image uploader
        var myDropzone = new Dropzone("div#imageUploadWide", {
            url: base_url + '/upload', // Set the url
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "post",
            uploadMultiple: false,
            maxFiles: 100,
            thumbnailWidth: $("div#imageUploadWide").width(),
            thumbnailHeight: $("div#imageUploadWide").height(),
            //previewTemplate: '.preview-container',
            autoProcessQueue: true,
            acceptedFile: "image/*",
            autoQueue: true, // Make sure the files aren't queued until manually added
            previewsContainer: ".preview-container", // Define the container to display the previews
            clickable: ".needsclick", // Define the element that should be used as click trigger to select files.

            complete: function (file){
                console.log(file);
                $('.upload-img').hide();
                files = $('.preview-container').find('.dz-preview');
                console.log(files.length);
                if(files.length > 1){
                    $('.preview-container').find('.dz-preview').first().remove();
                }
                $('.preview-container').find('.dz-preview').first().find('img').attr('src', file.dataURL);
                console.log("COMPLETE");
            },

            init: function() {
                console.log("INIT");
                this.on("success", function(file, serverResponse) {
                    if(serverResponse.success){
                        $('.actions-image').show();
                        $('.file-name').val(serverResponse.file);
                    }else{
                        alert(serverResponse.reason);
                    }
                });
            }
        });

        //Auto generate slug from name
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

        //DROP DOWNS
        function dropDown1() {
            document.getElementById("dropDown1").classList.toggle("show");
        }
        function dropDown2() {
            document.getElementById("dropDown2").classList.toggle("show");
        }
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>

</section>
@endsection


