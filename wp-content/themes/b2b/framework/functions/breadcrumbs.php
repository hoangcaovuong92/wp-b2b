<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

/**
 * Usage : do_action('wd_hook_init_breadcrumbs');
 */

if (!class_exists('WD_Breadcrumb')) {
	class WD_Breadcrumb {
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

		private $delimiter = '<span class="wd-breadcrumb-delimiter">/</span>';
		private $home_text;

		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
			static::$called = true;

			$this->home_text = esc_html__( 'Home', 'feellio' );
			
			//Breadcrumbs Init
			add_action('wd_hook_init_breadcrumbs', array($this, 'init_breadcrumbs'), 5);

			//$style = apply_filters('wd_filter_breadcrumb_style', true);
			add_filter( 'wd_filter_breadcrumb_style', array($this, 'breadcrumb_style'), 10, 2);

			//Rename "home" in woocommerce breadcrumb
			add_filter('woocommerce_breadcrumb_defaults', array($this, 'woo_change_breadcrumb_home_text'));
		}

		/* Get custom css breadcrumb */
		public function breadcrumb_style(){
			/**
			 * package: breadcrumb-custom-setting
			 * var: blog_archive
			 * var: product_archive
			 * var: woo_special_page
			 * var: search_page
			 */
			extract(apply_filters('wd_filter_get_data_package', 'breadcrumb-custom-setting' ));
		
			/**
			 * package: breadcrumb-blog-archive, breadcrumb-product-archive, breadcrumb-woo-special-page, breadcrumb-search-page, breadcrumb-default
			 * var: layout_breadcrumbs
			 * var: image_breadcrumbs
			 * var: height
			 * var: color_breadcrumbs
			 * var: text_color
			 * var: text_style
			 * var: text_align
			 */
			if (is_post_type_archive( 'post' ) && $blog_archive) { 
				//breadcrumb for blog archive
				extract(apply_filters('wd_filter_get_data_package', 'breadcrumb-blog-archive' ));
			}elseif ( class_exists('WooCommerce') && (is_shop() || is_product_taxonomy() || is_product_category()) && $product_archive ) { 
				//breadcrumb for shop archive
				extract(apply_filters('wd_filter_get_data_package', 'breadcrumb-product-archive' ));
			}elseif (class_exists('WooCommerce') &&  (is_checkout() || is_cart()) && $woo_special_page) {
				//breadcrumb for woocommerce special page (cart, checkout)
				extract(apply_filters('wd_filter_get_data_package', 'breadcrumb-woo-special-page' ));
			}elseif (is_search() && $search_page) {
				//breadcrumb search page
				extract(apply_filters('wd_filter_get_data_package', 'breadcrumb-search-page' ));
			}else{
				//breadcrumb general
				extract(apply_filters('wd_filter_get_data_package', 'breadcrumb-default' ));
			}
			
			$post_ID		= apply_filters('wd_filter_global_post_id', '');
			/*PAGE CONFIG*/
			$_page_config 	= apply_filters('wd_filter_post_layout_config', $post_ID);
		
			$default_image_breadcrumb = WD_THEME_IMAGES.'/banner_breadcrumb.jpg';
			if ($image_breadcrumbs != $default_image_breadcrumb && strpos($image_breadcrumbs, home_url()) === false) {
				$image_breadcrumbs = $default_image_breadcrumb;
			}
			
			$custom_page_breadcrumb_setting = (!empty($_page_config['style_breadcrumb'])) ? $_page_config['style_breadcrumb'] : 'breadcrumb_default' ;
		
			/* Custom Breadcrumb */
			if ($custom_page_breadcrumb_setting != 'breadcrumb_default') {
				$layout_breadcrumbs = $custom_page_breadcrumb_setting;
				$image_breadcrumbs = !empty($_page_config['image_breadcrumb']) ? esc_url(wp_get_attachment_url($_page_config['image_breadcrumb'])) : esc_url($default_image_breadcrumb);
			} 
		
			$custom_style_breadcrumb = "";
			if ($layout_breadcrumbs == 'no_breadcrumb') {
				$custom_style_breadcrumb .= '.wd-init-breadcrumb{display:none !important;}'; //hide breadcrumb
			}else{
				$custom_style_breadcrumb .= '.wd-init-breadcrumb, .wd-init-breadcrumb h3, .wd-init-breadcrumb a, .wd-init-breadcrumb .woocommerce-breadcrumb{color:'.esc_attr($text_color).'}'; //text color
		
				$custom_style_breadcrumb .= '.wd-init-breadcrumb, .wd-init-breadcrumb .container{height:'.esc_attr($height).'px !important;}'; //height
		
				if ($text_style == 'block') { //content center for breadcrumb block
					$custom_style_breadcrumb .= '.wd-init-breadcrumb, .wd-init-breadcrumb .wd-breadcrumb-content{height:'.esc_attr($height).'px !important;}'; //height
					$custom_style_breadcrumb .= '.wd-init-breadcrumb .wd-breadcrumb-content{display: flex; flex-direction: column; justify-content: center;}'; //content align middle
				}else{ //content center for breadcrumb inline
					$custom_style_breadcrumb .= '.wd-init-breadcrumb, .wd-init-breadcrumb .container{height:'.esc_attr($height).'px !important;}'; //height
					$custom_style_breadcrumb .= '.wd-init-breadcrumb .wd-breadcrumb-text-style-inline, .wd-init-breadcrumb .wd-breadcrumb-text-style-inline .wd-breadcrumb-title, .wd-init-breadcrumb .wd-breadcrumb-text-style-inline .wd-breadcrumb-slug{line-height:'.esc_attr($height).'px !important;}'; //content align middle
				}
		
				if ($layout_breadcrumbs == 'breadcrumb_banner' && $image_breadcrumbs != '') {
					$custom_style_breadcrumb .= '.wd-init-breadcrumb.breadcrumb_banner { background-image: url("'.esc_url($image_breadcrumbs).'"); }'; //background image
				}elseif ($layout_breadcrumbs == 'breadcrumb_default') {
					$custom_style_breadcrumb .= '.wd-init-breadcrumb.breadcrumb_default { background-color: '.esc_attr($color_breadcrumbs).'; }'; //background color
				}
			}
			return $custom_style_breadcrumb;
		}

		public function init_breadcrumbs(){
			if (is_home() || is_front_page()) {
				return;
			}
			/**
			 * package: breadcrumb-custom-setting
			 * var: blog_archive		
			 * var: product_archive	
			 * var: woo_special_page	
			 * var: search_page		
			 */
			extract(apply_filters('wd_filter_get_data_package', 'breadcrumb-custom-setting' ));
			/**
			 * package: breadcrumb-blog-archive, breadcrumb-product-archive, breadcrumb-woo-special-page, breadcrumb-search-page, breadcrumb-default
			 * var: layout_breadcrumbs
			 * var: image_breadcrumbs
			 * var: height
			 * var: color_breadcrumbs
			 * var: text_color
			 * var: text_style
			 * var: text_align
			 */
			if (is_post_type_archive( 'post' ) && $blog_archive) { 
				//breadcrumb for blog archive
				extract(apply_filters('wd_filter_get_data_package', 'breadcrumb-blog-archive' ));
			}elseif ( class_exists('WooCommerce') && (is_shop() || is_product_taxonomy() || is_product_category()) && $product_archive ) { 
				//breadcrumb for shop archive
				extract(apply_filters('wd_filter_get_data_package', 'breadcrumb-product-archive' ));
			}elseif (class_exists('WooCommerce') &&  (is_checkout() || is_cart()) && $woo_special_page) {
				//breadcrumb for woocommerce special page (cart, checkout)
				extract(apply_filters('wd_filter_get_data_package', 'breadcrumb-woo-special-page' ));
			}elseif (is_search() && $search_page) {
				//breadcrumb search page
				extract(apply_filters('wd_filter_get_data_package', 'breadcrumb-search-page' ));
			}else{
				//breadcrumb general
				extract(apply_filters('wd_filter_get_data_package', 'breadcrumb-default' ));
			}
	
			$post_ID		= apply_filters('wd_filter_global_post_id', '');
			/*PAGE CONFIG*/
			$_page_config 	= apply_filters('wd_filter_post_layout_config', $post_ID);
			
			$custom_page_breadcrumb_setting = (!empty($_page_config['style_breadcrumb'])) ? $_page_config['style_breadcrumb'] : 'breadcrumb_default' ;
	
			/*Custom Breadcrumb*/
			if ($custom_page_breadcrumb_setting != 'breadcrumb_default') {
				$layout_breadcrumbs = $custom_page_breadcrumb_setting;
			} 
		
			$text_class = 'wd-breadcrumb-text-style-'.$text_style;
			if ($text_style == 'block'){
				$text_class .= ' '.$text_align;		
			}
			?>
			<?php if ($layout_breadcrumbs != 'no_breadcrumb' && !is_page_template( 'page-templates/template-home.php' ) && !is_page_template( 'page-templates/template-home-header-left.php' )): ?>
				<div class="wd-init-breadcrumb <?php echo esc_attr($layout_breadcrumbs); ?>">
					<?php if ($text_style == 'inline'): ?>
						<div class="container">
					<?php endif ?>
						<div class="wd-breadcrumb-wrap-info-title <?php echo esc_attr($text_class); ?>">
							<div class="wd-breadcrumb-content">
								<?php if ($text_style != 'inline'): ?>
									<div class="wd-breadcrumb-title">
										<?php $this->show_breadcrumbs_title(); ?>
									</div>
								<?php endif ?>
								<div class="wd-breadcrumb-slug">
									<?php $this->show_breadcrumbs_slug(); ?>
								</div>
							</div>
						</div>
					<?php if ($text_style == 'inline'): ?>
						</div>
					<?php endif ?>
				</div>
			<?php endif ?>
			<?php
		}

		/* GET BREADCRUMB SLUG CONTENT
		Show breadcrumbs with format : 
			Home » Category » Subcategory » Post Title
			Home » Subcategory » Post Title
			Home » Page Level 1 » Page Level 2 » Page Level 3
		*/
		public function show_breadcrumbs_title(){ 
			if (is_search()) {
				echo "<h3>".esc_html__('SEARCH', 'feellio')."</h3>";
			}elseif(is_page() || is_single()){
				echo "<h3>".get_the_title()."</h3>";
			}elseif(class_exists('WooCommerce') && is_shop()){
				echo "<h3>".esc_html__('SHOP', 'feellio')."</h3>";
			}elseif(is_archive()){
				echo "<h3>".single_cat_title()."</h3>";
			}else{
				the_archive_title( '<h3>', '</h3>' ); 
			}
		}

		public function show_breadcrumbs_slug() {
			if( wd_is_woocommerce() ){
				if( function_exists('woocommerce_breadcrumb') && function_exists('is_woocommerce') && is_woocommerce() ){
					$args = array(
							'delimiter' => $this->delimiter,
							'before' 	=> '',
							'home' 		=> $this->home_text,
					); 
					woocommerce_breadcrumb( $args );
					return;
				}
			}
	
			wp_reset_postdata();
			// $front_id = get_option( 'page_on_front' );
			// if ( !empty( $front_id ) ) {
			// 	$home = get_the_title( $front_id );
			// } else {
				//$home = esc_html__( 'Home', 'feellio' );
			//}

			$ar_title = array(
				'search' 		=> esc_html__('Search results for ', 'feellio'),
				'404' 			=> esc_html__('Error 404', 'feellio'),
				'tagged' 		=> esc_html__('Tagged ', 'feellio'),
				'author' 		=> esc_html__('Articles posted by ', 'feellio'),
				'page' 			=> esc_html__('Page', 'feellio'),
				'portfolio' 	=> esc_html__('Portfolio', 'feellio'),
			);
		
			$before = '<span class="current">'; // tag before the current crumb
			$after = '</span>'; // tag after the current crumb
			global $wp_rewrite;
			$rewriteUrl = $wp_rewrite->using_permalinks();

			if ( !is_home() && !is_front_page() || is_paged() ) {
				echo '<div class="wd-breadcrumb-slug-content">';
				global $post;
				$homeLink = home_url('/'); //get_bloginfo('url');
				echo '<a class="wd-breadcrumb-home-link" href="' . $homeLink . '">' . $this->home_text . '</a> ' . $this->delimiter . ' ';
		
				if ( is_category() ) {
					global $wp_query;
					$cat_obj = $wp_query->get_queried_object();
					$thisCat = $cat_obj->term_id;
					$thisCat = get_category($thisCat);
					$parentCat = get_category($thisCat->parent);
					if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $this->delimiter . ' '));
						echo wp_kses_post($before . single_cat_title('', false) . $after);
				} elseif ( is_search() ) {
					echo wp_kses_post($before . $ar_title['search'] . '"' . get_search_query() . '"' . $after);
			
				}elseif ( is_day() ) {
					echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $this->delimiter . ' ';
					echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $this->delimiter . ' ';
					echo wp_kses_post($before . get_the_time('d') . $after);
			
				} elseif ( is_month() ) {
					echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $this->delimiter . ' ';
					echo wp_kses_post($before . get_the_time('F') . $after);
			
				} elseif ( is_year() ) {
					echo wp_kses_post($before . get_the_time('Y') . $after);
			
				} elseif ( is_single() && !is_attachment() ) {
					$title = (get_the_title() != '') ? esc_html(get_the_title()) : esc_html('(No title)', 'feellio');
					if ( get_post_type() != 'post' ) {
						$post_type		= get_post_type_object(get_post_type());
						$slug 			= $post_type->rewrite;
						$post_type_name = $post_type->labels->singular_name;
						if(strcmp('Portfolio Item',$post_type->labels->singular_name)==0){
							$post_type_name = $ar_title['portfolio'];
						}
						/*if($rewriteUrl){
							echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type_name . '</a> ' . $this->delimiter . ' ';
						}else{
							echo '<a href="' . $homeLink . '/?post_type=' . get_post_type() . '">' . $post_type_name . '</a> ' . $this->delimiter . ' ';
						}*/
						
						echo wp_kses_post($before . $title . $after);
					} else {
						$cat = get_the_category(); $cat = $cat[0];
						echo get_category_parents($cat, TRUE, ' ' . $this->delimiter . ' ');
						echo wp_kses_post($before . $title . $after);
					}
			
				} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
					$post_type 		= get_post_type_object(get_post_type());
					$slug			= $post_type->rewrite;
					$post_type_name = $post_type->labels->singular_name;
					if(strcmp('Portfolio Item',$post_type->labels->singular_name)==0){
						$post_type_name = $ar_title['portfolio'];
					}
					if ( is_tag() ) {
						echo wp_kses_post($before . $ar_title['tagged'] . '"' . single_tag_title('', false) . '"' . $after);
					}elseif(is_taxonomy_hierarchical(get_query_var('taxonomy'))){
						/*if($rewriteUrl){
							echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type_name . '</a> ' . $this->delimiter . ' ';
						}else{
							echo '<a href="' . $homeLink . '/?post_type=' . get_post_type() . '">' . $post_type_name . '</a> ' . $this->delimiter . ' ';
						}			*/
						
						$curTaxanomy 	= get_query_var('taxonomy');
						$curTerm 		= get_query_var( 'term' );
						$termNow 		= get_term_by( "name",$curTerm, $curTaxanomy);
						$pushPrintArr 	= array();
						if( $termNow !== false ){
							while ((int)$termNow->parent != 0){
								$parentTerm = get_term((int)$termNow->parent,get_query_var('taxonomy'));
								array_push($pushPrintArr,'<a href="' . get_term_link((int)$parentTerm->term_id,$curTaxanomy) . '">' . $parentTerm->name . '</a> ' . $this->delimiter . ' ');
								$curTerm = $parentTerm->name;
								$termNow = get_term_by( "name",$curTerm, $curTaxanomy);
							}
						}
						$pushPrintArr = array_reverse($pushPrintArr);
						array_push($pushPrintArr,$before  . get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) )->name  . $after);
						echo implode($pushPrintArr);
					}else{
						echo wp_kses_post($before . $post_type_name . $after);
					}
			
				} elseif ( is_attachment() ) {
					if( (int)$post->post_parent > 0 ){
						$parent = get_post($post->post_parent);
						$cat 	= get_the_category($parent->ID);
						if( count($cat) > 0 ){
							$cat = $cat[0];
							echo get_category_parents($cat, TRUE, ' ' . $this->delimiter . ' ');
						}
						echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $this->delimiter . ' ';
					}
					echo wp_kses_post($before . get_the_title() . $after);
				} elseif ( is_page() && !$post->post_parent ) {
					echo wp_kses_post($before . get_the_title() . $after);
			
				} elseif ( is_page() && $post->post_parent ) {
					$parent_id  	= $post->post_parent;
					$breadcrumbs 	= array();
					while ($parent_id) {
						$page 			= get_post($parent_id);
						$breadcrumbs[] 	= '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
						$parent_id  	= $page->post_parent;
					}
					$breadcrumbs 	= array_reverse($breadcrumbs);
					foreach ($breadcrumbs as $crumb) echo wp_kses_post($crumb . ' ' . $this->delimiter . ' ');
					echo wp_kses_post($before . get_the_title() . $after);
				} elseif ( is_tag() ) {
					echo wp_kses_post($before . $ar_title['tagged'] . '"' . single_tag_title('', false) . '"' . $after);
			
				} elseif ( is_author() ) {
					global $author;
					$userdata = get_userdata($author);
					echo wp_kses_post($before . $ar_title['author'] . $userdata->display_name . $after);
			
				} elseif ( is_404() ) {
					echo wp_kses_post($before . $ar_title['404'] . $after);
				}
		
				if ( get_query_var('paged') ) {
					if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_page_template() ||  is_post_type_archive() || is_archive() ) echo wp_kses_post($before .' (');
						echo wp_kses_post($ar_title['page'] . ' ' . get_query_var('paged'));
					if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_page_template() ||  is_post_type_archive() || is_archive() ) echo ')'. $after;
				} else { 
					if ( get_query_var('page') ) {
						if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_page_template() ||  is_post_type_archive() || is_archive() ) echo wp_kses_post($before .' (');
							echo wp_kses_post($ar_title['page'] . ' ' . get_query_var('page'));
						if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_page_template() ||  is_post_type_archive() || is_archive() ) echo ')'. $after;
					}
				}
				echo '</div>';
			}
			wp_reset_postdata();
		}

		//Rename "home" in woocommerce breadcrumb
		function woo_change_breadcrumb_home_text( $defaults ) {
			// Change the breadcrumb home text from 'Home' to 'Apartment'
			$defaults['home'] = $this->home_text;
			return $defaults;
		}
	}
	WD_Breadcrumb::get_instance();  // Start an instance of the plugin class 
}