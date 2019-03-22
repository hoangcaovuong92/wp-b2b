<?php
if (!function_exists('wd_payment_icon_function')) {
	function wd_payment_icon_function($atts) {
		extract(shortcode_atts(array(
			'list_icon_payment'	=> 'fa-cc-amex, fa-cc-discover, fa-cc-mastercard, fa-cc-paypal, fa-cc-visa',
			'size'				=> 'fa-2x',
			'text_align'		=> 'text-left',
			'class' 			=> ''
		), $atts));
		ob_start(); ?>
			<?php if ($list_icon_payment): ?>
	    		<?php $icons_class = explode(',', $list_icon_payment); ?>
	    		<?php if (count($icons_class) > 0): ?>
	    			<ul class="payment wd-icon-widget-payment <?php echo esc_attr($text_align) ?> <?php echo esc_attr($class); ?>">
	        			<?php foreach ($icons_class as $icon): ?>
	        				<?php if ($icon != ''): ?>
	        					<li><i class="fa <?php echo esc_html($size); ?> <?php echo esc_html(trim($icon)); ?>" aria-hidden="true"></i></li>
	        				<?php endif ?>
	        			<?php endforeach ?>
	    			</ul>
	    		<?php endif ?>
	    	<?php endif ?>
		<?php
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}

if (!function_exists('wd_payment_icon_vc_map')) {
	function wd_payment_icon_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Payment Icon", 'wd_package'),
			'base' 				=> 'wd_payment_icon',
			'description' 		=> esc_html__("Payment Icon", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'icon-wpb-vc_icon',
			"params" => array(
				array(
					'type' 			=> 'sorted_list',
					'heading' 		=> __( 'Select Payment Icon', 'wd_package' ),
					'param_name' 	=> 'list_icon_payment',
					'description' 	=> __( '', 'wd_package' ),
					'value' 		=> 'fa-cc-amex, fa-cc-discover, fa-cc-mastercard, fa-cc-paypal, fa-cc-visa',
					'options' 		=> wd_vc_get_list_payment_icon(),
				),
				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Icon Size", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "size",
					"value" 		=> wd_vc_get_list_awesome_font_size(),
					"std" 			=> "fa-2x",
					"description" 	=> "",
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Text Align', 'wd_package' ),
					'param_name' 	=> 'text_align',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_text_align_bootstrap(),
					'std'			=> 'text-left',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Extra class name", 'wd_package'),
					'description'	=> esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'class',
					'value' 		=> ''
				)
			)
		);
	}
}