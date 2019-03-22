//****************************************************************//
/*							Ajax Loadmore JS					  */
//****************************************************************//
jQuery( document ).ready( function($) {
	//hide loading button
	$(".wd-icon-loading").hide(); 

	//Ajax loadmore product. 
	//Template: wd_product_best_selling.php / wd_product_grid_list.php / wd_product_special.php 
	wd_ajax_load_more_product_basic();

	//Ajax loadmore blog. Template: wd_blog_grid_list.php
	wd_ajax_load_more_blog_grid_list();
	
	//Ajax load product tab. Template: wd_product_by_category_tabs.php
	wd_ajax_load_more_product_tabs();
});

//****************************************************************//
/*                          FUNCTIONS                             */
//****************************************************************//
//Ajax loadmore blog. Template: wd_blog_grid_list.php
if (typeof wd_ajax_load_more_blog_grid_list != 'function') {
	function wd_ajax_load_more_blog_grid_list(){
		var loadmore_btn = '.wd-loadmore-btn--blog';
		var loadmore_wrap = '.wd-infinite-scroll-wrap';
		jQuery(loadmore_btn).hide();
		if (jQuery(loadmore_btn).length > 0 && jQuery(loadmore_wrap).length > 0) {
			var timer;
			jQuery(window).scroll(function () {
				if (timer) clearTimeout(timer);
				timer = setTimeout(function(){
					if(jQuery(window).scrollTop() >= jQuery(loadmore_wrap).offset().top + jQuery(loadmore_wrap).outerHeight() - window.innerHeight) {
						if (!jQuery(loadmore_btn).hasClass('loading') && !jQuery(loadmore_btn).hasClass('ended')) {
							jQuery(loadmore_btn).trigger('click');
						}
					}
				}, 200);
			});
		}
		jQuery(document).on ( 'click', loadmore_btn, function( event ) {
			event.preventDefault();
			var _this = jQuery(this);
			var grid_list_layout = _this.data("grid_list_layout");
			var random_id = _this.data("random_id");
			var post__not_in = jQuery.parseJSON(_this.attr("data-post__not_in"));
			var layout_data = _this.data("layout_data");
			var query_data = _this.data("query_data");
			if (!_this.hasClass('loading') && !_this.hasClass('ended')) {
				jQuery.ajax({
					url: ajax_object.ajax_url,
					type: 'post',
					data: {
						action		 	:'load_more_blog',
						query_vars		: ajax_object.query_vars,
						post__not_in 	: post__not_in,
						layout_data 	: layout_data,
						query_data 		: query_data,
					},
	
					beforeSend: function(data) {
						jQuery('#' + random_id + '-icon-loading').show();
						_this.addClass('loading');
					},
					success: function( response ) {
						jQuery('#' + random_id + '-icon-loading').hide();
						_this.removeClass('loading');
						if (response.success) {
							//Update post__not_in list
							if (response.data.post__not_in) {
								_this.attr('data-post__not_in', response.data.post__not_in);
							}

							//hide loadmore button
							if (response.data.hide_button) {
								_this.fadeTo("fast", .5).hide();
								_this.addClass('ended');
							}
	
							if (grid_list_layout === 'grid') {
								jQuery('#' + random_id).isotope( 'insert', jQuery(response.data.data));
								setTimeout(() => {
									//jQuery('#' + random_id).isotope('reloadItems');
									if (typeof wd_mansory_layout === 'function') { 
										wd_mansory_layout();
									}
								}, 2000);
							}else{
								jQuery('#' + random_id).append(response.data.data);
							}
						}
					}
				});
			}
		});
	}	
}

