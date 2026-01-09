<!-- MS-Class Modal -->
<div class="modal fade" id="ms-class" tabindex="-1" role="dialog" aria-labelledby="ms-class" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <img class="m-class-pic" src="{{ asset('img/banners/m-class.png') }}" alt="Ollie listening to music with headphones on.">
                <p style="text-align:center"><b>Don't miss your last chance!</b></p>
                <p class="m-class-txt">Classes start in person Sept. 11th. If you haven't attended the required parent orientation please contact us at 
                    <a href="mailto:info@unhushed.org?subject=UN|HUSHED ms classes">info@unhushed.org</a> for more information and/or to be put on the wait list for next year.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .m-class-pic {
        max-width: 500px;
        width: 100%;
        height: auto;
        text-align: center;
    }
    .m-class-txt {
        font-size: 16px;
        padding: 20px 20px 0 20px;
        text-align: justify;
    }
</style>

<script type="text/javascript">
    $(window).on('load', function() {
        $('#ms-class').modal('show');
    });
</script>
