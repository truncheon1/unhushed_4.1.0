<!-- PRODUCT IMAGES -->
<div class="gallery">
    @php
        // Get selected variant ID (default to first available variant)
        $selectedVarId = $vars->first()->var_id ?? null;
    @endphp
    @foreach ($images as $img)
        @php
            // Render all images to DOM (JavaScript will filter them by variant)
            // Skip only if variant_ids is an empty array (no variants assigned)
            if(is_array($img->variant_ids) && empty($img->variant_ids)) {
                continue;
            }
            
            $fileExtension = strtolower(pathinfo($img->image, PATHINFO_EXTENSION));
            $isVideo = in_array($fileExtension, ['mp4', 'webm', 'ogg', 'mov']);
        @endphp
        @if($isVideo)
            <video data-type="video" data-src="{{ asset('uploads/products/'.$img->image) }}" data-variant-ids='@json($img->variant_ids)' controls>
                <source src="{{ asset('uploads/products/'.$img->image) }}" type="video/{{ $fileExtension === 'mov' ? 'quicktime' : $fileExtension }}">
                Your browser does not support the video tag.
            </video>
        @else
            <img data-type="image" data-src="{{ asset('uploads/products/'.$img->image) }}" data-variant-ids='@json($img->variant_ids)' src="{{ asset('uploads/products/'.$img->image) }}" alt="{{ $product->name ?? 'Product' }} - view {{ $loop->iteration }} of {{ count($images) }}"/>
        @endif
    @endforeach
</div>

