<?php
// Check Woo
if( !function_exists('wd_is_woocommerce') ){
	function wd_is_woocommerce(){
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		return ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) ? false : true;
	} 
}

if( !function_exists('wd_is_visual_composer') ){
	function wd_is_visual_composer(){
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		return ( !in_array( "js_composer/js_composer.php", $_actived ) ) ? false : true;
	} 
} 

// Check wishlist plugin
if( !function_exists('wd_is_wishlist_active') ){
	function wd_is_wishlist_active(){
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		return ( !in_array( "yith-woocommerce-wishlist/init.php", $_actived ) ) ? false : true;
	} 
}

// Custom Encode
// use : $encoded = wd_encode("help me vanish" , "ticket_to_haven");
if( !function_exists('wd_encode') ){
	function wd_encode($string,$key) {
	    $key = sha1($key);
	    $strLen = strlen($string);
	    $keyLen = strlen($key);
	    for ($i = 0; $i < $strLen; $i++) {
	        $ordStr = ord(substr($string,$i,1));
	        if ($j == $keyLen) { $j = 0; }
	        $ordKey = ord(substr($key,$j,1));
	        $j++;
	        $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
	    }
	    return $hash;
	}
}

// Custom Decode
// use : $decoded = wd_decode($encoded, "ticket_to_haven");
if( !function_exists('wd_decode') ){
	function wd_decode($string,$key) {
	    $key = sha1($key);
	    $strLen = strlen($string);
	    $keyLen = strlen($key);
	    for ($i = 0; $i < $strLen; $i+=2) {
	        $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
	        if ($j == $keyLen) { $j = 0; }
	        $ordKey = ord(substr($key,$j,1));
	        $j++;
	        $hash .= chr($ordStr - $ordKey);
	    }
	    return $hash;
	}
}

if( !function_exists('wd_url_encode') ){
	function wd_url_encode($string){
        return urlencode(utf8_encode($string));
    }
}

if( !function_exists('wd_url_decode') ){ 
    function wd_url_decode($string){
        return utf8_decode(urldecode($string));
    }
}

// Get Data Choose normal for widget
if(!function_exists ('wd_get_data')){
	function wd_get_data($array_data){
		global $post;
		$data_array = array();
		$data = new WP_Query($array_data);
		if( $data->have_posts() ){
			while( $data->have_posts() ){
				$data->the_post();
				$data_array[$post->ID] = $post->post_title;
			}
		}
		wp_reset_postdata();
		return $data_array;
	}
}

// Get Data Choose for visual composer
if(!function_exists ('wd_get_data_by_post_type')){
	function wd_get_data_by_post_type($post_type = 'post', $args = array()){
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
				$data_array[$post->ID] = html_entity_decode( $post->post_title, ENT_QUOTES, 'UTF-8' ).' ('.$post->ID.')';
			}
		}else{
			$data_array[] = sprintf(__( "Please add data for \"%s\" before", 'wd_package' ), $post_type);
		}
		wp_reset_postdata();
		return $data_array;
	}
} 

// Get list sidebar
if(!function_exists ('wd_get_list_sidebar_choices')){
	function wd_get_list_sidebar_choices($value_default = '') {
		global $wp_registered_sidebars;
  		$arr_sidebar = ($value_default != '') ? array('0' => $value_default) : array();
  		if (count($wp_registered_sidebars) > 0) {
  			foreach ( $wp_registered_sidebars as $sidebar ){
	  			$arr_sidebar[$sidebar['id']] = $sidebar['name'];
	  		}
  		}
  		return $arr_sidebar;
	}
}

// Get List Yes/No
if(!function_exists ('wd_get_list_tvgiao_boolean')){
	function wd_get_list_tvgiao_boolean(){
		return array(
			'1'	=> esc_html__('Yes','wd_package'),
			'0'	=> esc_html__('No','wd_package'),
		);
	}
}

