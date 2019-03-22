<?php
if (!function_exists('wd_product_single_category_function')) {
	function wd_product_single_category_function($atts) {
		extract(shortcode_atts(array(
			'id_category'		=> '-1',
			'image_url'			=> '',
			'image_size'		=> 'full',
			'title'				=> '1',
			'readmore'			=> '1',
			'meta'				=> '1',
			'class'				=> ''

		), $atts));
		if (!wd_is_woocommerce()) return;
		wp_reset_query();	

		$product_categorie = get_term( $id_category, 'product_cat' );	
		ob_start(); ?>
			<?php if($id_category == '-1') : ?>
				<?php esc_html_e('Please select category.','wd_package'); ?>
			<?php else: ?>
				<div class="wd-cate-pro-by-name woocommerce">
					<a href="<?php echo get_category_link($id_category); ?>">
						<div class="wd-image-cate">
							<?php echo apply_filters('wd_filter_image_html', array('attachment' => $image_url, 'image_size' => $image_size)); ?>
						</div>
					</a>
					<div class="wd-cate-info">
						<?php if($title ) : ?>
							<a href="<?php echo get_category_link($id_category); ?>">
								<h2><?php echo $product_categorie->name; ?></h2>
							</a>
						<?php endif; ?>
						<?php if($meta ) : ?>
							<span>(<?php echo $product_categorie->count; _e(' products','wd_package'); ?>)</span>
						<?php endif; ?>
						<?php if($readmore ) : ?>
							<a class='wd-cate-readmore' href="<?php echo get_category_link($id_category); ?>"><?php esc_html_e('Read more','wd_package'); ?></a>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		<?php
		$content = ob_get_clean();
		wp_reset_postdata();
		return $content;
	}
}

if (!function_exists('wd_product_single_category_vc_map')) {
	function wd_product_single_category_vc_map() {
		if (!wd_is_woocommerce()) return;
		return array(
			"name"				=> esc_html__("WD - Product Category (Single)",'wd_package'),
			"base"				=> 'wd_product_single_category',
			'description' 		=> esc_html__("Display detail of Single Product Category...", 'wd_package'),
			"category"			=> esc_html__("WD - Product",'wd_package'),
			'icon'       		=> 'icon-wpb-woocommerce',
			"params"=>array(	
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Select Category', 'wd_package' ),
					'param_name' 	=> 'id_category',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_product_categories_full(false, 'autocomplete'),
					'description' 	=> ''
				),
				array(
					'type' 			=> "attach_image",
					'class' 		=> "",
					'heading' 		=> esc_html__("Background Image", 'wd_package'),
					'param_name' 	=> "image_url",
					'value' 		=> "",
					'description' 	=> '',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Image size', 'wd_package' ),
					'param_name' 	=> 'image_size',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_image_size(),
					'std'			=> 'full',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show title', 'wd_package' ),
					'param_name' 	=> 'title',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show Readmore', 'wd_package' ),
					'param_name' 	=> 'readmore',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'description'  	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show meta', 'wd_package' ),
					'param_name' 	=> 'meta',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
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