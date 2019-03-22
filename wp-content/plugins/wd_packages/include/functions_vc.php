<?php
// Get Data Choose for visual composer
if(!function_exists ('wd_vc_get_data_by_post_type')){
	function wd_vc_get_data_by_post_type($post_type = 'post', $args = array()){
		$args_default = array(
			'post_type'			=> $post_type,
			'post_status'		=> 'publish',
			'posts_per_page' 	=> -1,
		);
		$args = wp_parse_args( $args, $args_default );
		$data_array = array();
		global $post;
		$data = new WP_Query($args);
		if( $data->have_posts() ){
			while( $data->have_posts() ){
				$data->the_post();
				$data_array[] = array(
					'label' => html_entity_decode( $post->post_title, ENT_QUOTES, 'UTF-8' ).' ('.$post->ID.')',
					'value' => $post->ID,	
				);
			}
		}else{
			$data_array[] = array(
				'label' => sprintf(__( "Please add data for \"%s\" before", 'wd_package' ), $post_type),
				'value' => '',	
			);
		}
		wp_reset_postdata();
		return $data_array;
	}
} 

// Get List boolean
if(!function_exists ('wd_vc_get_list_tvgiao_boolean')){
	function wd_vc_get_list_tvgiao_boolean(){
		return array(
			esc_html__('Yes', 'wd_package' )	=> '1', 
			esc_html__('No' , 'wd_package' )	=> '0'
		);
	}
}

// Get List Columns padding
if(!function_exists ('wd_vc_get_list_columns_padding')){
	function wd_vc_get_list_columns_padding(){
		return array(
			__( 'Normal', 'wd_package' ) 	=> 'normal',
			__( 'None', 'wd_package' ) 		=> 'none',
			__( 'Mini', 'wd_package' ) 		=> 'mini',
			__( 'Small', 'wd_package' ) 	=> 'small',
			__( 'Large', 'wd_package' ) 	=> 'large',
		);
	}
}

// Get List Columns
if(!function_exists ('wd_vc_get_list_tvgiao_columns')){
	function wd_vc_get_list_tvgiao_columns(){
		return array(
			__( '1 Columns', 'wd_package' )		=> '1',
			__( '2 Columns', 'wd_package' )		=> '2',
			__( '3 Columns', 'wd_package' )		=> '3',
			__( '4 Columns', 'wd_package' )		=> '4',
			__( '5 Columns', 'wd_package' )		=> '5',
			__( '6 Columns', 'wd_package' )		=> '6',
			__( '8 Columns', 'wd_package' )		=> '8',
			__( '12 Columns', 'wd_package' )	=> '12',
		);
	}
}

// Get List Columns on tablet
if(!function_exists ('wd_vc_get_list_columns_tablet')){
	function wd_vc_get_list_columns_tablet(){
		return array(
			__( '1 Columns', 'wd_package' )		=> '1',
			__( '2 Columns', 'wd_package' )		=> '2',
			__( '3 Columns', 'wd_package' )		=> '3',
		);
	}
}

// Get List Columns on mobile
if(!function_exists ('wd_vc_get_list_columns_mobile')){
	function wd_vc_get_list_columns_mobile(){
		return array(
			__( '1 Columns', 'wd_package' )		=> '1',
			__( '2 Columns', 'wd_package' )		=> '2',
		);
	}
}

// Get List Heading HTML Tag
if(!function_exists ('wd_vc_get_list_heading_tag')){
	function wd_vc_get_list_heading_tag(){
		return array(
			esc_html__('H1', 'wd_package' ) => 'h1',
			esc_html__('H2', 'wd_package' ) => 'h2',
			esc_html__('H3', 'wd_package' ) => 'h3',
			esc_html__('H4', 'wd_package' ) => 'h4',
			esc_html__('H5', 'wd_package' ) => 'h5',
			esc_html__('H6', 'wd_package' ) => 'h6',
		);
	}
}

// Get List Content Align Flexbox
if(!function_exists ('wd_vc_get_list_flex_align_class')){
	function wd_vc_get_list_flex_align_class(){
		return array(
			__( 'Left ', 'wd_package' ) 	=> 'wd-flex-justify-left',
			__( 'Center', 'wd_package' )	=> 'wd-flex-justify-center',
			__( 'Right', 'wd_package' ) 	=> 'wd-flex-justify-right',
		);
	}
}

