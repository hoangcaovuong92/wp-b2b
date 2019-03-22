<?php
if(!function_exists('wd_feedburner_subscription_function')){
	function wd_feedburner_subscription_function($atts){
		extract(shortcode_atts(array(
			'title'					=> "Sign up for Our Newsletter",
			'intro_text'			=> "A newsletter is a regularly distributed publication generally",
			'placeholder_text'		=> "Enter your email address",
			'button_text'			=> "Subscribe",
			'feedburner_id'			=> "WpComic-Manga",
			'class'					=> ''
		),$atts));
		ob_start(); ?>
		<div class="subscribe_widget <?php echo esc_attr( $class ); ?>">
			<?php if($title != "") : ?>
				<div class="wd-subscribe-header">
					<h2><?php echo esc_attr( $title ); ?></h2>
				</div>
			<?php endif; ?>
			<?php echo ($intro_text) ? '<div class="subscribe_intro_text">'.esc_html($intro_text).'</div>':'' ?>		
			<div class="subscribe_form">
				<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo esc_attr($feedburner_id); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
					<p class="subscribe-email"><input type="text" name="email" class="subscribe_email" value="" placeholder="<?php echo esc_html($placeholder_text); ?>" autocomplete="off" /></p>
					<input type="hidden" value="<?php echo esc_attr($feedburner_id);?>" name="uri"/>
					<input type="hidden" value="<?php echo get_bloginfo( 'name' );?>" name="title"/>
					<input type="hidden" name="loc" value="en_US"/>
					<button class="button" type="submit" title="Subscribe"><?php echo esc_attr($button_text); ?></button>
					<p class="hidden">Delivered by <a href="#" target="_blank">FeedBurner</a></p>
				</form>
			</div>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				"use strict";
				var subscribe_input = jQuery(".subscribe_widget input.subscribe_email");
				var value_default = subscribe_input.attr('data-default');
				subscribe_input.val(value_default);
				if( jQuery(this).val() === "" ) jQuery(this).val(value_default);
				subscribe_input.click(function(){
					if( jQuery(this).val() === value_default ) jQuery(this).val("");
				});
				subscribe_input.blur(function(){
					if( jQuery(this).val() === "" ) jQuery(this).val(value_default);
				});
			});
		</script>
		<?php
		$output = ob_get_clean();
		wp_reset_query();
		return $output;
	}
}

if (!function_exists('wd_feedburner_subscription_vc_map')) {
	function wd_feedburner_subscription_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Feedburner Subscriptions", 'wd_package'),
			'base' 				=> 'wd_feedburner_subscription',
			'description' 		=> esc_html__("Feedburner Subscriptions", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'vc_icon-vc-gitem-post-title',
			'params' => array(
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Title", 'wd_package'),
					'description' 	=> esc_html__("Title", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'title',
					'value' 		=> esc_html__("Sign up for Our Newsletter", 'wd_package'),
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Enter your Intro Text", 'wd_package'),
					'description' 	=> esc_html__("Intro text", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'intro_text',
					'value' 		=> esc_html__("A newsletter is a regularly distributed publication generally", 'wd_package'),
				),
	
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Enter your Placeholder Text", 'wd_package'),
					'description' 	=> esc_html__("Placeholder text", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'placeholder_text',
					'value' 		=> esc_html__("Enter your email address", 'wd_package'),
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Enter your Button", 'wd_package'),
					'description' 	=> esc_html__("Button Text", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'button_text',
					'value' 		=> esc_html__("Subscribe", 'wd_package'),
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Enter your Feedburner ID", 'wd_package'),
					'description' 	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'feedburner_id',
					'value' 		=> esc_html__("WpComic-Manga", 'wd_package'),
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