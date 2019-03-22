<?php
/**
 *  /!\ This is a copy of Walker_Nav_Menu_Edit class in core
 * 
 * Create HTML list of nav menu input items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker_Nav_Menu
 */
if (!class_exists('WD_Megamenu_Edit_Fields')) {
	class WD_Megamenu_Edit_Fields extends Walker_Nav_Menu  {
		/**
		 * @see Walker_Nav_Menu::start_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference.
		 */
		function start_lvl(&$output, $depth = 0, $args = array()) {	
		}
		
		/**
		 * @see Walker_Nav_Menu::end_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference.
		 */
		function end_lvl(&$output, $depth = 0, $args = array()) {
		}
		
		/**
		 * @see Walker::start_el()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param object $args
		 */
		function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)  {
			global $_wp_nav_menu_max_depth;
			$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
		
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			ob_start();
			$item_id = esc_attr( $item->ID );
			$removed_args = array(
				'action',
				'customlink-tab',
				'edit-menu-item',
				'menu-item',
				'page-tab',
				'_wpnonce',
			);
		
			$original_title = '';
			if ( 'taxonomy' == $item->type ) {
				$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
				if ( is_wp_error( $original_title ) )
					$original_title = false;
			} elseif ( 'post_type' == $item->type ) {
				$original_object = get_post( $item->object_id );
				$original_title = $original_object->post_title;
			}
		
			$classes = array(
				'menu-item menu-item-depth-' . $depth,
				'menu-item-' . esc_attr( $item->object ),
				'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
			);
		
			$title = $item->title;
		
			if ( ! empty( $item->_invalid ) ) {
				$classes[] = 'menu-item-invalid';
				/* translators: %s: title of menu item which is invalid */
				$title = sprintf( __( '%s (Invalid)', 'wd_package' ), $item->title );
			} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
				$classes[] = 'pending';
				/* translators: %s: title of menu item in draft status */
				$title = sprintf( __('%s (Pending)', 'wd_package'), $item->title );
			}
		
			$title = empty( $item->label ) ? $title : $item->label;
		
			?>
			<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
				<dl class="menu-item-bar">
					<dt class="menu-item-handle">
						<span class="item-title"><?php echo esc_html( $title ); ?></span>
						<span class="item-controls">
							<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
							<span class="item-order hide-if-js">
								<a href="<?php
									echo wp_nonce_url(
										add_query_arg(
											array(
												'action' => 'move-up-menu-item',
												'menu-item' => $item_id,
											),
											remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
										),
										'move-menu_item'
									);
								?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'wd_package'); ?>">&#8593;</abbr></a>
								|
								<a href="<?php
									echo wp_nonce_url(
										add_query_arg(
											array(
												'action' => 'move-down-menu-item',
												'menu-item' => $item_id,
											),
											remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
										),
										'move-menu_item'
									);
								?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down', 'wd_package'); ?>">&#8595;</abbr></a>
							</span>
							<a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item', 'wd_package'); ?>" href="<?php
								echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
							?>"><?php _e( 'Edit Menu Item', 'wd_package' ); ?></a>
						</span>
					</dt>
				</dl>
		
				<div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo $item_id; ?>">
					<?php if( 'custom' == $item->type ) : ?>
						<p class="field-url description description-wide">
							<label for="edit-menu-item-url-<?php echo $item_id; ?>">
								<?php _e( 'URL', 'wd_package' ); ?><br />
								<input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
							</label>
						</p>
					<?php endif; ?>
					<p class="description description-thin">
						<label for="edit-menu-item-title-<?php echo $item_id; ?>">
							<?php _e( 'Navigation Label', 'wd_package' ); ?><br /> 
							<input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
						</label>
					</p>
					<p class="description description-thin">
						<label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
							<?php _e( 'Title Attribute', 'wd_package' ); ?><br />
							<input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
						</label>
					</p>
					<p class="field-link-target description">
						<label for="edit-menu-item-target-<?php echo $item_id; ?>">
							<input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
							<?php _e( 'Open link in a new window/tab', 'wd_package' ); ?>
						</label>
					</p>
					<p class="field-css-classes description description-thin">
						<label for="edit-menu-item-classes-<?php echo $item_id; ?>">
							<?php _e( 'CSS Classes (optional)', 'wd_package' ); ?><br />
							<input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
						</label>
					</p>
					<p class="field-xfn description description-thin">
						<label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
							<?php _e( 'Link Relationship (XFN)', 'wd_package' ); ?><br />
							<input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
						</label>
					</p>
					<p class="field-description description description-wide">
						<label for="edit-menu-item-description-<?php echo $item_id; ?>">
							<?php _e( 'Description', 'wd_package' ); ?><br />
							<textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
							<span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.', 'wd_package'); ?></span>
						</label>
					</p>        
					<?php
					/* New fields insertion starts here */
					?>      
					<div class="wd-menu-wrap-clearfix"></div>

					<?php if ($depth == 0): ?>
						<div class="wd-menu-wrap-clearfix">
							<p class="wd-menu-custom-field-description description description-wide wd-main-menu-field wrap-menu-item-custom-megamenu-<?php echo $item_id; ?>">
								<?php $megamenu = esc_attr( $item->megamenu ); ?>
								<label for="edit-menu-item-megamenu-<?php echo $item_id; ?>"><?php _e( 'Megamenu', 'wd_package' ); ?></label>
								<select data-menu_id="<?php echo $item_id; ?>" 
										id="edit-menu-item-megamenu-<?php echo $item_id; ?>" 
										class="widefat code edit-menu-item-custom-megamenu" 
										name="wd-menu-item-custom-field[<?php echo $item_id; ?>][megamenu]">
									<option value="0" <?php if($megamenu == 0) echo "selected";?> ><?php _e( 'No', 'wd_package' ); ?></option>
									<option value="1" <?php if($megamenu == 1) echo "selected";?> ><?php _e( 'Yes', 'wd_package' ); ?></option>
								</select> 	
							</p>
							<div class="wd-megamenu-content-wrap-<?php echo $item_id; ?>">
								<p class="wd-menu-custom-field-description description description-thin wd-main-menu-field wrap-menu-item-custom-submenu-custom-content-effect-<?php echo $item_id; ?>">
									<?php $submenu_custom_content_effect = esc_attr( $item->submenu_custom_content_effect ); ?>
									<label for="edit-menu-item-submenu-custom-content-effect-<?php echo $item_id; ?>"><?php _e( 'Submenu Content Display', 'wd_package' ); ?></label>
									<select data-menu_id="<?php echo $item_id; ?>" 
											id="edit-menu-item-submenu-custom-content-effect-<?php echo $item_id; ?>" 
											class="widefat code edit-menu-item-custom-submenu-custom-content-effect" 
											name="wd-menu-item-custom-field[<?php echo $item_id; ?>][submenu_custom_content_effect]">
										<option value="normal" <?php if($submenu_custom_content_effect == 'normal') echo "selected";?> ><?php _e( 'Below Item Title', 'wd_package' ); ?></option>
										<option value="hover" <?php if($submenu_custom_content_effect == 'hover') echo "selected";?> ><?php _e( 'Show When Hover Title', 'wd_package' ); ?></option>
									</select> 	
								</p>
								<p class="wd-menu-custom-field-description description description-thin wd-main-menu-field wrap-menu-item-custom-columns-<?php echo $item_id; ?>">
									<?php $columns = esc_attr( $item->columns ); ?>
									<label for="edit-menu-item-columns-<?php echo $item_id; ?>"><?php _e( 'Submenu Columns', 'wd_package' ); ?></label>
									<select data-menu_id="<?php echo $item_id; ?>" 
											id="edit-menu-item-columns-<?php echo $item_id; ?>" 
											class="widefat code edit-menu-item-custom-columns" 
											name="wd-menu-item-custom-field[<?php echo $item_id; ?>][columns]">
										<option value="1" <?php if($columns == '1') echo "selected";?> ><?php _e( '1 Columns', 'wd_package' ); ?></option>
										<option value="2" <?php if($columns == '2') echo "selected";?> ><?php _e( '2 Columns', 'wd_package' ); ?></option>
										<option value="3" <?php if($columns == '3') echo "selected";?> ><?php _e( '3 Columns', 'wd_package' ); ?></option>
										<option value="4" <?php if($columns == '4') echo "selected";?> ><?php _e( '4 Columns', 'wd_package' ); ?></option>
									</select> 	
								</p>
								<p class="wd-menu-custom-field-description description description-thin wd-main-menu-field wrap-menu-item-custom-submenu-bg-source-<?php echo $item_id; ?>">
									<?php $submenu_bg_source = esc_attr( $item->submenu_bg_source ); ?>
									<label for="edit-menu-item-submenu_bg_source-<?php echo $item_id; ?>"><?php _e( 'Submenu Background Type', 'wd_package' ); ?></label>
									<select data-menu_id="<?php echo $item_id; ?>" 
											id="edit-menu-item-submenu-bg-source-<?php echo $item_id; ?>" 
											class="widefat code edit-menu-item-custom-bg-submenu" 
											name="wd-menu-item-custom-field[<?php echo $item_id; ?>][submenu_bg_source]">
										<option value="0" <?php if($submenu_bg_source == 0) echo "selected";?> ><?php _e( 'No', 'wd_package' ); ?></option>
										<option value="bg_image" <?php if($submenu_bg_source == 'bg_image') echo "selected";?> ><?php _e( 'Image', 'wd_package' ); ?></option>
										<option value="bg_color" <?php if($submenu_bg_source == 'bg_color') echo "selected";?> ><?php _e( 'Color', 'wd_package' ); ?></option>
									</select> 	
								</p>
								<p class="wd-menu-custom-field-description description description-thin wd-submenu-menu-field wrap-menu-item-custom-submenu-bg-image-<?php echo $item_id; ?>">
									<label for="edit-menu-item-submenu-bg-image-<?php echo $item_id; ?>"><?php _e( 'Submenu Background Image', 'wd_package' ); ?></label>
									
									<img id="edit-menu-item-submenu-bg-image-view-<?php echo $item_id; ?>" 
										src="<?php echo !empty($item->submenu_bg_image) ? esc_url(wp_get_attachment_url($item->submenu_bg_image)) : WDMM_IMAGE.'/no-image.jpg'; ?>"  width="100%" />
									
									<input 	data-menu_id="<?php echo $item_id; ?>" type="hidden" 
											name="wd-menu-item-custom-field[<?php echo $item_id; ?>][submenu_bg_image]" 
											id="edit-menu-item-submenu-bg-image-<?php echo $item_id; ?>" 
											value="<?php echo esc_attr( $item->submenu_bg_image ); ?>" />
									
									<a 	class="wd_media_lib_select_btn button button-primary button-large edit-menu-item-custom-submenu-bg-image" 
										data-type="image"
										data-image_value="edit-menu-item-submenu-bg-image-<?php echo $item_id; ?>" 
										data-image_preview="edit-menu-item-submenu-bg-image-view-<?php echo $item_id; ?>">
										<?php esc_html_e('Select Image File','wd_package'); ?>
									</a>

									<a 	class="wd_media_lib_clear_btn button" 
										data-image_value="edit-menu-item-submenu-bg-image-<?php echo $item_id; ?>" 
										data-image_preview="edit-menu-item-submenu-bg-image-view-<?php echo $item_id; ?>" 
										data-image_default="<?php echo WDMM_IMAGE.'/no-image.jpg' ?>">
										<?php esc_html_e('Reset','wd_package'); ?>
									</a>
								</p>

								<p class="wd-menu-custom-field-description description description-thin wd-submenu-menu-field wrap-menu-item-custom-submenu-bg-color-<?php echo $item_id; ?>">
									<label for="edit-menu-item-submenu-bg-color-<?php echo $item_id; ?>"><?php _e( 'Submenu Background Color', 'wd_package' ); ?></label>
									<input 	data-menu_id="<?php echo $item_id; ?>" type="text" 
											id="edit-menu-item-submenu-bg-color-<?php echo $item_id; ?>" 
											class="wd_colorpicker_select widefat code edit-menu-item-custom-submenu-bg-color" 
											name="wd-menu-item-custom-field[<?php echo $item_id; ?>][submenu_bg_color]" 
											value="<?php echo esc_attr( $item->submenu_bg_color ); ?>" />
								</p>\
							</div>
						</div>
					<?php elseif($depth == 1): ?>
						<div class="wd-megamenu-content-wrap-<?php echo $item->menu_item_parent; ?>">
							
							<div class="wd-menu-wrap-clearfix">
								<p class="wd-menu-custom-field-description description description-thin wd-main-menu-field wrap-menu-item-custom-submenu-width-<?php echo $item_id; ?>">
									<?php $submenu_width = esc_attr( $item->submenu_width ); ?>
									<label for="edit-menu-item-submenu-width-<?php echo $item_id; ?>"><?php _e( 'Submenu Width', 'wd_package' ); ?></label>
									<select data-menu_id="<?php echo $item_id; ?>" 
											id="edit-menu-item-submenu-width-<?php echo $item_id; ?>" 
											class="widefat code edit-menu-item-custom-submenu-width" 
											name="wd-menu-item-custom-field[<?php echo $item_id; ?>][submenu_width]">
										<option value="auto" <?php if($submenu_width == 'auto') echo "selected";?> ><?php _e( 'Auto', 'wd_package' ); ?></option>
										<option value="full" <?php if($submenu_width == 'full') echo "selected";?> ><?php _e( 'Fullwidth', 'wd_package' ); ?></option>
										<option value="1/3" <?php if($submenu_width == '1/3') echo "selected";?> ><?php _e( '1/3', 'wd_package' ); ?></option>
										<option value="1/3" <?php if($submenu_width == '1/3') echo "selected";?> ><?php _e( '1/3', 'wd_package' ); ?></option>
										<option value="2/3" <?php if($submenu_width == '2/3') echo "selected";?> ><?php _e( '2/3', 'wd_package' ); ?></option>
										<option value="1/4" <?php if($submenu_width == '1/4') echo "selected";?> ><?php _e( '1/4', 'wd_package' ); ?></option>
										<option value="2/4" <?php if($submenu_width == '2/4') echo "selected";?> ><?php _e( '2/4', 'wd_package' ); ?></option>
										<option value="3/4" <?php if($submenu_width == '3/4') echo "selected";?> ><?php _e( '3/4', 'wd_package' ); ?></option>
									</select> 	
								</p>

								<p class="wd-menu-custom-field-description description description-thin wd-main-menu-field wrap-menu-item-custom-submenu-content-source-<?php echo $item_id; ?>">
									<?php $submenu_content_source = esc_attr( $item->submenu_content_source ); ?>
									<label for="edit-menu-item-submenu-content-source-<?php echo $item_id; ?>"><?php _e( 'Submenu Custom Content', 'wd_package' ); ?></label>
									<select data-menu_id="<?php echo $item_id; ?>" 
											id="edit-menu-item-submenu-content-source-<?php echo $item_id; ?>" 
											class="widefat code edit-menu-item-custom-submenu-content-source" 
											name="wd-menu-item-custom-field[<?php echo $item_id; ?>][submenu_content_source]">
										<option value="0" <?php if($submenu_content_source == '0') echo "selected";?> ><?php _e( 'Nothing', 'wd_package' ); ?></option>
										<option value="widget-area" <?php if($submenu_content_source == 'widget-area') echo "selected";?> ><?php _e( 'Widget Area', 'wd_package' ); ?></option>
										<option value="megamenu-template" <?php if($submenu_content_source == 'megamenu-template') echo "selected";?> ><?php _e( 'Megamenu Template', 'wd_package' ); ?></option>
										<option value="custom-shortcode" <?php if($submenu_content_source == 'custom-shortcode') echo "selected";?> ><?php _e( 'Custom Shortcode', 'wd_package' ); ?></option>
									</select> 	
								</p>

								<p class="wd-menu-custom-field-description description description-wide wd-main-menu-field wrap-menu-item-custom-submenu-content-widget-<?php echo $item_id; ?>">
									<?php 
									$submenu_content_widget = esc_attr( $item->submenu_content_widget );
									$list_sidebar 			= $this->get_list_sidebar_choices(); ?>
									<label for="edit-menu-item-submenu-content-widget-<?php echo $item_id; ?>"><?php _e( 'Select Widget Area', 'wd_package' ); ?></label>
									<select data-menu_id="<?php echo $item_id; ?>" 
											id="edit-menu-item-submenu-content_widget-<?php echo $item_id; ?>" 
											class="widefat code edit-menu-item-custom-submenu-content-widget" 
											name="wd-menu-item-custom-field[<?php echo $item_id; ?>][submenu_content_widget]">
											<?php foreach ($list_sidebar as $key => $label): ?>
												<option value="<?php echo $key; ?>" <?php if($submenu_content_widget == $key) echo "selected";?> ><?php echo $label; ?></option>
											<?php endforeach ?>
									</select> 	
								</p>
								<p class="wd-menu-custom-field-description description description-wide wd-main-menu-field wrap-menu-item-custom-submenu-content-shortocde-template-<?php echo $item_id; ?>">
									<?php 
									$submenu_content_megamenu_template = esc_attr( $item->submenu_content_megamenu_template );
									$list_menu_template 				= $this->get_data_by_post_type('wd_megamenu'); ?>
									<label for="edit-menu-item-submenu-content-shortocde-template-<?php echo $item_id; ?>"><?php _e( 'Select Shortocde Template', 'wd_package' ); ?></label>
									<select data-menu_id="<?php echo $item_id; ?>" 
											id="edit-menu-item-submenu-content_shortocde-template-<?php echo $item_id; ?>" 
											class="widefat code edit-menu-item-custom-submenu-content-shortocde-template" 
											name="wd-menu-item-custom-field[<?php echo $item_id; ?>][submenu_content_megamenu_template]">
											<?php foreach ($list_menu_template as $key => $label): ?>
												<option value="<?php echo $key; ?>" <?php if($submenu_content_megamenu_template == $key) echo "selected";?> ><?php echo $label; ?></option>
											<?php endforeach ?>
									</select> 	
								</p>
								<p class="wd-menu-custom-field-description description description-wide wd-main-menu-field wrap-menu-item-custom-submenu-content-custom-shortcode-<?php echo $item_id; ?>">
									<?php $submenu_content_custom_shortcode = esc_attr( $item->submenu_content_custom_shortcode ); ?>
									<label for="edit-menu-item-submenu-columns-<?php echo $item_id; ?>"><?php _e( 'Content Shortcode', 'wd_package' ); ?></label>
									<textarea 	data-menu_id="<?php echo $item_id; ?>" 
												id="edit-menu-item-submenu-content-custom-shortcode-<?php echo $item_id; ?>" 
												class="widefat edit-menu-item-custom-submenu-content-custom-shortcode" 
												rows="3" cols="20" 
												name="wd-menu-item-custom-field[<?php echo $item_id; ?>][submenu_content_custom_shortcode]"><?php echo $submenu_content_custom_shortcode; // textarea_escaped ?></textarea>

									<span class="description"><?php _e('Paste your shortcode here.', 'wd_package'); ?></span>	
								</p>
							</div>
						</div>
					<?php endif ?>
					
					<div class="wd-megamenu-global-field-wrap wd-menu-wrap-clearfix">
						<p class="wd-menu-custom-field-description description description-wide wd-main-menu-field wrap-menu-item-custom-icon-class-<?php echo $item_id; ?>">
							<?php $icon_class = esc_attr( $item->icon_class ); ?>
							<label for="edit-menu-item-icon-class-<?php echo $item_id; ?>"><?php _e( 'Icon Class', 'wd_package' ); ?></label>
							<input data-menu_id="<?php echo $item_id; ?>" type="text" 
									id="edit-menu-item-icon-class-<?php echo $item_id; ?>" 
									class="widefat code edit-menu-item-custom-icon-class" 
									name="wd-menu-item-custom-field[<?php echo $item_id; ?>][icon_class]" 
									placeholder="<?php _e( 'Exam: fa fa-heart', 'wd_package' ); ?>" 
									value="<?php echo esc_attr( $item->icon_class ); ?>" />	
						</p>
						<p class="wd-menu-custom-field-description description description-wide wd-main-menu-field wrap-menu-item-custom-hide-menu-title-<?php echo $item_id; ?>">
							<?php $hide_title = esc_attr( $item->hide_title ); ?>
							<label for="edit-menu-item-hide-menu-title-<?php echo $item_id; ?>"><?php _e( 'Hide Menu Title', 'wd_package' ); ?></label>
							<select data-menu_id="<?php echo $item_id; ?>" 
									id="edit-menu-item-hide-menu-title-<?php echo $item_id; ?>" 
									class="widefat code edit-menu-item-custom-hide-menu-title" 
									name="wd-menu-item-custom-field[<?php echo $item_id; ?>][hide_title]">
								<option value="0" <?php if($hide_title == '0') echo "selected";?> ><?php _e( 'No', 'wd_package' ); ?></option>
								<option value="1" <?php if($hide_title == '1') echo "selected";?> ><?php _e( 'Yes', 'wd_package' ); ?></option>
							</select> 	
						</p>
						<div class="wd-megamenu-content-wrap-<?php echo ($depth == 1) ? $item->menu_item_parent : $item_id; ?> wd-menu-wrap-clearfix">
							<p class="wd-menu-custom-field-description description description-wide wd-global-menu-field wrap-menu-item-custom-flag-label-<?php echo $item_id; ?>">
								<?php $flag_label = esc_attr( $item->flag_label ); ?>
								<label for="edit-menu-item-flag_label-<?php echo $item_id; ?>"><?php _e( 'Flag Label', 'wd_package' ); ?></label>
								<select data-menu_id="<?php echo $item_id; ?>" 
										id="edit-menu-item-flag_label-<?php echo $item_id; ?>" 
										class="widefat code edit-menu-item-custom-flag-label" 
										name="wd-menu-item-custom-field[<?php echo $item_id; ?>][flag_label]">
									<option value="no" <?php if($flag_label == 'no') echo "selected";?> ><?php _e( 'No', 'wd_package' ); ?></option>
									<option value="new" <?php if($flag_label == 'new') echo "selected";?> ><?php _e( 'New', 'wd_package' ); ?></option>
									<option value="sale" <?php if($flag_label == 'sale') echo "selected";?> ><?php _e( 'Sale', 'wd_package' ); ?></option>
									<option value="hot" <?php if($flag_label == 'hot') echo "selected";?> ><?php _e( 'HOT', 'wd_package' ); ?></option>
								</select> 	
							</p>
						</div>
					</div>

					<div class="wd-menu-wrap-clearfix"></div>
					<?php
					/* New fields insertion ends here */
					?>
					<div class="menu-item-actions description-wide submitbox">
						<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
							<p class="link-to-original">
								<?php printf( __('Original: %s', 'wd_package'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
							</p>
						<?php endif; ?>
						<a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
						echo wp_nonce_url(
							add_query_arg(
								array(
									'action' => 'delete-menu-item',
									'menu-item' => $item_id,
								),
								remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
							),
							'delete-menu_item_' . $item_id
						); ?>"><?php _e('Remove', 'wd_package'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
							?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel', 'wd_package'); ?></a>
					</div>
		
					<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
					<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
					<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
					<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
					<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
					<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
				</div><!-- .menu-item-settings-->
				<ul class="menu-item-transport"></ul>
			<?php
			
			$output .= ob_get_clean();
		}

		function get_list_sidebar_choices($value_default = '') {
			global $wp_registered_sidebars;
			$arr_sidebar = ($value_default != '') ? array('0' => $value_default) : array();
			if (count($wp_registered_sidebars) > 0) {
				foreach ( $wp_registered_sidebars as $sidebar ){
					$arr_sidebar[$sidebar['id']] = $sidebar['name'];
				}
			}
			return $arr_sidebar;
		}

		function get_data_by_post_type($post_type = 'post', $args = array()){
			$args_default = array(
				'post_type'			=> $post_type,
				'post_status'		=> 'publish',
				'posts_per_page' 	=> -1,
			);
			$args = wp_parse_args( $args, $args_default );
			$data_array = array();
			global $post;
			$data = new WP_Query($args);
			if( $data->have_posts() ){
				while( $data->have_posts() ){
					$data->the_post();
					$data_array[$post->ID] = html_entity_decode( $post->post_title, ENT_QUOTES, 'UTF-8' ).' ('.$post->ID.')';
				}
			}else{
				$data_array[] = sprintf(__( "Please add data for \"%s\" before", 'wd_package' ), $post_type);
			}
			wp_reset_postdata();
			return $data_array;
		}
	}
}
