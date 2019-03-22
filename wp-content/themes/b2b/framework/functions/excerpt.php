<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

/**
 * Usage : 
 * echo apply_filters('wd_filter_excerpt_limit_character_length', array('charlength' => $charlength, 'post_obj' => $post_obj));
 * echo apply_filters('wd_filter_excerpt_limit_word_length', array('word_limit' => $word_limit, 'post_obj' => $post_obj, 'strip_tags' => $strip_tags));
 * echo apply_filters('wd_filter_number_fix_length', array('number' => '0', 'length' => '2', 'character' => '0'));
 */

if (!class_exists('WD_Excerpt')) {
	class WD_Excerpt {
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
			
			add_filter( 'wd_filter_excerpt_limit_character_length', array($this, 'limit_character_length' ), 10, 2);
			add_filter( 'wd_filter_excerpt_limit_word_length', array($this, 'limit_words_length' ), 10, 2);
			add_filter( 'wd_filter_number_fix_length', array($this, 'number_fix_length' ), 10, 2);
		}

		//print an excerpt by specifying a maximium number of characters
		function limit_character_length($charlength, $post_obj = '') {
			$default = array(
				'charlength' => '', 
				'post_obj' => '', 
			);
			extract(wp_parse_args($args, $default));
			$excerpt = ($post_obj) ? wp_strip_all_tags(get_the_excerpt_here($post_obj->ID)) : get_the_excerpt();
			$charlength++;
			if(strlen($excerpt) > $charlength) {
				$subex = substr($excerpt, 0, $charlength-5);
				$exwords = explode(" ", $subex);
				$excut = -(strlen($exwords[count($exwords)-1]));
				$result = ($excut < 0) ? substr($subex, 0, $excut) : $subex;
				$result .= "...";
			} else {
				$result =  $excerpt;
			}
			return $result;
		}

		function limit_words_length($args = array()) {
			$default = array(
				'word_limit' => '', 
				'post_obj' => '', 
				'strip_tags' => true
			);
			extract(wp_parse_args($args, $default));
			$post_id = ( $post_obj && is_object($post_obj) ) ? $post_obj->ID : get_the_ID();
			$excerpt = (is_home()) ? apply_filters('the_content', get_the_content($post_id)) : get_the_excerpt($post_id);
			
			if (!is_home()) {
				$excerpt = ( !$word_limit || $word_limit == '-1' ) ? $excerpt : wp_trim_words($excerpt, $word_limit, '...') ;
				$excerpt = esc_html( $excerpt );
				if( $strip_tags ){
					$excerpt = wp_strip_all_tags($excerpt);
					$excerpt = strip_shortcodes($excerpt);
				}
			}
			if(is_home()){
				echo do_shortcode($excerpt);
				/**
				 * wd_hook_page_link hook.
				 *
				 * @hooked display_blog_page_link - 5
				 */
				do_action('wd_hook_page_link');
			}else{
				echo $excerpt;
			}
		}
		
		//Pad a string to a certain length with another string
		function number_fix_length($setting) {
			$default = array(
				'number' => '0', 
				'length' => '2',
				'character' => '0'
			);
			extract(wp_parse_args($setting, $default));
			return ($number == '0') ? $number : str_pad($number, $length, $character, STR_PAD_LEFT);
		}
	}
	WD_Excerpt::get_instance();  // Start an instance of the plugin class 
}