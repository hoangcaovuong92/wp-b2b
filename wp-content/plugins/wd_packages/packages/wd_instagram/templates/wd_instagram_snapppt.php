<?php
if (!function_exists('wd_instagram_snapppt_function')) {
	function wd_instagram_snapppt_function($atts) {
		extract(shortcode_atts(array(
			'instagram_snapppt'			=> '',
			'class' 					=> '',
		), $atts));
		ob_start(); ?>
			<?php 
			if ( $instagram_snapppt != '' && !is_admin() ) { ?>
				<div class="wd-home-instagram-snapppt-shortcode <?php echo esc_attr( $class ); ?>">
					<div class="wd-home-instagram-snapppt-content">
						<script async="" src="<?php echo esc_url($instagram_snapppt); ?>" class="snapppt-widget"></script>
						
					</div>
				</div>
			<?php 
			} 
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}
add_shortcode('wd_instagram_snapppt', 'wd_instagram_snapppt_function');