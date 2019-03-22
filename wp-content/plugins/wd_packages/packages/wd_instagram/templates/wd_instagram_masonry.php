<?php
if (!function_exists('wd_instagram_masonry_function')) {
	function wd_instagram_masonry_function($atts) {
		$data_filter = shortcode_atts(array(
			'insta_user'				=> '484934710',
			'insta_client_id'			=> '3433732ada5842a5abce2eddeb702d41',
			'insta_access_token'		=> '84934710.3433732.c52c74a8827a4d8a9357d1912b04c8f2',
			'insta_style'				=> "style-insta-1",
			'insta_columns'				=> "4",
			'columns_tablet'			=> 2,
			'columns_mobile'			=> 1,
			'insta_number'				=> '4',
			'insta_padding'				=> '0',
			'insta_size'				=> 'low_resolution',
			'insta_sortby'				=> 'none',
			'insta_action_click_item'	=> 'lightbox',
			'insta_open_win'			=> '_blank',
			'mansory_image_size'		=> '1:1, 1:1, 2:2, 1:1, 1:1, 1:1, 1:1, 1:1, 1:1',
			'class' 					=> '',
		), $atts);
		extract($data_filter);
		ob_start(); 
			
		$status = wd_instagram_check_access_token($insta_user, $insta_access_token);
		$style_padding_item = ($insta_padding) ? 'padding:'.$insta_padding.'px;' : '' ;
		$style_wrap_item 	= ($insta_padding) ? 'margin-left:-'.$insta_padding.'px; margin-right:-'.$insta_padding.'px;' : '' ;
		$class_column 		= 'wd-columns-'.$insta_columns.' wd-tablet-columns-'.$columns_tablet.' wd-mobile-columns-'.$columns_mobile; 
		$random_id 			= mt_rand();
		$data_filter['random_id'] 			= $random_id; 
		$data_filter['style_padding_item'] 	= $style_padding_item;
		?>
		
		<?php if ($status['success']): ?>	
				<div class="wd-insta-masonry-wrapper clearfix <?php echo esc_attr($insta_style); ?> <?php echo esc_attr($class); ?>">
					<?php 
					$mansory_image_size = str_replace(', ', ',', $mansory_image_size);
					$mansory_image_size = $mansory_image_size != '' ? explode(',', $mansory_image_size) : '';
					$class_masonry_wrap = 'wd-instagram-masonry-wrap';
					$class_masonry_item = 'wd-instagram-masonry-item'; 
					$class_lightbox 	= ( $insta_action_click_item == 'lightbox' ) ? 'wd-gallery' : '';

					$data_filter['mansory_image_size'] 	= $mansory_image_size;
					$data_filter['class'] 				= $class_masonry_item;

					 ?>

					<div id="<?php echo esc_attr( $random_id ); ?>" class="wd-insta-masonry-content <?php echo esc_attr($class_column); ?>">
						<div class="wd-insta-masonry-content-wrapper">	
							<ul id="wd-instagram-content-<?php echo $random_id; ?>" class="wd-insta-masonry-content-item <?php echo esc_attr( $class_masonry_wrap ); ?> <?php echo esc_attr($class_lightbox); ?>"></ul>
						</div>
						<script type="text/javascript">	
							jQuery(document).ready(function(){
								"use strict";	
								var data = <?php echo json_encode($data_filter); ?>;
								wd_instagram_shortcode_script(data); 
							});
						</script>
					</div>
				</div>	
		<?php else: ?>
			<div class="wd-insta-error"><?php echo $status['error']; ?></div>
		<?php endif ?>
		<?php
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}
add_shortcode('wd_instagram_masonry', 'wd_instagram_masonry_function');