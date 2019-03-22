<?php
if( !class_exists( 'wd_widget_social_profiles' ) ) {
	class wd_widget_social_profiles extends WD_Widgets_Fields{
		public function __construct() {
			$this->widget_init();
		}
		
		public function init_settings(){
			$this->list_widget_field_default = array(
				'widget_title'				=> '',
				'style'						=> 'vertical', 
				'show_title'				=> '1',
				'item_align'				=> 'wd-flex-justify-left',
				'rss_id'					=> '#',
				'twitter_id'				=> '#',
				'facebook_id'				=> '#',
				'google_id'					=> '#',
				'pin_id'					=> '#',
				'youtube_id'				=> '#',
				'instagram_id'				=> '#',
				'class'						=> ''
			);
			$this->widget_name = esc_html__('WD - Social Icons','wd_package');
			$this->widget_desc = esc_html__('Display social icon with many style...','wd_package');
			$this->widget_slug = 'wd_social_icons';
			$this->callback = 'wd_social_icons_function';
		}

	    public function form( $instance ) {
	    	foreach ($this->list_widget_field_default as $key => $value) {
	    		${$key}   	= isset( $instance[$key] ) ? esc_attr( $instance[$key] ) : $value;
	    	}

	        $style_arr 			= array(
				'vertical' 		=> esc_html__('Style 1 (Vertical)', 'wd_package'),
				'horizontal' 	=> esc_html__('Style 2 (Horizontal)', 'wd_package'),
				'nav-user' 		=> esc_html__('Style 3 (Nav User)', 'wd_package')
			);
	        $show_title_arr 	= wd_get_list_tvgiao_boolean();
	        $item_align_arr 	= wd_get_list_flex_align_class();
		   
			$this->text_field(
    			esc_html__( 'Widget title:', 'wd_package' ), 
    			$this->get_field_name( 'widget_title' ),
    			$this->get_field_id( 'widget_title' ),
    			$widget_title, 
    			esc_html__("", 'wd_package')
			);
			
    		$this->select_field(
    			esc_html__( 'Style:', 'wd_package' ), 
    			$this->get_field_name( 'style' ), 
    			$this->get_field_id( 'style' ), 
    			$style_arr, 
    			$style
    		); 
    		$this->select_field(
    			esc_html__( 'Show Title:', 'wd_package' ), 
    			$this->get_field_name( 'show_title' ), 
    			$this->get_field_id( 'show_title' ), 
    			$show_title_arr, 
    			$show_title
			); 
			$this->select_field(
    			esc_html__( 'Item Align:', 'wd_package' ), 
    			$this->get_field_name( 'item_align' ), 
    			$this->get_field_id( 'item_align' ), 
    			$item_align_arr, 
    			$item_align
    		); 
    		$this->text_field(
    			esc_html__( 'RSS ID:', 'wd_package' ), 
    			$this->get_field_name( 'rss_id' ),
    			$this->get_field_id( 'rss_id' ),
    			$rss_id, 
    			esc_html__("", 'wd_package')
    		);
    		$this->text_field(
    			esc_html__( 'Twitter ID:', 'wd_package' ), 
    			$this->get_field_name( 'twitter_id' ),
    			$this->get_field_id( 'twitter_id' ),
    			$twitter_id, 
    			esc_html__("", 'wd_package')
    		);
    		$this->text_field(
    			esc_html__( 'Facebook ID:', 'wd_package' ), 
    			$this->get_field_name( 'facebook_id' ),
    			$this->get_field_id( 'facebook_id' ),
    			$facebook_id, 
    			esc_html__("", 'wd_package')
    		);
    		$this->text_field(
    			esc_html__( 'Google Plus ID:', 'wd_package' ), 
    			$this->get_field_name( 'google_id' ),
    			$this->get_field_id( 'google_id' ),
    			$google_id, 
    			esc_html__("", 'wd_package')
    		);
    		$this->text_field(
    			esc_html__( 'Pinterest ID:', 'wd_package' ), 
    			$this->get_field_name( 'pin_id' ),
    			$this->get_field_id( 'pin_id' ),
    			$pin_id, 
    			esc_html__("", 'wd_package')
    		);
    		$this->text_field(
    			esc_html__( 'Youtube ID:', 'wd_package' ), 
    			$this->get_field_name( 'youtube_id' ),
    			$this->get_field_id( 'youtube_id' ),
    			$youtube_id, 
    			esc_html__("", 'wd_package')
    		);
    		$this->text_field(
    			esc_html__( 'Instagram ID:', 'wd_package' ), 
    			$this->get_field_name( 'instagram_id' ),
    			$this->get_field_id( 'instagram_id' ),
    			$instagram_id, 
    			esc_html__("", 'wd_package')
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
	function wd_widget_social_profiles_register() {
		register_widget( 'wd_widget_social_profiles' );
	}
	add_action( 'widgets_init', 'wd_widget_social_profiles_register' );
}