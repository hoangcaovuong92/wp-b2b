<?php 
if ( ! function_exists( 'wd_product_categories_accordion_function' ) ) {
	function wd_product_categories_accordion_function( $atts ) {
		$attr = shortcode_atts( array(
			'show_product_count' => '1',
			'show_sub_cat' 		=> '1',
			'is_dropdown' 		=> '1',
			'sort'				=> 'DESC',
			'order_by'			=> 'date',
			'number' 			=> '-1',
			'class' 			=> '1',
		), $atts );
		if (!wd_is_woocommerce()) return;
		extract($attr);
		ob_start();
		$random_id 		= 'wd_product_categories_'.rand(0,1000);
		$current_cat 	= (isset($_GET['product_cat']) && $_GET['product_cat']!='')?$_GET['product_cat']:get_query_var('product_cat');
		?>
		<div class="wd-shortcode-product-categories-accordion woocommerce" id="<?php echo $random_id; ?>">
			<?php 
				$args = array(
					'taxonomy'     => 'product_cat',
					'orderby'      => $order_by,
					'order'        => $sort,
					'hierarchical' => 0,
					'parent'       => 0,
					'title_li'     => '',
					'hide_empty'   => 0,
					'number'   	   => $number
				);
				$all_categories = get_categories( $args );
				if( $all_categories ){
					if($order_by == 'rand'){
						shuffle($all_categories);
					}
					echo '<ul class="'.(($is_dropdown || wp_is_mobile())?'dropdown_mode is_dropdown':'hover_mode').'">';
					foreach ($all_categories as $cat) {
						$current_class = ($current_cat == $cat->slug)?'current':''; 
						echo '<li class="cat_item">';
						$category_id = $cat->term_id;
						echo '<a href="'. get_term_link($cat->slug, 'product_cat') .'" class="'.$current_class.'">'. $cat->name;
						if($show_product_count){
							echo ' ('. $cat->count .')';
						}
						echo "</a>";
						if($show_sub_cat){
							wd_get_sub_categories_accordion($category_id,$attr,$current_cat, 1);
						}
						echo '</li>';
					}
					echo '</ul>';
				}
			?>
		</div>
		<?php
		return ob_get_clean();
	}
}

if (!function_exists('wd_product_categories_accordion_vc_map')) {
	function wd_product_categories_accordion_vc_map() {
		if (!wd_is_woocommerce()) return;
		return array(
			'name'        => __( "WD - Product Categories Accordion", 'wd_package' ),
			'description' => __( "Display product categories list with accordion", 'wd_package' ),
			'base'        => 'wd_product_categories_accordion',
			'category'    => __( "WD - Product", 'wd_package' ),
			'icon'        => 'icon-wpb-woocommerce',
			'params'      => array(
				array(
					'type'        	=> 'dropdown',
					'heading'     	=> __( 'Show Product Count', 'wd_package' ),
					'description' 	=> __( '', 'wd_package' ),
					'param_name'  	=> 'show_product_count',
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'save_always' 	=> true,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'        	=> 'dropdown',
					'heading'     	=> __( 'Show Sub Categories', 'wd_package' ),
					'description' 	=> __( '', 'wd_package' ),
					'param_name'  	=> 'show_sub_cat',
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'save_always' 	=> true,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'        	=> 'dropdown',
					'heading'     	=> __( 'Is Dropdown', 'wd_package' ),
					'description' 	=> __( 'Enable this option to display the "View All Products" tab redirecting to the shop page.', 'wd_package' ),
					'param_name'  	=> 'is_dropdown',
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'save_always' 	=> true,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Sort By', 'wd_package' ),
					'param_name' 	=> 'sort',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_sort_by_values(),
					'std'			=> 'DESC',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Order By', 'wd_package' ),
					'param_name' 	=> 'order_by',
					'admin_label' 	=> true ,
					'value' 		=> wd_vc_get_order_by_values('product'),
					'std'			=> 'date',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Number Categories", 'wd_package'),
					'description'	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'number',
					'value' 		=> '-1',
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
			),
		);
	}
}