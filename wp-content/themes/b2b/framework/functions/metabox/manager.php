<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

/**
 * Usage : 
 * $result = apply_filters('wd_filter_post_layout_config', $post_id); //Get post layout settings
 * $result = apply_filters('wd_filter_taxonomy_layout_config', $taxonomy_id); //Get taxonomy / category layout setting
 */
if (!class_exists('WD_Metaboxes')) {
	class WD_Metaboxes{
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

			$this->constants();

			$this->load_libs();

			add_action("add_meta_boxes", array($this, "generate_customfields"));
			add_action('pre_post_update', array($this, 'save_customfield'), 10, 2);
			$this->category_configuration();

			add_action('admin_enqueue_scripts', array($this, 'load_script_style'));

			//Get layout config
			add_filter( 'wd_filter_post_layout_config', array($this, 'post_layout_config' ), 10, 2);
			add_filter( 'wd_filter_taxonomy_layout_config', array($this, 'taxonomy_layout_config' ), 10, 2);
		}

		public function constants(){
			if (!defined('WD_METABOX_JS')) define('WD_METABOX_JS'			, WD_THEME_METABOX_URI . '/js');
			if (!defined('WD_METABOX_CSS')) define('WD_METABOX_CSS'			, WD_THEME_METABOX_URI . '/css');
			if (!defined('WD_METABOX_IMAGES')) define('WD_METABOX_IMAGES'	, WD_THEME_METABOX_URI . '/images');
			if (!defined('WD_METABOX_LAYOUT')) define('WD_METABOX_LAYOUT'	, WD_THEME_METABOX . '/layout');
			if (!defined('WD_METABOX_LIBS')) define('WD_METABOX_LIBS'		, WD_THEME_METABOX . '/libs');
		}

		public function load_script_style(){
			/*----------------- Style ---------------------*/
			wp_enqueue_style('jquery-ui-core');
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_style('wd-custom-meta-box-css', 	WD_METABOX_CSS .'/wd-admin.css');
			/*----------------- Script ---------------------*/
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_script('wd-media-js', 			WD_METABOX_JS .'/wd_media.js', array('jquery', 'wp-color-picker'), false, true);
			wp_enqueue_script('wd-custom-meta-box-js', 	WD_METABOX_JS .'/wd_custom_post_layout.js', false, false, true);
		}

		public function generate_customfields(){
			$list_meta_box = array(
				//Blog, page, product layout...
				array(
					'id' 		=> 'wp_cp_custom_post_layout',
					'title'		=> esc_html__("LAYOUT CONFIGURATION", 'feellio'),
					'callback' 	=> array($this, "layout_configuration"),
					'page'		=> array('post', 'product', 'page'),
					'context'	=> 'side',
					'priority'	=> 'high',
				),
				//HTML Block
				array(
					'id' 		=> 'wp_cp_custom_post_layout',
					'title'		=> esc_html__("CUSTOM CLASS/ID", 'feellio'),
					'callback' 	=> array($this, "class_id_configuration"),
					'page'		=> array('wd_header', 'wd_footer'),
					'context'	=> 'side',
					'priority'	=> 'high',
				),
			);
			foreach ($list_meta_box as $meta_box) {
				add_meta_box($meta_box['id'], $meta_box['title'], $meta_box['callback'], $meta_box['page'], $meta_box['context'], $meta_box['priority']);
				foreach ($meta_box['page'] as $sceen) {
					add_filter( "postbox_classes_{$sceen}_{$meta_box['id']}", array($this,"meta_box_custom_class") );
				}
			}
		}

		public function load_libs(){
			global $pagenow;
			if(file_exists(WD_METABOX_LIBS.'/Tax-meta-class/Tax-meta-class.php') && $pagenow != 'nav-menus.php'){
				require_once WD_METABOX_LIBS.'/Tax-meta-class/Tax-meta-class.php';
			}
		}

		public function class_id_configuration(){
			if(file_exists(WD_METABOX_LAYOUT.'/class_id.php')){
				require_once WD_METABOX_LAYOUT.'/class_id.php';
			}
		}

		public function layout_configuration(){
			if(file_exists(WD_METABOX_LAYOUT.'/layout.php')){
				require_once WD_METABOX_LAYOUT.'/layout.php';
			}
		}

		public function category_configuration(){
			if(file_exists(WD_METABOX_LAYOUT.'/category.php')){
				require_once WD_METABOX_LAYOUT.'/category.php';
			}
		}

		// Save Custom
		public function save_customfield($post_id){
			// Bail if we're doing an auto save
		    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		     
		    // if our nonce isn't there, or we can't verify it, bail
		    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
		     
		    // if our current user can't edit this post, bail
		    //if( !current_user_can( 'edit_post' ) ) return;
			/*--------------------------------------------------------------------------*/
			/*						 SAVE CUSTOM POST/PAGE/PRODUCT LAYOUT 				*/
			/*--------------------------------------------------------------------------*/
			// Save post custom layout & sidebar and Media
			if (isset($_POST['wd_custom_header'])){
				update_post_meta($post_id, '_wd_custom_header', $_POST['wd_custom_header']);
			}
			// Footer
			if (isset($_POST['wd_custom_footer'])){
				update_post_meta($post_id, '_wd_custom_footer', $_POST['wd_custom_footer']);
			}

			$layout 			= isset($_POST['wd_custom_layout']) ? $_POST['wd_custom_layout'] : '';
			$left_sidebar 		= isset($_POST['wd_custom_left_sidebar']) ? $_POST['wd_custom_left_sidebar'] : '';
			$right_sidebar 		= isset($_POST['wd_custom_right_sidebar']) ? $_POST['wd_custom_right_sidebar'] : '';
			$style_breadcrumb 	= isset($_POST['wd_custom_breadcrumb_style']) ? $_POST['wd_custom_breadcrumb_style'] : '';
			$image_breadcrumb 	= isset($_POST['wd_custom_breadcrumb_image']) ? $_POST['wd_custom_breadcrumb_image'] : '';
			$custom_class 		= isset($_POST['wd_custom_class']) ? $_POST['wd_custom_class'] : '';
			$custom_id 			= isset($_POST['wd_custom_id']) ? $_POST['wd_custom_id'] : '';

			$_layout_config = array(
				'layout' 				=> $layout,
				'left_sidebar' 			=> $left_sidebar,
				'right_sidebar' 		=> $right_sidebar,
				'style_breadcrumb'		=> $style_breadcrumb,
				'image_breadcrumb'		=> $image_breadcrumb,
				'custom_class'			=> $custom_class,
				'custom_id'				=> $custom_id,
			);
			update_post_meta($post_id, '_wd_custom_layout_config', $_layout_config);	
		}

		/*--------------------------------------------------------------------------*/
		/*						 			FUNCTIONS 								*/
		/*--------------------------------------------------------------------------*/
		// Get post layout settings
		public function post_layout_config($post_id = '') {
			global $post;
			$post_id = ($post_id) ? $post_id : get_the_ID();
			//Config Page
			$_layout_config 		= get_post_meta($post_id, '_wd_custom_layout_config', true);

			$_default_layout_config = array(
					'layout' 					=> '0',
					'left_sidebar' 				=> 'left_sidebar',
					'right_sidebar' 			=> 'right_sidebar',
					'style_breadcrumb'			=> '0',
					'image_breadcrumb'			=> WD_THEME_IMAGES.'/banner_breadcrumb.jpg',			
					'custom_class'				=> '',		
					'custom_id'					=> '',		
			);
			
			if( is_array($_layout_config)){
				foreach ($_default_layout_config as $key => $value) {
					$_layout_config[$key] 	= ( isset($_layout_config[$key]) && strlen($_layout_config[$key]) > 0 ) ? $_layout_config[$key] : $_default_layout_config[$key];
				}
			}else{
				$_layout_config = $_default_layout_config;
			}
			return $_layout_config;
		}

		// Get taxonomy / category layout setting
		public function taxonomy_layout_config($taxonomy_id = ''){
			$custom_data 	= array();
			$taxonomy_id = ($taxonomy_id) ? $taxonomy_id : get_queried_object_id();

			if (!is_archive() || !$taxonomy_id) return array();
			$term_meta 		= get_term_meta( $taxonomy_id );
			if (isset($term_meta['custom_layout']) && $term_meta['custom_layout'][0] != '0') {
				$custom_data['layout'] 	= $term_meta['custom_layout'][0];
			}
			if (isset($term_meta['custom_content']) && $term_meta['custom_content'][0] != '') {
				$custom_data['custom_content'] 	= $term_meta['custom_content'][0];
			}
			return $custom_data;
		}


		// Get list sidebar choices
		public function get_list_sidebar_choices($value_default = '') {
			global $wp_registered_sidebars;
			$arr_sidebar = ($value_default != '') ? array('0' => $value_default) : array();
			if (count($wp_registered_sidebars) > 0) {
				foreach ( $wp_registered_sidebars as $sidebar ){
					$arr_sidebar[$sidebar['id']] = $sidebar['name'];
				}
			}
			return $arr_sidebar;
		}

		//Add class closed to metabox
		public function meta_box_custom_class( $classes ) {
			array_push( $classes, 'closed' );
			return $classes;
		}

		// Fields template
		public function get_header($html_header = '', $selected = 0){
			$html_header = (is_array($html_header)) ? $html_header : apply_filters('wd_filter_header_choices', array('value_return' => 'name')); ?>

			<div id="wd_custom_header_wrap">
				<p><strong><?php esc_html_e('Custom Header: ', 'feellio') ?></strong></p>
				<select name="wd_custom_header" id="wd_custom_header">
					<?php foreach ($html_header as $id => $title): ?>
						<?php $selected_html = selected($selected, $id, false); ?>
						<option value="<?php echo esc_html($id) ?>" <?php echo esc_attr($selected_html) ?>><?php echo esc_html($title) ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<?php if ($selected && is_numeric($selected)): ?>
					<a target="_blank" href="<?php echo (get_the_permalink( $selected ) ); ?>"><i class="fa fa-eye wd-icon" aria-hidden="true"></i> <?php esc_html_e('View Template', 'feellio') ?></a> | <a target="_blank" href="<?php echo (get_edit_post_link( $selected ) ); ?>"><i class="fa fa-pencil-square wd-icon" aria-hidden="true"></i> <?php esc_html_e('Edit Template', 'feellio') ?></a>
			<?php endif ?>

			<?php 
		}

		public function get_footer($html_footer = '', $selected = 0){
			$html_footer = (is_array($html_footer)) ? $html_footer : apply_filters('wd_filter_footer_choices', array('value_return' => 'name')); ?>

			<div id="wd_custom_footer_wrap">
				<p><strong><?php esc_html_e('Custom Footer: ', 'feellio') ?></strong></p>
				<select name="wd_custom_footer" id="wd_custom_footer">
					<?php foreach ($html_footer as $id => $title): ?>
						<?php $selected_html = selected($selected, $id, false); ?>
						<option value="<?php echo esc_html($id) ?>" <?php echo esc_attr($selected_html) ?>><?php echo esc_html($title) ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<?php if ($selected && is_numeric($selected)): ?>
					<a target="_blank" href="<?php echo (get_the_permalink( $selected ) ); ?>"><i class="fa fa-eye wd-icon" aria-hidden="true"></i> <?php esc_html_e('View Template', 'feellio') ?></a> | <a target="_blank" href="<?php echo (get_edit_post_link( $selected ) ); ?>"><i class="fa fa-pencil-square" aria-hidden="true"></i> <?php esc_html_e('Edit Template', 'feellio') ?></a>
			<?php endif ?>

			<?php 
		}

		public function get_layout($selected = 0){
			$layout = array(
				'0'			=> esc_html__('Default', 'feellio'),
				'0-0-0'		=> esc_html__('Fullwidth', 'feellio'),
				'1-0-0'		=> esc_html__('Left Sidebar', 'feellio'),
				'0-0-1'		=> esc_html__('Right Sidebar', 'feellio'),
				'1-0-1'		=> esc_html__('Left & Right Sidebar', 'feellio'),
			); ?>

			<div id="wd_custom_layout_wrap">
				<p><strong><?php esc_html_e('Layout:', 'feellio'); ?></strong></p>
				<select name="wd_custom_layout" id="wd_custom_layout">
					<?php foreach ($layout as $id => $title): ?>
						<?php $selected_html = selected($selected, $id, false); ?>
						<option value="<?php echo esc_html($id) ?>" <?php echo esc_attr($selected_html) ?>><?php echo esc_html($title) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php 
		}

		public function get_sidebar($position = 'left', $selected = 'left_sidebar'){
			$sidebar = $this->get_list_sidebar_choices(); ?>
			
			<?php if ($position == 'left'): ?>
				<div id="wd_custom_left_sidebar_wrap">
					<p><strong><?php esc_html_e('Left Sidebar:', 'feellio'); ?></strong></p>
					<select name="wd_custom_left_sidebar" id="wd_custom_left_sidebar">
						<?php foreach ($sidebar as $id => $title): ?>
							<?php $selected_html = selected($selected, $id, false); ?>
							<option value="<?php echo esc_html($id) ?>" <?php echo esc_attr($selected_html) ?>><?php echo esc_html($title) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php else: ?>
				<div id="wd_custom_right_sidebar_wrap">
					<p><strong><?php esc_html_e('Right Sidebar:', 'feellio'); ?></strong></p>
					<select name="wd_custom_right_sidebar" id="wd_custom_right_sidebar">
						<?php foreach ($sidebar as $id => $title): ?>
							<?php $selected_html = selected($selected, $id, false); ?>
							<option value="<?php echo esc_html($id) ?>" <?php echo esc_attr($selected_html) ?>><?php echo esc_html($title) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif ?>
			<?php 
		}

		public function get_breadcrumb_style($selected = 'breadcrumb_default'){
			$breadcrumb_style = array(
				'breadcrumb_default'	=> esc_html__('Default (Customize)', 'feellio'),
				'breadcrumb_banner'		=> esc_html__('Background Image', 'feellio'),
				'no_breadcrumb'			=> esc_html__('No Breadcrumb', 'feellio'),
			); ?>
			
			<div id="wd_custom_breadcrumb_style_wrap">
				<p><strong><?php esc_html_e('Breadcrumb Style:', 'feellio'); ?></strong></p>
				<select name="wd_custom_breadcrumb_style" id="wd_custom_breadcrumb_style">
					<?php foreach ($breadcrumb_style as $id => $title): ?>
						<?php $selected_html = selected($selected, $id, false); ?>
						<option value="<?php echo esc_html($id) ?>" <?php echo esc_attr($selected_html) ?>><?php echo esc_html($title) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php 
		}

		public function get_breadcrumb_image($selected = '', $default = ''){ ?>
			<div id="wd_custom_breadcrumb_image_wrap">
				<p><strong><?php esc_html_e('Image Breadcrumb:', 'feellio'); ?></strong></p>
				<p> 
					<img id="wd_custom_breadcrumb_image_view" src="<?php echo ($selected && is_numeric($selected)) ? esc_url(wp_get_attachment_url($selected)) : $default; ?>"  width="100%" />
					<input type="hidden" name="wd_custom_breadcrumb_image" id="wd_custom_breadcrumb_image" value="<?php echo ($selected && is_numeric($selected)) ? esc_attr($selected ) : ''; ?>" />

					<a 	class="wd_media_lib_select_btn button button-primary button-large" 
						data-image_value="wd_custom_breadcrumb_image" 
						data-image_preview="wd_custom_breadcrumb_image_view"><?php esc_html_e('Select Image', 'feellio'); ?></a>

					<a 	class="wd_media_lib_clear_btn button" 
						data-image_value="wd_custom_breadcrumb_image" 
						data-image_preview="wd_custom_breadcrumb_image_view" 
						data-image_default="<?php echo esc_url($default); ?>"><?php esc_html_e('Reset', 'feellio'); ?></a>
				</p>
			</div>
			<?php 
		}


		public function get_breadcrumb_color($selected = '', $default = ''){ ?>
			<div id="wd_custom_breadcrumb_color_wrap">
				<p><strong><?php esc_html_e('Color Breadcrumb:', 'feellio'); ?></strong></p>
				<p> 
					<input type="text" class="wd_colorpicker_select" name="wd_breadcrumb_background_color" id="wd_breadcrumb_background_color"  value="<?php echo ($selected) ? $selected : $default; ?>"/>
				</p>
			</div>
			<?php 
		}

		public function get_custom_class($selected = '', $custom_class = ''){ ?>
			<div id="wd_custom_class_wrap" class="<?php echo esc_attr( $custom_class ); ?>">
				<p><strong><?php esc_html_e('Custom Classes:', 'feellio'); ?></strong></p>
				<p><input type="text" name="wd_custom_class" id="wd_custom_class" value="<?php echo $selected; ?>" /></p>
			</div>
			<?php 
		}

		public function get_custom_id($selected = '', $custom_class = ''){ ?>
			<div id="wd_custom_id_wrap" class="<?php echo esc_attr( $custom_class ); ?>">
				<p><strong><?php esc_html_e('Custom ID:', 'feellio'); ?></strong></p>
				<p><input type="text" name="wd_custom_id" id="wd_custom_id" value="<?php echo $selected; ?>" /></p>
			</div>
			<?php 
		}
	}
	WD_Metaboxes::get_instance();
} ?>