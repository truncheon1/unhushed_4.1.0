@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
    <section>
        <!-- PAGE CONTENT -->
        <div class="containerLMS">

            <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Dashboard</a> |
                <a href="{{ url($path.'/backend') }}"> Admin Backend</a> |
                <a href="{{ url($path.'/backend/team') }}">Team Members</a> |
                <span style="font-weight: bold;color:#9acd57">Member</span>
            </div>
        </div>

            <!-- TABLE -->
            <div class="row">
                <div class="col-12 mb-5">
                    <div class="card" style="width: 50rem">
                        <div class="card-header">ADD TEAM MEMBER</div>
                        <div class="card-body">
                            <form action="{{url($path.'/backend/team')}}" method="POST" id="add_member" role="form" onsubmit="return addMember()">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                    <label for="first">First</label>
                                    <input id="first" type="text" class="form-control" name="first" placeholder="First">
                                    </div>
                                    <div class="form-group col-md-7">
                                    <label for="last">Last, Credintials</label>
                                    <input id="last" type="text" class="form-control" name="last" placeholder="Last">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                    <label for="title">Title</label>
                                    <input id="title" type="text" class="form-control" name="title" placeholder="Title">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="slug">Slug</label>
                                        <input id="slug" type="text" class="form-control" name="slug" placeholder="www.unhushed.org/slug">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="dept">Department</label>
                                        <select class="form-select" id="dept" name="dept">
                                            <option selected>Choose...</option>
                                            <option value="Executive Staff.Team: Execs, Team: All">Executive Staff</option>
                                            <option value="Field Members">Field Members</option>
                                            <option value="Board of Directors">Board of Directors</option>
                                            <option value="Curricula Advisory Council">Curricula Advisory Council</option>
                                            <option value="Legal Advisory Council">Legal Advisory Council</option>
                                            <option value="Medical Advisory Council">Medical Advisory Council</option>
                                            <option value="Youth Advisory Council">Youth Advisory Council</option>
                                            <option value="Volunteers">Volunteers</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                    <label for="ac_tags">AC Tags</label>
                                        <select class="form-select" id="ac_tags" name="ac_tags">
                                            <option selected>Choose...</option>
                                            <option value="Team: Execs, Team: All">Executive Staff</option>
                                            <option value="Team: Field, Team: All">Field Members</option>
                                            <option value="Team: BoD, Team: All">Board of Directors</option>
                                            <option value="Team: CAC, Team: All">Curricula Advisory Council</option>
                                            <option value="Team: LAC, Team: All">Legal Advisory Council</option>
                                            <option value="Team: MAC, Team: All">Medical Advisory Council</option>
                                            <option value="Team: YAC, Team: All">Youth Advisory Council</option>
                                            <option value="Team: Volunteers, Team: All">Volunteers</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                    <label for="bio">Bio</label>
                                        <textarea class="form-control teditor" name="bio" id="bio"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Headshot</label>
                                    <div class="col-md-9">
                                        <input type="hidden" name="file" class="file-name1" />
                                        <div id='imageUploadWide1' class='needsclick'>
                                            <div class='upload-img1 needsclick'>
                                                <i class="fa-regular fa-cloud-arrow-up"></i><br/>
                                            </div>
                                            <div class="preview-container1 needsclick">
                                            </div>
                                            <span class="note text-dark">
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
                <div class="col-12 pb-5">&nbsp;</div>
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
                    selector: 'textarea#bio',
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

                $('body').on("change", "#first", function(){
                    $('#slug').val(string_to_slug($(this).val()));
                })
            });
            var myDropzone = new Dropzone("div#imageUploadWide1", {
                url: base_url + '/upload', // Set the url
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "post",
                uploadMultiple: false,
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

            function addMember(){
                let _url = $("#add_member").attr('action');
                fd = $("#add_member").serialize();
                $.ajax({
                    url: _url,
                    type: 'post',
                    data: fd,
                    success: function(response){
                        console.log(response);
                        if(response.error === true){
                            alert(response.message);
                        }else{
                            alert("Team member added.");
                            document.location = '{{ url($path.'/backend/team') }}';
                        }
                    },
                    fail: function(){ alert("Error"); }
                });
                return false;
            }

            //Auto generate and clean slug from name
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
