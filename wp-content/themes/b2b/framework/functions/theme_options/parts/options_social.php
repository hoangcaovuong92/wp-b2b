<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'Socials', 'feellio' ),
    'id'               => 'wd_social_setting',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-facebook',
) );

if (class_exists('WD_Facebook_Chatbox')) {
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Facebook Chatbox', 'feellio' ),
        'id'               => 'wd_facebook_chatbox',
        'desc'             => __( '', 'feellio' ),
        'subsection'       => true,
        'customizer_width' => '400px',
        'fields'     => array(
            array(
                'id'       => 'wd_facebook_chatbox_display',
                'type'     => 'switch',
                'title'    => __( 'Display', 'feellio' ),
                'subtitle' => __( 'Enable/Disable facebook chatbox in website.', 'feellio' ),
                'default'  => $wd_default_data['fb_chatbox']['default']['display'],
                'on'       => 'Enable',
                'off'      => 'Disabled',
            ),
            array(
                'id'       => 'wd_facebook_chatbox_url',
                'type'     => 'text',
                'title'    => __( 'Fanpage URL', 'feellio' ),
                'subtitle' => __( 'Enter your fanpage facebook url...', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['fb_chatbox']['default']['url'],
                'required' => array('wd_facebook_chatbox_display','=','1'),
            ),
            array(
                'id'       => 'wd_facebook_chatbox_width',
                'type'     => 'text',
                'title'    => __( 'Width', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['fb_chatbox']['default']['width'],
                'required' => array('wd_facebook_chatbox_display','=','1'),
            ),
            array(
                'id'       => 'wd_facebook_chatbox_height',
                'type'     => 'text',
                'title'    => __( 'Height', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['fb_chatbox']['default']['height'],
                'required' => array('wd_facebook_chatbox_display','=','1'),
            ),
            array(
                'id'       => 'wd_facebook_chatbox_right',
                'type'     => 'text',
                'title'    => __( 'Right', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['fb_chatbox']['default']['right'],
                'required' => array('wd_facebook_chatbox_display','=','1'),
            ),
            array(
                'id'       => 'wd_facebook_chatbox_bottom',
                'type'     => 'text',
                'title'    => __( 'Bottom', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['fb_chatbox']['default']['bottom'],
                'required' => array('wd_facebook_chatbox_display','=','1'),
            ),
            array(
                'id'       => 'wd_facebook_chatbox_default_mode',
                'type'     => 'radio',
                'title'    => __( 'Default Layout', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'options'  => $wd_default_data['fb_chatbox']['choose']['default_mode'],
                'default'  => $wd_default_data['fb_chatbox']['default']['default_mode'],
                'required' => array('wd_facebook_chatbox_display','=','1'),
            ),
            array(
                'id'       => 'wd_facebook_chatbox_bg_color',
                'type'     => 'color',
                'transparent'=> false,
                'title'    => __( 'Background Color', 'feellio' ),
                'subtitle' => sprintf(__( '(Default: %s).', 'feellio' ), $wd_default_data['fb_chatbox']['default']['bg_color']),
                'default'  => $wd_default_data['fb_chatbox']['default']['bg_color'],
                'required' => array('wd_facebook_chatbox_display','=','1'),
            ),
            array(
                'id'       => 'wd_facebook_chatbox_logo',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Logo', 'feellio' ),
                'compiler' => 'true',
                'desc'     => __( '', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'default'  => array( 'url' => $wd_default_data['fb_chatbox']['default']['logo'] ),
                'required' => array('wd_facebook_chatbox_display','=','1'),
            ),
            array(
                'id'       => 'wd_facebook_chatbox_text_footer',
                'type'     => 'text',
                'title'    => __( 'Text Footer', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( 'Text displays when the chatbox window is hidden', 'feellio' ),
                'default'  => $wd_default_data['fb_chatbox']['default']['text_footer'],
                'required' => array('wd_facebook_chatbox_display','=','1'),
            ),
            array(
                'id'       => 'wd_facebook_chatbox_link_caption',
                'type'     => 'text',
                'title'    => __( 'Link Caption', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['fb_chatbox']['default']['link_caption'],
                'required' => array('wd_facebook_chatbox_display','=','1'),
            ),
            array(
                'id'       => 'wd_facebook_chatbox_link_url',
                'type'     => 'text',
                'title'    => __( 'Link URL', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['fb_chatbox']['default']['link_url'],
                'required' => array('wd_facebook_chatbox_display','=','1'),
            ),
        ) 
    ) );
}

Redux::setSection( $opt_name, array(
    'title'            => __( 'Social Share', 'feellio' ),
    'id'               => 'wd_share_button',
    'desc'             => __( '', 'feellio' ),
    'subsection'       => true,
    'customizer_width' => '400px',
    'fields'     => array(
        array(
            'id'       => 'wd_share_button_display',
            'type'     => 'switch',
            'title'    => __( 'Display', 'feellio' ),
            'subtitle' => __( 'Enable/Disable all social share button in website.', 'feellio' ),
            'default'  => $wd_default_data['social_share']['default']['display'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_share_button_title_display',
            'type'     => 'switch',
            'title'    => __( 'Title', 'feellio' ),
            'subtitle' => __( 'Enable/Disable title.', 'feellio' ),
            'default'  => $wd_default_data['social_share']['default']['title_display'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_share_button_custom_pubid',
            'type'     => 'text',
            'title'    => __( 'Addthis Profile ID', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['social_share']['default']['pubid'],
            'required' => array('wd_share_button_display','=','1'),
        ),

        array(
            'id'       => 'wd_share_button_button_class',
            'type'     => 'text',
            'title'    => __( 'Addthis Button Class', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['social_share']['default']['button_class'],
            'required' => array('wd_share_button_display','=','1'),
        ),
        
    ) 
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Social Link', 'feellio' ),
    'id'               => 'wd_social_link',
    'desc'             => __( 'Set the default social link for the default site. Leave blank to hide a button.', 'feellio' ),
    'subsection'       => true,
    'customizer_width' => '400px',
    'fields'     => array(
        array(
            'id'       => 'wd_social_link_rss_id',
            'type'     => 'text',
            'title'    => __( 'RSS ID', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['social_link']['default']['rss_id'],
        ),
        array(
            'id'       => 'wd_social_link_twitter_id',
            'type'     => 'text',
            'title'    => __( 'Twitter ID', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['social_link']['default']['twitter_id'],
        ),
        array(
            'id'       => 'wd_social_link_facebook_id',
            'type'     => 'text',
            'title'    => __( 'Facebook ID', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['social_link']['default']['facebook_id'],
        ),
        array(
            'id'       => 'wd_social_link_google_id',
            'type'     => 'text',
            'title'    => __( 'Google Plus ID', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['social_link']['default']['google_id'],
        ),
        array(
            'id'       => 'wd_social_link_pin_id',
            'type'     => 'text',
            'title'    => __( 'Pinterest ID', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['social_link']['default']['pin_id'],
        ),
        array(
            'id'       => 'wd_social_link_youtube_id',
            'type'     => 'text',
            'title'    => __( 'Youtube ID', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['social_link']['default']['youtube_id'],
        ),
        array(
            'id'       => 'wd_social_link_instagram_id',
            'type'     => 'text',
            'title'    => __( 'Instagram ID', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['social_link']['default']['instagram_id'],
        ),
    ) 
) );

if (class_exists('WD_Instagram')) {
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Instagram API', 'feellio' ),
        'id'               => 'wd_social_instagram',
        'desc'             => __( '', 'feellio' ),
        'subsection'       => true,
        'customizer_width' => '400px',
        'fields'     => array(
            array(
                'id'       => 'wd_social_instagram_user',
                'type'     => 'text',
                'title'    => __( 'Username ID', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => sprintf(__('<a href="%s" target="_blank">Get instagram user id by username</a>', 'feellio'), 'https://smashballoon.com/instagram-feed/find-instagram-user-id/'),
                'default'  => $wd_default_data['social_instagram']['default']['insta_user'],
            ),
            array(
                'id'       => 'wd_social_instagram_client_id',
                'type'     => 'text',
                'title'    => __( 'Client ID', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => sprintf(__('<a href="%s" target="_blank">How to get client id?</a>', 'feellio'), 'https://elfsight.com/blog/2016/05/how-to-get-instagram-access-token/'),
                'default'  => $wd_default_data['social_instagram']['default']['insta_client_id'],
            ),
            array(
                'id'       => 'wd_social_instagram_access_token',
                'type'     => 'text',
                'title'    => __( 'Access Token', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => sprintf(__('<a href="%s" target="_blank">How to get Access Token?</a>', 'feellio'), 'https://elfsight.com/blog/2016/05/how-to-get-instagram-access-token/'),
                'default'  => $wd_default_data['social_instagram']['default']['insta_access_token'],
            ),
        ) 
    ) );
}

Redux::setSection( $opt_name, array(
    'title'            => __( 'Google API', 'feellio' ),
    'id'               => 'wd_google_map_api_section',
    'desc'             => __( '', 'feellio' ),
    'subsection'       => true,
    'customizer_width' => '400px',
    // 'icon'             => 'el el-map-marker',
    'fields'     => array(
        array(
            'id'       => 'wd_google_map_api_key',
            'type'     => 'text',
            'title'    => __( 'Google Map API Key', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['google_map']['default']['api_key'],
        ),
        array(
            'id'       => 'wd_google_map_zoom',
            'type'     => 'text',
            'title'    => __( 'Map Zoom', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['google_map']['default']['zoom'],
        ),
    ) 
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Facebook API', 'feellio' ),
    'id'               => 'wd_facebook_api_section',
    'desc'             => __( '', 'feellio' ),
    'subsection'       => true,
    'customizer_width' => '400px',
    'fields'     => array(
        array(
            'id'       => 'wd_comment_facebook_user_id',
            'type'     => 'text',
            'title'    => __( 'User ID', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( 'Enter the facebook id of the administrator', 'feellio' ),
            'default'  => $wd_default_data['general']['default']['user_id'],
        ),
        array(
            'id'       => 'wd_comment_facebook_app_id',
            'type'     => 'text',
            'title'    => __( 'App ID', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['general']['default']['app_id'],
        ),
    ) 
) );