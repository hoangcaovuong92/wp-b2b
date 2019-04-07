<?php
if (!function_exists('wd_profile_function')) {
	function wd_profile_function($atts) {
		extract(shortcode_atts(array(
			'image'			=> '',
			'image_size'	=> 'full',
			'website'		=> '#',
			'target'		=> '_blank',
			'job'			=> 'BLOGER / PHOTOGRAPHER / DESIGNER',
			'title'			=> 'Hello everybody, my name is Lara Croft',
			'desc'			=> 'Great design is making something memorable and meaningful',
			'display_logo'	=> 0,
			'text_align'	=> 'text-left',
			'about'			=> '[vc_row][vc_column_inner width="1/2"][vc_column_text]Vivamus eleifend tincidunt dolor, id dapibus purus bibendum quis. Integer feugiat eusmod aliquet. In in maximus erat. Ut elit lacus, molestie vel justo sit amet, condim entum vitae condimentum risus. Suspendisse pulvinar posuere neque quis eleifend. Pell entesque augue nisi, lacinia sed mollis in, gravida in nisi. Nulla egestas dui acort elit semper mas dignissim. Aliquam condimentum ut neque eu cursus. Praesent a metus ege justo essrt fermentum mattis sed sit amet leo. Proin egestas facilisis ultrices. Nulla porta dolor vel quam sollicitudin, a vestibulum nisi dapibus. Curabitur fringilla vestibulum eleifend. Proin vel faucibus nisl. Pellentesque at suscipit nunc. Suspendi sse pulvinar vestibulum orci, vitae sagittis risus elementum ac. Sed eleifend sagittis ornare.[/vc_column_text][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]Nulla porta dolor vel quam sollicitudin, a vestibulum nisi dapibus. Curabitur fringilla vestibulum eleifend. Proin vel faucibus nisl. Pellentesque at suscipit nunc. Suspendi sse pulvinar vestibulum orci, vitae sagittis risus elementum ac. Sed eleifend sagittis ornare. Cras faucibus massa at ligula viverra pulvinar. Praesent tincidunt, eros acoal consectetur dapibus, magna ante elementum risus, tempor posuere lacus justo ut massa. Vivamus eleifend tincidunt dolor, id dapibus purus bibendum quis. Cras faucibus massa at ligula viverra pulvinar. Praesent tincidunt, eros acoal consectetur dapibus, magna ante elementum risus, tempor posuere lacus justo ut massa. Vivamus eleifend tincidunt dolor, id dapibus purus bibendum quis.[/vc_column_text][/vc_column_inner][/vc_row]',
			'display_sign'	=> 1,
			'sign_image'	=> '',
			'fullwidth_mode' => false,
			'class' 		=> ''
		), $atts));

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';

		ob_start(); ?>
		<div class="wd-shortcode wd-shortcode-profile <?php echo esc_attr($class); ?>">	
			<div class="wd-banner-image wd-banner-hover--border">
				<a target="<?php echo esc_attr($target);?>" href="<?php echo esc_url($website)?>">
					<?php echo apply_filters('wd_filter_image_html', array('attachment' => $image, 'image_size' => $image_size)); ?>
				</a>
			</div>
			<?php if ($job || $title || $desc) { ?>
				<div class="wd-profile-meta">
					<?php 
					if ($job) {
						echo '<div class="wd-profile-meta-item wd-profile-meta--job">'.$job.'</div>';
					} 
					if ($title) {
						echo '<div class="wd-profile-meta-item wd-profile-meta--title">'.$title.'</div>';
					}
					if ($desc) {
						echo '<div class="wd-profile-meta-item wd-profile-meta--desc">'.$desc.'</div>';
					} ?>
				</div>
			<?php } ?>
			<div class="wd-profile-content">
				<?php 
				if ($display_logo) {
					echo '<div class="wd-profile-content-item wd-profile-content-logo">';
					echo apply_filters('wd_filter_logo', array());
					echo '</div>';
				} 
				if ($about) {
					echo '<div class="wd-profile-content-item wd-profile-content-about '.esc_attr($text_align).'">';
					echo do_shortcode($about);
					echo '</div>';
				}
				if ($sign_image) {
					echo '<div class="wd-profile-content-item wd-profile-content-sign">';
					echo apply_filters('wd_filter_image_html', array('attachment' => $sign_image, 'image_size' => 'full'));
					echo '</div>';
				} 
				?>
			</div>
		</div>
		<?php
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}

if (!function_exists('wd_profile_vc_map')) {
	function wd_profile_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Profile", 'wd_package'),
			'base' 				=> 'wd_profile',
			'description' 		=> esc_html__("Display user profile...", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'vc_icon-vc-gitem-post-author',
			"params" => array(
				array(
					"type" 			=> "attach_image",
					"class" 		=> "",
					"heading" 		=> esc_html__("Avatar", 'wd_package'),
					"param_name" 	=> "image",
					"value" 		=> "",
					"description" 	=> '',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Image Size', 'wd_package' ),
					'param_name' 	=> 'image_size',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_image_size(),
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Website", 'wd_package'),
					"param_name" 	=> "website",
					'value' 		=> '#',
					"description" 	=> '',
				),
				array(
					"type" 			=> "dropdown",
					"holder" 		=> "div",
					"class" 		=> "",
					"heading" 		=> esc_html__("Link Target", 'wd_package'),
					"param_name" 	=> "target",
					"value" 		=> wd_vc_get_list_link_target(),
					"std" 			=> '_blank',
					"description" 	=> "",
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Occupation", 'wd_package'),
					"param_name" 	=> "job",
					'value' 		=> 'BLOGER / PHOTOGRAPHER / DESIGNER',
					"description" 	=> '',
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Title", 'wd_package'),
					"param_name" 	=> "title",
					'value' 		=> 'Hello everybody, my name is Lara Croft',
					"description" 	=> '',
				),

				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Description", 'wd_package'),
					"param_name" 	=> "desc",
					'value' 		=> 'Great design is making something memorable and meaningful',
					"description" 	=> '',
				),
				array(
					'type'        	=> 'dropdown',
					'heading'     	=> __( 'Display Site Logo', 'wd_package' ),
					'description' 	=> '',
					'param_name'  	=> 'display_logo',
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'std'			=> '0',
					'save_always' 	=> true,
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'About Text Align', 'wd_package' ),
					'param_name' 	=> 'text_align',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_text_align_bootstrap(),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					"type" 			=> "textarea",
					"class" 		=> "",
					"heading" 		=> esc_html__("About Text", 'wd_package'),
					"param_name" 	=> "about",
					"description" 	=> '',
					"value"			=> '[vc_row][vc_column_inner width="1/2"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_column_inner][/vc_row]'
				),
				array(
					'type'        	=> 'dropdown',
					'heading'     	=> __( 'Display Signature Image', 'wd_package' ),
					'description' 	=> '',
					'param_name'  	=> 'display_sign',
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'std'			=> '1',
					'save_always' 	=> true,
				),
				array(
					"type" 			=> "attach_image",
					"class" 		=> "",
					"heading" 		=> esc_html__("Sign Image", 'wd_package'),
					"param_name" 	=> "sign_image",
					"value" 		=> "",
					"description" 	=> '',
					'dependency'  	=> array('element' => "display_sign", 'value' => array('1'))
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