<?php
if( !class_exists( 'wd_widget_profile' ) ) {
	class wd_widget_profile extends WD_Widgets_Fields{
	    public function __construct() {
            $this->widget_init();
        }
        
        public function init_settings(){
			$this->list_widget_field_default = array(
                'widget_title'  => 'ABOUT ME',
                'image'			=> '',
                'image_size'	=> 'full',
                'website'		=> '#',
                'target'		=> '_blank',
                'job'			=> '',
                'title'			=> '',
                'desc'			=> '',
				'display_logo'	=> 1,
				'text_align'	=> 'text-left',
                'about'			=> 'I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
                'display_sign'	=> 1,
                'sign_image'	=> '',
                'class' 		=> ''
            );
			$this->widget_name = esc_html__('WD - Profile','wd_package');
			$this->widget_desc = esc_html__('Display user profile...','wd_package');
			$this->widget_slug = 'wd_profile';
			$this->callback = 'wd_profile_function';
		}

	    public function form( $instance ) {
	    	foreach ($this->list_widget_field_default as $key => $value) {
	    		${$key}   	= isset( $instance[$key] ) ? esc_attr( $instance[$key] ) : $value;
	    	}

            

            $image_size_arr 	= wd_get_list_image_size();
            $target_arr = wd_get_list_link_target();
			$display_logo_arr = wd_get_list_tvgiao_boolean();
			$text_align_arr = wd_get_list_text_align_bootstrap();
            $display_sign_arr = wd_get_list_tvgiao_boolean();

			$this->text_field(
    			esc_html__( 'Widget Title:', 'wd_package' ), 
    			$this->get_field_name( 'widget_title' ),
    			$this->get_field_id( 'widget_title' ),
    			$widget_title
            );

            $this->image_field(	
        		esc_html__( 'Avatar:', 'wd_package' ), 
        		$this->get_field_name( 'image' ), 
        		$this->get_field_id( 'image' ), 
        		$image, 
        		'', 
        		''
            ); 

            $this->select_field(
                esc_html__( 'Image Size:', 'wd_package' ), 
                $this->get_field_name( 'image_size' ), 
                $this->get_field_id( 'image_size' ), 
                $image_size_arr, 
                $image_size
            ); 

            $this->text_field(
    			esc_html__( 'Website:', 'wd_package' ), 
    			$this->get_field_name( 'website' ),
    			$this->get_field_id( 'website' ),
    			$website, 
    			''
            );

            $this->select_field(
                esc_html__( 'Link Target:', 'wd_package' ), 
                $this->get_field_name( 'target' ), 
                $this->get_field_id( 'target' ), 
                $target_arr, 
                $target
            ); 
            $this->text_field(
    			esc_html__( 'Occupation:', 'wd_package' ), 
    			$this->get_field_name( 'job' ),
    			$this->get_field_id( 'job' ),
    			$job, 
    			''
            );
            $this->text_field(
    			esc_html__( 'Title:', 'wd_package' ), 
    			$this->get_field_name( 'title' ),
    			$this->get_field_id( 'title' ),
    			$title, 
    			''
            );
            $this->text_field(
    			esc_html__( 'Description:', 'wd_package' ), 
    			$this->get_field_name( 'desc' ),
    			$this->get_field_id( 'desc' ),
    			$desc, 
    			''
            );

            $this->select_field(
                esc_html__( 'Display Site Logo:', 'wd_package' ), 
                $this->get_field_name( 'display_logo' ), 
                $this->get_field_id( 'display_logo' ), 
                $display_logo_arr, 
                $display_logo
			);
			
			$this->select_field(
                esc_html__( 'About Text Align:', 'wd_package' ), 
                $this->get_field_name( 'text_align' ), 
                $this->get_field_id( 'text_align' ), 
                $text_align_arr, 
                $text_align
            ); 
            
            $this->textarea_field(
                esc_html__( 'About:', 'wd_package' ), 
                $this->get_field_name( 'about' ),
                $this->get_field_id( 'about' ),
                $about, 
                ''
			);
			
			$this->select_field(
                esc_html__( 'Display Signature Image:', 'wd_package' ), 
                $this->get_field_name( 'display_sign' ), 
                $this->get_field_id( 'display_sign' ), 
                $display_sign_arr, 
                $display_sign
            ); 
            
            $this->image_field(	
        		esc_html__( 'Sign Image:', 'wd_package' ), 
        		$this->get_field_name( 'sign_image' ), 
        		$this->get_field_id( 'sign_image' ), 
        		$sign_image, 
        		'', 
        		''
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
	function wd_widget_profile_register() {
		register_widget( 'wd_widget_profile' );
	}
	add_action( 'widgets_init', 'wd_widget_profile_register' );
}