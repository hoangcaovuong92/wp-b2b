jQuery(document).ready(function($){
	"use strict";
	wd_product_grid_list_toggle(); //PRODUCT GRID/LIST TOGGLE
    wd_masonry_product(); //Masonry Product. Set first argument = true to enable
    wd_product_sale_date_countdown(); //Sale Date Countdown
	wd_set_cloud_zoom(); //Cloud zoom hover product image
	wd_product_variation_product_select(); //Variation product
	wd_cart_change_ajax(); //AJAX ADD TO CART CHANGE CART VIEW
	//wd_cache_fix_add_to_cart(); //W3 Total Cache & Wp Super Cache Fix
	wd_popup_minicart_mobile(); //Mini cart on mobile
	wd_popup_after_add_to_cart_ajax(); //Popup after added to cart ajax (shop loop)
	wd_wishlist(); //Custom wishlist
	wd_wishlist_ajax_update_count(); //Ajax wishlist
});

/*--------------------------------------------------------------------------*/
/*								FUNCTIONS   								*/
/*--------------------------------------------------------------------------*/
//PRODUCT GRID/LIST TOGGLE
if (typeof wd_product_grid_list_toggle != 'function') { 
	function wd_product_grid_list_toggle(){
		var wrap = '.wd-layout-toggle-wrap.wd-layout-toggle-product';
		if(jQuery(wrap).length > 0 ){
			var $action = jQuery(wrap + ' .wd-grid-list-toggle-action');
			var $grid = jQuery(wrap + ' .wd-grid-list-toggle-action[data-layout="grid"]');
			var $list = jQuery(wrap + ' .wd-grid-list-toggle-action[data-layout="list"]');

			var $columns_toggle = jQuery(wrap + ' .wd-columns-toggle-action');
			var $product_wrap = jQuery('.wd-products-wrapper.wd-product-switchable-layout');

			//Default cookie
			if (Cookies.get('product-layout-cookie') === undefined) {
				Cookies.set('product-layout-cookie','grid', { path: '/' });
			}
			$action.on('click', function(e) {
				e.preventDefault();
				var layout = jQuery(this).data('layout');
				$action.removeClass('active');
				jQuery(this).addClass('active')
				if (layout === 'grid') {
					Cookies.set('product-layout-cookie','grid', { path: '/' });
					$columns_toggle.show();
					$product_wrap.find('.products').addClass('grid').removeClass('list');
					$product_wrap.find('.products.grid > .product .wp_description_product.wd_hidden_desc_product').hide();
				}else{
					Cookies.set('product-layout-cookie','list', { path: '/' });
					$columns_toggle.hide();
					$product_wrap.find('.products').removeClass('grid').addClass('list');
					$product_wrap.find('.products.list > .product .wp_description_product').show();
				}
			});

			if (Cookies.get('product-layout-cookie') == 'grid') {
				$grid.trigger('click');
			}

			if (Cookies.get('product-layout-cookie') == 'list') {
				$list.trigger('click');
			}

			//current product columns
			var current_column = $product_wrap.data('columns');
			//add class active to current column button
			jQuery(wrap).find('.wd-columns-toggle-action[data-option-value="' + current_column + '"]').addClass('active');
			$columns_toggle.on('click', function(e) {
				e.preventDefault();
				var current_column = $product_wrap.data('columns');
				var new_column = jQuery(this).data('option-value');
				if (current_column !== new_column) {
					$product_wrap.addClass('wd-columns-' + new_column).removeClass('wd-columns-' + current_column);
					$product_wrap.data('columns', new_column);
					jQuery(wrap + " .wd-columns-toggle-action[data-option-value='" + current_column + "']").removeClass('active');
					jQuery(this).addClass('active');
				}
			});
		}
	}
}

//Custom wishlist
if (typeof wd_wishlist != 'function') { 
	function wd_wishlist(){
		jQuery( "html .woocommerce table.wishlist_table tbody tr td.product-name" ).attr({
		  "data-title": "Product"
		});
		jQuery( "html .woocommerce table.wishlist_table tbody tr td.product-price" ).attr({
		  "data-title": "Price"
		});
		jQuery( "html .woocommerce table.wishlist_table tbody tr td.product-stock-status" ).attr({
		  "data-title": "Stock"
		});
	}
}

