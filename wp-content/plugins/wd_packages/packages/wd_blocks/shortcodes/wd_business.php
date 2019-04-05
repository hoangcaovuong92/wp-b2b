<?php
if (!function_exists('wd_business_function')) {
	function wd_business_function($atts) {
		extract(shortcode_atts(array(
			'id_category'			=> '-1',
			'data_show'				=> 'recent_blog',
			'number_blogs'			=> '12',
			'sort'					=> 'DESC',
			'order_by'				=> 'date',
			'columns'				=> '3',
			'columns_tablet'		=> 2,
			'columns_mobile'		=> 1,
			'padding'				=> 'normal',
			'grid_list_button'		=> '1',
			'grid_list_layout'		=> 'grid',
			'grid_hover_style'		=> 'normal',
			'excerpt_words'			=> '20',
			'pagination_loadmore'	=> 'pagination',
			'fullwidth_mode'		=> false,
			'class'					=> ''

		), $atts));

		$args = array(
			'post_type' 		=> 'wd_business',
			'posts_per_page' 	=> (int)$number_blogs,
			'order' 			=> $sort,
			'orderby' 			=> $order_by,
		);
		$settings = array(
				'category' 	=> array(
					'wd_location_group'	=> (int)$id_category
				),
				'data_show' => $data_show
		);
		$args = apply_filters('wd_filter_get_blog_query', $args, $settings);
		
		$grid_list_class = ($grid_list_layout === 'list') ? $grid_list_layout : "grid";

		//$span_class 		= ($grid_list_layout === 'list') ? 'col-sm-24' : "col-sm-".(24/$columns);  
		$columns_class 	= 'wd-columns-'.$columns.' wd-tablet-columns-'.$columns_tablet.' wd-mobile-columns-'.$columns_mobile.' wd-columns-padding-'.$padding;

		$random_id 			= 'wd-blog-grid-list-'.rand(0,1000).time();

		$class_masonry 		= ($grid_list_layout !== 'list') ? 'wd-masonry-wrap' : '';
		$class_masonry		.= ($grid_hover_style === 'grid-inner') ? ' wd-masonry-blog-content-inner' : '';

		$class_masonry_item = ($grid_list_layout !== 'list') ? 'wd-masonry-item' : '';

		$image_size 		=  ($columns == 1 || $grid_list_layout !== 'list') ? 'full' : 'post-thumbnail';
		
		$wrap_class 		= $grid_list_class .' '.$columns_class.' '. $class_masonry;
		$wrap_class 		.= ($pagination_loadmore === "loadmore") ? ' wd-infinite-scroll-wrap' : '';
		$item_class 		= $class_masonry_item;

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';

		//array send to ajax loadmore
		$layout_data = array(
			'image_size' => $image_size,
			'item_class' => $item_class,
		);
		//List post ID
		$list_post_id = array();

		wp_reset_query();
		$special_blogs = new WP_Query( $args );
		ob_start(); ?>
		<?php if( $special_blogs->have_posts() ) :?> 
			<div class="wd-shortcode wd-shortcode-blog-grid-list <?php echo esc_attr($class); ?>">
				<?php if ($grid_list_button == 1) { ?>
					<div class="wd-blog-toggle-layout-wrapper">
						<?php
						/**
						 * wd_hook_blog_archive_toggle_button hook.
						 *
						 * @hooked blog_archive_toggle_button - 5
						 */
						do_action('wd_hook_blog_archive_toggle_button'); ?>
					</div>
				<?php } ?>
				
				<div id="<?php echo esc_attr($random_id); ?>" 
					class="wd-blog-wrapper wd-blog-switchable-layout <?php echo esc_attr($wrap_class); ?>">
					<?php while( $special_blogs->have_posts() ) {
						$special_blogs->the_post(); global $post;
						echo apply_filters('wd_filter_blog_content', array('thumbnail_size' => $image_size, 'custom_class' => $item_class)); 
						$list_post_id[] = $post->ID;
					} ?>
				</div>
				<div class="clear"></div>
				<?php if($pagination_loadmore == "pagination") : ?>
					<div class="wd-pagination">
						<?php echo apply_filters('wd_filter_display_pagination', 3, $special_blogs) ?>
					</div>
				<?php endif; ?>
				<?php if($pagination_loadmore == "loadmore") : ?>
					<div class="wd-loadmore">
						<div class="wd-icon-loading" id="<?php echo esc_html($random_id); ?>-icon-loading">
							<img src="<?php echo WD_BLOCKS_BASE_URI.'/assets/images/ajax-loader_image.gif';?>" alt="HTML5 Icon" style="height:15px;">
						</div>

						<div classs="wd-loadmore-btn-wrap">
							<a 	data-grid_list_layout="<?php echo esc_html($grid_list_layout); ?>"
								data-random_id="<?php echo esc_html($random_id); ?>"
								data-post__not_in='<?php echo json_encode($list_post_id); ?>'
								data-layout_data='<?php echo json_encode($layout_data); ?>'
								data-query_data='<?php echo json_encode($args); ?>'
								href="#" class="button wd-loadmore-btn wd-loadmore-btn--blog"><?php _e('LOAD MORE','wd_package') ?></a>
						</div>
					</div>				
				<?php endif; ?>
			</div>
		<?php endif;// End have post ?>
		<?php
		wp_reset_postdata();
		return ob_get_clean();
	}
}