// Get List Columns padding
if(!function_exists ('wd_get_list_columns_padding')){
	function wd_get_list_columns_padding(){
		return array(
			'normal' 	=> __( 'Normal', 'wd_package' ),
			'none' 		=> __( 'None', 'wd_package' ),
			'mini' 		=> __( 'Mini', 'wd_package' ),
			'small' 	=> __( 'Small', 'wd_package' ),
			'large' 	=> __( 'Large', 'wd_package' ),
		);
	}
}

// Get List Columns
if(!function_exists ('wd_get_list_tvgiao_columns')){
	function wd_get_list_tvgiao_columns(){
		return array(
			'1' => __( '1 Columns', 'wd_package' ),
			'2' => __( '2 Columns', 'wd_package' ),
			'3' => __( '3 Columns', 'wd_package' ),
			'4' => __( '4 Columns', 'wd_package' ),
			'5' => __( '5 Columns', 'wd_package' ),
			'6' => __( '6 Columns', 'wd_package' ),
			'8' => __( '8 Columns', 'wd_package' ),
			'12' => __( '12 Columns', 'wd_package' ),
		);
	}
}

// Get List Columns on tablet
if(!function_exists ('wd_get_list_columns_tablet')){
	function wd_get_list_columns_tablet(){
		return array(
			'1' => __( '1 Columns', 'wd_package' ),
			'2' => __( '2 Columns', 'wd_package' ),
			'3' => __( '3 Columns', 'wd_package' ),
		);
	}
}

// Get List Columns on mobile
if(!function_exists ('wd_get_list_columns_mobile')){
	function wd_get_list_columns_mobile(){
		return array(
			'1' => __( '1 Columns', 'wd_package' ),
			'2' => __( '2 Columns', 'wd_package' ),
		);
	}
}

// Get List Heading HTML Tag
if(!function_exists ('wd_get_list_heading_tag')){
	function wd_get_list_heading_tag(){
		return array(
			'h1' => esc_html__('H1', 'wd_package' ),
			'h2' => esc_html__('H2', 'wd_package' ),
			'h3' => esc_html__('H3', 'wd_package' ),
			'h4' => esc_html__('H4', 'wd_package' ),
			'h5' => esc_html__('H5', 'wd_package' ),
			'h6' => esc_html__('H6', 'wd_package' ),
		);
	}
}

// Get List Content Align Flexbox
if(!function_exists ('wd_get_list_flex_align_class')){
	function wd_get_list_flex_align_class(){
		return array(
			'wd-flex-justify-left'		=> __( 'Left ', 'wd_package' ),
			'wd-flex-justify-center'	=> __( 'Center', 'wd_package' ),
			'wd-flex-justify-right'	=> __( 'Right', 'wd_package' ),
		);
	}
}

// Get List Text Align Bootstrap
if(!function_exists ('wd_get_list_text_align_bootstrap')){
	function wd_get_list_text_align_bootstrap(){
		return array(
			''				=> __( 'Initial', 'wd_package' ),
			'text-left'		=> __( 'Text Left', 'wd_package' ),
			'text-center'	=> __( 'Text Center', 'wd_package' ),
			'text-right'	=> __( 'Text Right', 'wd_package' ),
			'text-justify'	=> __( 'Text Justified', 'wd_package' ),
			'text-nowrap'	=> __( 'Text No Wrap', 'wd_package' ),
		);
	}
}

// Get List name of special product
if(!function_exists ('wd_get_list_special_product_name')){
	function wd_get_list_special_product_name(){
		return array(
			'recent_product' 	=> __( 'Recent Product', 'wd_package' ),
			'mostview_product' 	=> __( 'Most View Product', 'wd_package' ),
			'onsale_product' 	=> __( 'On Sale Product', 'wd_package' ),
			'featured_product' 	=> __( 'Featured Product', 'wd_package' ),
		);
	}
}

// Get List name of special blog
if(!function_exists ('wd_get_list_special_blog_name')){
	function wd_get_list_special_blog_name(){
		return array(
			'recent_blog' 	=> __( 'Recent Blog', 'wd_package' ),
			'mostview_blog' => __( 'Most View Blog', 'wd_package' ),
			'comment_blog' 	=> __( 'Most Comment', 'wd_package' ),
		);
	}
}

