
<!-- Modal Header -->
<div class="modal-header">
    <p class="diazo" style="font-size:25px; line-height:30px;">UN|HUSHED: The Elementary School Curriculum</p>
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
</div>
<!-- Modal Body -->
<div class="modal-body">
    <p class="sketchnote-square px-5" style="text-align:justify;">As kids grow up they become aware of the world around them. This curriculum helps you teach them how to navigate those murky waters.
    You can click on the shopping cart icon below for pricing and more information about an annual subscription. We also offering low cost training once a year, and 
    you can contact us at <a href="mailto:info@unhushed.org">info@unhushed.org</a> to chat out pricing for consultation services.
    </p>
    <div class="row justify-content-center pb-1">
            <iframe src="{{ asset('/uploads/subscriptions/unhushed_e_u-00_s-00_fr-activities-list_v01.pdf#toolbar=0') }}" width="80%" height="300">
                This browser does not support PDFs. Please download the PDF to view it: <a href="{{ asset('/uploads/subscriptions/unhushed_e_u-00_s-00_fr-activities-list_v01.pdf') }}">Download PDF</a>
            </iframe>
    </div>
    <div class="row justify-content-center p-2">
        <div class="col-auto">
            <a href="{{ asset('/uploads/subscriptions/unhushed_e_u-00_s-00_fr-activities-list_v01.pdf') }}" target="_blank"><i class="fas fa-file-download"></i></a> Download this activities list.
        </div>
        <div class="col-auto">
            <a href="{{ url($path.'/store/elementary-school-curriculum') }}"><i class="fas fa-shopping-cart"></i> </a>Ready to purchase?
        </div>
    </div>
</div>
