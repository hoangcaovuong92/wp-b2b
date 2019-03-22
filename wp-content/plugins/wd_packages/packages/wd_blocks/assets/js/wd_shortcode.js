//****************************************************************//
/*							SHORTCODE JS						  */
//****************************************************************//
jQuery(document).ready(function($) {
	//Product categories accordion (wd_product_categories_accordion.php)
	wd_product_categories_accordion();

	//Simple Animated Counter on Scroll
	wd_number_counter_scroll();

	//Toggle Icon (Accordion shortcode)
	wd_accordion_toggleIcon();
});

//****************************************************************//
/*                          FUNCTIONS                             */
//****************************************************************//
//Product categories accordion (wd_product_categories_accordion.php)
if (typeof wd_product_categories_accordion != 'function') {
	function wd_product_categories_accordion(){
		if (jQuery('.wd-shortcode-product-categories-accordion').length > 0) {
			jQuery('.wd-shortcode-product-categories-accordion').each(function(){
				var _random_id = jQuery(this).attr('id');
				jQuery('#'+_random_id).find('ul.dropdown_mode.is_dropdown').dcAccordion({
					classArrow: 'wd-product-cat-accordion-icon  fa fa-chevron-down',
					classParent: 'wd-product-cat-accordion-parrent-wrap',
					classCount: 'wd-product-cat-accordion-count',
						classExpand: 'wd-product-cat-accordion-current-parent', 
					eventType: 'click',
					menuClose: false,
					autoClose: true,
					saveState: false,
					autoExpand: true,
					disableLink: false,
					speed: 'fast',
					cookie: 'wd-product-cat-accordion-cookie'
				});
				jQuery('#'+_random_id+' .wd-product-cat-accordion-icon').click(function(e){
					e.preventDefault();
					jQuery('#'+_random_id+' .wd-product-cat-accordion-icon').not(this).addClass('fa-chevron-down').removeClass('fa-chevron-up');
					if (!jQuery(this).parents('.wd-product-cat-accordion-parrent-wrap').hasClass('active')) {
						jQuery(this).removeClass('fa-chevron-down').addClass('fa-chevron-up');
					}else{
						jQuery(this).addClass('fa-chevron-down').removeClass('fa-chevron-up');
					}
				});
			});
		}
	}	
}

//Toggle Icon (Accordion shortcode)
if (typeof wd_number_counter_scroll != 'function') { 
	function wd_accordion_toggleIcon() {
		if (jQuery('.wd-shortcode-accordion').length > 0) {
			jQuery('.wd-shortcode-accordion').each(function(){
				var wrap_id = jQuery(this).attr('id');
		 		jQuery('#'+wrap_id+' .panel-group').on('hidden.bs.collapse', function(e){
					var icon_plus 	= jQuery(this).data('icon_plus');
					var icon_minus 	= jQuery(this).data('icon_minus');
					jQuery("wd-accordion-icon").not(this).removeClass(icon_minus).addClass(icon_plus);
					jQuery(e.target).prev('.panel-heading').removeClass('active').find(".wd-accordion-icon").toggleClass(icon_plus +' '+ icon_minus);
				});
				jQuery('#'+wrap_id+' .panel-group').on('shown.bs.collapse', function(e){
					var icon_plus 	= jQuery(this).data('icon_plus');
					var icon_minus 	= jQuery(this).data('icon_minus');
					jQuery("wd-accordion-icon").not(this).removeClass(icon_minus).addClass(icon_plus);
					jQuery(e.target).prev('.panel-heading').addClass('active').find(".wd-accordion-icon").toggleClass(icon_plus +' '+ icon_minus);
				});
			});
		}
	}
}

//Animated Skills Bar
if (typeof wd_skill_bar_animate != 'function') { 
	function wd_skill_bar_animate(){
		jQuery(document).ready(function(){
			jQuery('.skillbar').each(function(){
				jQuery(this).find('.skillbar-bar').animate({
					width:jQuery(this).attr('data-percent')
				},6000);
			});
		});
	}	
}

//Animated Circle Charts
if (typeof wd_skill_circle_charts_animate != 'function') { 
	function wd_skill_circle_charts_animate(){
		jQuery('.demo-5').percentcircle({
			animate : true,
			diameter : 100,
			guage: 3,
			coverBg: '#fff',
			bgColor: '#efefef',
			fillColor: '#8BC163',
			percentSize: '18px',
			percentWeight: '20px'
		});	
	}	
}

//Add a thousands separator to a number
if (typeof wd_thousand_number_separator != 'function') { 
	function wd_thousand_number_separator(number, separator = '.') {
		number 	= Math.floor(number);
	    number += '';
	    var x 	= number.split('.');
	    var x1 	= x[0];
	    var x2 	= x.length > 1 ? '.' + x[1] : '';
	    var rgx = /(\d+)(\d{3})/;
	    while (rgx.test(x1)) {
	        x1 = x1.replace(rgx, '$1' + separator + '$2');
	    }
	    return x1 + x2;
	}
}


//Simple Animated Counter on Scroll (Feature custom shortcode)
if (typeof wd_number_counter_scroll != 'function') { 
	function wd_number_counter_scroll(){
		if (jQuery('.wd-feature-counter-wrapper').length > 0) {
			jQuery('.wd-feature-counter-wrapper').each(function() {
				var timer;
			    var wrap_id = jQuery(this).attr('id');
				jQuery(window).scroll(function () {
					if (timer) clearTimeout(timer);
				    timer = setTimeout(function(){
				       	var oTop 	= jQuery('#'+wrap_id).offset().top - window.innerHeight;
						if (!jQuery('#'+wrap_id).hasClass('loaded') && !jQuery('#'+wrap_id).hasClass('loading') && jQuery(window).scrollTop() > oTop) {
							jQuery('#'+wrap_id+' .wd-feature-counter-start').each(function() {
								var $this 	= jQuery(this),
								countStart 	= $this.data('counter_start'),
								countTo 	= $this.data('counter_end'),
								duration 	= $this.data('counter_duration');
								jQuery({
									countNum: countStart
								}).animate({
									countNum: countTo
								},{
									duration: duration,
									easing: 'swing',
									step: function() {
										jQuery('#'+wrap_id).addClass('loading');
										$this.text(wd_thousand_number_separator(this.countNum));
									},
									complete: function() {
										$this.text(wd_thousand_number_separator(this.countNum));
										jQuery('#'+wrap_id).addClass('loaded');
							        }
							    });
							});
						}
				    }, 300);
				});
			});
		}
	}	
}