// Get List font awesome size
if(!function_exists ('wd_get_list_awesome_font_size')){
	function wd_get_list_awesome_font_size(){
		return array(
			'fa-1x' => '1x',
			'fa-2x' => '2x',
			'fa-3x' => '3x',
			'fa-4x' => '4x',
			'fa-5x' => '5x',
			'fa-6x' => '6x',
		);
	}
}

if ( ! function_exists( 'wd_get_order_by_values' ) ) {
	function wd_get_order_by_values($type = '') {
		if ($type == 'product') {
			$order_by = array(
		         'date' 		=> __( 'Date', 'wd_package' ),
		         'title' 		=> __( 'Title', 'wd_package' ),
		         'rand' 		=> __( 'Rand', 'wd_package' ),
		         'price' 		=> __( 'Price', 'wd_package' ),
		         'sales' 		=> __( 'Sales', 'wd_package' ),
			);
		}elseif ($type == 'term') {
			$order_by = array(
		         'name' 		=> __( 'Name', 'wd_package' ),
				 'count' 		=> __( 'Count', 'wd_package' ),
				 'slug' 		=> __( 'Slug', 'wd_package' ),
				 'term_group' 	=> __( 'Term Group', 'wd_package' ),
				 'term_order' 	=> __( 'Term Order', 'wd_package' ),
				 'term_id' 		=> __( 'Term ID', 'wd_package' ),
			);
		}else{
			$order_by = array(
		         'date' 		=> __( 'Date', 'wd_package' ) ,
		         'title' 		=> __( 'Title', 'wd_package' ),
		         'rand' 		=> __( 'Rand', 'wd_package' ) ,
			);
		}
		return $order_by;
	}
}

if ( ! function_exists( 'wd_get_sort_by_values' ) ) {
	function wd_get_sort_by_values() {
		return array(
             'DESC' 	=> __( 'DESC', 'wd_package' ),
			 'ASC' 		=> __( 'ASC', 'wd_package' ),
		);
	}
}


if ( ! function_exists( 'wd_get_list_style_class' ) ) {
	function wd_get_list_style_class($number = 5, $pre_text = 'style-') {
		$style_class = array();
		for ($i=1; $i <= $number ; $i++) { 
			$style_class[$pre_text.$i] = sprintf(__( 'Style %d', 'wd_package' ), $i);
		}
		return $style_class;
	}
}

// Get List woocomemrce image size
if(!function_exists ('wd_get_list_woocommerce_image_size')){
	function wd_get_list_woocommerce_image_size($fullsize = true){
		if ($fullsize == true) {
			$list_woocommerce_image_size = array(
				 'full' 			=> __( 'Fullsize', 'wd_package' ),
				 'shop_catalog' 	=> __( 'Shop Catalog', 'wd_package' ),
				 'shop_single' 		=> __( 'Shop Single (Big)', 'wd_package' ),
				 'shop_thumbnail' 	=> __( 'Shop Thumbnail (Small)', 'wd_package' ),
			);
		} else {
			$list_woocommerce_image_size = array(
				 'shop_catalog' 	=> __( 'Shop Catalog', 'wd_package' ),
				 'shop_single' 		=> __( 'Shop Single (Big)', 'wd_package' ),
				 'shop_thumbnail' 	=> __( 'Shop Thumbnail (Small)', 'wd_package' ),
			);
		}
		return $list_woocommerce_image_size;
	}
}

// Get link target
if(!function_exists ('wd_get_list_link_target')){
	function wd_get_list_link_target(){
		return array(
			'_blank' 	=> __( 'New window', 'wd_package' ),
			'_self' 	=> __( 'Current window', 'wd_package' ), 	
			'_parent' 	=> __( 'Parent', 'wd_package' ),
		);
	}
}

// Get List Slider Type (Slick or Owl)
if(!function_exists ('wd_get_list_slider_type')){
	function wd_get_list_slider_type(){
		return array(
			 'owl' 		=> __( 'Owl Carousel', 'wd_package' ),
			 'slick' 	=> __( 'Slick', 'wd_package' ),
		);
	}
}

