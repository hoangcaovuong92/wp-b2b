<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Main_Menu')) {
	class WD_Main_Menu {
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

			//Register Menu
			add_action( 'after_setup_theme', array($this, 'register_location_menu'));

			// $integrate_specific_menu = apply_filters('wd_filter_integrate_specific_menu', $all_category);
			add_filter( 'wd_filter_integrate_specific_menu', array($this, 'integrate_specific_menu' ), 10, 2);

			// echo apply_filters('wd_filter_main_menu', $screen); //Display menu
			add_filter( 'wd_filter_main_menu', array($this, 'main_menu' ), 10, 2);

			// do_action('wd_hook_main_menu');
			add_action( 'wd_hook_main_menu', array($this, 'display_main_menu' ), 5);
		}

		//Register Menu
		public function register_location_menu(){
			register_nav_menus(array(
				'primary' 	=> esc_html__('Primary Menu', 'feellio'),
		        'secondary' => esc_html__('Secondary Menu', 'feellio'),
		        'mobile' 	=> esc_html__('Mobile Menu', 'feellio'),
		    ));
		}

		// $screen: desktop / mobile
		public function main_menu($screen = 'desktop'){
			ob_start();
			if ($screen === 'desktop') {
				$megamenu = false;
				if (class_exists('WD_Megamenu_Walker') && !empty($this->integrate_specific_menu())) {
					foreach ($this->integrate_specific_menu() as $key => $value) {
						$menu_items = wp_get_nav_menu_items($key);
						if (!empty($menu_items)) {
							$megamenu = true;
							continue;
						}
					}
				}
				if ($megamenu) {
					/**
					 * package: megamenu
					 * var: layout
					 * var: vertical_submenu_position
					 * var: menu_container
					 * var: style
					 * var: hover_style
					 * var: type
					 * var: menu_theme_location
					 * var: integrate_specific_menu
					 */
					extract(apply_filters('wd_filter_get_data_package', 'megamenu' ));
					$class_style	= 'wd-megamenu-layout-'.$layout;
					$class_style 	.= ($layout === 'menu-vertical') ? ' wd-megamenu-vertical-submenu-'.$vertical_submenu_position : '';
					$class_style	.= ' wd-megamenu-'.$style;
					$class_style	.= ' wd-megamenu-hover-'.$hover_style;
					
					$args 			= array();
					if ($type == 'theme-location' && has_nav_menu($menu_theme_location)) {
						$args['theme_location'] = $menu_theme_location;
					}else{
						$args['menu'] = $integrate_specific_menu;
					}
					$args['walker'] = new WD_Megamenu_Walker(); ?>
					<div class="wd-megamenu-wrap <?php echo esc_attr($class_style) ?>">
						<div class="wd-megamenu-content <?php echo esc_attr($menu_container) ?>">
							<?php wp_nav_menu($args); ?>
						</div>
					</div>
					<?php
				}else{
					/**
					 * package: megamenu
					 * var: menu_location_desktop
					 * var: menu_location_mobile
					 */
					extract(apply_filters('wd_filter_get_data_package', 'main-menu' ));
					echo (!has_nav_menu($menu_location_desktop)) ? '<div class="wd-menu-desktop">' : '';
					wp_nav_menu( array( 
						'container_class' 	=> 'wd-menu-desktop navbar-nav',
						'menu_class' 		=> '', 
						'theme_location' 	=> $menu_location_desktop
					));
					echo (!has_nav_menu($menu_location_desktop)) ? '</div>' : '';
				}
			}else if ($screen === 'mobile'){
				/**
				 * package: megamenu
				 * var: menu_location_desktop
				 * var: menu_location_mobile
				 */
				extract(apply_filters('wd_filter_get_data_package', 'main-menu' ));
				echo (!has_nav_menu($menu_location_mobile)) ? '<div class="wd-menu-mobile">' : '';
				wp_nav_menu( array( 
					'container_class' 	=> 'wd-menu-mobile',
					'menu_class' 		=> '', 
					'theme_location' 	=> $menu_location_mobile
				));
				echo (!has_nav_menu($menu_location_mobile)) ? '</div>' : '';
			}

			$content = ob_get_clean();
			wp_reset_query();
			return $content;
		}

		public function display_main_menu(){
			echo $this->main_menu();
		}

		// Get List terms of taxonomy
		public function integrate_specific_menu($all_category = false){
			$list_categories = array();
			if ($all_category) {
				$list_categories[-1] = esc_html__('All Category', 'feellio');
			}
			$args = array(
				'taxonomy' 		=> 'nav_menu',
				'hide_empty' 	=> 0
			);
			$categories = get_terms($args);
			if (!is_wp_error($categories) && count($categories) > 0) {
				foreach ($categories as $category ) {
					$list_categories[$category->term_id] = html_entity_decode( $category->name, ENT_QUOTES, 'UTF-8' ).' (' . $category->count . ' items)';
				}
			}
			wp_reset_postdata();
			return $list_categories;
		}
	}
	WD_Main_Menu::get_instance();  // Start an instance of the plugin class 
}