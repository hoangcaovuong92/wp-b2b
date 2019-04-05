<?php
if (!class_exists('WD_Optimize')) {
	class WD_Optimize {
		/**
		 * Refers to a single instance of this class.
		 */
		private static $instance = null;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct(){
			$this->do_gutenberg_settings();
			$this->do_optimize_settings();
		}
		
		//Gutenberg settings
		public function do_gutenberg_settings(){
			// Disable Gutenberg
			if (version_compare($GLOBALS['wp_version'], '5.0-beta', '>')) {
				// WP > 5 beta
				add_filter('use_block_editor_for_post_type', array($this, 'disable_gutenberg'), 10, 2);
			} else {
				// WP < 5 beta
				add_filter('gutenberg_can_edit_post_type', array($this, 'disable_gutenberg'), 10, 2);
			}
		}

		public function disable_gutenberg($is_enabled, $post_type) {
			$options = get_option('wd_packages');
			$disable_list = isset($options['wd_disable_gutenberg']) ? array_keys($options['wd_disable_gutenberg']) : array();
			return (in_array($post_type, $disable_list)) ? false : $is_enabled;
		}

		//Optimize Settings
		public function do_optimize_settings(){
			$options = get_option('wd_packages');
			$auto_save_image = isset($options['wd_auto_save_image']) ? $options['wd_auto_save_image'] : false;
			$auto_set_featured_image = isset($options['wd_auto_set_featured_image']) ? $options['wd_auto_set_featured_image'] : false;
			$compress_html = isset($options['wd_compress_html']) ? $options['wd_compress_html'] : false;
			$disable_compress_image = isset($options['wd_disable_compress_image']) ? $options['wd_disable_compress_image'] : false;
			$disable_wpautop = isset($options['wd_disable_wpautop']) ? $options['wd_disable_wpautop'] : false;
			$disable_remove_version = isset($options['wd_disable_remove_version']) ? $options['wd_disable_remove_version'] : false;
			$disable_all_default_widgets = isset($options['wd_disable_all_default_widgets']) ? $options['wd_disable_all_default_widgets'] : false;
			$disable_all_widgets = isset($options['wd_disable_all_widgets']) ? $options['wd_disable_all_widgets'] : false;
			//$html_permalink = isset($options['wd_html_permalink']) ? $options['wd_html_permalink'] : false;

			if ($auto_save_image) {
				if(file_exists(WD_PACKAGE_INCLUDE."/auto_save_img.php")){
					require_once WD_PACKAGE_INCLUDE."/auto_save_img.php";
				}
			}

			if ($auto_set_featured_image) {
				add_action('save_post', array($this, 'auto_set_post_image'));
			}

			if ($compress_html) {
				if(file_exists(WD_PACKAGE_INCLUDE."/compress_html.php")){
					require_once WD_PACKAGE_INCLUDE."/compress_html.php";
				}
			}

			if (!$disable_compress_image) {
				if(file_exists(WD_PACKAGE_INCLUDE."/compress_image.php")){
					require_once WD_PACKAGE_INCLUDE."/compress_image.php";
				}
			}

			if (!$disable_wpautop) {
				remove_filter('the_content', 'wpautop'); //Gutenberg editor
				add_filter('tiny_mce_before_init', array($this, 'remove_auto_p_tinymce')); // Classic editor
			}
			
			// if ($html_permalink) {
			// 	$this->add_html_to_page_permalink();
			// 	//add_action('init', array($this, 'add_html_to_page_permalink'), -1);
			// 	add_filter('user_trailingslashit', array($this, 'nopage_slash'), 66, 2);
			// }

			if (!$disable_remove_version) {
				add_filter('the_generator', array($this, 'remove_version'));
				add_filter('admin_footer_text', array($this, 'change_footer_admin'), 9999);
				add_filter('update_footer', array($this, 'change_footer_version'), 9999);
				remove_action('wp_head', 'wp_generator');
				add_filter('the_generator', '__return_empty_string');
				add_filter('style_loader_src', array($this, 'remove_version_scripts_styles'), 9999);
				add_filter('script_loader_src', array($this, 'remove_version_scripts_styles'), 9999);
			}

			if ($disable_all_default_widgets) {
				add_action('widgets_init', array($this, 'unregister_default_widgets'), 11);
			}

			if ($disable_all_widgets) {
				add_filter('sidebars_widgets', array($this, 'disable_all_widgets'));
			}
		}

		//AUTO SET FEATURED IMAGE
		public function set_featured_img_content($post_id){
			if(isset($post_id)) {
				$content_post = get_post($post_id);
				$content = $content_post->post_content;
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
				$filename = isset($matches[1][0]) ? $matches[1][0] : false;
				return $filename;
			}
		}

		public function set_featured_image_on_save($post_id){
			$attachments = get_posts(array('numberposts' => '1', 'post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC'));
			if(sizeof($attachments) > 0){
				set_post_thumbnail($post_id, $attachments[0]->ID);
			}else{
				require_once(ABSPATH . 'wp-admin/includes/file.php');
				require_once(ABSPATH . 'wp-admin/includes/media.php');
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$img_featured  = $this->set_featured_img_content($post_id);
				if ($img_featured){
					$result = media_sideload_image($img_featured, $post_id);
					$attachments = get_posts(array('numberposts' => '1', 'post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC'));
					if(sizeof($attachments) > 0)
						set_post_thumbnail($post_id, $attachments[0]->ID);
				}else{
					return;
				}
			}
		}

		public function auto_set_post_image( $post_id ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
				  return;
			$attch_featured = get_post_meta($post_id,"_thumbnail_id",true);
			if (empty($attch_featured)){
				$this->set_featured_image_on_save($post_id);
			}
		}

		//REMOVE WPAUTOP
		public function remove_auto_p_tinymce($in) {
			$in['forced_root_block'] = "";
			//$in['force_br_newlines'] = false;
			$in['force_p_newlines'] = true;
			return $in;
		}


		//REWRITE PAGE SLUG - ADD HTML TO THE END OF PERMALINK
		public function add_html_to_page_permalink() {
			global $wp_rewrite;
		 	if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')){
				$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
		 	}
		}

		public function nopage_slash($string, $type){
			global $wp_rewrite;
			 if ($wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == true && $type == 'page'){
				return untrailingslashit($string);
		   	}else{
				return $string;
		   	}
		}

		//REMOVE VERSION
		public function remove_version(){
			return '';
		}
		
		public function change_footer_admin () {
			return ' ';
		}

		public function change_footer_version() {
			return ' ';
		}

		public function remove_version_scripts_styles($src) {
			if (strpos($src, 'ver=')) {
				$src = remove_query_arg('ver', $src);
			}
			return $src;
		}

		//SIDEBAR
		//Disabled all widgets
		public function disable_all_widgets($sidebars_widgets) {
			$sidebars_widgets = array( false );
			return $sidebars_widgets;
		}

		//Unregister default wordpress widgets
		public function unregister_default_widgets() {
			unregister_widget('WP_Widget_Pages');
			unregister_widget('WP_Widget_Calendar');
			unregister_widget('WP_Widget_Archives');
			unregister_widget('WP_Widget_Links');
			unregister_widget('WP_Widget_Meta');
			unregister_widget('WP_Widget_Search');
			unregister_widget('WP_Widget_Text');
			unregister_widget('WP_Widget_Categories');
			unregister_widget('WP_Widget_Recent_Posts');
			unregister_widget('WP_Widget_Recent_Comments');
			unregister_widget('WP_Widget_RSS');
			unregister_widget('WP_Widget_Tag_Cloud');
			unregister_widget('WP_Nav_Menu_Widget');
			unregister_widget('Twenty_Eleven_Ephemera_Widget');
		}
	}
	WD_Optimize::get_instance();  // Start an instance of the plugin class 
}