// Get List Image Size
if(!function_exists ('wd_get_list_image_size')){
	function wd_get_list_image_size($fullsize = true){
		global $_wp_additional_image_sizes;
		$image_size = array();
		if ($fullsize) {
			$image_size['full'] = 'Full';
		}
		if (!empty($_wp_additional_image_sizes)) {
			foreach ($_wp_additional_image_sizes as $key => $value) {
				$image_size[$key] = $key.' - '.$value['width'].'x'.$value['height'];
			}
		}
		return $image_size;
	}
}

// Get List of menu theme location (registed)
if(!function_exists ('wd_get_list_menu_registed')){
	function wd_get_list_menu_registed(){
		$menu_registed 	= get_registered_nav_menus();
		$list_menu 		= array();
		if ($menu_registed) {
			foreach ($menu_registed as $menu => $value ) {
				$list_menu[$menu] = html_entity_decode( $value, ENT_QUOTES, 'UTF-8' ).' ('.$menu.')';
			}
		}
		wp_reset_postdata();
		return $list_menu;
	}
}


if ( ! function_exists( 'wd_get_category_name_by_ids' ) ) {
	function wd_get_category_name_by_ids( $ids = array() ) {
		$output = array();
		foreach ( $ids as $id ) {
			if ( $term = get_term_by( 'id', $id, 'product_cat' ) ) {
				$output[] = array(
					'id'   => $term->term_id,
					'name' => $term->name,
					'slug' => str_replace( '_', '-', $term->slug ) . '-' . $term->term_id . '-' . rand(),
				);
			}
		}
		return $output;
	}
}


if ( ! function_exists( 'wd_get_categories_by_category_parent' ) ) {
	function wd_get_categories_by_category_parent( $id_category_parent ) {
		$args     = array(
			'hierarchical'     => 1,
			'show_option_none' => '',
			'hide_empty'       => 0,
			'parent'           => $id_category_parent,
			'taxonomy'         => 'product_cat',
		);
		$sub_cats = get_categories( $args );

		return $sub_cats;
	}
}

if ( ! function_exists( 'wd_get_subcategory' ) ) {
	function wd_get_subcategory( $id ) {
		$sub_categories = wd_get_categories_by_category_parent( $id );
		$output         = array();
		foreach ( $sub_categories as $category ) {
			$output[] = array(
				'id'   => $category->term_id,
				'name' => html_entity_decode( $category->name, ENT_QUOTES, 'UTF-8' ),
				'slug' => str_replace( '_', '-', $category->slug ) . '-' . $category->term_id . '-' . rand(),
			);
		}

		return $output;
	}
}

// Get Data Choose for visual composer
if(!function_exists ('wd_get_sub_categories_accordion')){
	function wd_get_sub_categories_accordion($category_id, $instance, $current_cat, $level){
		$args = array(
		   'taxonomy'     => 'product_cat',
		   'child_of'     => 0,
		   'parent'       => $category_id,
		   'orderby'      => $instance['order_by'],
		   'order'        => $instance['sort'],
		   'hierarchical' => 0,
		   'title_li'     => '',
		   'hide_empty'   => 0
		);
		$sub_cats = get_categories( $args );
		if($sub_cats) {
			if($instance['order_by'] == 'rand'){
				shuffle($sub_cats);
			}
			echo '<ul class="sub_cat">';
			foreach($sub_cats as $sub_category) {
				$current_class = ($current_cat == $sub_category->slug)?'current':'';
				echo '<li>';
				echo '<a href="'. get_term_link($sub_category, 'product_cat') .'" class="'.$current_class.'">';
				echo str_repeat('&#151;', $level).' '. $sub_category->name;
				if( $instance['show_product_count'] ){
					echo ' (' . $sub_category->count . ')';
				}
				echo "</a>";
				wd_get_sub_categories_accordion($sub_category->term_id, $instance, $current_cat, $level+1);
				echo '</li>';
			}
			echo '</ul>';

		}
	}
}

