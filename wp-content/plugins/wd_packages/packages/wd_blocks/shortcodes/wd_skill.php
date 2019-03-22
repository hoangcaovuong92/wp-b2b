<?php
if (!function_exists('wd_skill_function')) {
	function wd_skill_function($atts) {
        $args = array(
            'style'                 => "style-1",
            'show_icon_font_image'  => "1",
            'class_icon_font'       => "fa-rocket",
            'image_pricing_url'     => "",
            'title'                 => "Basic Plan",
            'description'           => "",
            'price'                 => "0",
            'currency'              => "$",
            'price_period'          => "month",
            'link'                  => "http://wpdance.com/",
            'target'                => "",
            'button_text'           => "Buy Now",
            'active'                => ""
        );  
		extract(shortcode_atts($args, $atts));
           
	    $html = ""; 
	        
        if($target == ""){
            $target = "_self";
        }
        ob_start();
        ?>
        
        <div class='wd_price_table price_<?php echo esc_attr($style); ?>'>
            <div class="price_table_inner <?php if($active == "yes") echo 'acitve_price'; ?>">
            <?php if($style == "style-3") : ?>
                <div class="wd-feature-icon "><a class="feature_icon fa fa-4x <?php echo esc_attr($class_icon_font) ?>"></a></div> 
            <?php endif; ?>             
                <ul>
                    <?php if($style == "style-1" || $style == "style-2" || $style == "style-4" || $style == "style-6") : ?>
                        <li class='prices'>
                            <span class='price_in_table'>
                                <span class='value'><?php echo esc_attr($currency); ?></span>
                                <span class='pricing'><?php echo esc_attr($price); ?></span>
                                <span class='mark'><?php echo esc_attr($price_period); ?></span>
                            </span>
                        </li> <!-- close price li wrapper -->
                        <?php if($style == "style-4" && $description != "") : ?>
                            <li class='description'><?php echo esc_attr($description); ?></li> 
                        <?php endif; ?>
                        <li class='cell table_title'><h1><?php echo esc_attr($title); ?></h1></li> 
                	<?php endif; ?>

                    <?php if($style == "style-3" || $style == "style-5" || $style == "style-7" || $style == "style-8") : ?>
                        <li class='cell table_title'><h1><?php echo esc_attr($title); ?></h1></li>
                        <?php if($style == "style-7" && $description != "") : ?>
                            <li class='description'><?php echo esc_attr($description); ?></li> 
                        <?php endif; ?> 
                        <li class='prices'>
                            <?php if($style == "style-8") : ?>
                                <?php echo apply_filters('wd_filter_image_html', array('attachment' => $image_pricing_url)); ?>
                            <?php endif; ?>
                            <span class='price_in_table'>
                                <span class='value'><?php echo esc_attr($currency); ?></span>
                                <span class='pricing'><?php echo esc_attr($price); ?></span>
                                <span class='mark'><?php echo esc_attr($price_period); ?></span>
                            </span>
                        </li> <!-- close price li wrapper --> 
                    <?php endif; ?>        	    
                	<li><?php echo ($content); ?></li> <!-- append pricing table content -->

                	<li class='price_button'>
                	   <a class='button normal' href='<?php echo esc_url($link); ?>' target='<?php echo esc_attr($target); ?>'><?php echo esc_attr($button_text); ?></a>
                	</li> <!-- close button li wrapper -->
            	    
            	</ul>
            </div>
        </div>
        
	    <?php
        $output = ob_get_clean();
        wp_reset_postdata();
        return $output; 
	}
}

