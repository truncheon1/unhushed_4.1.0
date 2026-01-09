{{-- 
    Address Form JavaScript Handler
    
    This script handles:
    - Country change (US state dropdown vs international province input)
    - Form validation
    - Must be included after the address form partial
--}}

<script type="text/javascript">
(function() {
    // Country change handler - show/hide state vs province
    $('#country').on('change', function(){
        const country = $(this).val();
        if(country === 'US'){
            $('#state-group').show();
            $('#state').prop('required', true);
            $('#province-group').hide();
            $('#province').prop('required', false);
            $('#province').val('');
        } else {
            $('#state-group').hide();
            $('#state').prop('required', false);
            $('#state').val('');
            $('#province-group').show();
            $('#province').prop('required', true);
        }
    });
    
    // Initialize on page load - trigger country change to set initial state
    $(document).ready(function(){
        $('#country').trigger('change');
    });
})();
</script>
