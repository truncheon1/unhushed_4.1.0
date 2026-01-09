/**
 * UNHUSHED Unified Badge System
 * Handles product variant selection for all non-curriculum/non-training products
 * Supports both option-based variants (color, size, etc.) and simple variant selection
 */

(function() {
    'use strict';
    
    // Wait for DOM ready
    $(document).ready(function() {
        const productCategory = parseInt($('section[data-product-category]').data('product-category'));
        
        console.log('[UNIFIED BADGES] Product category detected:', productCategory);
        
        // Only run for non-curriculum (1) and non-training (7) products
        if (productCategory === 1 || productCategory === 7) {
            console.log('[UNIFIED BADGES] Skipping - curriculum or training product');
            return;
        }
        
        // Also skip if category is NaN (not found)
        if (isNaN(productCategory)) {
            console.warn('[UNIFIED BADGES] No product category found, skipping initialization');
            return;
        }
        
        console.log('[UNIFIED BADGES] Initializing for product category', productCategory);
        
        // Load variant combinations from JSON (if product has options)
        const combinationsJson = document.getElementById('variantCombinations');
        const combinations = combinationsJson ? JSON.parse(combinationsJson.textContent) : [];
        const hasOptions = combinations.length > 0;
        
        console.log('[UNIFIED BADGES] Variant combinations loaded:', combinations.length);
        
        // ========================================
        // HELPER FUNCTIONS (Define early so they're available)
        // ========================================
        
        /**
         * Update descriptions visibility based on selected variant
         */
        function updateDescriptionsVisibility(varId) {
            console.log('[UNIFIED BADGES] Updating descriptions for variant:', varId);
            const $descriptions = $('.product-description');
            
            $descriptions.each(function() {
                const $desc = $(this);
                // Use .attr() to get raw string value instead of .data() which may cache/process
                let variantIdsRaw = $desc.attr('data-variant-ids');
                let variantIds = null;
                let shouldShow = false;
                
                if (variantIdsRaw === undefined || variantIdsRaw === 'null' || variantIdsRaw === null) {
                    // null = assigned to all variants
                    shouldShow = true;
                } else if (typeof variantIdsRaw === 'string') {
                    // Parse JSON string to array
                    try {
                        variantIds = JSON.parse(variantIdsRaw);
                        // Duck-type check: has length and includes method (works even if Array.isArray fails)
                        if (variantIds && variantIds.length > 0 && typeof variantIds.includes === 'function') {
                            shouldShow = variantIds.includes(varId);
                        }
                    } catch(e) {
                        console.error('[UNIFIED BADGES] Parse error for description', $desc.data('description-id'), ':', e);
                    }
                } else if (Array.isArray(variantIdsRaw)) {
                    // Already parsed as array somehow
                    shouldShow = variantIdsRaw.includes(varId);
                }
                
                if (shouldShow) {
                    $desc.show();
                } else {
                    $desc.hide();
                }
            });
        }
        
        /**
         * Check if variant is free and toggle UI accordingly
         */
        function updateFreeVariantUI(price) {
            console.log('[UNIFIED BADGES] Checking if variant is free, price:', price);
            const isFree = parseFloat(price) === 0;
            
            if (isFree) {
                // Hide cart interface, show free resource message
                $('#qty-field').hide();
                $('#total-field').hide();
                $('#add-cart-button').hide();
                $('#free-product-message').show();
                console.log('[UNIFIED BADGES] Free variant detected - showing free resource message');
            } else {
                // Show cart interface, hide free resource message
                $('#qty-field').show();
                $('#total-field').show();
                $('#add-cart-button').show();
                $('#free-product-message').hide();
                console.log('[UNIFIED BADGES] Paid variant detected - showing cart interface');
            }
        }
        
        // ========================================
        // VARIANT NAME BADGES (No Options)
        // ========================================
        $('.variant-badge').on('click', function() {
            const $clickedBadge = $(this);
            const varId = parseInt($clickedBadge.data('var-id'));
            const price = parseFloat($clickedBadge.data('price'));
            const sku = $clickedBadge.data('sku');
            const shipType = $clickedBadge.data('ship-type');
            const description2 = $clickedBadge.data('description2') || '';
            
            // Don't process if single variant (cursor: default)
            if ($clickedBadge.css('cursor') === 'default') {
                return;
            }
            
            // Update selected state
            $('.variant-badge').removeClass('selected');
            $clickedBadge.addClass('selected');
            
            // Update form and display
            $("input[name='var_id']").val(varId);
            $("#variantSku").text(sku);
            $("#shippingType").text(shipType);
            $("#displayPrice").text(price.toFixed(2));
            
            const qty = parseInt($("#qty").val()) || 1;
            const total = price * qty;
            $("#total").val("$" + total.toFixed(2));
            
            // Check if variant is free and update UI
            updateFreeVariantUI(price);
            
            // Update descriptions visibility
            updateDescriptionsVisibility(varId);
            
            // Update images
            if (typeof window.updateImagesForVariant === 'function') {
                window.updateImagesForVariant(varId);
            }
        });
        
        // ========================================
        // OPTION BADGES (With Options)
        // ========================================
        if (hasOptions) {
            // Initialize availability states on page load
            updateBadgeAvailability();
            
            // Update initial variant images
            const initialVarId = parseInt($("input[name='var_id']").val());
            if (initialVarId && typeof window.updateImagesForVariant === 'function') {
                window.updateImagesForVariant(initialVarId);
            }
            
            // Handle option badge clicks (exclude variant-badge class)
            $('.option-badge:not(.variant-badge)').on('click', function() {
                const $clickedBadge = $(this);
                const optionId = parseInt($clickedBadge.data('option-id'));
                const valueId = parseInt($clickedBadge.data('value-id'));
                
                // Update selected state within this option group
                $('.option-badge[data-option-id="' + optionId + '"]').removeClass('selected');
                $clickedBadge.addClass('selected');
                
                // If clicked badge is unavailable, auto-switch other options
                if ($clickedBadge.hasClass('unavailable')) {
                    autoSwitchToAvailableOption($clickedBadge);
                }
                
                // Update availability states for all badges
                updateBadgeAvailability();
                
                // Find and update matching variant
                updateVariantFromSelectedOptions();
            });
        }
        
        // ========================================
        // BADGE AVAILABILITY FUNCTIONS
        // ========================================
        
        /**
         * Update badge availability based on current selections
         */
        function updateBadgeAvailability() {
            // Get all currently selected option values
            const selectedOptions = {};
            $('.option-badge.selected:not(.variant-badge)').each(function() {
                const optId = parseInt($(this).data('option-id'));
                const valId = parseInt($(this).data('value-id'));
                selectedOptions[optId] = valId;
            });
            
            // Update each badge's availability
            $('.option-badge:not(.variant-badge)').each(function() {
                const $badge = $(this);
                const badgeOptId = parseInt($badge.data('option-id'));
                const badgeValId = parseInt($badge.data('value-id'));
                const availableWith = $badge.data('available-with') || [];
                
                // Check if this badge is compatible with other selected options
                let isAvailable = true;
                for (const optId in selectedOptions) {
                    const optIdInt = parseInt(optId);
                    if (optIdInt !== badgeOptId) {
                        // This is a different option - check compatibility
                        if (!availableWith.includes(selectedOptions[optId])) {
                            isAvailable = false;
                            break;
                        }
                    }
                }
                
                if (isAvailable) {
                    $badge.removeClass('unavailable');
                } else {
                    $badge.addClass('unavailable');
                }
            });
        }
        
        /**
         * Auto-switch to an available option when clicking unavailable badge
         */
        function autoSwitchToAvailableOption($clickedBadge) {
            const clickedOptId = parseInt($clickedBadge.data('option-id'));
            const clickedValId = parseInt($clickedBadge.data('value-id'));
            
            // Get currently selected values from OTHER options
            const otherSelectedValues = [];
            $('.option-badge.selected:not(.variant-badge)').each(function() {
                const optId = parseInt($(this).data('option-id'));
                const valId = parseInt($(this).data('value-id'));
                if (optId !== clickedOptId) {
                    otherSelectedValues.push(valId);
                }
            });
            
            // Find a compatible combination by switching ONE other option
            let foundCompatible = false;
            
            // Try each other option group
            $('.option-badges-container[data-option-id]').each(function() {
                if (foundCompatible) return;
                
                const $container = $(this);
                const containerOptId = parseInt($container.data('option-id'));
                
                if (containerOptId === clickedOptId) return; // Skip clicked option's container
                
                // Try each badge in this container
                $container.find('.option-badge').each(function() {
                    if (foundCompatible) return;
                    
                    const $testBadge = $(this);
                    const testValId = parseInt($testBadge.data('value-id'));
                    const testAvailableWith = $testBadge.data('available-with') || [];
                    
                    // Check if this test value is compatible with clicked value
                    if (testAvailableWith.includes(clickedValId)) {
                        // Found a match - switch to this badge
                        $('.option-badge[data-option-id="' + containerOptId + '"]').removeClass('selected');
                        $testBadge.addClass('selected');
                        foundCompatible = true;
                    }
                });
            });
            
            // If no compatible combination found, try switching multiple options
            if (!foundCompatible) {
                // Try to find ANY valid combination that includes clicked value
                for (let i = 0; i < combinations.length; i++) {
                    const combo = combinations[i];
                    if (combo.optionValues.includes(clickedValId)) {
                        // Found a valid combination - select all its options
                        combo.optionValues.forEach(function(valId) {
                            const $matchingBadge = $('.option-badge[data-value-id="' + valId + '"]');
                            if ($matchingBadge.length) {
                                const optId = parseInt($matchingBadge.data('option-id'));
                                $('.option-badge[data-option-id="' + optId + '"]').removeClass('selected');
                                $matchingBadge.addClass('selected');
                            }
                        });
                        foundCompatible = true;
                        break;
                    }
                }
            }
        }
        
        /**
         * Find matching variant from selected options and update form
         */
        function updateVariantFromSelectedOptions() {
            // Get all selected option values
            const selectedValues = [];
            $('.option-badge.selected:not(.variant-badge)').each(function() {
                selectedValues.push(parseInt($(this).data('value-id')));
            });
            
            if (selectedValues.length === 0) return;
            
            // Find matching variant
            let matchingVariant = null;
            for (let i = 0; i < combinations.length; i++) {
                const combo = combinations[i];
                // Check if combo has all selected values
                const matches = selectedValues.every(function(valId) {
                    return combo.optionValues.includes(valId);
                });
                
                if (matches && combo.optionValues.length === selectedValues.length) {
                    matchingVariant = combo;
                    break;
                }
            }
            
            // Update form with matching variant
            if (matchingVariant) {
                $("input[name='var_id']").val(matchingVariant.varId);
                $("#variantSku").text(matchingVariant.sku);
                
                const qty = parseInt($("#qty").val()) || 1;
                const total = matchingVariant.price * qty;
                $("#total").val("$" + total.toFixed(2));
                $("#displayPrice").text(parseFloat(matchingVariant.price).toFixed(2));
                
                // Check if variant is free and update UI
                updateFreeVariantUI(matchingVariant.price);
                
                // Update descriptions visibility
                updateDescriptionsVisibility(matchingVariant.varId);
                
                // Update images
                if (typeof window.updateImagesForVariant === 'function') {
                    window.updateImagesForVariant(matchingVariant.varId);
                }
            }
        }
        
        // ========================================
        // QUANTITY CHANGES
        // ========================================
        $("#qty").on("change", function() {
            if ($(this).val() < 1) {
                $(this).val(1);
            }
            
            // Update total based on current price
            const currentPrice = parseFloat($("#displayPrice").text()) || 0;
            const qty = parseInt($(this).val()) || 1;
            const total = currentPrice * qty;
            $("#total").val("$" + total.toFixed(2));
        });
        
        // ========================================
        // FORM SUBMISSION
        // ========================================
        $(".add-cart").on("click.unified", function(e) {
            e.preventDefault();
            console.log('[UNIFIED] Form submit triggered');
            $("#addForm").submit();
        });
        
        // ========================================
        // HELPER FUNCTIONS
        // ========================================
        
        // updateDescriptionsVisibility is now defined at the top
        
        // Initialize descriptions on page load
        let initialVarId = null;
        
        // Try to get var_id from selected badge
        const $firstBadge = $('.variant-badge.selected, .option-badge.selected').first();
        if ($firstBadge.length) {
            initialVarId = $firstBadge.data('var-id');
        }
        
        // If no selected badge but we have option combinations, get first var_id
        if (!initialVarId && hasOptions && combinations.length > 0) {
            initialVarId = combinations[0].varId;
        }
        
        // If no combinations but we have variant badges, get first one
        if (!initialVarId) {
            const $firstVariantBadge = $('.variant-badge').first();
            if ($firstVariantBadge.length) {
                initialVarId = $firstVariantBadge.data('var-id');
            }
        }
        
        // If we have a var_id from hidden input (single variant products)
        if (!initialVarId) {
            initialVarId = $("input[name='var_id']").val();
        }
        
        // Initialize descriptions visibility
        if (initialVarId) {
            console.log('[UNIFIED BADGES] Initializing descriptions for variant:', initialVarId);
            updateDescriptionsVisibility(initialVarId);
            
            // Initialize free variant UI based on initial price
            const initialPrice = parseFloat($("#displayPrice").text()) || 0;
            updateFreeVariantUI(initialPrice);
        } else {
            console.log('[UNIFIED BADGES] No initial variant found, showing all assigned-to-all descriptions');
            // No specific variant, just show descriptions assigned to all
            $('.product-description').each(function() {
                const variantIds = $(this).data('variant-ids');
                if (variantIds === null || variantIds === 'null') {
                    $(this).show();
                }
            });
        }
    });
})();