<style>
    .gallery {
        width: 100%;
        height: auto;
    }
    .gallery .inner {
        position: relative;
        display: block;
        width: auto;
        max-width: 600px;
    }
    .gallery img,
    .gallery video {
        display: none;
    }
    .main {
        position: relative;
        width: 300px;
        max-width: 300px;
        height: 300px;
        background: #fff;
        margin: 0 auto 5px auto;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .main.image-mode {
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
    }
    .main video {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .main-selected {
        animation: crossfade 0.5s ease;
        -webkit-animation: crossfade 0.5s ease;
        -moz-animation: crossfade 0.5s ease;
    }
    @keyframes crossfade {
        0% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    @-webkit-keyframes crossfade {
        0% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    @-moz-keyframes crossfade {
        0% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    .thumb-roll {
        position: relative;
        width: auto;
        overflow-x: auto;
        overflow-y: hidden;	
        white-space: nowrap;
    }
    .thumb {
        display: inline-block;
        position: relative;
        width: 100px;
        height: 100px;
        margin-right: 5px;
        background: #fff;
        overflow: hidden;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
        overflow: hidden;
        cursor: pointer;
    }
    .thumb:last-of-type {
        margin-right: 0px;
    }
    .thumb:after {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
    }
    .thumb.current:after {
        cursor: default;
    }
    .thumb:hover:after {
        box-shadow: inset 2px 2px 0px rgba(51, 204, 255, 1), inset -2px -2px 0px rgba(51, 204, 255, 1);
    }
</style>
<script>
    // Initialize gallery structure
    $('<div />', { 'class': 'inner'  }).appendTo('.gallery');
    $('<div />', { 'class': 'main'  }).appendTo('.gallery .inner');
    $('<div />', { 'class': 'thumb-roll'  }).appendTo('.gallery .inner');

    // Function to update main display
    function updateMainDisplay(thumb) {
        var mediaSrc = thumb.attr('data-src');
        var mediaType = thumb.attr('data-type');
        
        $('.main').removeClass('image-mode').empty();
        
        if (mediaType === 'video') {
            var videoHtml = '<video controls><source src="' + mediaSrc + '" type="video/mp4">Your browser does not support the video tag.</video>';
            $('.main').html(videoHtml);
        } else {
            $('.main').addClass('image-mode').css('background-image', 'url(' + mediaSrc + ')');
        }
        
        $('.main').addClass('main-selected');
        setTimeout(function() {
            $('.main').removeClass('main-selected');
        }, 500);
    }

    // Select thumbnail function
    $('.thumb').click(function() {
        // Make clicked thumbnail selected
        $('.thumb').removeClass('current');
        $(this).addClass('current');
        updateMainDisplay($(this));
    });

    // Arrow key control function
    $(document).keyup(function(e) {

    // If right arrow
    if (e.keyCode === 39) {
        // Mark current thumbnail
        var currentThumb = $('.thumb.current');
        var currentThumbIndex = currentThumb.index();
        if ( (currentThumbIndex+1) >= noOfImages) { // if on last media
            nextThumbIndex = 0; // ...loop back to first media
        } else {
            nextThumbIndex = currentThumbIndex+1;
        }
        var nextThumb = $('.thumb').eq(nextThumbIndex);
        currentThumb.removeClass('current');
        nextThumb.addClass('current');
        updateMainDisplay(nextThumb);
    }
    
    // If left arrow
    if (e.keyCode === 37) { 
        // Mark current thumbnail
        var currentThumb = $('.thumb.current');
        var currentThumbIndex = currentThumb.index();
        if ( currentThumbIndex == 0) { // if on first media
            prevThumbIndex = noOfImages-1; // ...loop back to last media
        } else {
            prevThumbIndex = currentThumbIndex-1;
        }
        var prevThumb = $('.thumb').eq(prevThumbIndex);
        currentThumb.removeClass('current');
        prevThumb.addClass('current');
        updateMainDisplay(prevThumb);
        }
    });
    
    // Function to filter and rebuild gallery images based on selected variant
    window.updateImagesForVariant = function(variantId) {
        console.log('[IMAGE GALLERY] updateImagesForVariant called with variant ID:', variantId);
        
        var allMediaItems = $('.gallery').children('img[data-variant-ids], video[data-variant-ids]');
        console.log('[IMAGE GALLERY] Total media items found:', allMediaItems.length);
        
        var visibleItems = [];
        
        // Filter media items for this variant
        allMediaItems.each(function(index){
            // Use .attr() instead of .data() to avoid jQuery caching issues
            var attrValue = $(this).attr('data-variant-ids');
            var variantIds = null;
            var shouldShow = false;
            
            if(attrValue === undefined || attrValue === 'null' || attrValue === null) {
                // null = assigned to all variants
                shouldShow = true;
            } else if(typeof attrValue === 'string') {
                // Parse JSON string to array
                try {
                    variantIds = JSON.parse(attrValue);
                    // Duck-type check: has length and includes method (works even if Array.isArray fails)
                    if(variantIds && variantIds.length > 0 && typeof variantIds.includes === 'function') {
                        shouldShow = variantIds.includes(variantId);
                    }
                } catch(e) {
                    console.error('[IMAGE GALLERY] Parse error for item', index, ':', e);
                }
            } else if(Array.isArray(attrValue)) {
                // Already parsed as array somehow
                shouldShow = attrValue.includes(variantId);
            }
            
            if(shouldShow) {
                visibleItems.push(this);
            }
        });
        
        console.log('[IMAGE GALLERY] Visible items after filtering:', visibleItems.length);
        
        // Rebuild thumb roll with visible items only
        $('.thumb-roll').empty();
        var firstVisibleItem = null;
        
        $(visibleItems).each(function(index) {
            var mediaSrc = $(this).data('src') || $(this).attr('src');
            var mediaType = $(this).data('type');
            var thumb = $('<div />', { 'class': 'thumb'  })
                .appendTo('.gallery .inner .thumb-roll')
                .css('background-image', 'url(' + mediaSrc + ')')
                .attr('data-src', mediaSrc)
                .attr('data-type', mediaType);
            
            // Add video icon overlay for video thumbnails
            if (mediaType === 'video') {
                thumb.append('<i class="fas fa-play-circle" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:white;font-size:30px;opacity:0.8;"></i>');
            }
            
            // Store first item for main display
            if(index === 0) {
                firstVisibleItem = { src: mediaSrc, type: mediaType };
                thumb.addClass('current');
            }
            
            // Reattach click handler
            thumb.click(function() {
                $('.thumb').removeClass('current');
                $(this).addClass('current');
                updateMainDisplay($(this));
            });
        });
        
        // Update main display with first visible item
        if(firstVisibleItem) {
            $('.main').removeClass('image-mode').empty();
            if (firstVisibleItem.type === 'video') {
                $('.main').html('<video controls><source src="' + firstVisibleItem.src + '" type="video/mp4">Your browser does not support the video tag.</video>');
            } else {
                $('.main').addClass('image-mode').css('background-image', 'url(' + firstVisibleItem.src + ')');
            }
        }
    };
    
    // Initialize gallery on page load
    @if($selectedVarId)
        // Product has variants - filter images by selected variant
        window.updateImagesForVariant({{ $selectedVarId }});
    @else
        // Product has no variants (e.g., curriculum) - show all images
        console.log('[IMAGE GALLERY] No variants detected - showing all images');
        var allMediaItems = $('.gallery').children('img[data-variant-ids], video[data-variant-ids]');
        
        $('.thumb-roll').empty();
        var firstItem = null;
        
        allMediaItems.each(function(index) {
            var mediaSrc = $(this).data('src') || $(this).attr('src');
            var mediaType = $(this).data('type');
            var thumb = $('<div />', { 'class': 'thumb'  })
                .appendTo('.gallery .inner .thumb-roll')
                .css('background-image', 'url(' + mediaSrc + ')')
                .attr('data-src', mediaSrc)
                .attr('data-type', mediaType);
            
            if (mediaType === 'video') {
                thumb.append('<i class="fas fa-play-circle" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:white;font-size:30px;opacity:0.8;"></i>');
            }
            
            if(index === 0) {
                firstItem = { src: mediaSrc, type: mediaType };
                thumb.addClass('current');
            }
            
            thumb.click(function() {
                $('.thumb').removeClass('current');
                $(this).addClass('current');
                updateMainDisplay($(this));
            });
        });
        
        // Display first image
        if(firstItem) {
            $('.main').removeClass('image-mode').empty();
            if (firstItem.type === 'video') {
                $('.main').html('<video controls><source src="' + firstItem.src + '" type="video/mp4">Your browser does not support the video tag.</video>');
            } else {
                $('.main').addClass('image-mode').css('background-image', 'url(' + firstItem.src + ')');
            }
        }
    @endif
</script>