if (!function_exists('wd_business_vc_map')) {
	function wd_business_vc_map() {
		return array(
			"name"				=> esc_html__("WD - Business",'wd_package'),
			"base"				=> 'wd_business',
			'description' 		=> esc_html__("Display business listing...", 'wd_package'),
			"category"			=> esc_html__("WD - Business",'wd_package'),
			'icon'        		=> 'icon-wpb-toggle-small-expand',
			"params"=>array(
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Select Category', 'wd_package' ),
					'param_name' 	=> 'id_category',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_category('wd_location_group'),
					'description' 	=> '',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Data Show', 'wd_package' ),
					'param_name' 	=> 'data_show',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_special_blog_name(),
					'description' => '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'			=> 'textfield',
					'heading' 		=> esc_html__( 'Number of blogs', 'wd_package' ),
					'param_name' 	=> 'number_blogs',
					'admin_label' 	=> true,
					'value' 		=> '12',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Sort By', 'wd_package' ),
					'param_name' 	=> 'sort',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_sort_by_values(),
					'std'			=> 'DESC',
					'description'	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Order By', 'wd_package' ),
					'param_name' 	=> 'order_by',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_order_by_values(),
					'std'			=> 'date',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Columns', 'wd_package' ),
					'param_name' 	=> 'columns',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_columns(),
					'std'			=> '3',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 				=> 'dropdown',
					'heading' 			=> esc_html__( 'Columns On Tablet', 'wd_package' ),
					'param_name' 		=> 'columns_tablet',
					'admin_label' 		=> true,
					'value' 			=> wd_vc_get_list_columns_tablet(),
					'std'				=> 2,
					'description' 		=> esc_html__( '', 'wd_package' ),
					"group"				=> esc_html__('Responsive', 'wd_package'),
				),
				array(
					'type' 				=> 'dropdown',
					'heading' 			=> esc_html__( 'Columns On Mobile', 'wd_package' ),
					'param_name' 		=> 'columns_mobile',
					'admin_label' 		=> true,
					'value' 			=> wd_vc_get_list_columns_mobile(),
					'std'				=> 1,
					'description' 		=> esc_html__( '', 'wd_package' ),
					"group"				=> esc_html__('Responsive', 'wd_package'),
				),
				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Padding", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "padding",
					"value" 		=> wd_vc_get_list_columns_padding(),
					"description" 	=> esc_html__('Padding between items.', 'wd_package'),
					"std"			=> 'normal',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show Layout Switch Button', 'wd_package' ),
					'param_name' 	=> 'grid_list_button',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Default Layout', 'wd_package' ),
					'param_name' 	=> 'grid_list_layout',
					'admin_label' 	=> true,
					'value' 		=> array(
						esc_html__( 'Grid', 'wd_package' )		=> 'grid',
						esc_html__( 'List', 'wd_package' )		=> 'list'
					),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "grid_list_button", 'value' => array('0'))
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Grid Hover Style', 'wd_package' ),
					'param_name' 	=> 'grid_hover_style',
					'admin_label' 	=> true,
					'value' 		=> array(
						esc_html__( 'Normal', 'wd_package' )			=> 'normal',
						esc_html__( 'Content Inner', 'wd_package' ) 	=> 'grid-inner',
					),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
					//'dependency'  	=> array('element' => "grid_list_layout", 'value' => array('grid'))
				),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__( 'Number of excerpt words', 'wd_package' ),
					'param_name' 	=> 'excerpt_words',
					'admin_label' 	=> true,
					'value' 		=> '20',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show Pagination/Load More', 'wd_package' ),
					'param_name' 	=> 'pagination_loadmore',
					'admin_label' 	=> true,
					'value' 	=> array(
						esc_html__( 'Pagination', 'wd_package' )		=> 'pagination',
						esc_html__( 'Infinite Scroll', 'wd_package' )	=> 'loadmore',
						esc_html__( 'No Show', 'wd_package' )			=> '0'
					),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Extra class name", 'wd_package'),
					'description'	=> esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'class',
					'value' 		=> ''
				)
			)
		);
	}
}