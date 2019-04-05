<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'Page', 'feellio' ),
    'id'               => 'wd_page_setting',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-file'
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Account Page', 'feellio' ),
    'id'               => 'wd_account_page',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( 'Options to change the link and content of the default pages such as login, register, forgot password... You can use the available WPDance shortcodes to create page content.', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_page_login_url',
            'type'     => 'select',
            'title'    => __( 'Login Page', 'feellio' ),
            'desc'     => __( 'Select a page to replace the default login page.', 'feellio' ),
            'data'     => 'pages',
            'args'     => array(
                'posts_per_page' => -1,
            ),
        ),
        array(
            'id'       => 'wd_page_register_url',
            'type'     => 'select',
            'title'    => __( 'Register Page', 'feellio' ),
            'desc'     => __( 'Select a page to replace the default register page.', 'feellio' ),
            'data'     => 'pages',
            'args'     => array(
                'posts_per_page' => -1,
            ),
        ),
        array(
            'id'       => 'wd_page_forgot_password_url',
            'type'     => 'select',
            'title'    => __( 'Forgot Password Page', 'feellio' ),
            'desc'     => __( 'Select a page to replace the default forgot password page.', 'feellio' ),
            'data'     => 'pages',
            'args'     => array(
                'posts_per_page' => -1,
            ),
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Default Page', 'feellio' ),
    'id'               => 'wd_layout_page_default',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_layout_page_default_layout',
            'type'     => 'image_select',
            'title'    => __( 'Select The Layout', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['layout']['choose'],
            'default'  => $wd_default_data['layout']['default']['page_default'],
        ),

        array(
            'id'       => 'wd_layout_page_default_left_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Left Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['page_default_left'],
            /*'required' => array('wd_layout_page_default_layout','=',array( '1-0-0', '1-0-1' ) ),*/
        ),

        array(
            'id'       => 'wd_layout_page_default_right_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Right Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['page_default_right'],
            /*'required' => array('wd_layout_page_default_layout','=',array( '0-0-1', '1-0-1' ) ),*/
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'            => __( '404 Page', 'feellio' ),
    'id'               => 'wd_layout_page_404',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_layout_page_404_background_style',
            'type'     => 'radio',
            'title'    => __( 'Background Style', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['404_page']['choose']['bg_style'],
            'default'  => $wd_default_data['404_page']['default']['bg_style'],
        ),

        array(
            'id'       => 'wd_layout_page_404_background_color',
            'type'     => 'color',
            'transparent'=> false,
            'title'    => __( 'Background Color', 'feellio' ),
            'subtitle' => sprintf(__( '(Default: %s).', 'feellio' ), $wd_default_data['404_page']['default']['bg_color']),
            'default'  => $wd_default_data['404_page']['default']['bg_color'],
            'required' => array('wd_layout_page_404_background_style', '=', 'bg_color'),
        ),

        array(
            'id'       => 'wd_layout_page_404_background_image',
            'type'     => 'media',
            'url'      => true,
            'title'    => __( 'Background Image', 'feellio' ),
            'compiler' => 'true',
            'desc'     => __( '', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => array( 'url' => $wd_default_data['404_page']['default']['bg_image'] ),
            'required' => array('wd_layout_page_404_background_style', '=', 'bg_image'),
        ),

        array(
            'id'       => 'wd_layout_page_404_show_header_footer',
            'type'     => 'switch',
            'title'    => __( 'Header & Footer', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['404_page']['default']['header_footer'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_page_404_show_search_form',
            'type'     => 'switch',
            'title'    => __( 'Search Form', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['404_page']['default']['search_form'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_page_404_show_back_to_home_button',
            'type'     => 'switch',
            'title'    => __( 'Back To Home Button', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['404_page']['default']['button'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),
        array(
           'id'       => 'wd_layout_page_404_show_back_to_home_button_section_start',
            'type'     => 'section',
            'title'    => __( 'Button Settings', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'indent'   => true,
            'required' => array('wd_layout_page_404_show_back_to_home_button','=', '1' ),
        ),

        /****************************/
            array(
                'id'       => 'wd_layout_page_404_show_back_to_home_button_text',
                'type'     => 'text',
                'title'    => __( 'Text Button', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['404_page']['default']['button_text'],
                'required' => array('wd_layout_page_404_show_back_to_home_button', '=', '1'),
            ),

            array(
                'id'       => 'wd_layout_page_404_show_back_to_home_button_class',
                'type'     => 'text',
                'title'    => __( 'Class Button', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['404_page']['default']['button_class'],
                'required' => array('wd_layout_page_404_show_back_to_home_button', '=', '1'),
            ),
        /****************************/
        
        array(
            'id'        => 'wd_layout_page_404_show_back_to_home_button_section_end',
            'type'      => 'section',
            'indent'    => false,
            'required'  => array('wd_layout_page_404_show_back_to_home_button','=', '1' ),
        ),
    )
) );