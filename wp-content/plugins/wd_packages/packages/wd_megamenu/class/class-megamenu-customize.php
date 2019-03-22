<?php
if (!class_exists('WD_Megamenu_Customize')) {
	class WD_Megamenu_Customize {
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
			//Apply walker menu
			//add_filter( 'wp_nav_menu_args', array($this, 'modify_nav_menu_args' ));

			// add custom menu fields to menu
			add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_custom_nav_fields' ) );

			// save menu custom fields
			add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_nav_fields'), 10, 3 );
			
			// edit menu walker
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_walker'), 10, 2 );
		}
		

		/*--------------------------------------------*
		 * Constructor
		 *--------------------------------------------*/

		//Apply walker menu
		function modify_nav_menu_args( $args ){
			//if( 'primary' == $args['theme_location'] ){
				$args['walker'] = new WD_Megamenu_Walker();
			//}
			return $args;
		}
		
		/**
		 * Add custom fields to $item nav object
		 * in order to be used in custom Walker
		 *
		 * @access      public
		 * @since       1.0 
		 * @return      void
		*/
		function add_custom_nav_fields( $menu_item ) {
			$meta_data 										= get_post_meta( $menu_item->ID, '_wd_menu_item_custom_field', true );
		    $menu_item->megamenu 							= !empty($meta_data['megamenu']) 	? $meta_data['megamenu'] 	: '';
		    $menu_item->columns 							= !empty($meta_data['columns']) 	? $meta_data['columns'] 	: '';
		    $menu_item->flag_label 							= !empty($meta_data['flag_label']) 	? $meta_data['flag_label'] 	: '';
		    //$menu_item->text_align 							= !empty($meta_data['text_align']) 	? $meta_data['text_align'] 	: '';
		    $menu_item->icon_class 							= !empty($meta_data['icon_class']) 	? $meta_data['icon_class'] 	: '';
		    $menu_item->hide_title 							= !empty($meta_data['hide_title']) 	? $meta_data['hide_title'] 	: '';
		    $menu_item->submenu_bg_source 					= !empty($meta_data['submenu_bg_source']) 	? $meta_data['submenu_bg_source'] 	: '';
		    $menu_item->submenu_bg_color 					= !empty($meta_data['submenu_bg_color']) 	? $meta_data['submenu_bg_color'] 	: '';
		    $menu_item->submenu_bg_image 					= !empty($meta_data['submenu_bg_image']) 	? $meta_data['submenu_bg_image'] 	: '';
		    $menu_item->submenu_width 						= !empty($meta_data['submenu_width']) 	? $meta_data['submenu_width'] 	: '';
		    $menu_item->submenu_content_source 				= !empty($meta_data['submenu_content_source']) ? $meta_data['submenu_content_source'] : '';
		    $menu_item->submenu_custom_content_effect 		= !empty($meta_data['submenu_custom_content_effect']) ? $meta_data['submenu_custom_content_effect'] : '';
		    $menu_item->submenu_content_widget 				= !empty($meta_data['submenu_content_widget']) ? $meta_data['submenu_content_widget'] : '';
		    $menu_item->submenu_content_megamenu_template 	= !empty($meta_data['submenu_content_megamenu_template']) 	? $meta_data['submenu_content_megamenu_template'] 	: '';
		    $menu_item->submenu_content_custom_shortcode 	= !empty($meta_data['submenu_content_custom_shortcode']) 	? $meta_data['submenu_content_custom_shortcode'] 	: '';
		    return $menu_item;
		}
		
		/**
		 * Save menu custom fields
		 *
		 * @access      public
		 * @since       1.0 
		 * @return      void
		*/
		function update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
		
		    // Check if element is properly sent
		    if ( !empty($_REQUEST['wd-menu-item-custom-field']) && is_array($_REQUEST['wd-menu-item-custom-field']) ) {
		        $subtitle_value = $_REQUEST['wd-menu-item-custom-field'][$menu_item_db_id];
		        update_post_meta( $menu_item_db_id, '_wd_menu_item_custom_field', $subtitle_value );
		    }
		    
		}
		
		/**
		 * Define new Walker edit
		 *
		 * @access      public
		 * @since       1.0 
		 * @return      void
		*/
		function edit_walker($walker,$menu_id) {
			return 'WD_Megamenu_Edit_Fields';
		}	
	}
	WD_Megamenu_Customize::get_instance();  // Start an instance of the plugin class 
}