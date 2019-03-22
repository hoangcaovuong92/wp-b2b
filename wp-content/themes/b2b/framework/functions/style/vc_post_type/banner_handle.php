<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (class_exists('WD_Banners_Post_Type') && !class_exists('WD_Banner_Handle')) {
	class WD_Banner_Handle extends WD_Banners_Post_Type{
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

		protected $list_data = '';

		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
			static::$called = true;

			add_action('wp_enqueue_scripts', array($this,'css_handle'));
			add_action('wd_hook_header_meta_data', array($this, 'data_handle'), 15);

			$this->list_data = $this->get_list_data();
		}

		public function css_handle(){
			if ($this->get_style()) {
				wp_enqueue_style('wd-advance-post-type-inline-css');
				wp_add_inline_style('wd-advance-post-type-inline-css', $this->minify_css($this->get_style()));
			}
		}

		public function get_style(){
			$args = array(
				'post_type'			=> $this->post_type,
				'post_status'		=> 'publish',
				'posts_per_page' 	=> -1,
			);
			$data = new WP_Query($args);
			$css = '';

			if( $data->have_posts() ){
				while( $data->have_posts() ){
					$data->the_post();
					global $post;
					$pre_post = $post;
					$css .= get_post_meta( get_the_ID(), '_wpb_shortcodes_custom_css', true );
					$css .= get_post_meta( get_the_ID(), '_wpb_post_custom_css', true );
					$post = $pre_post;
				}
			}
			wp_reset_postdata();
			return $css;
		}

		public function data_handle(){
			$list_data = $this->get_list_data();
			if (is_array($list_data) && count($list_data)) {
				foreach ($list_data as $value) {
					$hook = $value['hook'];
					$content = $value['content'];
					add_action($hook, function() use ($content){
						echo $content;
					});
				}
			}
		}

		public function get_list_data(){
			$args = array(
				'post_type'			=> $this->post_type,
				'post_status'		=> 'publish',
				'posts_per_page' 	=> -1,
			);
			$data = new WP_Query($args);
			$data_return = array();
			if( $data->have_posts() ){
				while( $data->have_posts() ){
					$data->the_post();
					global $post;
					$pre_post = $post;
					$meta_data = $this->get_meta_data($this->post_type);
					if (!is_array($meta_data) || empty($meta_data['position'])) continue;

					$position 	= $meta_data['position'];
					$banner_id 	= $meta_data['banner'];
					$link 		= esc_url($meta_data['link']);
					$target 	= $meta_data['target'];

					ob_start();
					echo '<div class="wd-additional-banner-wrap wd-main-content">';
					if ($banner_id) {
						echo '<div class="wd-banner-image wd-banner-hover--style-1">';
						echo '<a target="'.$target.'" href="'.$link.'">';
						echo wp_get_attachment_image($banner_id, 'full', false, array( 
														'title' 	=> get_the_title(), 
														'alt'		=> get_the_title(), 
														'itemprop'	=> "image"));
						echo '</a>';
						echo '</div>';
					}

					if (get_the_content()) {
						echo '<div class="wd-additional-banner-content">';
						echo do_shortcode(apply_filters('the_content', get_the_content()));
						echo '</div>';
					}
					echo '</div>';
					$content = ob_get_clean();
					
					foreach ($position as $hook) {
						$data_return[] = array(
							'hook' => $hook,
							'content' => $content,
						);
					}
					$post = $pre_post;
				}
			}
			wp_reset_postdata();
			return $data_return;
		}

		public function minify_css( $content ) {
			$content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
			$content = str_replace(["\r\n","\r","\n","\t",'  ','    ','     '], '', $content);
			$content = preg_replace(['(( )+{)','({( )+)'], '{', $content);
			$content = preg_replace(['(( )+})','(}( )+)','(;( )*})'], '}', $content);
			$content = preg_replace(['(;( )+)','(( )+;)'], ';', $content);
			return $content;
		}
	}
	WD_Banner_Handle::get_instance();  // Start an instance of the plugin class 
}