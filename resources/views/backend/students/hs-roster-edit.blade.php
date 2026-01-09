<!-- Modal Header -->
<div class="modal-header">
    EDIT STUDENT INFO
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
</div>
<!-- Modal Body -->
<div class="modal-body">
    <form action="{{url($path.'/backend/hs-roster')}}" method="POST" id="edit_student" role="form" onsubmit="return editStudent()" aria-label="edit_student">
        @if(count($details))    
        <input type="hidden" name="id" id="eid" value="{{$detail['id']}}" />
        @csrf
        <p><b>Youth's info:</b></p>
        <div class="form-group row">
            <label for="ename" class="col-2 col-form-label text-md-right">Full Name</label>
            <div class="col-md-6">
                <input id="ename" type="text" class="form-control" name="youth_name" >
            </div>
            <label for="eage" class="col-1 col-form-label text-md-right">Age</label>
            <div class="col-md-2">
                <input id="eage" type="number" class="form-control"  name="age" min="14" max="17">
            </div>
        </div>
        <div class="form-group row">
            <label for="egrade" class="col-2 col-form-label text-md-right">Grade</label>
            <div class="col-md-2">
                <input id="egrade" type="number" class="form-control" name="grade" min="9" max="12">
            </div>
            <label for="egender" class="col-2 col-form-label text-md-right">Gender Identity</label>
            <div class="col-4">
                <select id="egender" class="form-control" name="gender_identity">
                    <option value="girl">Girl           </option>
                    <option value="boy">Boy             </option>
                    <option value="nonbinary">Nonbinary </option>
                    <option value="trans">Trans         </option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="epronouns" class="col-2 col-form-label text-md-right">Pronouns</label>
            <div class="col-md-3">
                <input id="epronouns" type="text" class="form-control" name="pronouns">
            </div>
            <label for="ekid_phone" class="col-md-2 col-form-label text-md-right">Kid's phone</label>
            <div class="col-md-4">
                <input id="ekid_phone" type="text" class="form-control" name="youth_phone" placeholder="optional">
            </div>
        </div>
        <div class="form-group row">
            <label for="eemail" class="col-md-2 col-form-label text-md-right">Kid's email</label>
            <div class="col-md-6">
                <input id="eemail" type="text" class="form-control" name="youth_email" placeholder="optional">
            </div>
        </div>
        <p><b>Paperwork:</b></p>
        <div class="form-group row">
            <label for="epaid" class="col-md-2 col-form-label text-md-right">Payments</label>
            <div class="col-md-2 input-group mb-3">
                <span class="input-group-text">$</span>
                <input id="epaid" type="text" class="form-control" name="paid" aria-label="Amount">
            </div>
            <label for="elegal" class="col-md-2 col-form-label text-md-right">Contract Status</label>
            <div class="col-4">
                <select id="elegal" class="form-control" name="legal">
                    <option value="✓"> ✓-everything completed                      </option>
                    <option value="sent">contract sent                              </option>
                    <option value="resent">contract resent                          </option>
                    <option value="need to send">need to send                       </option>
                    <option value="orientation needed">parent orientation needed    </option>
                </select>
            </div>
        </div>
        <p><b>Parent 1 info:</b></p>
        <div class="form-group row">
            <label for="eparent1_name" class="col-md-2 col-form-label text-md-right">Full name</label>
            <div class="col-md-6">
                <input id="eparent1_name" type="text" class="form-control" name="parent1_name">
            </div>
        </div>
        <div class="row form-group">
            <label for="eparent1_email" class="col-md-2 col-form-label text-md-right">Email</label>
            <div class="col-md-6">
                <input id="eparent1_email" type="text" class="form-control" name="parent1_email">
            </div>
        </div>
        <div class="row form-group">
            <label for="eparent1_phone" class="col-md-2 col-form-label text-md-right">Phone</label>
            <div class="col-md-6">
                <input id="eparent1_phone" type="text" class="form-control" name="parent1_phone">
            </div>
        </div>
        <p><b>Parent 2 info:</b></p>
        <div class="form-group row">
            <label for="eparent2_name" class="col-md-2 col-form-label text-md-right">Full name</label>
            <div class="col-md-6">
                <input id="eparent2_name" type="text" class="form-control" name="parent2_name">
            </div>
        </div>
        <div class="row form-group">
            <label for="eparent2_email" class="col-md-2 col-form-label text-md-right">Email</label>
            <div class="col-md-6">
                <input id="eparent2_email" type="text" class="form-control" name="parent2_email">
            </div>
        </div>
        <div class="row form-group">
            <label for="eparent2_phone" class="col-md-2 col-form-label text-md-right">Phone</label>
            <div class="col-md-6">
                <input id="eparent2_phone" type="text" class="form-control" name="parent2_phone">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-auto mx-auto">
                <button type="submit" class="btn btn-secondary">SAVE</button>
            </div>
        </div>
        @endif
    </form>
</div>

<script>
    //modal draggable
    $("#editStudentMaster").draggable({
        handle: ".modal-header"
    });
    /***********************************************
         * EDIT STUDENT
         **********************************************/
        function editStudent(){
            let _url = $("#edit_student").attr('action');
            fd = $("#edit_student").serialize();
            $.ajax({
                url: _url,
                type: 'patch',
                data: fd,
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        alert("Student updated");
                        location.reload();
                        $("#editStudent").modal('hide');
                    }
                },
                fail: function(){
                    alert("Error");
                }
            });
            return false;
        };
        $('body').on('click', '.student-edit-link', function(e){
            e.preventDefault();
            _url = $(this).attr('href');
            $.ajax({
                url: _url,
                type: 'get',
                success: function(response){
                    console.log(response);
                    if(response.error === true){
                        alert(response.message);
                    }else{
                        $("#eid").val(response.student.id);
                        $("#ename").val(response.student.kid_name);
                        $("#ekid_email").val(response.student.kid_email);
                        $("#ekid_phone").val(response.student.kid_phone);
                        $("#eage").val(response.student.age);
                        $("#egrade").val(response.student.grade);
                        $("#egender").val(response.student.gender);
                        $("#epronouns").val(response.student.pronouns);
                        $("#epaid").val(response.student.paid);
                        $("#elegal").val(response.student.legal);
                        $("#eparent1_name").val(response.student.parent1_name);
                        $("#eparent1_email").val(response.student.parent1_email);
                        $("#eparent1_phone").val(response.student.parent1_phone);
                        $("#eparent2_name").val(response.student.parent2_name);
                        $("#eparent2_email").val(response.student.parent2_email);
                        $("#eparent2_phone").val(response.student.parent2_phone);
                        $("#editStudent").modal('show');
                    }
                },
                fail: function(){ alert("Error"); }
            });
        });
</script>








