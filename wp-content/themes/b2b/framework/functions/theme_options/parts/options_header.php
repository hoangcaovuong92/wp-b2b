<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'Header', 'feellio' ),
    'id'               => 'wd_header',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-arrow-up',
    'fields'     => array(
        array(
            'id'       => 'wd_header_layout',
            'type'     => 'select',
            'title'    => __( 'Select The Template', 'feellio' ), 
            'desc'     => __( 'Leave blank to use default template', 'feellio' ),
            // Must provide key => value pairs for select options
            'options'  => apply_filters('wd_filter_header_choices', array('value_default' => '', 'value_return' => 'name')),
        ),
        array(
           'id'       => 'wd_header_section_start',
            'type'     => 'section',
            'title'    => __( 'Header Default Settings', 'feellio' ),
            'subtitle' => __( 'The custom sections below are only visible to the default header, header mobile.', 'feellio' ),
            'indent'   => true,
            /*'required' => array('wd_header_layout','=',''),*/
        ),
        /****************************/
            array(
                'id'       => 'wd_header_show_site_title',
                'type'     => 'button_set',
                'title'    => __( 'Title/Logo', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'options'  => $wd_default_data['header']['choose']['site_title'],
                'default'  => $wd_default_data['header']['default']['site_title'],
                /*'required' => array('wd_header_layout','=',''),*/
            ),

            array(
                'id'       => 'wd_header_logo',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Custom Logo', 'feellio' ),
                'compiler' => 'true',
                'desc'     => __( '', 'feellio' ),
                'subtitle' => __( 'If no image is selected, the header will use Logo in the general settings', 'feellio' ),
                'default'  => array( 'url' => $wd_default_data['general']['default']['logo'] ),
                'required' => array('wd_header_show_site_title','=','0'),
            ),
            array(
                'id'       => 'wd_header_show_logo',
                'type'     => 'switch',
                'title'    => __( 'Show Logo', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'default'  => $wd_default_data['header']['default']['show_logo'],
                'on'       => 'Show',
                'off'      => 'Hide',
            ),
            array(
                'id'       => 'wd_header_show_social',
                'type'     => 'switch',
                'title'    => __( 'Show Social', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'default'  => $wd_default_data['header']['default']['show_social'],
                'on'       => 'Show',
                'off'      => 'Hide',
            ),
            array(
                'id'       => 'wd_header_show_navuser',
                'type'     => 'switch',
                'title'    => __( 'Show Nav User', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'default'  => $wd_default_data['header']['default']['show_navuser'],
                'on'       => 'Show',
                'off'      => 'Hide',
            ),
        /****************************/
        
        array(
            'id'     => 'wd_header_section_end',
            'type'   => 'section',
            'indent' => false,
            /*'required' => array('wd_header_layout','=',''),*/
        ),
    )
) );