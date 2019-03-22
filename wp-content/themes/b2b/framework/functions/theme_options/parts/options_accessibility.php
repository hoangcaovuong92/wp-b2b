<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'Accessibility', 'feellio' ),
    'id'               => 'wd_accessibility',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-wrench-alt',
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Back To Top', 'feellio' ),
    'id'               => 'wd_back_to_top',
    'desc'             => __( 'This feature will not show on mobile devices!', 'feellio' ),
    'subsection'       => true,
    'customizer_width' => '400px',
    'fields'     => array(
        array(
            'id'       => 'wd_back_to_top_display',
            'type'     => 'switch',
            'title'    => __( 'Display', 'feellio' ),
            'subtitle' => __( 'Enable/Disable scroll button in website.', 'feellio' ),
            'default'  => $wd_default_data['back_to_top']['default']['display'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_back_to_top_style',
            'type'     => 'radio',
            'title'    => __( 'Select Style', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['back_to_top']['choose']['style'],
            'default'  => $wd_default_data['back_to_top']['default']['style'],
            'required' => array('wd_back_to_top_display','=','1'),
        ),

        array(
            'id'       => 'wd_back_to_top_background_color',
            'type'     => 'color',
            'transparent'=> true,
            'title'    => __( 'Background Color', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['back_to_top']['default']['bg_color'],
            'required' => array('wd_back_to_top_style', '=', 'icon-background'),
        ),

        array(
            'id'       => 'wd_back_to_top_border_color',
            'type'     => 'color_rgba',
            'options'       => array(
                'show_input'                => true,
                'show_initial'              => true,
                'show_alpha'                => true,
                'show_palette'              => true,
                'show_palette_only'         => false,
                'show_selection_palette'    => true,
                'max_palette_size'          => 10,
                'allow_empty'               => true,
                'clickout_fires_change'     => false,
                'choose_text'               => 'Choose',
                'cancel_text'               => 'Cancel',
                'show_buttons'              => true,
                'use_extended_classes'      => true,
                'palette'                   => null,  // show default
                'input_text'                => 'Select Color'
            ),
            'title'    => __( 'Border Color', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['back_to_top']['default']['border_color'],
            'required' => array('wd_back_to_top_style', '=', 'icon-background'),
        ),

        array(
            'id'       => 'wd_back_to_top_background_shape',
            'type'     => 'radio',
            'title'    => __( 'Background Shape', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['back_to_top']['choose']['bg_shape'],
            'default'  => $wd_default_data['back_to_top']['default']['bg_shape'],
            'required' => array('wd_back_to_top_style', '=', 'icon-background'),
        ),

        array(
            'id'       => 'wd_back_to_top_select_icon',
            'type'     => 'select',
            'data'     => 'elusive-icons',
            'title'    => __( 'Select Icon', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['back_to_top']['default']['icon'],
            'required' => array('wd_back_to_top_display','=','1'),
        ),

        array(
            'id'       => 'wd_back_to_top_icon_color',
            'type'     => 'color',
            'transparent'=> false,
            'title'    => __( 'Icon Color', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['back_to_top']['default']['icon_color'],
            'required' => array('wd_back_to_top_display', '=', '1'),
        ),
        array(
            'id'       => 'wd_back_to_top_width',
            'type'     => 'text',
            'title'    => __( 'Width', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['back_to_top']['default']['width'],
            'required' => array('wd_back_to_top_display', '=', '1'),
        ),
        array(
            'id'       => 'wd_back_to_top_height',
            'type'     => 'text',
            'title'    => __( 'Height', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['back_to_top']['default']['height'],
            'required' => array('wd_back_to_top_display', '=', '1'),
        ),
        array(
            'id'       => 'wd_back_to_top_right',
            'type'     => 'text',
            'title'    => __( 'Right', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['back_to_top']['default']['right'],
            'required' => array('wd_back_to_top_display', '=', '1'),
        ),
        array(
            'id'       => 'wd_back_to_top_bottom',
            'type'     => 'text',
            'title'    => __( 'Bottom', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['back_to_top']['default']['bottom'],
            'required' => array('wd_back_to_top_display', '=', '1'),
        ),
    ) 
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Visual Effects', 'feellio' ),
    'id'               => 'wd_effect',
    'desc'             => __( '', 'feellio' ),
    'subsection'       => true,
    'customizer_width' => '400px',
    'fields'     => array(
        array(
            'id'       => 'wd_loading_effect_display',
            'type'     => 'switch',
            'title'    => __( 'Loading Effect', 'feellio' ),
            'subtitle' => __( 'Enable/Disable loading effect in website.', 'feellio' ),
            'default'  => $wd_default_data['effects']['default']['loading'],
            'on'       => 'Enable',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'wd_loading_effect_style',
            'type'     => 'radio',
            'title'    => __( 'Loading Style', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['effects']['choose']['loading_style'],
            'default'  => $wd_default_data['effects']['default']['loading_style'],
            'required' => array('wd_loading_effect_display','=','1'),
        ),
        array(
            'id'       => 'wd_sidebar_fixed_effect_display',
            'type'     => 'switch',
            'title'    => __( 'Sidebar Fixed', 'feellio' ),
            'subtitle' => __( 'Enable/Disable sidebar fixed effect in website.', 'feellio' ),
            'default'  => $wd_default_data['effects']['default']['sidebar_fixed'],
            'on'       => 'Enable',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'wd_scroll_smooth',
            'type'     => 'switch',
            'title'    => __( 'Scroll Smooth', 'feellio' ),
            'subtitle' => __( 'Enable/Disable scroll smooth effect.', 'feellio' ),
            'default'  => $wd_default_data['effects']['default']['scroll_smooth'],
            'on'       => 'Enable',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'wd_scroll_smooth_step',
            'type'     => 'text',
            'title'    => __( 'Smooth Step', 'feellio' ),
            'desc'     => __( 'Unit: pixel (px)', 'feellio' ),
            'subtitle' => __( 'Number of pixels on each mouse scroll.', 'feellio' ),
            'default'  => $wd_default_data['effects']['default']['scroll_smooth_step'],
            'required' => array('wd_scroll_smooth','=','1'),
        ),
        array(
            'id'       => 'wd_scroll_smooth_speed',
            'type'     => 'text',
            'title'    => __( 'Smooth Speed', 'feellio' ),
            'desc'     => __( 'Unit: milliseconds (ms)', 'feellio' ),
            'subtitle' => __( 'Number of time on each mouse scroll.', 'feellio' ),
            'default'  => $wd_default_data['effects']['default']['scroll_smooth_speed'],
            'required' => array('wd_scroll_smooth','=','1'),
        ),
    ) 
) );

if (class_exists('WD_Popup_Email')) {
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Email Subscriber Popup', 'feellio' ),
        'id'               => 'wd_email_subscriber_popup',
        'desc'             => __( '', 'feellio' ),
        'subsection'       => true,
        'customizer_width' => '400px',
        'fields'     => array(
            array(
                'id'       => 'wd_email_subscriber_popup_display',
                'type'     => 'switch',
                'title'    => __( 'Display', 'feellio' ),
                'subtitle' => __( 'Enable/Disable email subscriber popup in website.', 'feellio' ),
                'default'  => $wd_default_data['email_popup']['default']['display'],
                'on'       => 'Enable',
                'off'      => 'Disabled',
            ),
            array(
                'id'       => 'wd_email_subscriber_popup_only_home',
                'type'     => 'switch',
                'title'    => __( 'Only Home', 'feellio' ),
                'subtitle' => __( 'Enable/Disable email subscriber popup only on homepage.', 'feellio' ),
                'default'  => $wd_default_data['email_popup']['default']['only_home'],
                'on'       => 'Enable',
                'off'      => 'Disabled',
                'required' => array('wd_email_subscriber_popup_display','=','1'),
            ),

            array(
                'id'       => 'wd_email_subscriber_popup_mobile',
                'type'     => 'switch',
                'title'    => __( 'Mobile', 'feellio' ),
                'subtitle' => __( 'Enable/Disable email subscriber popup on mobile devices.', 'feellio' ),
                'default'  => $wd_default_data['email_popup']['default']['popup_mobile'],
                'on'       => 'Enable',
                'off'      => 'Disabled',
                'required' => array('wd_email_subscriber_popup_display','=','1'),
            ),
            array(
                'id'       => 'wd_email_subscriber_popup_delay_time',
                'type'     => 'text',
                'title'    => __( 'Delay Time', 'feellio' ),
                'subtitle' => __( 'Unit: seconds (s)', 'feellio' ),
                'desc'     => __( 'Time delay after page load to show popup.', 'feellio' ),
                'default'  => $wd_default_data['email_popup']['default']['delay_time'],
                'required' => array('wd_email_subscriber_popup_display','=','1'),
            ),
            array(
                'id'       => 'wd_email_subscriber_popup_session_expire',
                'type'     => 'text',
                'title'    => __( 'Session expire', 'feellio' ),
                'subtitle' => __( 'Unit: minutes (m)', 'feellio' ),
                'desc'     => __( 'Time does not allow popup display on page (for "Dont show this again" option). Set -1 to forever.', 'feellio' ),
                'default'  => $wd_default_data['email_popup']['default']['session_expire'],
                'required' => array('wd_email_subscriber_popup_display','=','1'),
            ),
            array(
                'id'       => 'wd_email_subscriber_popup_banner',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Banner', 'feellio' ),
                'compiler' => 'true',
                'desc'     => __( '', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'default'  => '',
                'required' => array('wd_email_subscriber_popup_display','=','1'),
            ),
            array(
                'id'       => 'wd_email_subscriber_popup_source',
                'type'     => 'radio',
                'title'    => __( 'Source', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'options'  => $wd_default_data['email_popup']['choose']['source'],
                'default'  => $wd_default_data['email_popup']['default']['source'],
                'required' => array('wd_email_subscriber_popup_display','=','1'),
            ),

            //Custom content
            array(
                'id'       => 'wd_email_subscriber_popup_custom_content',
                'type'     => 'editor',
                'title'    => __( 'Custom Content', 'feellio' ),
                'subtitle' => __( 'Custom HTML / Shortcode', 'feellio' ),
                'desc'     => __( 'You can create a shortcode from the new page creation interface.', 'feellio' ),
                'default'  => $wd_default_data['email_popup']['default']['custom_content'],
                'required' => array('wd_email_subscriber_popup_source','=','custom'),
            ),

            //Feedburner source
            array(
                'id'       => 'wd_email_subscriber_popup_feedburner_id',
                'type'     => 'text',
                'title'    => __( 'Feedburner ID', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['email_popup']['default']['feedburner_id'],
                'required' => array('wd_email_subscriber_popup_source','=','feedburner'),
            ),
            array(
                'id'       => 'wd_email_subscriber_popup_width',
                'type'     => 'text',
                'title'    => __( 'Width', 'feellio' ),
                'subtitle' => __( 'Unit: Pixel', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['email_popup']['default']['width'],
                'required' => array('wd_email_subscriber_popup_source','=','feedburner'),
            ),
            array(
                'id'       => 'wd_email_subscriber_popup_height',
                'type'     => 'text',
                'title'    => __( 'Height', 'feellio' ),
                'subtitle' => __( 'Unit: Pixel', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['email_popup']['default']['height'],
                'required' => array('wd_email_subscriber_popup_source','=','feedburner'),
            ),
            
            array(
                'id'       => 'wd_email_subscriber_popup_title',
                'type'     => 'text',
                'title'    => __( 'Title', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['email_popup']['default']['title'],
                'required' => array('wd_email_subscriber_popup_source','=','feedburner'),
            ),
            array(
                'id'       => 'wd_email_subscriber_popup_desc',
                'type'     => 'text',
                'title'    => __( 'Description', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['email_popup']['default']['desc'],
                'required' => array('wd_email_subscriber_popup_source','=','feedburner'),
            ),
            array(
                'id'       => 'wd_email_subscriber_popup_placeholder',
                'type'     => 'text',
                'title'    => __( 'Placeholder', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['email_popup']['default']['placeholder'],
                'required' => array('wd_email_subscriber_popup_source','=','feedburner'),
            ),
            array(
                'id'       => 'wd_email_subscriber_popup_button_text',
                'type'     => 'text',
                'title'    => __( 'Button Text', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['email_popup']['default']['button_text'],
                'required' => array('wd_email_subscriber_popup_source','=','feedburner'),
            ),
        ) 
    ) );
}