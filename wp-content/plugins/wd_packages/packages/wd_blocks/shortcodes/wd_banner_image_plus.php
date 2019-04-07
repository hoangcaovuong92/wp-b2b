<?php
if (!function_exists('wd_banner_image_plus_function')) {
	function wd_banner_image_plus_function($atts) {
		extract(shortcode_atts(array(
			'type'				=> 'image',
			'banner_image'		=> '',
			'gallery_image'		=> '',
			'gallery_style'		=> 'style-1',
			'slider_image'		=> '',
			'slider_columns'	=> '1',
			'columns_tablet'	=> 2,
			'columns_mobile'	=> 1,
			'image_size'		=> 'full',
			'video_id'			=> '',
			'video_width'		=> 570,
			'video_height'		=> 400,
			'video_autoplay'	=> 0,
			'heading_line_1'	=> '',
			'heading_line_2'	=> "",
			'show_line'			=> '1',
			'description'		=> '',
			'position_content'	=> 'center',
			'border_content'	=> '1',
			'show_button'		=> '1',
			'button_style'		=> 'wd-button-primary',
			'link_type'			=> 'category_link',
			'url'				=> '#',
			'id_category' 		=> '',
			'button_text' 		=> 'View Category',
			'button_class' 		=> '',
			'fullwidth_mode'	=> false,
			'class' 			=> ''
		), $atts));
		
		$type_class = ($type == 'video') ? 'wd-banner-plus-video' : 'wd-banner-plus-image';

		if ($link_type == 'category_link' && wd_is_woocommerce()) {
			if ($id_category != '') {
				$link_url = ($id_category == -1 || !term_exists( $id_category, 'product_cat' )) ? get_permalink( wc_get_page_id( 'shop' ) ) : get_term_link( get_term_by( 'id', $id_category, 'product_cat' ), 'product_cat' );
			}else{
				$link_url = '#';
			}
		}else{
			$link_url = $url;
		}
		
		$position_content_class = 'wd-banner-image-position-content-'.$position_content;
		$border_content_class   = ($border_content == 1) ? 'wd-banner-plus-with-border_content' : '';
		$image_attr				= array(
			'alt' 		=> get_bloginfo('name').' - Banner Image Plus',
			'title' 	=> get_bloginfo('name').' - Banner Image Plus',
		);

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';

		ob_start(); ?>
			<?php if ($type == 'image'): ?>
				<?php if ($banner_image): ?>
					<?php echo wp_get_attachment_image($banner_image, $image_size, false, $image_attr); ?>
				<?php endif ?>
			<?php elseif ($type == 'gallery'): ?>
				<?php if ($gallery_image != ''){
					$gallery_image = explode(',', $gallery_image); 
					$gallery_class = 'wd-banner-image-gallery-'. $gallery_style; ?>
					<div class="<?php echo esc_attr($gallery_class); ?>">
						<?php foreach ($gallery_image as $image): ?>
							<div class="wd-banner-image-gallery-item">
								<?php echo wp_get_attachment_image($image, $image_size, false, $image_attr); ?>
							</div>
						<?php endforeach ?>
					</div>
				<?php } ?>
			<?php elseif ($type == 'video'): ?>
				<?php if ($video_id != ''): ?>
					<iframe width="<?php echo esc_html($video_width); ?>px" height="<?php echo esc_html($video_height); ?>px" src="https://www.youtube.com/embed/<?php echo esc_html($video_id); ?>?autoplay=<?php echo esc_html($video_autoplay); ?>&showinfo=0&controls=0&rel=0" frameborder="0" allowfullscreen></iframe>
				<?php endif ?>	
			<?php elseif ($type == 'slider'): ?>
				<?php 
				$slider_options = json_encode(array(
					'slider_type' => 'slick',
					'column_desktop' => esc_attr($slider_columns),
					'column_tablet' => esc_attr($columns_tablet),
					'column_mobile' => esc_attr($columns_mobile),
				)); ?>
				<div class="wd-slider-wrap wd-slider-wrap--banner-plus" 
					data-slider-options='<?php echo $slider_options; ?>'>
					<?php 
					$banner_image = array();
					if ($slider_image) {
						$slider_image = explode(',', $slider_image);
						foreach ($slider_image as $image) {
							echo wp_get_attachment_image($image, $image_size, false, $image_attr);
						}
					}
					?>
				</div>
			<?php endif ?>
		<?php
		$main_content = ob_get_clean();
		ob_start(); ?>
			<div class="wd-shortcode wd-shortcode-banner-plus <?php echo esc_attr($position_content_class); ?> <?php echo esc_attr($class); ?>">
				<?php if ($position_content == 'outside-right' || $position_content == 'inside-right'): ?>
					<div class="<?php echo esc_attr($type_class); ?>">
						<?php echo $main_content; ?>
					</div>			
				<?php endif ?>
				
				<div class="wd-banner-plus-body <?php echo esc_attr($border_content_class); ?>">
					<?php if ($position_content == 'center' && ($banner_image != '' || $slider_image != '' || $video_id)): ?>
						<!-- Show banner image -->
						<div class="wd-banner-plus-image">
							<?php echo $main_content; ?>
						</div>			
					<?php endif ?>
					
					<!-- Content heading... -->
					<?php if( $heading_line_1 != '' || $heading_line_2 != '' || $description != '' || $show_button == '1' ):?>
						<div class="wd-banner-plus-text">
							<?php if ($heading_line_1 != ''): ?>
								<h2 class="wd-banner-plus-heading-1"><?php echo esc_html($heading_line_1); ?></h2>
							<?php endif ?>
							
							<?php if ($heading_line_2 != ''): ?>
								<h3 class="wd-banner-plus-heading-2"><?php echo esc_html($heading_line_2); ?></h3>
							<?php endif ?>
							
							<?php if ($show_line == '1'): ?>
								<hr class="wd-banner-plus-line" />
							<?php endif ?>
							
							<?php if ($description != ''): ?>
								<h3 class="wd-banner-plus-description"><?php echo esc_html($description); ?></h3>
							<?php endif ?>

							<?php if($show_button == '1'):?>
								<div class="wd-banner-plus-button">
									<a class="<?php echo esc_attr($button_style); ?> <?php echo esc_attr($button_class); ?>" href="<?php echo esc_url($link_url); ?>"><?php echo esc_attr($button_text); ?></a>
								</div>
							<?php endif;?>
						</div>
					<?php endif ?>
				</div>
				
				<?php if ( $position_content == 'outside-left' || $position_content == 'inside-left' ): ?>
					<div class="<?php echo esc_attr($type_class); ?>">
						<?php echo $main_content; ?>
					</div>				
				<?php endif ?>		
			</div>
		<?php
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}

if (!function_exists('wd_banner_image_plus_vc_map')) {
	function wd_banner_image_plus_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Banner Image Plus", 'wd_package'),
			'base' 				=> 'wd_banner_image_plus',
			'description' 		=> esc_html__("Banner/Slider/Video youtube...", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'icon-wpb-images-stack',
			"params" => array(
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Type', 'wd_package' ),
					'param_name' 	=> 'type',
					'admin_label' 	=> true,
					'value' 		=> array(
						esc_html__( 'Image', 'wd_package' )				=> 'image',
						esc_html__( 'Gallery', 'wd_package' )			=> 'gallery',
						esc_html__( 'Video Youtube', 'wd_package' )		=> 'video',
						esc_html__( 'Slider Image', 'wd_package' )		=> 'slider',
					),
				),
				/*-----------------------------------------------------------------------------------
					IMAGE SETTING
				-------------------------------------------------------------------------------------*/
				array(
					"type" 			=> "attach_image",
					"class" 		=> "",
					"heading" 		=> esc_html__("Banner Image", 'wd_package'),
					"param_name" 	=> "banner_image",
					"value" 		=> "",
					"description" 	=> esc_html__('', 'wd_package'),
					'dependency'  	=> array('element' => "type", 'value' => array('image'))
				),
				/*-----------------------------------------------------------------------------------
					IMAGE SETTING
				-------------------------------------------------------------------------------------*/
				array(
					"type" 			=> "attach_images",
					"class" 		=> "",
					"heading" 		=> esc_html__("Gallery Image", 'wd_package'),
					"param_name" 	=> "gallery_image",
					"value" 		=> "",
					"description" 	=> esc_html__('', 'wd_package'),
					'dependency'  	=> array('element' => "type", 'value' => array('gallery'))
				),
				array(
					'type' 			=> 'dropdown',
					'class' 		=> '',
					'heading' 		=> esc_html__("Gallery Style", 'wd_package'),
					'description'	=> esc_html__("", 'wd_package'),
					'param_name' 	=> 'gallery_style',
					'value' 		=> array(
						esc_html__( 'Style 1 (2 images)', 'wd_package' )		=> 'style-1',
						esc_html__( 'Style 2 (2 images)', 'wd_package' )		=> 'style-2',
						esc_html__( 'Style 3 (2 images)', 'wd_package' )		=> 'style-3',
						esc_html__( 'Style 4 (3 images)', 'wd_package' )		=> 'style-4',
					),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "type", 'value' => array('gallery'))
				),
				/*-----------------------------------------------------------------------------------
					SLIDER SETTING
				-------------------------------------------------------------------------------------*/
				array(
					"type" 			=> "attach_images",
					"class" 		=> "",
					"heading" 		=> esc_html__("Slider Images", 'wd_package'),
					"param_name" 	=> "slider_image",
					"value" 		=> "",
					"description" 	=> esc_html__('Select multi banner for slider', 'wd_package'),
					'dependency'  	=> array('element' => "type", 'value' => array('slider'))
				),
				array(
					"type" 			=> "textfield",
					  "holder" 		=> "div",
					  "class" 		=> "",
					"heading" 		=> esc_html__("Slider Columns", 'wd_package'),
					"param_name" 	=> "slider_columns",
					"value" 		=> "1",
					"description" 	=> esc_html__('', 'wd_package'),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "type", 'value' => array('slider'))
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Image Size', 'wd_package' ),
					'param_name' 	=> 'image_size',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_image_size(),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "type", 'value' => array('image', 'gallery', 'slider'))
				),
				/*-----------------------------------------------------------------------------------
					VIDEO SETTING
				-------------------------------------------------------------------------------------*/
				array(
					"type" 			=> "textfield",
					  "holder" 		=> "div",
					  "class" 		=> "",
					"heading" 		=> esc_html__("Video ID", 'wd_package'),
					"param_name" 	=> "video_id",
					"description" 	=> esc_html__('Your Youtube Video ID...', 'wd_package'),
					'dependency'  	=> array('element' => "type", 'value' => array('video'))
				),
				array(
					"type" 			=> "textfield",
					  "holder" 		=> "div",
					  "class" 		=> "",
					"heading" 		=> esc_html__("Video Width (Px)", 'wd_package'),
					"param_name" 	=> "video_width",
					"description" 	=> esc_html__('', 'wd_package'),
					'value'			=> 570,
					'edit_field_class' => 'vc_col-sm-4',
					'dependency'  	=> array('element' => "type", 'value' => array('video'))
				),
				array(
					"type" 			=> "textfield",
					  "holder" 		=> "div",
					  "class" 		=> "",
					"heading" 		=> esc_html__("Video height (Px)", 'wd_package'),
					"param_name" 	=> "video_height",
					"description" 	=> esc_html__('', 'wd_package'),
					'value'			=> 400,
					'edit_field_class' => 'vc_col-sm-4',
					'dependency'  	=> array('element' => "type", 'value' => array('video'))
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Video Autoplay', 'wd_package' ),
					'param_name' 	=> 'video_autoplay',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'std'			=> '0',
					'edit_field_class' => 'vc_col-sm-4',
					'dependency'  	=> array('element' => "type", 'value' => array('video'))
				),
	
				/*-----------------------------------------------------------------------------------
					CONTENT SETTING
				-------------------------------------------------------------------------------------*/
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show Content', 'wd_package' ),
					'param_name' 	=> 'show_content',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'group'			=> esc_html__( 'Content Setting', 'wd_package' ),
					'description' 	=> esc_html__('Display title, description, line, button...', 'wd_package'),
				),
				array(
					"type" 			=> "textfield",
					  "holder" 		=> "div",
					  "class" 		=> "",
					"heading" 		=> esc_html__("Heading Line 1", 'wd_package'),
					"param_name" 	=> "heading_line_1",
					"description" 	=> esc_html__('Leave blank to hide...', 'wd_package'),
					'group'			=> esc_html__( 'Content Setting', 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "show_content", 'value' => array('1'))
				),
				array(
					"type" 			=> "textfield",
					  "holder"		=> "div",
					  "class" 		=> "",
					"heading" 		=> esc_html__("Heading Line 2", 'wd_package'),
					"param_name" 	=> "heading_line_2",
					"description" 	=> esc_html__('Leave blank to hide...', 'wd_package'),
					'group'			=> esc_html__( 'Content Setting', 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "show_content", 'value' => array('1'))
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show Line', 'wd_package' ),
					'param_name' 	=> 'show_line',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'description' 	=> esc_html__('Line separates heading and desc', 'wd_package'),
					'group'			=> esc_html__( 'Content Setting', 'wd_package' ),
					'dependency'  	=> array('element' => "show_content", 'value' => array('1'))
				),
				array(
					"type" 			=> "textarea",
					"class" 		=> "",
					"heading" 		=> esc_html__("Description", 'wd_package'),
					"param_name" 	=> "description",
					"description" 	=> esc_html__('Leave blank to hide...', 'wd_package'),
					'group'			=> esc_html__( 'Content Setting', 'wd_package' ),
					'dependency'  	=> array('element' => "show_content", 'value' => array('1'))
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Position Content', 'wd_package' ),
					'param_name' 	=> 'position_content',
					'admin_label' 	=> true,
					'value' 		=> array(
						esc_html__( 'Center', 'wd_package' ) 			=> 'center',
						esc_html__( 'Outside - Left', 'wd_package' ) 	=> 'outside-left',
						esc_html__( 'Outside - Right', 'wd_package' ) 	=> 'outside-right',
						esc_html__( 'Inside - Left', 'wd_package' ) 	=> 'inside-left',
						esc_html__( 'Inside - Right', 'wd_package' ) 	=> 'inside-right',
					),
					'description' 	=> '',
					'group'			=> esc_html__( 'Content Setting', 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "show_content", 'value' => array('1'))
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Border Content', 'wd_package' ),
					'param_name' 	=> 'border_content',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'std'			=> '0',
					'description' 	=> '',
					'group'			=> esc_html__( 'Content Setting', 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "show_content", 'value' => array('1'))
				),
				/*-----------------------------------------------------------------------------------
					BUTTON SETTING
				-------------------------------------------------------------------------------------*/
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show Button', 'wd_package' ),
					'param_name' 	=> 'show_button',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'description' 	=> '',
					'group'			=> esc_html__( 'Button Setting', 'wd_package' ),
					'dependency'  	=> array('element' => "show_content", 'value' => array('1'))
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Button Style', 'wd_package' ),
					'param_name' 	=> 'button_style',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_button_style(),
					'description' 	=> '',
					'group'			=> esc_html__( 'Button Setting', 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "show_button", 'value' => array('1'))
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Link type', 'wd_package' ),
					'param_name' 	=> 'link_type',
					'admin_label' 	=> true,
					'value' 		=> array(
						esc_html__( 'Category Link', 'wd_package' )	=> 'category_link',
						esc_html__( 'Another Url', 'wd_package' )	=> 'url',
					),
					'description' 	=> '',
					'group'			=> esc_html__( 'Button Setting', 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "show_button", 'value' => array('1'))
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Select Category', 'wd_package' ),
					'param_name' 	=> 'id_category',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_category(),
					'description' 	=> '',
					'group'			=> esc_html__( 'Button Setting', 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "link_type", 'value' => array('category_link'))
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("URL", 'wd_package'),
					"param_name" 	=> "url",
					"description" 	=> esc_html__('', 'wd_package'),
					'value' 		=> esc_html__('#', 'wd_package'),
					'group'			=> esc_html__( 'Button Setting', 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "link_type", 'value' => array('url'))
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Button text", 'wd_package'),
					"param_name" 	=> "button_text",
					"description" 	=> esc_html__('', 'wd_package'),
					'value' 		=> esc_html__('View Category', 'wd_package'),
					'group'			=> esc_html__( 'Button Setting', 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "show_button", 'value' => array('1'))
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Button class", 'wd_package'),
					"param_name" 	=> "button_class",
					"description" 	=> '',
					'group'			=> esc_html__( 'Button Setting', 'wd_package' ),
					'dependency'  	=> array('element' => "show_button", 'value' => array('1'))
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