// Get List terms of taxonomy
if(!function_exists ('wd_get_list_category')){
	function wd_get_list_category($taxonomy = 'product_cat', $all_category = true){
		$list_categories = array();
		if ($all_category) {
			$list_categories[-1] = esc_html__('All Category','wd_package');
		}

		$args = array(
			'taxonomy' => $taxonomy,
			'hide_empty' 	=> 0,
		);

		$condition = ($taxonomy == 'product_cat' && !class_exists('WooCommerce')) ? false : true;
		if ($condition) {
			$categories = get_terms($args);
			if (!is_wp_error($categories) && is_array($categories) && count($categories) > 0) {
				foreach ($categories as $category ) {
					$list_categories[$category->term_id] = html_entity_decode( $category->name, ENT_QUOTES, 'UTF-8' ).' (' . $category->count . ' items)';
				}
			}
		}

		wp_reset_postdata();
		return $list_categories;
	}
}

// Get List pages
if(!function_exists ('wd_get_list_pages')){
	function wd_get_list_pages(){
		$args = array(
			'sort_column' => 'post_title',
			'post_type' => 'page',
			'post_status' => 'publish'
		); 
		$pages = get_pages($args);
		$list_pages = array();
		if (!is_wp_error($pages) && count($pages) > 0) {
			foreach ($pages as $page ) {
				$list_pages[$page->ID] = html_entity_decode( $page->post_title, ENT_QUOTES, 'UTF-8' ).' ( ID: ' . $page->ID . ' )';
			}
		}
		
		wp_reset_postdata();
		return $list_pages;
	}
}

if ( ! function_exists( 'wd_get_product_categories_full' ) ) {
	function wd_get_product_categories_full( $all_category = true ) {
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
			$product_categories_dropdown[-1] = __( 'All Category', 'wd_package' );
		}

		wd_get_category_childs_full( 0, 0, $categories, 0, $product_categories_dropdown);

		return $product_categories_dropdown;
	}
}

if ( ! function_exists( 'wd_get_category_childs_full' ) ) {
	function wd_get_category_childs_full( $parent_id, $pos, $array, $level, &$dropdown) {
		for ( $i = $pos; $i < count( $array ); $i ++ ) {
			if ( $array[ $i ]->category_parent == $parent_id ) {
				$term_id    = $array[ $i ]->term_id;
				$name       = str_repeat( '- ', $level ) . $array[ $i ]->name;
				$name 		= html_entity_decode( $name, ENT_QUOTES, 'UTF-8' ).' [ ID: '.$term_id.' ]';
				
				$dropdown[$term_id] = $name;
				wd_get_category_childs_full( $array[ $i ]->term_id, $i, $array, $level + 1, $dropdown );
			}
		}
	}
} 