//Ajax wishlist
if (typeof wd_wishlist_ajax_update_count != 'function') { 
    function wd_wishlist_ajax_update_count(){
        jQuery(document).on( 'added_to_wishlist removed_from_wishlist', function(){
	        var counter = jQuery('.wd-count-pill--wishlist');
	        jQuery.ajax({
	            url: yith_wcwl_l10n.ajax_url,
	            data: {
	                action: 'yith_wcwl_update_wishlist_count'
	            },
	            dataType: 'json',
	            success: function( data ){
	                counter.html( data.count );
	            },
	            beforeSend: function(){
	                //counter.block();
	            },
	            complete: function(){
	                //counter.unblock();
	            }
	        })
	    });   
    }
}

//Masonry Product
if (typeof wd_masonry_product != 'function') { 
	function wd_masonry_product(selector_wrap = '.wd-product-mansonry-wrap'){
		if(jQuery(selector_wrap+' .products.grid').length > 0 ){
			setTimeout(function(){
				jQuery(selector_wrap+' .products.grid').isotope({
					layoutMode: 'masonry',
					itemSelector: '.wd-product-mansonry-item'
				});
			}, 1000)
		}
	}
}

//Sale Date Countdown
if (typeof wd_product_sale_date_countdown != 'function') { 
	function wd_product_sale_date_countdown(){
		var data_offer = jQuery( '.wd-offer-shop-date' );
		if (data_offer.length > 0) {
			data_offer.each( function () {
				jQuery( this ).find( '.countdown' ).countdown( jQuery( this ).data( 'offer' ), function ( event ) {
					jQuery( this ).find( '.year' ).text( event.strftime( '%Y' ) );
					jQuery( this ).find( '.month' ).text( event.strftime( '%m' ) );
					jQuery( this ).find( '.day' ).text( event.strftime( '%D' ) );
					jQuery( this ).find( '.hour' ).text( event.strftime( '%H' ) );
					jQuery( this ).find( '.minute' ).text( event.strftime( '%M' ) );
					jQuery( this ).find( '.second' ).text( event.strftime( '%S' ) );
				} );
			} );
		}
	}
}

// Cloud zoom hover product image
if (typeof wd_set_cloud_zoom != 'function') { 
	function wd_set_cloud_zoom(){
		var _clz_element 	= jQuery('.cloud-zoom, .cloud-zoom-gallery');
		var clz_width 		= _clz_element.width();
		var clz_img_width 	= _clz_element.children('img').width();
		var cl_zoom 		= _clz_element;
		var temp 			= (clz_width-clz_img_width)/2;
		_clz_element.data('zoom',null).siblings('.mousetrap').unbind().remove();
		_clz_element.CloudZoom({ 
			adjustX:temp	
		});

		jQuery('.wd-single-product-thumbnail-link').on('click', function(e){
			_clz_element.removeClass('active');
			jQuery(this).addClass('active');
		});
	}
} 

//Variation product
if (typeof wd_product_variation_product_select != 'function') { 
	function wd_product_variation_product_select(){
		// Select Color
		jQuery('body').on('click', '.variations_form .wd-select-option', function(e){
			var val = jQuery(this).attr('data-value');
			var _this = jQuery(this);
			var color_select = jQuery(this).parents('.value').find('select');
			color_select.trigger('focusin');
			if(color_select.find('option[value='+val+']').length !== 0) {
				color_select.val( val ).change();
				_this.parent('.wd-color-image-swap').find('.wd-select-option').removeClass('selected');
				_this.addClass('selected');
			}			
		});

		jQuery('.variations_form').on('click', '.reset_variations', function(e){
			jQuery(this).parents('.variations').find('.wd-color-image-swap .wd-select-option.selected').removeClass('selected');
		});
						
		jQuery('body').on('change', '.variations_form .variations select', function(e){
			jQuery('.variations_form .variations .wd-color-image-swap').parents('.value').find('select').trigger('focusin');
			jQuery('.variations_form .variations .wd-color-image-swap .wd-select-option').each(function(i,e){
				var val = jQuery(this).attr('data-value');
				var _this = jQuery(this);
				var op_elemend = jQuery(this).parents('.value').find('select option[value='+val+']');
				if(op_elemend.length == 0) {
					_this.hide();
				} else {
					_this.show();
				}
				
			});
		});
		
		jQuery('body').on('show_variation', '.variations_form .variations .single_variation_wrap', function(e){
			jQuery('.variations_form ').find( '.single_variation' ).parent().parent('.single_variation_wrap').removeClass('no-price');
			if(jQuery('.variations_form ').find( '.single_variation' ).html() == ''){
				jQuery('.variations_form ').find( '.single_variation' ).parent().parent('.single_variation_wrap').addClass('no-price');
			}
		});
	}
}

