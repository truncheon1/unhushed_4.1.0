/**
 * UNHUSHED Curriculum Product Handler
 * Handles curriculum products (category = 1) with tiered pricing
 */

(function() {
    'use strict';
    
    $(document).ready(function() {
        const productCategory = parseInt($('section[data-product-category]').data('product-category'));
        
        // Only run for curriculum products
        if (productCategory !== 1) {
            return;
        }
        
        console.log('[CURRICULUM] Initializing curriculum product handler');
        
        // Show all descriptions assigned to all variants (null variant_ids) on page load
        $('.product-description').each(function() {
            const variantIds = $(this).data('variant-ids');
            if (variantIds === null || variantIds === 'null') {
                $(this).show();
            }
        });
        
        // Handle quantity changes with tiered pricing
        $("#qty1").on("change", function() {
            if ($(this).val() < 1) {
                $(this).val(1);
            }
            
            const productId = $("input[name='id\\[\\]']").val();
            const basePath = window.location.pathname.split('/')[1]; // Extract path segment
            
            $.ajax({
                url: '/' + basePath + '/store/' + productId + '/calc_curriculum_price',
                type: 'get',
                data: 'qty=' + $(this).val(),
                success: function(response) {
                    console.log(response);
                    if (response.error === true) {
                        alert(response.message);
                    } else {
                        $(".package-caption").text(response.package_caption);
                        const qty = parseInt($("#qty1").val());
                        const sum = response.discount_price * qty;
                        $("#total").val("$" + sum.toFixed(2));
                        $(".discount-price").text(parseFloat(response.discount_price).toFixed(2));
                        $(".recurring-price").text(parseFloat(response.recurring_price).toFixed(2));
                        // Update display price in info section
                        $("#displayPrice").text(parseFloat(response.discount_price).toFixed(2));
                    }
                },
                error: function() { 
                    alert("Error calculating price"); 
                }
            });
        });
        
        // Handle form submission
        $(".add-cart").on("click.curriculum", function(e) {
            e.preventDefault();
            console.log('[CURRICULUM] Form submit triggered');
            $('.cart-form').submit();
        });
    });
})();
