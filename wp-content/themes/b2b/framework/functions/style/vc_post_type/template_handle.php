<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (class_exists('WD_Templates_Post_Type') && !class_exists('WD_Template_Handle')) {
	class WD_Template_Handle extends WD_Templates_Post_Type{
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
			return;
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
	WD_Template_Handle::get_instance();  // Start an instance of the plugin class 
}