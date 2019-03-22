<?php
/**
 * Custom Walker
 *
 * @access      public
 * @since       1.0 
 * @return      void
 */
if (!class_exists('WD_Megamenu_Walker')) {
    class WD_Megamenu_Walker extends Walker_Nav_Menu {
        function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0){
            global $wp_query;
            $has_children                   = $args->walker->has_children;
            $has_parent                     = $item->menu_item_parent ? true : false;
            $is_megamenu                    = $item->megamenu;
            $submenu_custom_content_effect  = $item->submenu_custom_content_effect;
            $submenu_bg_source              = $item->submenu_bg_source;
            $submenu_bg_image               = $item->submenu_bg_image;
            $submenu_bg_color               = $item->submenu_bg_color;

            //Parent is megamenu?
            if ($has_parent) {
                $parent_setting                 = get_post_meta( $item->menu_item_parent, '_wd_menu_item_custom_field', true );
                $is_megamenu                    = isset($parent_setting['megamenu']) ? $parent_setting['megamenu'] : $is_megamenu;
                $submenu_custom_content_effect  = isset($parent_setting['submenu_custom_content_effect']) ? $parent_setting['submenu_custom_content_effect'] : $submenu_custom_content_effect;
                $submenu_bg_source              = isset($parent_setting['submenu_bg_source']) ? $parent_setting['submenu_bg_source'] : $submenu_bg_source;
                $submenu_bg_image               = isset($parent_setting['submenu_bg_image']) ? $parent_setting['submenu_bg_image'] : $submenu_bg_image;
                $submenu_bg_color               = isset($parent_setting['submenu_bg_color']) ? $parent_setting['submenu_bg_color'] : $submenu_bg_color;
            }

            $indent = ($depth) ? str_repeat("\t", $depth) : ''; 
            
            $li_class_names = $value = '';
            
            $classes            = empty($item->classes) ? array() : (array) $item->classes;
            
            $li_class_names     = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
            //$li_class_names     .= ($item->text_align) ? ' '.$item->text_align : ''; //text align
            $li_class_names     .= ($is_megamenu == '1') ? ' wd-submenu-enable-megamenu' : ' wd-submenu-disabled-megamenu'; //submenu megamenu
            $li_class_names     .= ($item->hide_title) ? ' wd-submenu-megamenu-hide-title' : ' wd-submenu-megamenu-show-title'; //Show/hide title
            $li_class_names     .= ' wd-menu-item-depth-'.$depth; //menu depth
            $li_class_names     .= ($item->submenu_width && $has_parent) ? ' wd-menu-item-submenu-width-'.str_replace('/', '-', $item->submenu_width) : ''; //Submenu width
            $li_class_names     .= ($is_megamenu && $submenu_custom_content_effect == 'hover') ? ' wd-submenu-custom-content-show-when-hover' : '';
            //$li_class_names     .= ($has_children) ? ' dropdown' : '';
            $li_class_names     = ' class="' . esc_attr($li_class_names) . '"';

            //background color
            $output             .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $li_class_names . '>';
            
            $link_attributes    = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
            $link_attributes    .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
            $link_attributes    .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
            $link_attributes    .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

            //text color
            $title_attributes    = ''; //parrent item
            
            $prepend     = '<h3 class="wd-menu-item-title" '.$title_attributes.'>';
            $append      = '</h3>';
            $description = !empty($item->description) ? '<span class="wd-menu-item-desc">' . esc_attr($item->description) . '</span>' : '';

            if ($depth != 0) {
                $description = $append = $prepend = "";
            }
            
            //Flag label
            $flag_label    = '';
            if(!$item->hide_title || !empty($item->icon_class)){
                $flag_label_text     = '';
                if ($item->flag_label == 'new') {
                    $flag_label_text = __( 'NEW', 'wd_package' );
                }elseif ($item->flag_label == 'sale') {
                    $flag_label_text = __( 'SALE', 'wd_package' );
                }elseif ($item->flag_label == 'hot') {
                    $flag_label_text = __( 'HOT', 'wd_package' );
                }
                $flag_label = ($flag_label_text != '') ? '<span class="wd-menu-item-flag wd-menu-item-flag-style-'.$item->flag_label.'">' . esc_html($flag_label_text) . '</span>' : '';
            }
            
            //icon
            $icon = !empty($item->icon_class) ? '<span class="'.$item->icon_class.'"></span>' : '';

            //Hide text title
            //var_dump($item);
            $item->title = (!$item->hide_title) ? $icon.' '.$item->title : $icon;

            //Custom Content
            $custom_content = '';
            if ($item->submenu_content_source && $is_megamenu) {
                $custom_content_class       = 'wd-submenu-custom-content-wrap';
                $custom_content_class       .= ($submenu_custom_content_effect == 'hover') ? ' wd-submenu-custom-content-show-when-hover' : '';
                $custom_content_attributes  = '';

                if ($has_parent && $submenu_custom_content_effect == 'hover') {
                    if ($submenu_bg_source == 'bg_image' && $submenu_bg_image) {
                    $custom_content_attributes  .= ' style="background-image: linear-gradient(rgba(10, 9, 9, 0.65), #2b2929e6), url('.esc_url(wp_get_attachment_image_src( $submenu_bg_image, 'full' )[0]).'); background-size: cover;"';
                    }elseif ($submenu_bg_source == 'bg_color' && $submenu_bg_color) {
                        $custom_content_attributes  .= ' style="background-color:' . esc_attr($submenu_bg_color) . ';"';
                    }
                }

                $custom_content .= '<div class="'.$custom_content_class.'"'.$custom_content_attributes.'>';
                ob_start();
                if ($item->submenu_content_source == 'widget-area' && $item->submenu_content_widget) {
                    if (is_active_sidebar($item->submenu_content_widget) ) {
                        dynamic_sidebar( $item->submenu_content_widget );
                    }
                }elseif ($item->submenu_content_source == 'megamenu-template' && $item->submenu_content_megamenu_template){
                    echo do_shortcode(get_post_field('post_content', $item->submenu_content_megamenu_template));
                    //echo apply_filters('the_content', get_post_field('post_content', $item->submenu_content_megamenu_template));
                }elseif ($item->submenu_content_source == 'custom-shortcode' && $item->submenu_content_custom_shortcode){
                    echo do_shortcode( $item->submenu_content_custom_shortcode );
                }
                $custom_content .= ob_get_clean();
                $custom_content .= '</div>';
            }
            
            $item_output = $args->before;
            $item_output .= '<a' . $link_attributes . '>';
            $item_output .= $args->link_before;
            $item_output .= $prepend . apply_filters('the_title', $item->title, $item->ID) . $flag_label . $append;
            $item_output .= $description;
            $item_output .= $args->link_after;
            $item_output .= $item->subtitle;
            $item_output .='</a>';
            $item_output .= $args->after;
            $item_output .= $custom_content;

            //setting for submenu
            $args->megamenu                             = $is_megamenu;
            $args->submenu_custom_content_effect        = $submenu_custom_content_effect;
            $args->columns                              = $item->columns;
            $args->submenu_bg_source                    = $submenu_bg_source;
            $args->submenu_bg_color                     = $submenu_bg_color;
            $args->submenu_bg_image                     = $submenu_bg_image;

            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }


        public function start_lvl( &$output, $depth = 0, $args = array() ) {
            if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = str_repeat( $t, $depth );
    
            // Default class.
            $classes = array( 'wd-sub-menu' );
    
            /**
             * Filters the CSS class(es) applied to a menu list element.
             *
             * @since 4.8.0
             *
             * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
             * @param stdClass $args    An object of `wp_nav_menu()` arguments.
             * @param int      $depth   Depth of menu item. Used for padding.
             */
            $class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
            $class_names .= ($args->megamenu == '1') ? ' wd-submenu-enable-megamenu' : ' wd-submenu-disabled-megamenu'; //submenu megamenu
            $class_names .= ($args->columns) ? ' wd-submenu-columns-'.$args->columns : ''; //submenu columns
            $class_names .= ' wd-submenu-depth-'.$depth; //menu depth
            $class_names .= ($args->submenu_bg_source == 'bg_image' && $args->submenu_bg_image) || ($args->submenu_bg_source == 'bg_color' && $args->submenu_bg_color) ? ' wd-submenu-with-bg-image-color' : '';
            $class_names .= ($args->megamenu == '1' && $args->submenu_custom_content_effect == 'hover') ? ' wd-submenu-custom-content-show-when-hover' : '';
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
            $attributes  = '';

            //Bg image / bg color
            if ($args->submenu_bg_source == 'bg_image' && $args->submenu_bg_image) {
            $attributes  .= ' style="background-image: linear-gradient(rgba(10, 9, 9, 0.65), #2b2929e6), url('.esc_url(wp_get_attachment_image_src( $args->submenu_bg_image, 'full' )[0]).'); background-size: cover;"';
            }elseif ($args->submenu_bg_source == 'bg_color' && $args->submenu_bg_color) {
                $attributes  .= ' style="background-color:' . esc_attr($args->submenu_bg_color) . ';"';
            }
    
            $output .= "{$n}{$indent}<ul$class_names $attributes>{$n}";
        }

        public function end_lvl( &$output, $depth = 0, $args = array() ) {
            if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = str_repeat( $t, $depth );
            $output .= "$indent</ul>{$n}";
        }

        public function end_el( &$output, $item, $depth = 0, $args = array() ) {
            if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $output .= "</li>{$n}";
        }
    }
}