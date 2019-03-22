<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_SearchForm')) {
	class WD_SearchForm {
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

		protected $type, $ajax, $show_thumbnail, $search_only_title;

		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
			static::$called = true;
			
			$this->get_setting();
			//Display search form 
			/* echo apply_filters('wd_filter_search_form', array('style' => 'normal', 'class' => '')); */
			add_filter('wd_filter_search_form', array($this, 'get_search_form'), 10, 2);

			//Display search icon with filter hook
 			/* echo apply_filters('wd_filter_search_icon', array('show_icon' => 1, 'show_text' => 0, 'show_text' => 0, 'class' => '')); */
			add_filter('wd_filter_search_icon', array($this, 'get_search_icon'), 10, 2);

			//Array post title name
 			/* echo apply_filters('wd_filter_ajax_search_result', array('post_type' => $post_type, 'json' => $json, 'ppp' => $ppp)); */
			add_filter('wd_filter_ajax_search_result', array($this, 'get_ajax_result'), 10, 2);

			//Replace default search form
			add_filter('get_search_form', array($this, 'custom_get_search_form'));

			//Replace default search form
 			/* echo apply_filters('get_product_search_form', $class); */
			add_filter('get_product_search_form', array($this, 'product_search'));

			// Search by Post Title only
			if ($this->search_only_title) {
				add_filter('posts_search', array($this, 'search_by_title_only'), 500, 2);
			}

			//$style = apply_filters('wd_filter_search_page_style', true);
			add_filter( 'wd_filter_search_page_style', array($this, 'search_page_style'), 10, 2);

			//get listings post name for ajax search
			add_action('wp_ajax_nopriv_wd_ajax_search', array($this, 'ajax_search'));
			add_action('wp_ajax_wd_ajax_search', array($this, 'ajax_search'));
		}

		//**************************************************************//
		/*							DEFAULT SEARCH			  			*/
		//**************************************************************//
		public function get_setting() {
			/**
			 * package: search-form
			 * var: type 
			 * var: ajax 
			 * var: show_thumbnail 
			 * var: search_only_title 
			 */
			extract(apply_filters('wd_filter_get_data_package', 'search-form'));
			$this->type = $type;
			$this->ajax = $ajax;
			$this->show_thumbnail = $show_thumbnail;
			$this->search_only_title = $search_only_title;
		}

		//Popup search
		public function get_search_icon($setting = array()) {
			$default = array(
				'show_icon' => 1, //icon
				'show_text' => 0, 
				'class' => ''
			);
			extract(wp_parse_args($setting, $default));
			$icon_html 	= ($show_icon) ? '<span class="fa fa-search wd-icon"></span>' : '';
			$title 		= ($show_text) ? '<span class="wd-navUser-action-text">'.esc_html__('Search', 'feellio').'</span>' : '';
			$random_id 	= 'wd-search-form-popup-'.mt_rand();
			ob_start(); ?>
				<div class="wd-navUser-action-wrap wd-search wd-search--popup <?php echo esc_attr($class) ?>">
					<a data-ajax="<?php echo esc_attr($this->ajax); ?>" href="#" class="wd-navUser-action wd-navUser-action--search wd-click-popup-search" data-target="<?php echo esc_attr($random_id); ?>">
						<?php echo $icon_html.$title; ?>
					</a>
					<div id="<?php echo esc_attr($random_id); ?>" class="wd-popup-search">
						<?php get_search_form();?>
					</div>
				</div>
			<?php
			return ob_get_clean();
		}

		//Normal search
		public function get_search_form($setting = array()) { 
			$default = array(
				'style' => 'normal',
				'class' => ''
			);
			extract(wp_parse_args($setting, $default)); ?>
			<?php if ($style === 'normal') { ?>
				<div class="wd-search wd-search--normal <?php echo esc_attr($class) ?>">
					<?php get_search_form(); ?>
				</div>
			<?php } else {
				echo $this->get_search_icon();
			} ?>
		<?php
		}

		//Custom wp search form
		public function custom_get_search_form( $form ) {
			$random_id   		= 'wd-search-form-'.mt_rand();
			$custom_class   	= ($this->ajax) ? ' wd-search-with-ajax' : '';
			$button_title		= ($this->type == 'post') ? esc_html__( 'Search blog' , 'feellio') : esc_html__( 'Search product' , 'feellio');
			ob_start(); ?>
			<div class="wd-search-form-default">
				<form role="search" method="get" id="<?php echo esc_attr($random_id); ?>" action="<?php echo esc_url(home_url( '/' )); ?>" >
					<div class="wd-search-form-wrapper">
						<div class="wd-search-input-wrap">
							<?php if ($this->ajax): ?>
								<div class="wd-search-form-image-loading hidden">
									<img src="<?php echo WD_THEME_IMAGES.'/loading.gif'; ?>" alt="<?php echo esc_html__( 'Loading Icon' , 'feellio'); ?>">
								</div>
							<?php endif ?>
							<input type="hidden" name="post_type" value="<?php echo esc_attr($this->type); ?>" />
							<input 	type="text" name="s" 
									class="wd-search-form-text <?php echo esc_attr($custom_class); ?>" 
									data-post_type="<?php echo esc_attr($this->type); ?>" 
									placeholder="<?php echo esc_html__( 'What are you looking for?' , 'feellio'); ?>" 
									autocomplete="off" 
									value="<?php echo esc_attr(get_search_query()); ?>" />
						</div>
						<div class="wd-search-button-wrap">
							<button type="submit" title="<?php echo $button_title; ?>">
								<i class="fa fa-search wd-icon"></i><span class="wd-button-text"><?php esc_html_e( 'Search' , 'feellio'); ?></span>
							</button>
						</div>
					</div>
				</form>
				<?php if ($this->type): ?>
					<div class="wd-search-form-ajax-result"></div>
				<?php endif ?>
			</div>
			<?php
			return ob_get_clean();
		}

		//**************************************************************//
		/*							AJAX SEARCH			  				*/
		//**************************************************************//
		public function ajax_search(){
			$post_type = isset($_REQUEST['post_type']) ? $_REQUEST['post_type'] : '';
			$s = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';

			$post_type = explode(',', $post_type);
			$result['html'] = $this->get_ajax_result(array(
				'post_type' => $post_type, 
				's' => $s, 
				'show_thumbnail' => $this->show_thumbnail, 
				'search_only_title' => $this->search_only_title, 
				'json' => false)
			);
			wp_send_json_success($result);
			die(); //stop "0" from being output
		}

		/* Get array post name (autocomplete search) */
		public function get_ajax_result($setting = array()){ 
			$default = array(
				'post_type' => 'post', 
				's' => '', 
				'json' => true, 
				'show_thumbnail' => true, 
				'search_only_title' => true, 
				'ppp' => -1, 
			);
			extract(wp_parse_args($setting, $default));
			$data_return = '';
			if (is_array($post_type) && !empty($post_type)) {
				foreach ($post_type as $post_type_name) {
					$args = array(
						'post_type'			=> $post_type_name,
						'posts_per_page'	=> $ppp,
						'wd_search_post_title' => $s,
					);
					
					if ($search_only_title) {
						add_filter('posts_where', array($this, 'title_filter'), 10, 2);
					}
					$data = new WP_Query( $args );
					if ($search_only_title) {
						remove_filter('posts_where', array($this, 'title_filter'), 10, 2);
					}

					if( $data->have_posts() ) {
						$view_all_url = esc_url(home_url('/'));
						$view_all_url = add_query_arg('s', $s, $view_all_url);
						$view_all_url = add_query_arg('post_type', $post_type_name, $view_all_url);
						$data_return .= '<p class="wd-search-result-title"><i class="fa fa-search wd-icon"></i>';
						$data_return .= sprintf(esc_html__( '%1$s results (%2$s)' , 'feellio'), $post_type_name, $data->found_posts);
						$data_return .= ' - <a target="_blank" href="'.$view_all_url.'">'.esc_html__( 'View all' , 'feellio').'</a>';
						$data_return .= '</p>';

						$data_return .= '<ul class="wd-search-result-list">';
						while( $data->have_posts() ) {
							$data->the_post(); 
							global $post;
							$title = $this->highlight_text($post->post_title, $s);
							$thumb_id = get_post_thumbnail_id($post->ID) ? get_post_thumbnail_id($post->ID) : apply_filters('wd_filter_demo_image', true);
							$thumb = ($show_thumbnail) ? wp_get_attachment_image_src($thumb_id, 'wd_image_size_thumbnail') : '';
							$data_return .= '<li class="wd-search-result-item"><a href="'.get_the_permalink().'">';
							$data_return .= ($thumb) ? '<div class="wd-search-result-item-img" style="background: url('.$thumb[0].')"></div>' : '';
							$data_return .= '<div class="wd-search-result-item-text">'.$title.'</div>';
							$data_return .= '</a></li>';
						}
						$data_return .= '</ul>';
					}
					wp_reset_postdata();
				}
			}

			if (!$data_return) {
				$data_return .= '<p class="wd-search-result-title">'.sprintf(esc_html__( 'No result found for "%s"!' , 'feellio'), $s).'</p>';
			}
			return ($json) ? json_encode($data_return) : $data_return;
		}

		public function highlight_text($string, $key){
			$pattern = preg_quote($key);
			return preg_replace("/($pattern)/i", '<span class="wd-search-highlight">$1</span>', $string);
		}

		// Search by Post Title only
		//https://wordpress.stackexchange.com/a/185736
		// add 'title_filter' => $s, 'title_filter_relation' => 'OR' to WP Query params
		public function title_filter($where, $wp_query){
			global $wpdb;
			if ( $search_term = $wp_query->get( 'wd_search_post_title' ) ) {
				$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . $wpdb->esc_like( $search_term ) . '%\'';
			}
			return $where;
		}

		// Search by Post Title only
		public function search_by_title_only($search, $wp_query){
			global $wpdb;
			if ( empty( $search ) )
				return $search; // skip processing - no search term in query
			$q = $wp_query->query_vars;
			$n = ! empty( $q['exact'] ) ? '' : '%';
			$search = '';
			$searchand = '';
			foreach ( (array) $q['search_terms'] as $term ) {
				$term = esc_sql( $wpdb->esc_like( $term ) );
				$search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
				$searchand = ' AND ';
			}
			if ( ! empty( $search ) ) {
				$search = " AND ({$search}) ";
				if ( ! is_user_logged_in() )
					$search .= " AND ($wpdb->posts.post_password = '') ";
			}
			return $search;
		}

		//**************************************************************//
		/*							PRODUCT SEARCH			  			*/
		//**************************************************************//
		public function product_search($class){
			if (!wd_is_woocommerce()) return;
			ob_start(); ?>
			<div class="wd-search-form-default wd-product-search-wrap <?php echo esc_attr($class); ?>">
				<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/'  ) ) ?>">
					<div class="wd-search-form-wrapper">
						<div class="wd-search-input-wrap">
							<?php echo $this->product_categories_select2(); ?>
							<input type="text" 
								class="wd-search-form-text" 
								value="<?php echo get_search_query(); ?>" 
								name="s" 
								autocomplete="off" 
								placeholder="<?php esc_html_e( 'Search for products', 'feellio' ); ?> " />
							<input type="hidden" name="post_type" value="product" />
							<input type="hidden" name="taxonomy" value="product_cat" />
						</div>
						<div class="wd-search-button-wrap">
							<button type="submit" title="<?php esc_html_e( 'Search' , 'feellio'); ?>">
								<i class="fa fa-search wd-icon"></i><span class="wd-button-text"><?php esc_html_e( 'Search' , 'feellio'); ?></span>
							</button>
						</div>
					</div>
				</form>
			</div>
			<?php
			return ob_get_clean();
		}

		public function product_categories_select2(){
			if (!wd_is_woocommerce()) return;
			wp_reset_query();
			$args = array(
				'taxonomy' => 'product_cat',
				'number'     => '',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => true,
				'include'    => array()
			);

			$product_categories = get_terms($args); 
			$categories_show = '<option value="">'.esc_html__( 'All Categories', 'feellio' ).'</option>';
			$check = '';
			if(is_search()){
				if(isset($_GET['term']) && $_GET['term']!=''){
					$check = $_GET['term'];	
				}
			}
			$checked = '';
			foreach($product_categories as $category){
				if(isset($category->slug)){
					if(trim($category->slug) == trim($check)){
						$checked = 'selected="selected"';
					}
					$categories_show  .= '<option '.$checked.' value="'.$category->slug.'">'.$category->name.'</option>';
					$checked = '';
				}
			}
			ob_start(); ?>
				<select class="wd-select2-element" name="term"><?php echo balanceTags($categories_show); ?></select>
			<?php
			wp_reset_postdata();
			return ob_get_clean();
		}

		//**************************************************************//
		/*						SEARCH PAGE STYLE			  			*/
		//**************************************************************//
		/* Get custom css search Page */
		public function search_page_style(){
			/**
			 * package: search-style
			 * var: select_style
			 * var: bg_search_url
			 * var: bg_search_color
			 */
			extract(apply_filters('wd_filter_get_data_package', 'search-style' ));
			$custom_style_search_page = '';
			if($select_style == 'bg_image'){
				$default_url_search 		= WD_THEME_IMAGES.'/bg_404.jpg';
				if ($bg_search_url != $default_url_search && strpos($bg_search_url, home_url()) === false) {
					$bg_search_url 	= $default_url_search;
				}
				$custom_style_search_page 	.= '.wd-search-result-page { background-image: url("'.esc_url($bg_search_url).'"); background-attachment: fixed; }';
			}else{
				$custom_style_search_page 	.= '.wd-search-result-page { background-color: '.esc_url($bg_search_color).'; }';
			}
			return $custom_style_search_page;
		}
	}
	WD_SearchForm::get_instance();  // Start an instance of the plugin class 
}