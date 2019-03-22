<?php
if(!function_exists('wd_social_icons_function')){
	function wd_social_icons_function($atts){
		extract(shortcode_atts(array(
			'style'						=> 'vertical', 
			'show_title'				=> '1',
			'item_align'				=> 'wd-flex-justify-left',
			'rss_id'					=> '#',
			'twitter_id'				=> '#',
			'facebook_id'				=> '#',
			'google_id'					=> '#',
			'pin_id'					=> '#',
			'youtube_id'				=> '#',
			'instagram_id'				=> '#',
			'class'						=> ''
		),$atts));
		
		$data_settings = array(
			'icon-facebook' => array(
				'status'		=> $facebook_id != '' ? true : false,
				'title'			=> esc_html__('Facebook', 'wd_package'),
				'desc'			=> esc_html__('Become our fan on facebook', 'wd_package'),
				'icon'			=> 'fa fa-facebook',
				'pre_url'		=> 'http://www.facebook.com/',
				'user'			=> $facebook_id,
			),
			'icon-rss' 		=> array(
				'status'		=> $rss_id != '' ? true : false,
				'title'			=> esc_html__('Rss', 'wd_package'),
				'desc'			=> esc_html__('Rss', 'wd_package'),
				'icon'			=> 'fa fa-rss',
				'pre_url'		=> 'https://www.rss.com/',
				'user'			=> $rss_id,
			),
			'icon-twitter' 	=> array(
				'status'		=> $twitter_id != '' ? true : false,
				'title'			=> esc_html__('Twitter', 'wd_package'),
				'desc'			=> esc_html__('Twitter', 'wd_package'),
				'icon'			=> 'fa fa-twitter',
				'pre_url'		=> 'http://twitter.com/',
				'user'			=> $twitter_id,
			),
			'icon-google' => array(
				'status'		=> $google_id != '' ? true : false,
				'title'			=> esc_html__('Google', 'wd_package'),
				'desc'			=> esc_html__('Google', 'wd_package'),
				'icon'			=> 'fa fa-google-plus',
				'pre_url'		=> 'https://plus.google.com/u/0/',
				'user'			=> $google_id,
			),
			'icon-pinterest' => array(
				'status'		=> $pin_id != '' ? true : false,
				'title'			=> esc_html__('Pinterest', 'wd_package'),
				'desc'			=> esc_html__('Pinterest', 'wd_package'),
				'icon'			=> 'fa fa-pinterest',
				'pre_url'		=> 'http://www.pinterest.com/',
				'user'			=> $pin_id,
			),
			'icon-youtube' => array(
				'status'		=> $youtube_id != '' ? true : false,
				'title'			=> esc_html__('Youtube', 'wd_package'),
				'desc'			=> esc_html__('Youtube', 'wd_package'),
				'icon'			=> 'fa fa-youtube',
				'pre_url'		=> 'http://youtube.com/',
				'user'			=> $youtube_id,
			),
			'icon-instagram' => array(
				'status'		=> $instagram_id != '' ? true : false,
				'title'			=> esc_html__('Instagram', 'wd_package'),
				'desc'			=> esc_html__('Instagram', 'wd_package'),
				'icon'			=> 'fa fa-instagram',
				'pre_url'		=> 'http://www.instagram.com/',
				'user'			=> $instagram_id,
			),
		);
		$style_class = 'wd-social-icons-'.$style;
		$style_class .= $show_title ? ' wd-social-has-title' : ' wd-social-hide-title';
		$list_class = ($style === 'nav-user') ? ' wd-navUser-list' : '';
		$list_class .= ' '.$item_align;
		ob_start();
		?>
		<div class="wd-social-icons <?php echo esc_attr( $style_class ); ?> <?php echo esc_attr( $class ); ?>">
			<ul class="wd-social-icons-list<?php echo esc_attr( $list_class ); ?>">
				<?php foreach ($data_settings as $key => $value): ?>
					<?php if ($value['status']): ?>
						<?php 
						$url 		= ($value['user'] != '#' && $value['user'] != '') ? $value['pre_url'].$value['user'] : '#' ;
						$icon_html 	= '<span class="'.esc_attr($value['icon']).' wd-icon"></span>';
						$title 		= ($show_title) ? '<span class="wd-navUser-action-text">'.$value['title'].'</span>' : ''; ?>
						<li class="<?php echo esc_attr($key); ?>">
						<?php if ($style === 'nav-user'): ?>
							<div class="wd-navUser-action-wrap">
								<a class="wd-navUser-action wd-navUser-action--social" target="_blank" href="<?php echo esc_url($url); ?>">
									<?php echo $icon_html.$title; ?>
								</a>
							</div>
						<?php else: ?>
							<a href="<?php echo $url; ?>" target="_blank" title="<?php echo $value['desc']; ?>" >
								<?php echo $icon_html.$title; ?>
							</a>
						<?php endif ?>
						</li>
					<?php endif ?>
				<?php endforeach ?>
			</ul>
		</div>

		<?php
		$output = ob_get_clean();
		wp_reset_query();
		return $output;
	}
}

if (!function_exists('wd_social_icons_vc_map')) {
	function wd_social_icons_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Social Icons", 'wd_package'),
			'base' 				=> 'wd_social_icons',
			'description' 		=> esc_html__("Display social icon with many style...", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'icon-wpb-balloon-facebook-left',
			'params' => array(
				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Style Show", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "style",
					"value" => array(
							esc_html__('Style 1 (Vertical)', 'wd_package') 		=> 'vertical',
							esc_html__('Style 2 (Horizontal)', 'wd_package') 	=> 'horizontal',
							esc_html__('Style 3 (Nav User)', 'wd_package') 		=> 'nav-user'
						),
					"description" 	=> "",
				),
				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Show Title Social", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "show_title",
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					"description" 	=> "",
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Item Align", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "item_align",
					'value' 		=> wd_vc_get_list_flex_align_class(),
					'std'			=> 'wd-flex-justify-left',
					"description" 	=> "",
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("RSS ID", 'wd_package'),
					'description' 	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'rss_id',
					'value' 		=> '#',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Twitter ID", 'wd_package'),
					'description' 	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'twitter_id',
					'value' 		=> '#',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Facebook ID", 'wd_package'),
					'description' 	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'facebook_id',
					'value' 		=> '#',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Google Plus ID", 'wd_package'),
					'description' 	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'google_id',
					'value' 		=> '#',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Pinterest ID", 'wd_package'),
					'description' 	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'pin_id',
					'value' 		=> '#',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Youtube ID", 'wd_package'),
					'description' 	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'youtube_id',
					'value' 		=> '#',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("RSS Instagram", 'wd_package'),
					'description' 	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'instagram_id',
					'value' 		=> '#',
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