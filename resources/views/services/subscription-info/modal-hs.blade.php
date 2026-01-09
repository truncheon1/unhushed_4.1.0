
<!-- Modal Header -->
<div class="modal-header">
<p class="diazo" style="font-size:35px; line-height:30px;">UN|HUSHED: The High School Curriculum</p>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
</div>
<!-- Modal Body -->
<div class="modal-body">
    <p class="sketchnote-square">This curriculum is designed to support teenagers ages 15 - 18 as they consider sex and sexuality as it influences and is influenced by themselves,
        their peers, and the larger world.
        To this end, every unit refers back to the Social Justice Compass as the underpinning for discussing sex and sexuality at every turn.
    </p>
    <div class="row justify-content-center">
            <iframe src="{{ asset('/uploads/lists/unhushed_high-school-activities-list_v03.pdf#toolbar=0') }}" width="80%" height="300">
                This browser does not support PDFs. Please download the PDF to view it: <a href="{{ asset('/uploads/lists/unhushed_high-school-activities-list_v03.pdf') }}">Download PDF</a>
            </iframe>
    </div>
    <div class="row p-2">
        <div class="col-auto">
            <a href="{{ asset('/uploads/lists/unhushed_high-school-activities-list_v03.pdf') }}" target="_blank"><i class="fas fa-file-download"></i></a> Download this activities list.
        </div>
        <div class="col-auto">
            <a href="{{ url($path.'/store/high-school-curriculum') }}"><i class="fas fa-shopping-cart"></i> </a>Ready to purchase?
        </div>
    </div>
</div>
