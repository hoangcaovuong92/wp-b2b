<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */
 
if (!class_exists('WD_HTML_Block')) {
	class WD_HTML_Block {
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
			
			// do_action('wd_hook_header_init_action') //Display header
			add_action( 'wd_hook_header_init_action', array($this, 'html_header_init'), 5 );

			// do_action('wd_hook_header_mobile') //Display header mobile
			add_action( 'wd_hook_header_mobile', array($this, 'html_header_mobile_init'), 5 );

			// do_action('wd_hook_footer_init_action') //Display footer
			add_action( 'wd_hook_footer_init_action', array($this, 'html_footer_init'), 5 );

			// $result = apply_filters('wd_filter_header_choices', array('value_default' => $value_default, 'value_return' => $value_return)); //Get header choices
			add_filter( 'wd_filter_header_choices', array($this, 'get_header_choices' ), 10, 2);
			
			// $result = apply_filters('wd_filter_footer_choices', array('value_default' => $value_default, 'value_return' => $value_return)); //Get footer choices
			add_filter( 'wd_filter_footer_choices', array($this, 'get_footer_choices' ), 10, 2);

			//$style = apply_filters('wd_filter_html_block_style', true);
			add_filter( 'wd_filter_html_block_style', array($this, 'htmlblock_vc_styles'), 10, 2);
		}

		/** Get custom css from html block */
		/**
		 * Add Visual Composer custom css styles of HTML Blocks
		 *
		 * Visual Composer only includes css style of the main post, so we have
		 * to add custom css styles of HTML blocks by ourself.
		 */
		public function htmlblock_vc_styles() {
			$custom_css = '';
			if ($this->get_html_block_id('header')){
				$custom_css .= $this->htmlblock_css($this->get_html_block_id('header'));
			}
				
			if ($this->get_html_block_id('footer')){
				$custom_css .= $this->htmlblock_css($this->get_html_block_id('footer'));
			}
			return $custom_css;
		}
		
		/* Get Custom CSS */
		/**
		 * public Function add custom CSS of HTML Block in the head element
		 *
		 * @param integer $post_id Post ID
		 * @return string CSS to add to the head tag
		 */
		public function htmlblock_css($post_id) {
			$custom_css = '';
			/** code copied from Vc_Base::addPageCustomCss() */
			$post_custom_css = get_post_meta( $post_id, '_wpb_post_custom_css', true );
			if ( ! empty( $post_custom_css ) )
				$custom_css .= $post_custom_css;
			
			/** code copied from Vc_Base::addShortcodesCustomCss() */
			$shortcodes_custom_css = get_post_meta( $post_id, '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $shortcodes_custom_css ) ) {
				$custom_css .= $shortcodes_custom_css;
			}
			
			return $custom_css;
		}

		// Get Header HTML Block choices
		// value_return = name/image
		public function get_header_choices($setting = array()) {
			$default = array(
				'value_default' => esc_html__('General Header', 'feellio'), 
				'value_return' => 'name', 
			);
			extract(wp_parse_args($setting, $default));
			return $this->get_html_block_choices(array(
				'post_type' => 'wd_header', 
				'value_default' => $value_default, 
				'value_return' => $value_return,
				'addtional_data' => $this->get_list_header_template_file()
			));
		}

		// Get Footer HTML Block choices
		// value_return = name/image
		public function get_footer_choices($setting = array()) {
			$default = array(
				'value_default' => esc_html__('General Footer', 'feellio'), 
				'value_return' => 'name', 
			);
			extract(wp_parse_args($setting, $default));
			return $this->get_html_block_choices(array(
				'post_type' => 'wd_footer', 
				'value_default' => $value_default, 
				'value_return' => $value_return,
				'addtional_data' => $this->get_list_footer_template_file()
			));
		}

		//Create template file at framework/layout/headers/templates with file name: header_style-{number_1_to_10}.php;
		public function get_list_header_template_file() {
			$list_template_file = array();
			for ($i=0; $i <= 10; $i++) { 
				$file_name = 'header_style-'.$i;
				$template_name = sprintf(esc_html__('Template file - style %d', 'feellio'), $i);
				if($this->check_template_exist($file_name, 'header')){
					$list_template_file[$file_name] = $template_name;
				}
			}
			return $list_template_file;
		}

		//Create template file at framework/layout/footers/templates with file name: footer_style-{number_1_to_10}.php;
		public function get_list_footer_template_file() {
			$list_template_file = array();
			for ($i=0; $i <= 10; $i++) {
				$file_name = 'footer_style-'.$i;
				$template_name = sprintf(esc_html__('Template file - Style %d', 'feellio'), $i);
				if($this->check_template_exist($file_name, 'footer')){
					$list_template_file[$file_name] = $template_name;
				}
			}
			return $list_template_file;
		}

		//Check file template exist
		//Return file url if file exist
		public function check_template_exist($file_name, $choose = 'header') {
			$file_url = ($choose === 'header') ?
						WD_THEME_LAYOUT. "/headers/templates/{$file_name}.php" :
						WD_THEME_LAYOUT. "/footers/templates/{$file_name}.php";

			return file_exists($file_url) ? $file_url : false;
		}

		// Get HTML Block choices
		// value_return = name/image
		// post_type = wd_header/wd_footer
		public function get_html_block_choices($setting = array()) {
			$default = array(
				'post_type' => 'wd_header', 
				'value_default' => '', 
				'value_return' => 'name', 
				'addtional_data' => array(), 
			);
			extract(wp_parse_args($setting, $default));
			$choices 	= ($value_default != '') ? array('' => $value_default) : array();
			global $post;
			$pre_post 	= $post;
			$args = array(
				'post_type' 	=> $post_type,
				'posts_per_page'=> -1,
				'orderby' 		=> 'post_title',
				'order' 		=> 'ASC',
			);
			$html_block = new WP_Query( $args );

			while ($html_block->have_posts()) {
				$html_block->the_post();
				if($value_return == 'image'){
					$choices[get_the_ID()] = (wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()))) 
											? wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())) 
											: WD_THEME_IMAGES.'/headers/wd_header_no_image.jpg';
				}else{
					$choices[get_the_ID()] = esc_html__('HTML Block - ', 'feellio') . get_the_title();
				}
			}
			wp_reset_postdata();
			$post 		= $pre_post;
			
			if($value_return == 'name' && count($addtional_data) > 0){
				$choices = array_replace_recursive($choices, $addtional_data);
			}
			return $choices;
		}

		/* Get ID of custom HEADER/FOOTER */
		public function get_html_block_id($choose = 'header') { 
			$meta_key 			= ($choose == 'header') ? '_wd_custom_header' : '_wd_custom_footer';
			$theme_option_key 	= ($choose == 'header') ? 'wd_header_layout' : 'wd_footer_layout';
			$theme_option_value = apply_filters('wd_filter_get_option_value', $theme_option_key);
			$current_page_id = (is_archive()) ? get_queried_object_id() : get_the_ID();

			$custom_html_block_id = (is_archive()) 
									? get_term_meta( $current_page_id, $meta_key, true ) 
									: get_post_meta($current_page_id, $meta_key , true);
											
			if ((is_search() || !$current_page_id || !$custom_html_block_id) && !$theme_option_value)  return;

			return (!$custom_html_block_id) ? $theme_option_value : $custom_html_block_id;
		}

		/* Get HTML Content */
		/**
		 * Return the content of HTML Block
		 * @return string
		 */
		public function get_html_content($choose = 'header') {
			global $post;
			$pre_post 	= $post;
			$template_id = $this->get_html_block_id($choose);
			$cur_post 	= ($template_id) ? get_post($template_id) : '';
			if (!($cur_post)) return;
			$post 		= $cur_post;
			$content 	= apply_filters('the_content', $cur_post->post_content);
			$post 		= $pre_post;
			return $content;
		}

		/* Load Header HTML */
		public function html_header_init(){
			$header_id 			= $this->get_html_block_id('header');
			$content_header 	= $this->get_html_content('header');
			$class_id_config 	= apply_filters('wd_filter_post_layout_config', $header_id);

			if($this->check_template_exist($header_id, 'header')){
				$template_file = $this->check_template_exist($header_id, 'header');
				require_once $template_file;
			}else if(!(empty($content_header))){ ?>
				<div class="container">
					<div class="wd-header-content <?php echo esc_attr( $class_id_config['custom_class'] ); ?>" 
						id="<?php echo esc_attr( $class_id_config['custom_id'] ); ?>">
						<?php echo ($content_header); ?>
					</div>
				</div>
			<?php } else {
				if(file_exists(WD_THEME_LAYOUT. "/headers/header_default.php")){
					require_once WD_THEME_LAYOUT. "/headers/header_default.php";
				}	
			}
		}

		/* Load header mobile content from file */
		public function html_header_mobile_init(){
			if(file_exists(WD_THEME_LAYOUT. "/headers/header_mobile.php")){
				require_once WD_THEME_LAYOUT. "/headers/header_mobile.php";
			}
		}

		/* Load Footer HTML */
		public function html_footer_init(){
			$footer_id 			= $this->get_html_block_id('footer');
			$content_footer 	= $this->get_html_content('footer');
			$class_id_config 	= apply_filters('wd_filter_post_layout_config', $footer_id);

			if($this->check_template_exist($footer_id, 'footer')){
				$template_file = $this->check_template_exist($footer_id, 'footer');
				require_once $template_file;
			}else if(!(empty($content_footer))){ ?>
				<div class="container">
					<div class="wd-footer-content <?php echo esc_attr( $class_id_config['custom_class'] ); ?>" 
						id="<?php echo esc_attr( $class_id_config['custom_id'] ); ?>">
						<?php echo $content_footer; ?>
					</div>
				</div>
			<?php }else{
				if(file_exists(WD_THEME_LAYOUT. "/footers/footer_default.php")){
					require_once WD_THEME_LAYOUT. "/footers/footer_default.php";
				}	
			}
		}
	}
	WD_HTML_Block::get_instance();  // Start an instance of the plugin class 
} 