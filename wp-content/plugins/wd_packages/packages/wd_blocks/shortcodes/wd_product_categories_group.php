<?php 
if ( ! function_exists( 'wd_product_categories_group_function' ) ) {
	function wd_product_categories_group_function( $atts ) {
		$attr = shortcode_atts( array(
			'categories_group' => '',
			'is_slider'        => '0',
		), $atts );
		if (!wd_is_woocommerce()) return;
		$categories_group = json_decode( urldecode( $attr['categories_group'] ), true );

		ob_start();

		?>
		<div class="wd-categories-group-wrapper woocommerce">
			<ul class="wd-categories-group list-inline" data-slider="<?php echo $attr['is_slider'] ?>">
				<?php foreach ( $categories_group as $categories ):
					// Get image for category
					if ( wp_check_filetype( basename( get_attached_file( $categories['image_category'] ) ) )['ext'] === 'svg' ) {
						$image = file_get_contents( get_attached_file( $categories['image_category'] ) );
					} else {
						$image = wp_get_attachment_image( $categories['image_category'] );
					}
					// Get category name and category slug
					$term = get_term_by( 'id', $categories['id_category'], 'product_cat' );
					$link = get_term_link( $term->slug, $term->taxonomy );
					?>
					<li class="wd-category-group-item text-center">
						<a href="<?php echo $link ?>">
							<?php echo $image ?>
							<div class="wd-category-group-name"><?php echo $term->name ?></div><!-- .category-name -->
						</a>
					</li><!-- .category-item -->
				<?php endforeach; ?>
			</ul><!-- .categories-group -->
		</div><!--.categories-group-wrapper-->
		<?php
		return ob_get_clean();
	}
}

if (!function_exists('wd_product_categories_group_vc_map')) {
	function wd_product_categories_group_vc_map() {
		if (!wd_is_woocommerce()) return;
		return array(
			'name'        => __( "WD - Product Categories Group", 'wd_package' ),
			'description' => __( "Display product categories name with custom image", 'wd_package' ),
			'base'        => 'wd_product_categories_group',
			'category'    => __( "WD - Product", 'wd_package' ),
			'icon'        => 'icon-wpb-woocommerce',
			'params'      => array(
				array(
					'type'       => 'param_group',
					'heading'    => __( 'Categories group', 'wd_package' ),
					'param_name' => 'categories_group',
					'params'     => array(
						array(
							'type'             => 'dropdown',
							'heading'          => __( 'Category', 'wd_package' ),
							'description'      => __( 'Product category list', 'wd_package' ),
							'param_name'       => 'id_category',
							'admin_label'      => true,
							'value'            => wd_vc_get_list_category('product_cat', false),
							'edit_field_class' => 'vc_col-sm-8',
						),
						array(
							'type'             => 'attach_image',
							'heading'          => __( "Category Image", 'wd_package' ),
							"param_name"       => "image_category",
							'admin_label'      => true,
							'edit_field_class' => 'vc_col-sm-4',
						),
	
					),
				),
				array(
					"type"        => "dropdown",
					"class"       => "",
					"heading"     => __( "Is Slider", 'wd_package' ),
					"admin_label" => true,
					"param_name"  => "is_slider",
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
				),
			),
		);
	}
}