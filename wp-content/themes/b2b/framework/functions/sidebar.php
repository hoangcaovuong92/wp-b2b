<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

/**
 * Usage : 
 * echo apply_filters('wd_filter_display_left_sidebar', $sidebar_left, $layout); //Left sidebar
 * echo apply_filters('wd_filter_display_right_sidebar', $sidebar_right, $layout); //Right sidebar
 */

if (!class_exists('WD_Sidebar')) {
	class WD_Sidebar {
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
			
			add_action('widgets_init', array($this, 'register_sidebar'));

			add_filter( 'wd_filter_display_left_sidebar', array($this, 'display_left_sidebar' ), 10, 2);

			add_filter( 'wd_filter_display_right_sidebar', array($this, 'display_right_sidebar' ), 10, 2);
		}

		// Sidebar HTML
		function display_left_sidebar($sidebar_left = 'left_sidebar', $layout = '1-0-0'){ 
			ob_start(); ?>
			<?php if( ($layout == '1-0-0') || ($layout == '1-0-1') ) : ?> 
				
				<div class="col-md-6 col-sm-24 wd-sidebar wd-sidebar--left">
					<a data-panel-target="#wd-panel-sidebar-left" class="wd-navUser-action wd-navUser-action--sidebar wd-panel-action wd-panel-action--left-sidebar"><i class="lnr lnr-arrow-right-circle wd-icon"></i></a>
					<div id="wd-panel-sidebar-left" class="wd-panel-mobile-wrap wd-panel--right">
						<div class="wd-title wd-panel-title wd-panel-title--sidebar">
							<span class="wd-title-heading wd-panel-title-heading">
								<?php esc_html_e('Left Sidebar', 'feellio'); ?>
							</span>
						</div>
						<?php if (is_active_sidebar($sidebar_left) ) {
							echo '<div class="wd-sidebar--'.$sidebar_left.'">';
							dynamic_sidebar( $sidebar_left );
							echo '</div>';
						}else{
							printf(__('Go to Appearance => Widgets and add at least 1 widget to <strong>%s</strong> area', 'feellio'), $sidebar_left);
						} ?>
					</div>
				</div>
			<?php endif; // Endif Left?>
			<?php 
			echo ob_get_clean();
		}

		function display_right_sidebar($sidebar_right = 'right_sidebar', $layout = '0-0-1'){ 
			ob_start(); ?>
			<?php if( ($layout == '0-0-1') || ($layout == '1-0-1') ) : ?> 
				<div class="col-md-6 col-sm-24 wd-sidebar wd-sidebar--right">
					<a data-panel-target="#wd-panel-sidebar-right" class="wd-navUser-action wd-navUser-action--sidebar wd-panel-action wd-panel-action--right-sidebar"><i class="lnr lnr-arrow-left-circle wd-icon"></i></a>
					<div id="wd-panel-sidebar-right" class="wd-panel-mobile-wrap wd-panel--left">
						<div class="wd-title wd-panel-title wd-panel-title--sidebar">
							<span class="wd-title-heading wd-panel-title-heading">
								<?php esc_html_e('Right Sidebar', 'feellio'); ?>
							</span>
						</div>
						<?php if (is_active_sidebar($sidebar_right) ) {
							echo '<div class="wd-sidebar--'.$sidebar_right.'">';
							dynamic_sidebar( $sidebar_right );
							echo '</div>';
						}else{
							printf(__('Go to Appearance => Widgets and add at least 1 widget to <strong>%s</strong> area', 'feellio'), $sidebar_right);
						} ?>
					</div>
				</div>
			<?php endif; // Endif Right ?>
			<?php 
			echo ob_get_clean();
		}

		// Register Sidebar
		function register_sidebar(){
			register_sidebar(array(
				'name' 				=> esc_html__('Left Sidebar', 'feellio'),
				'id' 				=> 'left_sidebar',
				'description'   	=> esc_html__( 'Main sidebar that appears on the left.', 'feellio' ),
				'before_widget' 	=> '<aside id="%1$s" class="widget %2$s">',
				'after_widget' 		=> '</aside>',
				'before_title' 		=> '<div class="wd-title-widget-wrap"><h2 class="wd-title-widget">',
				'after_title' 		=> '</h2></div>',
			));
			register_sidebar(array(
				'name' 				=> esc_html__('Right Sidebar', 'feellio'),
				'id' 				=> 'right_sidebar',
				'description'   	=> esc_html__( 'Main sidebar that appears on the right.', 'feellio' ),
				'before_widget' 	=> '<aside id="%1$s" class="widget %2$s">',
				'after_widget' 		=> '</aside>',
				'before_title' 		=> '<div class="wd-title-widget-wrap"><h2 class="wd-title-widget">',
				'after_title' 		=> '</h2></div>',
			));
			register_sidebar(array(
				'name' 				=> esc_html__('Left Sidebar Product', 'feellio'),
				'id' 				=> 'left_sidebar_product',
				'description'   	=> esc_html__( 'Left Sidebar for single product', 'feellio' ),
				'before_widget' 	=> '<aside id="%1$s" class="widget %2$s">',
				'after_widget' 		=> '</aside>',
				'before_title' 		=> '<div class="wd-title-widget-wrap"><h2 class="wd-title-widget">',
				'after_title' 		=> '</h2></div>',
			));
			register_sidebar(array(
				'name' 				=> esc_html__('Right Sidebar Product', 'feellio'),
				'id' 				=> 'right_sidebar_product',
				'description'   	=> esc_html__( 'Right Sidebar for single product', 'feellio' ),
				'before_widget' 	=> '<aside id="%1$s" class="widget %2$s">',
				'after_widget' 		=> '</aside>',
				'before_title' 		=> '<div class="wd-title-widget-wrap"><h2 class="wd-title-widget">',
				'after_title' 		=> '</h2></div>',
			));
			register_sidebar(array(
				'name' 				=> esc_html__('Left Sidebar Shop', 'feellio'),
				'id' 				=> 'left_sidebar_shop',
				'description'   	=> esc_html__( 'Left Sidebar for shop page', 'feellio' ),
				'before_widget' 	=> '<aside id="%1$s" class="widget %2$s">',
				'after_widget' 		=> '</aside>',
				'before_title' 		=> '<div class="wd-title-widget-wrap"><h2 class="wd-title-widget">',
				'after_title' 		=> '</h2></div>',
			));
			register_sidebar(array(
				'name' 				=> esc_html__('Right Sidebar Shop', 'feellio'),
				'id' 				=> 'right_sidebar_shop',
				'description'   	=> esc_html__( 'Right Sidebar for shop page', 'feellio' ),
				'before_widget' 	=> '<aside id="%1$s" class="widget %2$s">',
				'after_widget' 		=> '</aside>',
				'before_title' 		=> '<div class="wd-title-widget-wrap"><h2 class="wd-title-widget">',
				'after_title' 		=> '</h2></div>',
			));
		}
	}
	WD_Sidebar::get_instance();  // Start an instance of the plugin class 
}