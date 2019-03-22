<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Main')) {
	class WD_Main {
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
			
			//Wrap content
			// do_action('wd_hook_before_main_content');
			add_action('wd_hook_before_main_content', array($this, 'before_main_content_wrapper') ,10);
			// do_action('wd_hook_after_main_content');
			add_action('wd_hook_after_main_content', array($this, 'after_main_content_wrapper') ,10);

			// $content_class = apply_filters('wd_filter_content_class_by_layout', $layout);
			add_filter( 'wd_filter_content_class_by_layout', array($this, 'get_content_class_by_layout' ), 10, 2);

			// image HTML
			// echo apply_filters('wd_filter_image_html', array('attachment' => '', 'image_size' => '', 'alt' 	=> get_bloginfo( 'name', 'display' ), 'title' => get_bloginfo( 'name', 'display' ), 'class'	=> ''));
			add_filter( 'wd_filter_image_html', array($this, 'image_html' ), 10, 2);

			//Display logo
			//echo apply_filters('wd_filter_logo', array('logo_url' => $logo_url, 'logo_default' => $logo_default, 'show_logo_title' => $show_logo_title, 'width' => '', 'custom_class' => ''));
			add_filter( 'wd_filter_logo', array($this, 'main_logo' ), 10, 2);

			//Display copyright section
			//echo apply_filters('wd_filter_copyright', array('list_item' => $list_item, 'custom_class' => $custom_class));
			add_filter( 'wd_filter_copyright', array($this, 'copyright' ), 10, 2);

			//Display youtube video
			//echo apply_filters('wd_filter_youtube_video', array('url' => '', 'width' => 600, 'height' => 400));
			add_filter( 'wd_filter_youtube_video', array($this, 'youtube_video' ), 10, 2);

			//Get global post id
			//$post_id = apply_filters('wd_filter_global_post_id', $current_post); 
			add_filter( 'wd_filter_global_post_id', array($this, 'get_global_post_id' ), 10, 2);
		}

		// HTML before main content
		public function before_main_content_wrapper(){ ?>
			<?php $woo_class = (wd_is_woocommerce()) ? 'woocommerce' : ''; ?>
			<div id="wd-main-content-wrap" class="wd-main-content-wrap <?php //echo esc_attr( $woo_class ); ?>">
				<div class="container">
					<div class="row">
		<?php }

		// HTML after main content
		public function after_main_content_wrapper(){ ?>
					</div><!-- End row -->
				</div><!-- End container -->
			</div><!-- End main-content -->
		<?php }

		// $layout : 1-0-0 / 0-0-1 / 1-0-1 / leave blank = fullwidth
		public function get_content_class_by_layout($layout = ''){ 
			if( ($layout == '1-0-0') || ($layout == '0-0-1') ){
				$content_class = "col-md-18 col-sm-24 wd-layout-1-sidebar";
			}elseif($layout == '1-0-1'){
				$content_class = "col-md-12 col-sm-24 wd-layout-2-sidebar";
			}else{
				$content_class = "col-md-24 wd-layout-fullwidth";
			} 
			return $content_class;				
		}

		// return image html from image url or attachment id
		public function image_html($args = array()){ 
			$default = array(
				'attachment' => '', //id or url of leave blank to get demo image
				'image_size' => 'full',
				'alt' 	=> get_bloginfo( 'name', 'display' ), 
				'title' => get_bloginfo( 'name', 'display' ),
				'class'	=> '',
			);
			extract(wp_parse_args($args, $default));
			$image_url = '';
			if ($attachment) {
				if (is_numeric($attachment)) {
					$image_url 	= wp_get_attachment_image_src($attachment, $image_size);
					if (!empty($image_url) && is_array($image_url)) {
						$image_url 	= $image_url[0];
					}
				}else{
					$image_url = $attachment;
				}
			}

			if (!$image_url){
				$attachment = apply_filters('wd_filter_demo_image', true);
				$image_url 	= wp_get_attachment_image_src($attachment, $image_size);
				$image_url 	= $image_url[0];
			}

			$class = ($class) ? 'class="'.esc_attr($class).'"' : '';

			ob_start(); ?>
				<img <?php echo $class; ?> src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($alt); ?>" title="<?php echo esc_attr($title); ?>">
			<?php 
			return ob_get_clean();
		}

		// HTML main logo
		public function main_logo($args = array()){ 
			$default = array(
				'logo_url' 		=> '', 
				'logo_default' 	=> '', 
				'show_logo_title' => '0',
				'width'			=> '',
				'custom_class' 	=> ''
			);
			extract(wp_parse_args($args, $default));

			$logo_default = $logo_default ? $logo_default : WD_THEME_IMAGES.'/logo.png'; 
			$logo_url = empty($logo_url) ? $logo_default : $logo_url;
			$width   = ($width) ? 'width="'.esc_html($width).'"' : '';

			ob_start(); ?>
			<div class="wd-logo <?php echo esc_html($custom_class); ?>">
				<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<?php if ($show_logo_title): ?>
						<?php if (is_front_page() && is_home()): ?>
							<h1 class="wd-logo-text"><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></h1>
						<?php else: ?>
							<h2 class="wd-logo-text"><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></h2>
						<?php endif ?>
					<?php else: ?>
						<img <?php echo $width; ?> src='<?php echo esc_url($logo_url); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
					<?php endif ?>
				</a>
			</div>
		<?php return ob_get_clean();
		}

		// HTML copyright
		public function copyright($args = array()){ 
			$default = array(
				'list_item' => false, 
				'custom_class' => ''
			);
			extract(wp_parse_args($args, $default)); 

			/**
			 * package: copyright
			 * var: copyright
			 */
			extract(apply_filters('wd_filter_get_data_package', 'copyright' ));

			ob_start(); ?>
			<?php if (!$list_item) { ?>
				<div class="wd-footer-info <?php echo esc_html($custom_class); ?>">
					<?php echo $copyright; ?>
				</div>
			<?php }else{ ?>
				<li class="wd-footer-info <?php echo esc_html($custom_class); ?>">
					<?php echo $copyright; ?>
				</li>
			<?php } 
			return ob_get_clean();
		}

		// Display video from youtube
		public function youtube_video($args = array()){ 
			$default = array(
				'url' => '', 
				'width' => 600, 
				'height' => 400
			);
			extract(wp_parse_args($args, $default));
			$shortcode = '[embed width="'.$width.'" height="'.$height.'"]'.$url.'[/embed]';
			return $GLOBALS['wp_embed']->run_shortcode($shortcode);
		}

		// Get global ID
		public function get_global_post_id($current_post = ''){
			global $post;
			$current_post = ($current_post) ? $current_post : $post;
			if ($current_post) {
				return $current_post->ID;
			}
		}
	}
	WD_Main::get_instance();  // Start an instance of the plugin class 
}