// Get List Text Align Bootstrap
if(!function_exists ('wd_vc_get_list_text_align_bootstrap')){
	function wd_vc_get_list_text_align_bootstrap(){
		return array(
			__( 'Initial', 'wd_package' ) 			=> '',
			__( 'Text Left', 'wd_package' ) 		=> 'text-left'	,
			__( 'Text Center', 'wd_package' ) 		=> 'text-center',
			__( 'Text Right', 'wd_package' ) 		=> 'text-right',
			__( 'Text Justified', 'wd_package' ) 	=> 'text-justify',
			__( 'Text No Wrap', 'wd_package' ) 		=> 'text-nowrap',
		);
	}
}

// Get List name of special product
if(!function_exists ('wd_vc_get_list_special_product_name')){
	function wd_vc_get_list_special_product_name(){
		return array(
			__( 'Recent Product', 'wd_package' )		=> 'recent_product',
			__( 'Most View Product', 'wd_package' )		=> 'mostview_product',
			__( 'On Sale Product', 'wd_package' )		=> 'onsale_product',
			__( 'Featured Product', 'wd_package' )		=> 'featured_product'
		);
	}
}

// Get List name of special blog
if(!function_exists ('wd_vc_get_list_special_blog_name')){
	function wd_vc_get_list_special_blog_name(){
		return array(
			__( 'Recent Blog', 'wd_package' )		=> 'recent_blog',
			__( 'Most View Blog', 'wd_package' )	=> 'mostview_blog',
			__( 'Most Comment', 'wd_package' )		=> 'comment_blog',
		);
	}
}

// Get List payment icon (awesome font)
if(!function_exists ('wd_vc_get_list_payment_icon')){
	function wd_vc_get_list_payment_icon(){
		return array(
			array( 'fa-cc-amex', 'fa-cc-amex' ),
			array( 'fa-cc-diners-club', 'fa-cc-diners-club' ),
			array( 'fa-cc-discover', 'fa-cc-discover' ),
			array( 'fa-cc-jcb', 'fa-cc-jcb' ),
			array( 'fa-cc-mastercard', 'fa-cc-mastercard' ),
			array( 'fa-cc-paypal', 'fa-cc-paypal' ),
			array( 'fa-cc-stripe', 'fa-cc-stripe' ),
			array( 'fa-cc-visa', 'fa-cc-visa' ),
			array( 'fa-credit-card', 'fa-credit-card' ),
			array( 'fa-credit-card-alt', 'fa-credit-card-alt' ),
			array( 'fa-google-wallet', 'fa-google-wallet' ),
			array( 'fa-paypal', 'fa-paypal' ),
		);
	}
}

// Get List font awesome size
if(!function_exists ('wd_vc_get_list_awesome_font_size')){
	function wd_vc_get_list_awesome_font_size(){
		return array(
			'1x'	=> 'fa-1x',
			'2x'	=> 'fa-2x',
			'3x'	=> 'fa-3x',
			'4x'	=> 'fa-4x',
			'5x'	=> 'fa-5x',
			'6x'	=> 'fa-6x',
		);
	}
}

if ( ! function_exists( 'wd_vc_get_order_by_values' ) ) {
	function wd_vc_get_order_by_values($type = '') {
		if ($type == 'product') {
			$order_by = array(
		        __( 'Date', 'wd_package' ) 		=> 'date',
		        __( 'Title', 'wd_package' ) 	=> 'title',
		        __( 'Rand', 'wd_package' ) 		=> 'rand',
		        __( 'Price', 'wd_package' ) 	=> 'price',
		        __( 'Sales', 'wd_package' ) 	=> 'sales',
			);
		}elseif ($type == 'term') {
			$order_by = array(
		        __( 'Name', 'wd_package' ) 			=> 'name',
				__( 'Count', 'wd_package' ) 		=> 'count',
				__( 'Slug', 'wd_package' ) 			=> 'slug',
				__( 'Term Group', 'wd_package' ) 	=> 'term_group',
				__( 'Term Order', 'wd_package' ) 	=> 'term_order',
				__( 'Term ID', 'wd_package' ) 		=> 'term_id',
			);
		}else{
			$order_by = array(
		        __( 'Date', 'wd_package' ) 	=> 'date',
		        __( 'Title', 'wd_package' ) => 'title',
		        __( 'Rand', 'wd_package' ) 	=> 'rand',
			);
		}
		return $order_by;
	}
}

if ( ! function_exists( 'wd_vc_get_sort_by_values' ) ) {
	function wd_vc_get_sort_by_values() {
		return array(
            __( 'DESC', 'wd_package' ) 	=> 'DESC',
			__( 'ASC', 'wd_package' ) 	=> 'ASC',
		);
	}
}

