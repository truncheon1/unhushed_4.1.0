/**
 * UNHUSHED Training Product Handler
 * Handles training products (category = 7) with participant-based pricing
 */

(function() {
    'use strict';
    
    $(document).ready(function() {
        const productCategory = parseInt($('section[data-product-category]').data('product-category'));
        
        // Only run for training products
        if (productCategory !== 7) {
            return;
        }
        
        console.log('[TRAINING] Initializing training product handler');
        
        // Show all descriptions assigned to all variants (null variant_ids) on page load
        $('.product-description').each(function() {
            const variantIds = $(this).data('variant-ids');
            if (variantIds === null || variantIds === 'null') {
                $(this).show();
            }
        });
        
        // Handle quantity changes (participant count)
        $("#qty").on("change", function() {
            if ($(this).val() < 1) {
                $(this).val(1);
            }
            
            const varId = $("input[name='var_id']").val();
            const qty = parseInt($(this).val()) || 1;
            const productId = $("input[name='item_id']").val();
            const basePath = window.location.pathname.split('/')[1]; // Extract path segment
            
            $.ajax({
                url: '/' + basePath + '/store/' + productId + '/calc_training_price',
                type: 'get',
                data: { var_id: varId, qty: qty },
                success: function(response) {
                    if (response.error !== true) {
                        const total = response.price * qty;
                        $("#total").val("$" + total.toFixed(2));
                        $("#displayPrice").text(parseFloat(response.price).toFixed(2));
                    }
                },
                error: function() {
                    console.error('Error calculating training price');
                }
            });
        });
        
        // Handle form submission
        $(".add-cart").on("click.training", function(e) {
            e.preventDefault();
            console.log('[TRAINING] Form submit triggered');
            $("#addForm").submit();
        });
    });
})();
