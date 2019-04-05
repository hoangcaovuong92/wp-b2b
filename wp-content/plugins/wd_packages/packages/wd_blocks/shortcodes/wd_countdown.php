<?php
if (!function_exists('wd_countdown_function')) {
	function wd_countdown_function($atts) {
		extract(shortcode_atts(array(
			'title'					=> '',
			'icon_image'			=> '',
			'date_count_down'		=> '',
			'fullwidth_mode'		=> false,
			'class' 				=> ''
		), $atts));
		$image_url 	= wp_get_attachment_image_src($icon_image, "full");
		$imgSrc 	= $image_url[0];
		$title_img	= get_bloginfo('name');
		
		$random_id = 'wd_count_down'.mt_rand();

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';

		ob_start(); ?>
			<div id="<?php echo esc_attr($random_id); ?>" class="wd-shortcode wd-shortcode-countdown <?php echo esc_attr($class); ?>">
				<?php if($imgSrc != "") : ?>
					<div class="wd-image-banner">
						<img alt="<?php echo esc_attr($title_img);?>" title="<?php echo esc_attr($title_img);?>" class="img" src="<?php echo esc_url($imgSrc)?>" />
					</div>
				<?php endif; ?>	
				<?php if($title != "") : ?>
					<div class="wd-count-title"><?php echo esc_attr($title); ?></div>	
				<?php endif; ?>
				<div class="wd-content-count"></div>
				<div class="wd-data-curent"><span><?php $date=date_create($date_count_down); echo date_format($date,"F d,Y"); ?></span></div>
			</div>
			<script type="text/javascript">
			  	jQuery( document ).ready( function($) {
			  		var $_this = jQuery('#<?php echo esc_attr( $random_id ); ?>');
			  		$_this.find('.wd-content-count').countdown("<?php echo esc_attr($date_count_down); ?>", function(event) {
						var $this = $(this).html(event.strftime(''
							+ '<div class="time weeks"><span class="count">%-w</span><span class="label">Week%!w</span></div>'
							+ '<div class="time days"><span class="count">%-d</span><span class="label">Day%!d</span></div>'
							+ '<div class="time hours"><span class="count">%H</span><span class="label">Hr</span></div>'
							+ '<div class="time minutes"><span class="count">%M</span><span class="label">Min</span></div>'
							+ '<div class="time seconds"><span class="count">%S</span><span class="label">Sec</span></div>'
						));
					});
	
				});
			</script>
		<?php
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}

if (!function_exists('wd_countdown_vc_map')) {
	function wd_countdown_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Countdown", 'wd_package'),
			'base' 				=> 'wd_countdown',
			'description' 		=> esc_html__("WD Count Down", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'vc_icon-vc-gitem-post-date',
			"params" => array(
				array(
					"type" 			=> "attach_image",
					"class" 		=> "",
					"heading" 		=> esc_html__("Icon Count Down", 'wd_package'),
					"param_name" 	=> "icon_image",
					"value" 		=> "",
					"description" 	=> esc_html__("", 'wd_package'),
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Title", 'wd_package'),
					'description'	=> esc_html__("Title", 'wd_package'),
					"param_name" 	=> "title",
					"description" 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Select End Date", 'wd_package'),
					'description'	=> esc_html__("Exam: 30/12/2018", 'wd_package'),
					"param_name" 	=> "date_count_down",
					"description" 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Extra class name", 'wd_package'),
					'description'	=> esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'class',
					'value' 		=> '',
				)
			)
		);
	}
}