if ( ! function_exists( 'wd_vc_get_list_blog_special_layout' ) ) {
	function wd_vc_get_list_blog_special_layout() {
		return  array(
			array(
				0 => 'title',
		      	1 =>  __( 'Title', 'wd_package' ),
			),
			array(
				0 => 'meta',
		      	1 =>  __( 'Meta', 'wd_package' ),
			),
			array(
				0 => 'excerpt',
		      	1 =>  __( 'Excerpt', 'wd_package' ),
			),
			array(
				0 => 'readmore',
		      	1 =>  __( 'Readmore', 'wd_package' ),
			),
		);
	}
}

if ( ! function_exists( 'wd_vc_get_list_style_class' ) ) {
	function wd_vc_get_list_style_class($number = 5, $pre_text = 'style-') {
		$style_class = array();
		for ($i=1; $i <= $number ; $i++) { 
			$style_class[sprintf(__( 'Style %d', 'wd_package' ), $i)] = $pre_text.$i;
		}
		return $style_class;
	}
}

// Get List woocomemrce image size
if(!function_exists ('wd_vc_get_list_woocommerce_image_size')){
	function wd_vc_get_list_woocommerce_image_size($fullsize = true){
		if ($fullsize == true) {
			$list_woocommerce_image_size = array(
				__( 'Fullsize', 'wd_package' )					=> 'full',
				__( 'Shop Catalog', 'wd_package' )				=> 'shop_catalog',
				__( 'Shop Single (Big)', 'wd_package' ) 		=> 'shop_single',
				__( 'Shop Thumbnail (Small)', 'wd_package' )	=> 'shop_thumbnail',
			);
		} else {
			$list_woocommerce_image_size = array(
				__( 'Shop Catalog', 'wd_package' )				=> 'shop_catalog',
				__( 'Shop Single (Big)', 'wd_package' ) 		=> 'shop_single',
				__( 'Shop Thumbnail (Small)', 'wd_package' )	=> 'shop_thumbnail',
			);
		}
		return $list_woocommerce_image_size;
	}
}

// Get link target
if(!function_exists ('wd_vc_get_list_link_target')){
	function wd_vc_get_list_link_target(){
		return array(
			__( 'New window', 'wd_package' ) 		=> '_blank',
 			__( 'Current window', 'wd_package' ) 	=> '_self',	
 			__( 'Parent', 'wd_package' ) 			=> '_parent',
		);
	}
}

// Get List Slider Type (Slick or Owl)
if(!function_exists ('wd_vc_get_list_slider_type')){
	function wd_vc_get_list_slider_type(){
		return array(
			__( 'Owl Carousel', 'wd_package' ) 	=> 'owl',
			__( 'Slick', 'wd_package' )			=> 'slick'
		);
	}
}

// Get List Image Size
if(!function_exists ('wd_vc_get_list_image_size')){
	function wd_vc_get_list_image_size($fullsize = true){
		global $_wp_additional_image_sizes;
		$image_size = array();
		if ($fullsize) {
			$image_size['Full'] = 'full';
		}
		if (!empty($_wp_additional_image_sizes)) {
			foreach ($_wp_additional_image_sizes as $key => $value) {
				$image_size[$key.' - '.$value['width'].'x'.$value['height']] = $key;
			}
		}
		return $image_size;
	}
}

// Get List of menu theme location (registed)
if(!function_exists ('wd_vc_get_list_menu_registed')){
	function wd_vc_get_list_menu_registed(){
		$menu_registed = get_registered_nav_menus();
		$list_menu = array();
		if ($menu_registed) {
			foreach ($menu_registed as $menu => $value ) {
				$list_menu[] = array(
					'label' => html_entity_decode( $value, ENT_QUOTES, 'UTF-8' ).' ('.$menu.')',
					'value' => $menu,	
				);
			}
		}
		wp_reset_postdata();
		return $list_menu;
	}
}