//AJAX ADD TO CART CHANGE CART VIEW
if (typeof wd_cart_change_ajax != 'function') { 
	function wd_cart_change_ajax(){
		jQuery('body').bind( 'adding_to_cart', function() {
			jQuery('.wd-dropdown--minicart').addClass('disabled working');
		} );		
	    	      
		jQuery('.add_to_cart_button').live('click',function(event){
			var _li_prod = jQuery(this).parent().parent().parent().parent();
			_li_prod.trigger('wd_adding_to_cart');
		});	

		jQuery('li.product').each(function(index,value){
			jQuery(value).bind('wd_added_to_cart', function() {
				var _loading_mark_up = jQuery(value).find('.loading-mark-up').remove();
				jQuery(value).removeClass('adding_to_cart').addClass('added_to_cart').append('<span class="loading-text"></span>');//Successfully added to your shopping cart
				var _load_text = jQuery(value).find('.loading-text').css({'width' : jQuery(value).width()+'px','height' : jQuery(value).height()+'px'}).delay(1500).fadeOut();
				setTimeout(function(){
					_load_text.hide().remove();
				},1500);
				//delete view cart		
				jQuery('.list_add_to_cart .added_to_cart').remove();
				var _current_currency = Cookies.get('woocommerce_current_currency');
			});	
		});	
	}
}

//W3 Total Cache & Wp Super Cache Fix
if (typeof wd_cache_fix_add_to_cart != 'function') { 
	function wd_cache_fix_add_to_cart(){
		jQuery('body').trigger('added_to_cart');
	}
}

//Mini cart on mobile
if (typeof wd_popup_minicart_mobile != 'function') { 
	function wd_popup_minicart_mobile(){
		//mini cart popup on mobile
		jQuery('body').on('click', '.wd-header-mobile .wd-navUser-action--minicart', function(e){
			//Return if cart is empty
			if (jQuery(this).hasClass('wd-minicart-none-items')) return;
			var content_id = jQuery(this).data('content_id');
			var popup_wrap = '#wd-popup-after-add-to-cart';
			var status = jQuery(popup_wrap).data('active');
			if (!content_id || !status) return;
			
			e.preventDefault();
			jQuery.fancybox('#'+content_id, {
				openEffect: 'fade',
				closeEffect: 'fade',
				padding: 10,
				margin: [20, 20, 20, 20],
				fitToView: true,
				autoSize: true,
				width: '100%',
				height: '100%',
				closeBtn: true,
				arrows: false,
				scrolling: 'auto',
				helpers: {
					overlay: {
						locked: true
					}
				},
				onComplete: function () {},
				beforeShow: function () {},
				afterClose: function () {},
				afterLoad: function () {}
			});
		});
	}
}

//Popup after added to cart ajax (shop loop)
if (typeof wd_popup_after_add_to_cart_ajax != 'function') { 
	function wd_popup_after_add_to_cart_ajax(){
		jQuery('body').on('added_to_cart',function(e,data) {
			var popup_wrap = '#wd-popup-after-add-to-cart';
			var status = jQuery(popup_wrap).data('active');
			if (!status) return;

			jQuery(popup_wrap).find('.wd-popup-content').html(data['div.widget_shopping_cart_content']);
			jQuery.fancybox(popup_wrap, {
				openEffect: 'fade',
				closeEffect: 'fade',
				padding: 10,
				margin: [20, 20, 20, 20],
				fitToView: true,
				autoSize: true,
				width: '100%',
				height: '100%',
				closeBtn: true,
				arrows: false,
				scrolling: 'auto',
				helpers: {
					overlay: {
						locked: true
					}
				},
				onComplete: function () {},
				beforeShow: function () {},
				afterClose: function () {
					jQuery(popup_wrap).find('.wd-popup-content').html('');
				},
				afterLoad: function () {}
			});
		});
	}
}