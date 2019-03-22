<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'Nav User', 'feellio' ),
    'id'               => 'wd_nav_user',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-user',
    'fields'     => array(
        array(
            'id'       => 'wd_nav_user_desktop_sorter',
            'type'     => 'sortable',
            'mode'     => 'checkbox', // checkbox or text
            'title'    => __( 'Desktop Layout', 'feellio' ),
            'subtitle' => __( 'Define and reorder these however you want.', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['nav_user']['choose']['sorter'],
            'default'  => $wd_default_data['nav_user']['default']['sorter_desktop'],
        ),
        array(
            'id'       => 'wd_nav_user_mobile_sorter',
            'type'     => 'sortable',
            'mode'     => 'checkbox', // checkbox or text
            'title'    => __( 'Mobile Layout', 'feellio' ),
            'subtitle' => __( 'Define and reorder these however you want.', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['nav_user']['choose']['sorter'],
            'default'  => $wd_default_data['nav_user']['default']['sorter_mobile'],
        ),
        array(
            'id'       => 'wd_nav_user_pushmenu_sorter',
            'type'     => 'sortable',
            'mode'     => 'checkbox', // checkbox or text
            'title'    => __( 'Pushmenu Layout', 'feellio' ),
            'subtitle' => __( 'Define and reorder these however you want.', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['nav_user']['choose']['sorter'],
            'default'  => $wd_default_data['nav_user']['default']['sorter_pushmenu'],
        ),
        array(
            'id'       => 'wd_nav_user_show_icon',
            'type'     => 'switch',
            'title'    => __( 'Display Icon', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['nav_user']['default']['show_icon'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),
        array(
            'id'       => 'wd_nav_user_show_text',
            'type'     => 'switch',
            'title'    => __( 'Display Title', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['nav_user']['default']['show_text'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),
        array(
            'id'       => 'wd_nav_user_dropdown_position',
            'type'     => 'button_set',
            'title'    => __( 'Dropdown Position', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['nav_user']['choose']['dropdown_position'],
            'default'  => $wd_default_data['nav_user']['default']['dropdown_position'],
            /*'required' => array('wd_header_layout','=',''),*/
        ),
    )
) );