if (!function_exists('wd_skill_vc_map')) {
	function wd_skill_vc_map() {
		return array(
            'name' 				=> esc_html__("WD - Skill", 'wd_package'),
            'base' 				=> 'wd_skill',
            'description' 		=> esc_html__("Skill", 'wd_package'),
            'category' 			=> esc_html__("WD - Content", 'wd_package'),
            'icon'        		=> 'icon-wpb-vc_carousel',
            "params" 			=> array(
                array(
                    'type' 			=> 'dropdown',
                    'heading' 		=> esc_html__( 'Style', 'wd_package' ),
                    'param_name' 	=> 'style',
                    'admin_label' 	=> true,
                    'value' 		=> array(
                        esc_html__( 'Style 1', 'wd_package' )	=> 'style-1',
                        esc_html__( 'Style 2', 'wd_package' )	=> 'style-2',
                        esc_html__( 'Style 3', 'wd_package' )	=> 'style-3',
                        esc_html__( 'Style 4', 'wd_package' )	=> 'style-4',
                        esc_html__( 'Style 5', 'wd_package' )	=> 'style-5',
                        esc_html__( 'Style 6', 'wd_package' )	=> 'style-6',
                        esc_html__( 'Style 7', 'wd_package' )	=> 'style-7',
                        esc_html__( 'Style 8', 'wd_package' )	=> 'style-8'
                    ),
                    'description' 	=> ''
                ),
                array(
                    'type' 			=> 'dropdown',
                    'heading' 		=> esc_html__( 'Show image or icon font', 'wd_package' ),
                    'param_name' 	=> 'show_icon_font_image',
                    'admin_label' 	=> true,
                    'value' 		=> array(
                        esc_html__( 'Show icon font', 'wd_package' )	=> '1',
                        esc_html__( 'Show Image', 'wd_package' )		=> '0'
                    ),
                    'description' 	=> ''
                ),
                array(
                    'type' 			=> 'iconpicker',
                    'heading' 		=> esc_html__( 'Icon font', 'wd_package' ),
                    'param_name' 	=> 'class_icon_font',
                    'value' 		=> 'fa fa-adjust', 
                    'settings' 		=> array(
                        esc_html__( 'emptyIcon', 'wd_package' ) 	=> false,
                        esc_html__( 'iconsPerPage', 'wd_package' ) 	=> 4000,
                    ),
                    'description' 	=> esc_html__( 'Select icon from library.', 'wd_package' ),
                    'dependency'  	=> array('element' => "show_icon_font_image", 'value' => array('1'))
                ),
                array(
                    "type" 			=> "attach_image",
                    "class" 		=> "",
                    "heading" 		=> esc_html__("Image Pricing", 'wd_package'),
                    "description" 	=> esc_html__("Image pricing", 'wd_package'),
                    "param_name" 	=> "image_pricing_url",
                    "value" 		=> "",
                    'dependency'  	=> array('element' => "show_icon_font_image", 'value' => array('0'))
                ),
                array(
                    "type" 			=> "textfield",
                    "holder" 		=> "div",
                    "class" 		=> "",
                    "heading" 		=> esc_html__("Title", 'wd_package'),
                    "param_name" 	=> "title",
                    "value" 		=> esc_html__("Basic Plan", 'wd_package'),
                    "description" 	=> ""
                ),
                array(
                    "type" 			=> "textfield",
                    "holder" 		=> "div",
                    "class" 		=> "",
                    "heading" 		=> esc_html__("Description", 'wd_package'),
                    "param_name" 	=> "description",
                    "value" 		=> "",
                    "description" 	=> ""
                ),
                array(
                    "type" 			=> "textfield",
                    "holder" 		=> "div",
                    "class" 		=> "",
                    "heading" 		=> esc_html__("Price", 'wd_package'),
                    "param_name" 	=> "price",
                    "description" 	=> "",
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    "type" 			=> "textfield",
                    "holder" 		=> "div",
                    "class" 		=> "",
                    "heading" 		=> esc_html__("Currency", 'wd_package'),
                    "param_name" 	=> "currency",
                    "description" 	=> "",
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    "type" 			=> "textfield",
                    "holder" 		=> "div",
                    "class" 		=> "",
                    "heading" 		=> esc_html__("Price Period", 'wd_package'),
                    "param_name" 	=> "price_period",
                    "description" 	=> "",
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    "type" 			=> "textfield",
                    "holder" 		=> "div",
                    "class" 		=> "",
                    "heading" 		=> esc_html__("Link", 'wd_package'),
                    "param_name" 	=> "link",
                    "description" 	=> "",
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    "type" 			=> "dropdown",
                    "holder" 		=> "div",
                    "class" 		=> "",
                    "heading" 		=> esc_html__("Target", 'wd_package'),
                    "param_name" 	=> "target",
                    "value" 		=> wd_vc_get_list_link_target(),
                    "description" 	=> "",
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    "type" 			=> "textfield",
                    "holder" 		=> "div",
                    "class" 		=> "",
                    "heading" 		=> esc_html__("Button Text", 'wd_package'),
                    "param_name" 	=> "button_text",
                    "description" 	=> "",
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    "type" 			=> "dropdown",
                    "holder" 		=> "div",
                    "class" 		=> "",
                    "heading" 		=> esc_html__("Active", 'wd_package'),
                    "param_name" 	=> "active",
                    "value" 		=> array(
                        esc_html__( "No", 'wd_package' ) 			=> "no",
                        esc_html__( "Yes", 'wd_package' ) 			=> "yes"	
                    ),
                    "description" 	=> "",
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    "type" 			=> "textarea_html",
                    "holder" 		=> "div",
                    "class" 		=> "",
                    "heading" 		=> esc_html__("Content", 'wd_package'),
                    "param_name" 	=> "content",
                    "value" 		=> "Lorem ipsum dolor sit amet, consectetur adipisicing elit.",
                    "description" 	=> ""
                )
            )
        );
	}
}