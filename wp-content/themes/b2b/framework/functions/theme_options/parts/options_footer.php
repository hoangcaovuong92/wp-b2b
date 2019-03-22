<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'Footer', 'feellio' ),
    'id'               => 'wd_footer',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-arrow-down',
    'fields'           => array(
        array(
            'id'       => 'wd_footer_layout',
            'type'     => 'select',
            'title'    => __( 'Select The Template', 'feellio' ), 
            'desc'     => __( 'Leave blank to use default template', 'feellio' ),
            // Must provide key => value pairs for select options
            'options'  => apply_filters('wd_filter_footer_choices', array('value_default' => '', 'value_return' => 'name')),
        ),
        array(
           'id'       => 'wd_footer_section_start',
            'type'     => 'section',
            'title'    => __( 'Footer Default Settings', 'feellio' ),
            'subtitle' => __( 'The custom sections below are only visible to the default footer.', 'feellio' ),
            'indent'   => true,
            /*'required' => array('wd_footer_layout','=',''),*/
        ),

        /****************************/
            array(
                'id'       => 'wd_footer_logo',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Custom Logo', 'feellio' ),
                'compiler' => 'true',
                'desc'     => __( '', 'feellio' ),
                'subtitle' => __( 'If no image is selected, the footer will use Logo in the general settings', 'feellio' ),
                'default'  => array( 'url' => $wd_default_data['general']['default']['logo-footer'] ),
                /*'required' => array('wd_footer_layout','=',''),*/
            ),

            array(
                'id'      => 'wd_footer_copyright_text',
                'type'    => 'editor',
                'title'   => __( 'Copyright Text', 'feellio' ),
                'default' => $wd_default_data['footer']['default']['copyright_text'],
                'args'    => array(
                    'wpautop'       => false,
                    'media_buttons' => false,
                    'textarea_rows' => 5,
                    //'tabindex' => 1,
                    //'editor_css' => '',
                    'teeny'         => false,
                    //'tinymce' => array(),
                    'quicktags'     => false,
                ),
                /*'required' => array('wd_footer_layout','=',''),*/
            ),
            array(
                'id'       => 'wd_footer_instagram',
                'type'     => 'switch',
                'title'    => __( 'Instagram', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'default'  => $wd_default_data['footer']['default']['instagram'],
                'on'       => 'Show',
                'off'      => 'Hide',
            ),
            array(
                'id'       => 'wd_footer_social',
                'type'     => 'switch',
                'title'    => __( 'Social', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'default'  => $wd_default_data['footer']['default']['social'],
                'on'       => 'Show',
                'off'      => 'Hide',
            ),
            array(
                'id'       => 'wd_footer_copyright',
                'type'     => 'switch',
                'title'    => __( 'Copyright', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'default'  => $wd_default_data['footer']['default']['copyright'],
                'on'       => 'Show',
                'off'      => 'Hide',
            ),
        /****************************/
        
        array(
            'id'     => 'wd_footer_section_end',
            'type'   => 'section',
            'indent' => false,
            /*'required' => array('wd_footer_layout','=',''),*/
        ),
        
    ) 
) );