// Get List terms of taxonomy
if(!function_exists ('wd_vc_get_list_category')){
	function wd_vc_get_list_category($taxonomy = 'product_cat', $all_category = true, $type = 'autocomplete'){
		/* type : 
		 * Default : autocomplete => return array with label & value 
		 * sorted_list  => return special array use for sorted_list type
		 */
		$list_categories = array();
		if ($all_category) {
			if ($type == 'sorted_list') {
				$list_categories[] = array( -1, esc_html__('All Category','wd_package') );
			}else{
				$list_categories[esc_html__('All Category','wd_package')] = -1;
			}
		}

		$args = array(
			'taxonomy' => $taxonomy,
			'hide_empty' 	=> 0
		);

		$condition = ($taxonomy == 'product_cat' && !class_exists('WooCommerce')) ? false : true;

		if ($condition) {
			$categories = get_terms($args);
			if (!is_wp_error($categories) && count($categories) > 0) {
				foreach ($categories as $category ) {
					$name       = html_entity_decode( $category->name, ENT_QUOTES, 'UTF-8' ).' (' . $category->count . ' items)';
					$term_id    = $category->term_id;
					if ($type == 'sorted_list') {
						$list_categories[] = array( $term_id, $name );
					}else{
						$list_categories[] = array(
							'label' => $name,
							'value' => $term_id,	
						);	
					}
				}
			}
		}
		wp_reset_postdata();
		return $list_categories;
	}
}

// Get List pages
if(!function_exists ('wd_vc_get_list_pages')){
	function wd_vc_get_list_pages($type = 'sorted_list'){
		/* type : 
		 * Default : sorted_list  => return special array use for sorted_list type
		 */
		$args = array(
			'sort_column' => 'post_title',
			'post_type' => 'page',
			'post_status' => 'publish'
		); 
		$pages = get_pages($args);
		$list_pages = array();
		if (!is_wp_error($pages) && count($pages) > 0) {
			foreach ($pages as $page ) {
				$name       = html_entity_decode( $page->post_title, ENT_QUOTES, 'UTF-8' ).' ( ID: ' . $page->ID . ' )';
				$page_id    = $page->ID;
				if ($type == 'sorted_list') {
					$list_pages[] = array( $page_id, $name );
				}else{
					$list_pages[] = array(
						'label' => $name,
						'value' => $page_id,	
					);	
				}
			}
		}
		
		wp_reset_postdata();
		return $list_pages;
	}
}

if ( ! function_exists( 'wd_vc_get_product_categories_full' ) ) {
	function wd_vc_get_product_categories_full( $all_category = true, $type = "" ) {
		/* type : 
		 * Default : '' => return normal array
		 * autocomplete => return array with label & value 
		 * sorted_list  => return special array use for sorted_list type
		 */
		$args = array(
			'type'         => 'post',
			'child_of'     => 0,
			'parent'       => '',
			'orderby'      => 'id',
			'order'        => 'ASC',
			'hide_empty'   => false,
			'hierarchical' => 1,
			'exclude'      => '',
			'include'      => '',
			'number'       => '',
			'taxonomy'     => 'product_cat',
			'pad_counts'   => false,
		);

		$categories = get_categories( $args );

		$product_categories_dropdown = array();

		if ( $all_category ) {
			if ($type == 'autocomplete') {
				$product_categories_dropdown[] = array(
					'label' => __( 'All Category', 'wd_package' ),
					'value' => -1,
				);
			}elseif ($type == 'sorted_list') {
				$product_categories_dropdown[] = array( -1, __( 'All Category', 'wd_package' ) );
			}else{
				$product_categories_dropdown[ __( 'All Category', 'wd_package' ) ] = - 1;
			}
		}

		wd_vc_get_category_childs_full( 0, 0, $categories, 0, $product_categories_dropdown, $type );

		return $product_categories_dropdown;
	}
}

if ( ! function_exists( 'wd_vc_get_category_childs_full' ) ) {
	function wd_vc_get_category_childs_full( $parent_id, $pos, $array, $level, &$dropdown, $type = "" ) {
		for ( $i = $pos; $i < count( $array ); $i ++ ) {
			if ( $array[ $i ]->category_parent == $parent_id ) {
				$term_id    = $array[ $i ]->term_id;
				$name       = str_repeat( '- ', $level ) . $array[ $i ]->name;
				$name 		= html_entity_decode( $name, ENT_QUOTES, 'UTF-8' ).' [ ID: '.$term_id.' ]';
				if ($type == 'autocomplete') {
					$dropdown[] = array(
						'label' => $name,
						'value' => $term_id,	
					);
				}elseif ($type == 'sorted_list') {
					$dropdown[] = array( $term_id, $name );
				}else{
					$dropdown[$name] = $term_id;
				}
				wd_vc_get_category_childs_full( $array[ $i ]->term_id, $i, $array, $level + 1, $dropdown );
			}
		}
	}
} 