//Ajax loadmore product. 
//Template: wd_product_best_selling.php / wd_product_grid_list.php / wd_product_special.php 
if (typeof wd_ajax_load_more_product_basic != 'function') { 
	function wd_ajax_load_more_product_basic(){
		var loadmore_btn = '.wd-loadmore-btn--product';
		var loadmore_wrap = '.wd-infinite-scroll-wrap';
		jQuery(loadmore_btn).hide();
		if (jQuery(loadmore_btn).length > 0 && jQuery(loadmore_wrap).length > 0) {
			var timer;
			jQuery(window).scroll(function () {
				if (timer) clearTimeout(timer);
				timer = setTimeout(function(){
					if(jQuery(window).scrollTop() >= jQuery(loadmore_wrap).offset().top + jQuery(loadmore_wrap).outerHeight() - window.innerHeight) {
						if (!jQuery(loadmore_btn).hasClass('loading') && !jQuery(loadmore_btn).hasClass('ended')) {
							jQuery(loadmore_btn).trigger('click');
						}
					}
				}, 200);
			});
		}
		jQuery(document).on ( 'click', loadmore_btn, function( event ) {
			event.preventDefault();
			var _this = jQuery(this);
			var random_id = _this.data("random_id");
			var post__not_in = jQuery.parseJSON(_this.attr("data-post__not_in"));
			var query_data = _this.data("query_data");
			if (!_this.hasClass('loading') && !_this.hasClass('ended')) {
				jQuery.ajax({
					url: ajax_object.ajax_url,
					type: 'post',
					data: {
						action		 	:'load_more_product',
						query_vars		: ajax_object.query_vars,
						post__not_in 	: post__not_in,
						query_data 		: query_data,
					},
	
					beforeSend: function(data) {
						jQuery('#' + random_id + '-icon-loading').show();
						_this.addClass('loading');
					},
					success: function( response ) {
						jQuery('#' + random_id + '-icon-loading').hide();
						_this.removeClass('loading');
						if (response.success) {
							//Update post__not_in list
							if (response.data.post__not_in) {
								_this.attr('data-post__not_in', response.data.post__not_in);
							}

							//hide loadmore button
							if (response.data.hide_button) {
								_this.fadeTo("fast", .5).hide();
								_this.addClass('ended');
							}
	
							jQuery('#' + random_id).find('.wd-products-wrapper ul.products').append(response.data.data);
						}
					}
				});
			}
		});
	}	
}

//Ajax load product tab. Template: wd_product_by_category_tabs.php
if (typeof wd_ajax_load_more_product_tabs != 'function') { 
	function wd_ajax_load_more_product_tabs(){
		jQuery( '.products-by-category-tabs a[data-toggle="tab"]' ).on( 'show.bs.tab', function ( e ) {
			const type    				= jQuery( this ).data( 'type' );
			const slug    				= jQuery( this ).data( 'slug' );
			const id      				= jQuery( this ).data( 'id' );
			const sort   				= jQuery( this ).data( 'sort' );
			const orderby 				= jQuery( this ).data( 'orderby' );
			const columns 				= jQuery( this ).data( 'columns' );
			const columns_tablet 		= jQuery( this ).data( 'columns_tablet' );
			const columns_mobile 		= jQuery( this ).data( 'columns_mobile' );
			const posts_per_page 		= jQuery( this ).data( 'posts_per_page' );
			const is_slider 			= jQuery( this ).data( 'is_slider' );
			const mansory_layout 		= jQuery( this ).data( 'mansory_layout' );
			const mansory_image_size 	= jQuery( this ).data( 'mansory_image_size' );
			const show_category_thumb 	= jQuery( this ).data( 'show_category_thumb' );
			const show_nav 				= jQuery( this ).data( 'show_nav' );
			const auto_play 			= jQuery( this ).data( 'auto_play' );
			const per_slide 			= jQuery( this ).data( 'per_slide' );

			if ( jQuery( jQuery( this ).attr( 'href' ) ).data( 'load' ) === 'loading' ) {
				const that = jQuery( this );
				jQuery.ajax( {
					url: ajax_object.ajax_url,
					type: 'post',
					data: {
						action				: 'product_by_category_tabs',
						type				: type,
						slug				: slug,
						id					: id,
						sort				: sort,
						orderby				: orderby,
						columns				: columns,
						columns_tablet		: columns_tablet,
						columns_mobile		: columns_mobile,
						posts_per_page		: posts_per_page,
						is_slider			: is_slider,
						mansory_layout		: mansory_layout,
						mansory_image_size	: mansory_image_size,
						show_category_thumb	: show_category_thumb,
						show_nav			: show_nav,
						auto_play			: auto_play,
						per_slide			: per_slide,
					},
					error: function ( response ) {
						console.log( response );
					},
					success: function ( response ) {
						jQuery( that.attr( 'href' ) ).html( response );
						jQuery( that.attr( 'href' ) ).data( 'load', 'loaded' ).attr( 'data-load', 'loaded' );
						jQuery('.products .product').find('.wp_description_product.wd_hidden_desc_product').addClass('hidden');
	   					jQuery('.products .product').find('.wp_description_product.wd_show_desc_product').removeClass('hidden');
	   					if (typeof qs_prettyPhoto == 'function') { qs_prettyPhoto(); }
					}
				} );
			}
		} );
	}	
}