<?php
if (!function_exists('wd_instagram_function')) {
	function wd_instagram_function($atts) {
		$data_filter = shortcode_atts(array(
			'insta_title'				=> '',
			'insta_desc'				=> '',
			'insta_follow'				=> '1',
			'insta_follow_text'			=> 'Follow Me',
			'insta_style'				=> "style-insta-1",
			'insta_hover_style'			=> '',
			'insta_columns'				=> "4",
			'columns_tablet'			=> 2,
			'columns_mobile'			=> 1,
			'insta_number'				=> '4',
			'insta_padding'				=> 'normal',
			'insta_size'				=> 'low_resolution',
			'insta_sortby'				=> 'none',
			'insta_action_click_item'	=> 'lightbox',
			'insta_open_win'			=> '_blank',
			'is_slider'					=> '0',
			'show_loadmore'				=> '0',
			'show_nav'					=> '1',
			'auto_play'					=> '1',
			'per_slide'					=> '1',
			'class' 					=> '',
		), $atts);

		$data_settings = apply_filters('wd_filter_get_data_package', 'instagram');
		if (empty($data_settings)) return;

		$data_filter =  array_merge($data_filter, $data_settings);
		extract($data_filter);
		ob_start(); 
			$status = wd_instagram_check_access_token($insta_user, $insta_access_token);
			$class_column 		= ( $is_slider == '0') ? 'wd-columns-'.$insta_columns.' wd-tablet-columns-'.$columns_tablet.' wd-mobile-columns-'.$columns_mobile.' wd-columns-padding-'.$insta_padding : '';
			$random_id 			= 'random-'.mt_rand();
			$data_filter['random_id'] 	= $random_id; ?>
			
			<?php if ($status['success']): ?>
				<div class="wd-instagram-wrapper clearfix <?php echo esc_attr($insta_style); ?> <?php echo esc_attr($class); ?>"  style="width: 100%;">
					<?php if ($insta_title != "" || $insta_desc != '' || $insta_follow): ?>
						<div class="wd-insta-header">
							<div class="wd-title">
								<?php if($insta_title != ""): ?>
									<h2 class="wd-title-heading"><?php echo esc_attr($insta_title); ?></h2>
								<?php endif; ?>

								<?php if($insta_desc != '' || $insta_follow) : ?> 
									<p class="wd-insta-follow">
										<?php if($insta_desc != '') : ?>
											<?php echo esc_html($insta_desc); ?>
										<?php endif; ?>
										<?php if($insta_desc != '' && $insta_follow) : ?>
											<?php _e(' | ','wd_package') ?>
										<?php endif; ?>
										<?php if($insta_follow) : ?> 
											<a target="_blank" href="https://www.instagram.com/<?php echo esc_attr($status['username']);?>"><?php echo esc_html($insta_follow_text); ?></a>
										<?php endif; ?>
									</p>
								<?php endif; ?>
							</div>
						</div>
					<?php endif ?>
					<div class="wd-insta-content <?php echo esc_attr($class_column); ?>">
						<div class="wd-insta-content-wrapper"">
							<ul id="wd-instagram-content-<?php echo $random_id; ?>" class="wd-insta-content-item wd-columns-list-item <?php echo esc_attr($insta_hover_style); ?>"></ul>
							
							<?php if ($show_loadmore == '1' && $is_slider == '0'): ?>
								<div class="wd-insta-loadmore-btn clearfix">
									<input type="submit" value="<?php _e('LOAD MORE','wd_package') ?>" id="wd-instagram-load-more-<?php echo $random_id; ?>" />
								</div>
							<?php endif ?>
							
							<?php if( $show_nav && $is_slider ){ ?>
								<?php wd_slider_control(); ?>
							<?php } ?>
							<script type="text/javascript">
								jQuery(document).ready(function(){
									"use strict";
									var data = <?php echo json_encode($data_filter); ?>;
									wd_instagram_shortcode_script(data); 
								});
							</script>
						</div>
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
add_shortcode('wd_instagram', 'wd_instagram_function');