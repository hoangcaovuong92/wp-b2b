<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'Navigation', 'feellio' ),
    'id'               => 'wd_menus',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-indent-left',
) );

if (class_exists('WD_Megamenu')) {
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Megamenu', 'feellio' ),
        'id'               => 'wd_megamenu_settings',
        'subsection'       => true,
        'customizer_width' => '450px',
        'desc'             => __( 'The custom sections below are only visible to the push menu mobile.', 'feellio' ),
        'fields'           => array(
            array(
                'id'       => 'wd_megamenu_layout',
                'type'     => 'radio',
                'title'    => __( 'Layout', 'feellio' ), 
                'desc'     => '',
                // Must provide key => value pairs for select options
                'options'  => $wd_default_data['menu']['megamenu']['choose']['layout'],
                'default'  => $wd_default_data['menu']['megamenu']['default']['layout'],
            ),
            array(
                'id'       => 'wd_megamenu_vertical_submenu_position',
                'type'     => 'radio',
                'title'    => __( 'Submenu Position', 'feellio' ), 
                'desc'     => '',
                'options'  => $wd_default_data['menu']['megamenu']['choose']['vertical_submenu_position'],
                'default'  => $wd_default_data['menu']['megamenu']['default']['vertical_submenu_position'],
                'required' => array('wd_megamenu_layout','=','menu-vertical'),
            ),
            array(
                'id'       => 'wd_megamenu_menu_container',
                'type'     => 'radio',
                'title'    => __( 'Menu Container', 'feellio' ), 
                'desc'     => '',
                'options'  => $wd_default_data['menu']['megamenu']['choose']['menu_container'],
                'default'  => $wd_default_data['menu']['megamenu']['default']['menu_container'],
            ),
            array(
                'id'       => 'wd_megamenu_style',
                'type'     => 'button_set',
                'title'    => __( 'Layout Style', 'feellio' ), 
                'desc'     => '',
                'options'  => $wd_default_data['menu']['megamenu']['choose']['style'],
                'default'  => $wd_default_data['menu']['megamenu']['default']['style'],
            ),
            array(
                'id'       => 'wd_megamenu_hover_style',
                'type'     => 'button_set',
                'title'    => __( 'Hover Style', 'feellio' ), 
                'desc'     => '',
                'options'  => $wd_default_data['menu']['megamenu']['choose']['style'],
                'default'  => $wd_default_data['menu']['megamenu']['default']['hover_style'],
            ),
            array(
                'id'       => 'wd_megamenu_type',
                'type'     => 'radio',
                'title'    => __( 'Type', 'feellio' ), 
                'desc'     => '',
                'options'  => $wd_default_data['menu']['megamenu']['choose']['type'],
                'default'  => $wd_default_data['menu']['megamenu']['default']['type'],
            ),
            array(
                'id'       => 'wd_megamenu_menu_theme_location',
                'type'     => 'radio',
                'title'    => __( 'Menu Theme Location', 'feellio' ), 
                'desc'     => '',
                'options'  => $wd_default_data['menu']['main_menu']['choose']['menu_location'],
                'default'  => $wd_default_data['menu']['main_menu']['default']['menu_location_desktop'],
                'required' => array('wd_megamenu_type','=','theme-location'),
            ),
            array(
                'id'       => 'wd_megamenu_integrate_specific_menu',
                'type'     => 'radio',
                'title'    => __( 'Integrate Specific Menu', 'feellio' ), 
                'desc'     => '',
                'options'  => apply_filters('wd_filter_integrate_specific_menu', false),
                'default'  => $wd_default_data['menu']['megamenu']['default']['integrate_specific_menu'],
                'required' => array('wd_megamenu_type','=','specific-menu'),
            ),
        )
    ) );
}

Redux::setSection( $opt_name, array(
    'title'            => __( 'Main Menu', 'feellio' ),
    'id'               => 'wd_menu_settings',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_menu_location_desktop',
            'type'     => 'radio',
            'title'    => __( 'Desktop', 'feellio' ), 
            'desc'     => __( 'This option will be applied when the megamenu function is not activated.', 'feellio' ),
            'options'  => $wd_default_data['menu']['main_menu']['choose']['menu_location'],
            'default'  => $wd_default_data['menu']['main_menu']['default']['menu_location_desktop'],
        ),
        array(
            'id'       => 'wd_menu_location_mobile',
            'type'     => 'radio',
            'title'    => __( 'Mobile', 'feellio' ), 
            'desc'     => '',
            'options'  => $wd_default_data['menu']['main_menu']['choose']['menu_location'],
            'default'  => $wd_default_data['menu']['main_menu']['default']['menu_location_mobile'],
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Pushmenu', 'feellio' ),
    'id'               => 'wd_pushmenu_settings',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( 'The custom sections below are only visible to the push menu mobile.', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_pushmenu_panel_position',
            'type'     => 'button_set',
            'title'    => __( 'Panel Position', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['menu']['pushmenu']['choose']['panel_positon'],
            'default'  => $wd_default_data['menu']['pushmenu']['default']['panel_positon'],
            /*'required' => array('wd_header_layout','=',''),*/
        ),
    )
) );