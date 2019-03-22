<?php 
if (!wd_is_woocommerce()) return;
Redux::setSection( $opt_name, array(
    'title'            => __( 'Products', 'feellio' ),
    'id'               => 'wd_woocommerce_setting',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-shopping-cart-sign'
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Product Config', 'feellio' ),
    'id'               => 'wd_layout_product_layout',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array(

        array(
            'id'       => 'wd_layout_product_config_display_buttons',
            'type'     => 'switch',
            'title'    => __( 'Display Buttons', 'feellio' ),
            'subtitle' => __( 'Show/Hide Add To Cart, Compare, Wishlist button on your site', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['woo']['config']['default']['display_buttons'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),
        array(
           'id'       => 'wd_layout_product_config_display_buttons_section_start',
            'type'     => 'section',
            'title'    => __( 'Group Button Settings', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'indent'   => true,
            'required' => array('wd_layout_product_config_display_buttons','=', '1' ),
        ),

        /****************************/
            array(
                'id'       => 'wd_layout_product_config_button_group_position',
                'type'     => 'radio',
                'title'    => __( 'Button Position', 'feellio' ),
                'subtitle' => __( 'Position of the buttons: add to cart, compare, wishlist on shop loop', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'options'  => $wd_default_data['woo']['config']['choose']['button_position'],
                'default'  => $wd_default_data['woo']['config']['default']['button_position'],
                'required' => array('wd_layout_product_config_display_buttons','=', '1' ),
            ),

            array(
                'id'       => 'wd_layout_product_config_wishlist_default',
                'type'     => 'switch',
                'title'    => __( 'Wishlist Button Default', 'feellio' ),
                'subtitle' => __( 'In some cases, the layout will have surplus wishlist buttons on single product page. Disable them to avoid errors.', 'feellio' ),
                'default'  => $wd_default_data['woo']['config']['default']['wishlist_default'],
                'on'       => 'Enable',
                'off'      => 'Disabled',
                'required' => array('wd_layout_product_config_display_buttons','=', '1' ),
            ),

            array(
                'id'       => 'wd_layout_product_config_compare_default',
                'type'     => 'switch',
                'title'    => __( 'Compare Button Default', 'feellio' ),
                'subtitle' => __( 'In some cases, the layout will have surplus compare buttons on single product page. Disable them to avoid errors.', 'feellio' ),
                'default'  => $wd_default_data['woo']['config']['default']['compare_default'],
                'on'       => 'Enable',
                'off'      => 'Disabled',
                'required' => array('wd_layout_product_config_display_buttons','=', '1' ),
            ),
        /****************************/
        
        array(
            'id'     => 'wd_layout_product_config_display_buttons_section_end',
            'type'   => 'section',
            'indent' => false,
            'required' => array('wd_layout_product_config_display_buttons','=', '1' ),
        ),

        array(
            'id'       => 'wd_layout_product_config_title_display',
            'type'     => 'switch',
            'title'    => __( 'Product Title', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['woo']['config']['default']['title'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_product_config_number_title_word',
            'type'     => 'text',
            'title'    => __( 'Number Title Words', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( 'Set -1 to display the full title.', 'feellio' ),
            'default'  => $wd_default_data['woo']['config']['default']['title_word'],
            'required' => array('wd_layout_product_config_title_display','=', '1' ),
        ),

        array(
            'id'       => 'wd_layout_product_config_description_display',
            'type'     => 'switch',
            'title'    => __( 'Product Description', 'feellio' ),
            'subtitle' => __( 'Hide Product Description may not work with some cases: list view mode in the shop page, shortcode single product detail...', 'feellio' ),
            'default'  => $wd_default_data['woo']['config']['default']['desc'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_product_config_number_desc_word',
            'type'     => 'text',
            'title'    => __( 'Number Description Words', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( 'Set -1 to display the full description.', 'feellio' ),
            'default'  => $wd_default_data['woo']['config']['default']['desc_word'],
            /*'required' => array('wd_layout_product_config_description_display','=', '1' ),*/
        ),

        array(
            'id'       => 'wd_layout_product_config_rating_display',
            'type'     => 'switch',
            'title'    => __( 'Product Rating', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['woo']['config']['default']['rating'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_product_config_price_display',
            'type'     => 'switch',
            'title'    => __( 'Product Price', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['woo']['config']['default']['price'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_product_config_price_decimal_display',
            'type'     => 'switch',
            'title'    => __( 'Price Decimals', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['woo']['config']['default']['price_decimal'],
            'on'       => 'Show',
            'off'      => 'Hide',
            'required' => array('wd_layout_product_config_price_display','=', '1' ),
        ),

        array(
            'id'       => 'wd_layout_product_config_meta_display',
            'type'     => 'switch',
            'title'    => __( 'Product Meta', 'feellio' ),
            'subtitle' => __( 'Show/Hide sale/featured product', 'feellio' ),
            'default'  => $wd_default_data['woo']['config']['default']['meta'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),
    )
) );  

Redux::setSection( $opt_name, array(
    'title'            => __( 'Product Visual', 'feellio' ),
    'id'               => 'wd_product_visual',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( 'Setting Product Visual Effect', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_product_effect_popup_cart',
            'type'     => 'switch',
            'title'    => __( 'Popup Add To Cart', 'feellio' ),
            'subtitle' => __( 'Enable / Disable popup display mini cart info after add to cart with ajax.', 'feellio' ),
            'default'  => $wd_default_data['woo']['visual']['default']['popup_cart'],
            'on'       => 'Enable',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'wd_product_effect_hover_thumbnail',
            'type'     => 'switch',
            'title'    => __( 'Hover Change Thumbnail', 'feellio' ),
            'subtitle' => __( 'Enable / Disable thumbnail change effect when hover product image. Effects disabled on mobile devices.', 'feellio' ),
            'default'  => $wd_default_data['woo']['visual']['default']['hover_thumbnail'],
            'on'       => 'Enable',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'wd_product_effect_hover_style',
            'type'     => 'image_select',
            'title'    => __( 'Thumbnail Hover Style', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['woo']['visual']['choose']['hover_style'],
            'default'  => $wd_default_data['woo']['visual']['default']['hover_style']
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Archive Product', 'feellio' ),
    'id'               => 'wd_layout_archive_product',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_layout_archive_product_layout',
            'type'     => 'image_select',
            'title'    => __( 'Select The Layout', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['layout']['choose'],
            'default'  => $wd_default_data['layout']['default']['product_archive'],
        ),

        array(
            'id'       => 'wd_layout_archive_product_left_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Left Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['archive_product_left'],
            /*'required' => array('wd_layout_archive_product_layout','=',array( '1-0-0', '1-0-1' ) ),*/
        ),

        array(
            'id'       => 'wd_layout_archive_product_right_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Right Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['archive_product_right'],
            /*'required' => array('wd_layout_archive_product_layout','=',array( '0-0-1', '1-0-1' ) ),*/
        ),

        array(
            'id'       => 'wd_layout_archive_product_posts_per_page',
            'type'     => 'text',
            'title'    => __( 'Posts Per Page', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( 'Number products display on each page', 'feellio' ),
            'default'  => $wd_default_data['woo']['archive']['default']['posts_per_page'],
        ),

        array(
            'id'       => 'wd_layout_archive_product_columns',
            'type'     => 'button_set',
            'title'    => __( 'Columns', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['columns']['choose'],
            'default'  => $wd_default_data['columns']['default']['product_archive'], 
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Single Product', 'feellio' ),
    'id'               => 'wd_layout_single_product',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_layout_single_product_layout',
            'type'     => 'image_select',
            'title'    => __( 'Select The Layout', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['layout']['choose'],
            'default'  => $wd_default_data['layout']['default']['single_product'],
        ),

        array(
            'id'       => 'wd_layout_single_product_left_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Left Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['single_product_left'],
            /*'required' => array('wd_layout_single_product_layout','=',array( '1-0-0', '1-0-1' ) ),*/
        ),

        array(
            'id'       => 'wd_layout_single_product_right_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Right Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['single_product_right'],
            /*'required' => array('wd_layout_single_product_layout','=',array( '0-0-1', '1-0-1' ) ),*/
        ),

        array(
            'id'       => 'wd_layout_single_product_position_thumbnail',
            'type'     => 'radio',
            'title'    => __( 'Position Thumbnail', 'feellio' ),
            'subtitle' => __( 'The position of the thumbnail slider compared to the large thumbnail.', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['woo']['single']['choose']['position_thumbnail'],
            'default'  => $wd_default_data['woo']['single']['default']['position_thumbnail'],
        ),

        array(
            'id'       => 'wd_layout_single_product_thumbnail_number',
            'type'     => 'text',
            'title'    => __( 'Thumbnail Number', 'feellio' ),
            'subtitle' => __( 'The maximum number of thumbnails on the slider.', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['woo']['single']['default']['thumbnail_number'],
        ),

        array(
            'id'       => 'wd_layout_single_product_summary_layout',
            'type'     => 'sortable',
            'mode'     => 'checkbox', // checkbox or text
            'title'    => __( 'Product Summary Layout', 'feellio' ),
            'subtitle' => __( 'Custom content layout for single product template. Define and reorder these however you want.', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['woo']['single']['choose']['summary_layout'],
            'default'  => $wd_default_data['woo']['single']['default']['summary_layout'],
        ),

        array(
            'id'       => 'wd_layout_single_product_recent_product',
            'type'     => 'switch',
            'title'    => __( 'Recent Product', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['woo']['single']['default']['recent'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_single_product_upsell_product',
            'type'     => 'switch',
            'title'    => __( 'Upsell Product', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['woo']['single']['default']['upsell'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Woo Page Template', 'feellio' ),
    'id'               => 'wd_layout_woo_template',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( 'Setting for pages use layout WooCommerce Template', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_layout_woo_template_layout',
            'type'     => 'image_select',
            'title'    => __( 'Select The Layout', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['layout']['choose'],
            'default'  => $wd_default_data['layout']['default']['product_archive'],
        ),

        array(
            'id'       => 'wd_layout_woo_template_left_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Left Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['woo_template_left'],
            /*'required' => array('wd_layout_woo_template_layout','=',array( '1-0-0', '1-0-1' ) ),*/
        ),

        array(
            'id'       => 'wd_layout_woo_template_right_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Right Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['woo_template_right'],
            /*'required' => array('wd_layout_woo_template_layout','=',array( '0-0-1', '1-0-1' ) ),*/
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Cart Page', 'feellio' ),
    'id'               => 'wd_layout_cart_page',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_layout_cart_page_payment_method',
            'type'     => 'editor',
            'title'    => __( 'Payment Mothod', 'feellio' ),
            'subtitle' => __( 'HTML/Shortcode will display on the left Cart Total.', 'feellio' ),
            'desc'     => __( 'You can create a shortcode from the new page creation interface.', 'feellio' ),
            'default'  => $wd_default_data['woo']['cart_page']['default']['payment_method'],
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Sale Flash', 'feellio' ),
    'id'               => 'wd_layout_sale_flash',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_layout_product_sale_flash_text',
            'type'     => 'text',
            'title'    => __( 'Text', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['woo']['sale_flash']['default']['text'],
        ),
        array(
            'id'       => 'wd_layout_product_sale_flash_percent',
            'type'     => 'switch',
            'title'    => __( 'Percent Sale', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['woo']['sale_flash']['default']['percent'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),
    )
) );