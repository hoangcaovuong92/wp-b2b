<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'Comments', 'feellio' ),
    'id'               => 'wd_comment_setting',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-comment-alt',
    'fields'           => array(
        array(
            'id'       => 'wd_comment_sorter',
            'type'     => 'sortable',
            'mode'     => 'checkbox', // checkbox or text
            'title'    => __( 'Comment Form', 'feellio' ),
            'subtitle' => __( 'Define and reorder these however you want.', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['comment']['choose']['sorter'],
            'default'  => $wd_default_data['comment']['default']['sorter'],
        ),

        array(
            'id'       => 'wd_comment_layout_style',
            'type'     => 'radio',
            'title'    => __( 'Layout', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['comment']['choose']['layout'],
            'default'  => $wd_default_data['comment']['default']['layout'],
        ),

        array(
           'id'       => 'wd_comment_setting_section_start',
            'type'     => 'section',
            'title'    => __( 'Facebook Comment Settings', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'indent'   => true,
        ),

        /****************************/
            array(
                'id'       => 'wd_comment_facebook_display_on_single_product',
                'type'     => 'switch',
                'title'    => __( 'Single Product', 'feellio' ),
                'subtitle' => __( 'Show facebook comment form on product details page', 'feellio' ),
                'default'  => $wd_default_data['comment']['default']['single_product'],
                'on'       => 'Show',
                'off'      => 'Hide',
            ),
            array(
                'id'       => 'wd_comment_facebook_number_comment_display',
                'type'     => 'text',
                'title'    => __( 'Number Comment Display', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['comment']['default']['number_comment'],
            ),
            array(
                'id'       => 'wd_comment_facebook_mode',
                'type'     => 'button_set',
                'title'    => __( 'Comment Mode', 'feellio' ),
                'subtitle' => __( 'Select "Multi Domain" if you intend to change the domain and want to keep the old comments.', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'options'  => $wd_default_data['comment']['choose']['mode'],
                'default'  => $wd_default_data['comment']['default']['mode'],
            ),
        /****************************/
        
        array(
            'id'     => 'wd_comment_setting_section_end',
            'type'   => 'section',
            'indent' => false,
        ),
    )
) );
?>