//****************************************************************//
/*                          FRONT END INSTAGRAM JS                */
//****************************************************************//
jQuery(document).ready(function($) {
    "use strict";
});
if (typeof wd_instagram_shortcode_script != 'function') { 
    function wd_instagram_shortcode_script(setting){
		var class_lightbox 	= ( setting['insta_action_click_item'] == 'lightbox' ) ? 'wd-fancybox-image-gallery' : '';

        var userFeed = new Instafeed({
            get        : 'user',
            userId     : setting['insta_user'],
            clientId   : setting['insta_client_id'],
            accessToken: setting['insta_access_token'],
            resolution : setting['insta_size'],
                filter: function(image) {
                image.custom_class = setting['class'];
                return true;
            },
            template   : '<li  style="width: 100%;" class="wd-instafeed-item swiper-slide {{model.custom_class}}"><a data-fancybox-group="wd-instagram-'+setting['random_id']+'" class="wd-insta-item '+class_lightbox+'" href="{{image}}" data-href="{{link}}" target="_blank" id="{{id}}"><img style="width:100%;" src="{{image}}" alt="{{caption}}" /></a></li>',
            sortBy     : setting['insta_sortby'],
            limit      : setting['insta_number'],
            links      : false,
            target     : 'wd-instagram-content-'+setting['random_id'],
            after:function(){
                var images = jQuery('#wd-instagram-content-'+setting['random_id']).find('.wd-instafeed-item');
                if (typeof(setting['mansory_image_size']) != 'undefined') {
                    jQuery.each( setting['mansory_image_size'], function( key, value ) {
                        if (value == '2:2') {
                            jQuery(images[key]).addClass('wd-columns-double-width');
                        }
                    });
                }

                if(jQuery('.wd-instagram-masonry-wrap').length > 0 ){
                    setTimeout(function(){
                            jQuery('.wd-instagram-masonry-wrap').isotope({
                                layoutMode: 'masonry',
                                itemSelector: '.wd-instagram-masonry-item',
                            }); 
                    }, 300);
                }

                if (typeof(setting['is_slider']) != 'undefined' && setting['is_slider'] == '1') {
                    wd_instagram_shortcode_slider(setting);
                }
            }
        });
        userFeed.run();

        jQuery("#wd-instagram-load-more-"+setting['random_id']).click( function(){
            event.preventDefault();
            userFeed.next();
        });
    }   
}

if (typeof wd_instagram_shortcode_slider != 'function') { 
    function wd_instagram_shortcode_slider(setting){
        var $_this = jQuery('#wd-instagram-content-'+setting['random_id']);
        var _auto_play = setting['auto_play'] == '1' ? 'true' : 'false';
        var ins_padding = 30;
        if (setting['insta_padding'] === 'none') {
            ins_padding = 0;
        } else if (setting['insta_padding'] === 'small'){
            ins_padding = 15;
        }
        var owl = $_this.owlCarousel({
            loop : true,
            items : parseInt(setting['insta_columns']),
            nav : false,
            margin: parseInt(ins_padding),
            dots : false,
            navSpeed : 1000,
            slideBy: 1,
            rtl:jQuery('body').hasClass('rtl'),
            navRewind: false,
            autoplay: _auto_play,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            autoplaySpeed: false,
            mouseDrag: true,
            touchDrag: false,
            responsiveBaseElement: $_this,
            responsiveRefreshRate: 1000,
            responsive:{
                0:{
                    items : parseInt(setting['columns_mobile']),
                },
                426:{
                    items : parseInt(setting['columns_tablet']),
                },
                768:{
                    items : parseInt(setting['insta_columns']),
                }
            },
            onInitialized: function(){
            }
        });
        $_this.parents('.wd-instagram-wrapper').on('click', '.next', function(e){
            e.preventDefault();
            owl.trigger('next.owl.carousel');
        });

        $_this.parents('.wd-instagram-wrapper').on('click', '.prev', function(e){
            e.preventDefault();
            owl.trigger('prev.owl.carousel');
        });
    }   
}