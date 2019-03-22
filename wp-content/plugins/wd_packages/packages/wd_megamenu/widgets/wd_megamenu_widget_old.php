<?php
if( !class_exists( 'wd_widget_megamenu' ) ) {
	class wd_widget_megamenu extends WD_Widgets_Fields {
		public function __construct() {
			$this->widget_init();
		}
		
		public function init_settings(){
			$this->list_widget_field_default = array(
				'title'						=> '',
				'layout'					=> 'menu-horizontal',
				'vertical_submenu_position' => 'right',
				'bg_color'					=> '',
				'menu_container'			=> '',
				'menu_fixed'				=> '0',
				'style'						=> 'style-1',
				'hover_style'				=> 'style-1',
				'layout'					=> 'menu-horizontal',
				'type'						=> 'theme-location',
				'menu_theme_location'		=> '',
				'integrate_specific_menu'	=> '',
				'class' 					=> '',
			);
			$this->widget_name = esc_html__('WD - Megamenu','wd_package');
			$this->widget_desc = esc_html__('Display megamenu by special menu or menu id...','wd_package');
			$this->widget_slug = 'widget_wd_megamenu';
			$this->callback = 'wd_megamenu_function';
		}

		public function form( $instance ) {
	       	foreach ($this->list_widget_field_default as $key => $value) {
	    		${$key}   	= isset( $instance[$key] ) ? esc_attr( $instance[$key] ) : $value;
	    	}

	        $layout_arr = array(
				'menu-horizontal' 	=> 'Menu Horizontal',
				'menu-vertical' 	=> 'Menu Vertical',
			);

			$vertical_submenu_position_arr = array(
				'right'			=> 'Right',
				'left'			=> 'Left',
			);
			$menu_container_arr = array(
				 '' 			=> 'Container Fluid (Without Padding)',
				 'container' 	=> 'Container (Padding)',
			);
			$menu_fixed_arr 	= wd_get_list_tvgiao_boolean();
			$style_arr 			= wd_get_list_style_class(5);
			$hover_style_arr 	= wd_get_list_style_class(5);

			$type_arr = array(
				'theme-location' 	=> 'Menu Theme Location',
				'specific-menu' 	=> 'Integrate Specific Menu',
			); 

			$integrate_specific_menu_arr 	= wd_get_list_category('nav_menu', false);
	        $menu_theme_location_arr 		= wd_get_list_menu_registed();

	        $this->text_field(
    			esc_html__( 'Title:', 'wd_package' ), 
    			$this->get_field_name( 'title' ),
    			$this->get_field_id( 'title' ),
    			$title, 
    			esc_html__("", 'wd_package')
    		);

	        $this->select_field(
                esc_html__( 'Layout:', 'wd_package' ), 
                $this->get_field_name( 'layout' ), 
                $this->get_field_id( 'layout' ), 
                $layout_arr, 
                $layout
            );

            $this->select_field(
                esc_html__( 'Vertical Submenu Position:', 'wd_package' ), 
                $this->get_field_name( 'vertical_submenu_position' ), 
                $this->get_field_id( 'vertical_submenu_position' ), 
                $vertical_submenu_position_arr, 
                $vertical_submenu_position
            );

            $this->color_field(
    			esc_html__( 'Background Color:', 'wd_package' ), 
    			$this->get_field_name( 'bg_color' ),
    			$this->get_field_id( 'bg_color' ),
    			$bg_color, 
    			esc_html__("", 'wd_package')
    		);

    		$this->select_field(
                esc_html__( 'Menu Container:', 'wd_package' ), 
                $this->get_field_name( 'menu_container' ), 
                $this->get_field_id( 'menu_container' ), 
                $menu_container_arr, 
                $menu_container
            );

            $this->select_field(
                esc_html__( 'Menu Fixed:', 'wd_package' ), 
                $this->get_field_name( 'menu_fixed' ), 
                $this->get_field_id( 'menu_fixed' ), 
                $menu_fixed_arr, 
                $menu_fixed
            );

            $this->select_field(
                esc_html__( 'Style:', 'wd_package' ), 
                $this->get_field_name( 'style' ), 
                $this->get_field_id( 'style' ), 
                $style_arr, 
                $style
            );

            $this->select_field(
                esc_html__( 'Hover Style:', 'wd_package' ), 
                $this->get_field_name( 'hover_style' ), 
                $this->get_field_id( 'hover_style' ), 
                $hover_style_arr, 
                $hover_style
            );

            $this->select_field(
                esc_html__( 'Type:', 'wd_package' ), 
                $this->get_field_name( 'type' ), 
                $this->get_field_id( 'type' ), 
                $type_arr, 
                $type
            );

            $this->select_field(
                esc_html__( 'Menu Theme Location:', 'wd_package' ), 
                $this->get_field_name( 'menu_theme_location' ), 
                $this->get_field_id( 'menu_theme_location' ), 
                $menu_theme_location_arr, 
                $menu_theme_location
            );

            $this->select_field(
                esc_html__( 'Integrate Specific Menu:', 'wd_package' ), 
                $this->get_field_name( 'integrate_specific_menu' ), 
                $this->get_field_id( 'integrate_specific_menu' ), 
                $integrate_specific_menu_arr, 
                $integrate_specific_menu
            );

            $this->text_field(
    			esc_html__( 'Extra class name:', 'wd_package' ), 
    			$this->get_field_name( 'class' ),
    			$this->get_field_id( 'class' ),
    			$class, 
    			esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package')
    		);
	    }
	}
	function wp_widget_register_widget_megamenu() {
		register_widget( 'wd_widget_megamenu' );
	}
	add_action( 'widgets_init', 'wp_widget_register_widget_megamenu' );
}