if ( ! function_exists( 'wd_product_result_count' ) ) {
	function wd_product_result_count($query = '') {
		global $wp_query; ?>
		<p class="woocommerce-result-count">
			<?php
			$my_query = ($query == '') ? $wp_query : $query;
			$paged    = max( 1, $my_query->get( 'paged' ) );
			$per_page = $my_query->get( 'posts_per_page' );
			$total    = $my_query->found_posts;
			$first    = ( $per_page * $paged ) - $per_page + 1;
			$last     = min( $total, $my_query->get( 'posts_per_page' ) * $paged );

			if ( $total <= $per_page || -1 === $per_page ) {
				/* translators: %d: total results */
				printf( _n( 'Showing the single result', 'Showing all %d results', $total, 'wd_package' ), $total );
			} else {
				/* translators: 1: first result 2: last result 3: total results */
				printf( _nx( 'Showing the single result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'wd_package' ), $first, $last, $total );
			}
			?>
		</p>
	<?php
	}
} 

if ( ! function_exists( 'wd_get_slider_control' ) ) {
	function wd_get_slider_control() {
		ob_start(); ?>
			<div class="slider_control">
		          <a href="#!" class="prev" title="<?php echo esc_attr__( 'Previous', 'wd_package' ); ?>" data-toggle="tooltip" data-placement="top"><i class="lnr lnr-chevron-left" aria-hidden="true"></i></a>
		          <a href="#!" class="next" title="<?php echo esc_attr__( 'Next', 'wd_package' ); ?>" data-toggle="tooltip" data-placement="top"><i class="lnr lnr-chevron-right" aria-hidden="true"></i></a>
		    </div><!-- .slider-control -->
      	<?php 
      	return ob_get_clean();
	}
}

if ( ! function_exists( 'wd_slider_control' ) ) {
	function wd_slider_control() {
		echo wd_get_slider_control();
	}
}

/* var: $content = array(
 *			0 => array(
 *				'title' 	=> ...,
 *				'content' 	=> ...,
 *			),
 *			1 => array(
 *				'title' 	=> ...,
 *				'content' 	=> ...,
 *			)
 *		)
 *
 */
if ( ! function_exists( 'wd_tab_bootstrap' ) ) {
	function wd_tab_bootstrap($content = array()) {
		$tab_content 	= '';
		$random_num 	= mt_rand();
		if (count($content) > 0 && is_array($content)) {
			ob_start(); ?>
			<ul class="nav nav-tabs">
				<?php $i = 0;
					foreach ($content as $key => $value) {
						$active = ($i == 0) ? 'active' : '';
						echo '<li class="'.$active.'"><a data-toggle="tab" href="#wd-tab-'.$i.'-'.$random_num.'">'.$value['title'].'</a></li>';
						$i ++;
					}
				?>
			</ul>
			<div class="tab-content">
				<?php $i = 0;
					foreach ($content as $key => $value) {
						$active = ($i == 0) ? 'active' : '';
						echo '<div id="wd-tab-'.$i.'-'.$random_num.'" class="tab-pane fade in '.$active.'"><p>'.do_shortcode( $value['content'] ).'</p></div>';
						$i ++;
					}
				?>
			</div>
			<?php
			$tab_content = ob_get_clean();
		}
		return $tab_content;
	}
}

//Get Twitter feed.
//Variable : $screen_name is your twitter username 
if ( ! function_exists( 'wd_get_twitter_feed' ) ) {
	function wd_get_twitter_feed($screen_name, $args = array()) {
		$screen_name 		= str_replace( '@', '', $screen_name );
		$options = array(
			'consumer_key'          => 'QHp9xWA3AItW4o0Du3TOY95nP',
            'consumer_secret'       => 'KwJqH4keg2tbNacXp07w1djtrjZ73fDuZVwhxTrWToz28DcfQC',
            'access_token'          => '1223837155-6ZH4h4bg5Wa6A0uNsHCKddfMeZsG5LFS5648ISU',
            'access_token_secret'   => 'uBy1sTCmRje6h80rMOUu0KZ2LRTZFuv9ZlDy8vgGLRyQz',
            'twitter_screen_name'   => $screen_name,
            'tweets_to_retrieve'    => 5, // Number of tweets to display
		);
		$options = wp_parse_args( $args, $options );
		
		$wd_twitter_feed = array();
		$twitter_data  = new TweetPHP($options);
		$twitter_data  = $twitter_data->get_tweet_array();

		$twitter_data  = is_array($twitter_data) || (is_object($twitter_data) && $twitter_data->response['response']) ? $twitter_data : new WP_Error( 'error_data', esc_html__( 'No network connection.', 'wd_package' ) );;
		if (!empty($twitter_data) && !is_wp_error( $twitter_data )) {
			foreach ($twitter_data as $status) {
				$wd_twitter_feed[] = array(
					'user'	=> array(
						'name'			=> $status['user']['name'],
						'screen_name'	=> $status['user']['screen_name'],
						'location'		=> $status['user']['location'],
						'description'	=> $status['user']['description'],
						'url'			=> $status['user']['url'],
						'image_url'		=> $status['user']['profile_image_url'],
					),
					'status'	=> $status['text'],
					'url'		=> 'https://twitter.com/intent/user?screen_name='.$screen_name,
				);
			}
		}

		if ( ! empty( $wd_twitter_feed ) ) {
			return $wd_twitter_feed;
		} else {
			return new WP_Error( 'error_data', esc_html__( 'Twitter did not return any data.', 'wd_package' ) );
		}
	}
}