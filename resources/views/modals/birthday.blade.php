<!-- Birthday Modal -->
<div class="modal fade" id="birthday" tabindex="-1" role="dialog" aria-labelledby="birthday" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <img class="bday-pic" src="{{ asset('img/banners/bday1.png') }}" alt="Ollie holding four balloons under a banner that reads 'It's our birthday!' in front of falling confetti'.">
                <p class="bday-txt">
                    <span class="diazo2 text-center" style="font-size:25px;">Come celebrate 7 years of UN|HUSHED!</span>
                <br>Thursday, October 24th, 2024 
                <br>at Cheer Up Charlies in Austin, TX!
                <br>
                <br><a href="https://www.eventbrite.com/e/unhusheds-7th-birthday-bash-tickets-1011354387197?aff=oddtdtcreator" target="_blank"><i class="fas fa-birthday-cake"></i> For more details and tickets <i class="fas fa-gifts"></i></a></p>
            </div>
        </div>
    </div>
</div>

<!-- add this to welcome pages -->
        <!-- birthday modal 
        at symbol include('modals.birthday')
-->
<style>
    .bday-pic {
        max-width: 500px;
        width: 100%;
        height: auto;
        text-align: center;
    }
    .bday-txt {
        font-size: 16px;
        padding: 20px 20px 0 20px;
        text-align: center;
    }
</style>

<script type="text/javascript">
    $(window).on('load', function() {
        $('#birthday').modal('show');
    });
</script>
