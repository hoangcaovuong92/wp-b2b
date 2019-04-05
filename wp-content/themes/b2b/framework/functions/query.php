<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Query')) {
	class WD_Query{
		/**
		 * Refers to a single instance of this class.
		 */
		private static $instance = null;

		// Ensure construct function is called only once
		private static $called = false;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
			static::$called = true;
			
			//$args = apply_filters('wd_filter_get_product_query', $args, $settings);
			add_filter( 'wd_filter_get_product_query', array($this, 'product_query' ), 10, 2);

			//$args = apply_filters('wd_filter_get_blog_query', $args, $settings);
			add_filter( 'wd_filter_get_blog_query', array($this, 'blog_query' ), 10, 2);
		}

		//$args : replace WP_Query arguments (https://www.billerickson.net/code/wp_query-arguments/)
		//Ex: $args = array('posts_per_page' => 12);
		//$settings : array of settings
		//Ex: $settings = array(
		// 		'category' => array(
		// 			'product_cat'	=> 123/'sale_product' //category/taxonomy => id/slug
		// 		),
		// 		'order_by' => 'price' / 'sales',
		// 		'data_show' => 'recent_product' / 'mostview_product', 'onsale_product', 'featured_product'
		// );
		public function product_query($args = array(), $settings = array()) {
			$args_default = array(  
				'post_type' 		=> 'product',  
				'posts_per_page' 	=> -1,
				'order'				=> 'DESC',
				'orderby' 			=> 'date',
				'post_status'		=> 'publish',
				// 'ignore_sticky_posts' => true,
				'update_post_term_cache' => false, 
				'update_post_meta_cache' => false, 
				'paged' 			=> get_query_var('paged')
			);
			$args = wp_parse_args($args, $args_default);
			if (is_array($settings) && count($settings)) {
				foreach ($settings as $key => $data) {
					if ($key === 'category' && is_array($data) && count($data)) {
						foreach ($data as $cat => $value) {
							if (!$value || $value === -1) continue;
							$args['tax_query']['relation'] = 'AND';
							$args['tax_query'][] = array(
								'taxonomy' 		=> $cat,
								'terms' 		=> $value,
								'field' 		=> is_numeric($value) ? 'term_id' : 'slug',
								'operator' 		=> 'IN'
							);
						}
					}else if($key === 'order_by'){
						switch ( $data ) {
							case 'price':
								$args['meta_key'] = '_price';
								$args['orderby']  = 'meta_value_num';
								break;
							case 'sales':
								$args['meta_key'] = 'total_sales';
								$args['orderby']  = 'meta_value_num';
								break;
						}
					}else if($key === 'data_show'){
						switch ( $data ) {
							case 'mostview_product':
								$args['meta_key'] 	= '_wd_post_views_count';
								$args['orderby'] 	= 'meta_value_num';
								//$args['order'] 		= 'DESC';
								break;
							case 'onsale_product':
								$args['meta_query'] = array(
									'relation' => 'OR',
									array( // Simple products type
										'key'           => '_sale_price',
										'value'         => 0,
										'compare'       => '>',
										'type'          => 'numeric'
									),
									array( // Variable products type
										'key'           => '_min_variation_sale_price',
										'value'         => 0,
										'compare'       => '>',
										'type'          => 'numeric'
									)
								);
								break;
							case 'featured_product':
								$args['tax_query'][] = array(
									'taxonomy' => 'product_visibility',
									'field'    => 'name',
									'terms'    => 'featured',
								);
								break;
						}
					}
				}
			}
			return $args;
		}

		//$args : replace WP_Query arguments (https://www.billerickson.net/code/wp_query-arguments/)
		//Ex: $args = array('posts_per_page' => 12);
		//$settings : array of settings
		//Ex: $settings = array(
		// 		'category' => array(
		// 			'category'	=> 123/'hot_blog' //category/taxonomy => id/slug
		// 		),
		// 		'data_show' => 'recent_blog' / 'mostview_blog', 'comment_blog'
		// );
		public function blog_query($args = array(), $settings = array()) {
			$args_default = array(  
				'post_type' 		=> 'post',
				'posts_per_page' 	=> -1,
				'order'				=> 'DESC',
				'orderby' 			=> 'date',
				'post_status'		=> 'publish',
				// 'ignore_sticky_posts' => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
				'paged' 			=> get_query_var('paged')
			);
			$args = wp_parse_args($args, $args_default);
			if (is_array($settings) && count($settings)) {
				foreach ($settings as $key => $data) {
					if ($key === 'category' && is_array($data) && count($data)) {
						foreach ($data as $cat => $value) {
							if (!$value || $value === -1) continue;
							$args['tax_query']['relation'] = 'AND';
							$args['tax_query'][] = array(
								'taxonomy' 		=> $cat,
								'terms' 		=> $value,
								'field' 		=> is_numeric($value) ? 'term_id' : 'slug',
								'operator' 		=> 'IN'
							);
						}
					}else if($key === 'data_show'){
						switch ( $data ) {
							case 'mostview_blog':
								$args['meta_key'] 	= '_wd_post_views_count';
								$args['orderby'] 	= 'meta_value_num';
								//$args['order'] 		= 'DESC';
								break;
							case 'comment_blog':
								$args['orderby']		= 'comment_count';
								break;
						}
					}
				}
			}
			return $args;
		}
	}
	WD_Query::get_instance();  // Start an instance of the plugin class 
}