<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Sidebar_Installation')) {
	class WD_Sidebar_Installation {
		/**
		 * Refers to a single instance of this class.
		 */
		private static $instance = null;

		// Ensure construct function is called only once
		private static $called = false;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
            static::$called = true;

			add_action('admin_menu', array($this, 'admin_page_register'));
			
			//Sidebar manager ajax
			add_action('wp_ajax_nopriv_sidebar_manager_action', array($this, 'sidebar_manager_action_ajax'));
			add_action('wp_ajax_sidebar_manager_action', array($this, 'sidebar_manager_action_ajax'));

			//$this->export_widgets_data('left_sidebar');
		}

		//**************************************************************//
		/*								SIDEBAR						  	*/
        //**************************************************************//
        public function admin_page_register(){
            add_submenu_page( 
                'wd-package-setting', 
                esc_html__('Demo Widgets', 'wd_package'), 
                esc_html__('Demo Widgets', 'wd_package'), 
                'manage_options', 
                'wd-sidebar-setting', 
                array($this, 'admin_page_callback')
            );
		}

		public function admin_page_callback(){ 
			global $wp_registered_sidebars;
			$default_widgets = $this->get_default_widgets(); ?>
		    <div class="wrap wd-admin-page-wrap">
				<div class="tab-content card">
					<div id="setting" class="tab-pane fade in active">
						<?php WD_Packages_Admin_Page::setting_page_tabs(); ?>
						<h3 class="wd-admin-page-heading"><span class="dashicons dashicons-align-right"></span> <?php esc_html_e("Create or restore the default content of sidebar:", 'wd_package'); ?></h3>
						<?php 
						if (is_array($default_widgets) && count($default_widgets)) { ?>
							<div class="wd-table-responsive">
								<table class="table wd-page-admin-page-form">
									<tr valign="top">
										<th><?php esc_html_e("Sidebar Area", 'wd_package'); ?></th>
										<th><?php esc_html_e("Actived Widgets", 'wd_package'); ?></th>
										<th><?php esc_html_e("Default Widgets", 'wd_package'); ?></th>
										<th><?php esc_html_e("Action", 'wd_package'); ?></th>
									</tr>
									<?php foreach ($default_widgets as $sidebar_id => $data) {
										if (empty($data)) continue;

										$actived_widgets = $this->get_actived_widgets($sidebar_id);
										$sidebar_action = array(
											'empty' => esc_html__("Remove all widgets", 'wd_package'),
											'insert_if_empty' => esc_html__("Add default widgets (if sidebar empty)", 'wd_package'),
											'insert_after' => esc_html__("Add default widgets to the end", 'wd_package'),
											'replace' => esc_html__("Remove all/add default widgets", 'wd_package'),
										); ?>
										<tr valign="top">
											<td>
												<h3>
													<?php if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) { 
														echo $wp_registered_sidebars[$sidebar_id]['name'].' ('.$sidebar_id.')';
													} ?>
												</h3>
											</td>
											<td>
												<?php
												if (!empty($actived_widgets)) {
													foreach ($actived_widgets as $widget) {
														echo '<p>'.$widget.'</p>';
													}
												}else{
													echo '<p class="wd-form-desc">'.esc_html__("Does not exist", 'wd_package').'</p>';
												} ?>
											</td>
											<td>
												<?php
												if (!empty($data)){
													foreach ($data as $item) {
														echo '<p>'.$item['id_base'].'</p>';
													}
												} else{
													echo '<p class="wd-form-desc">'.esc_html__("Does not exist", 'wd_package').'</p>';
												} ?>
											</td>
											<td>
												<p>	
													<?php 
													$count = 1;
													foreach ($sidebar_action as $key => $value) {
														$checked = $count == 1 ? 'checked="checked"' : '';
														echo '<label><input type="radio" '.$checked.' name="set_sidebar_action_'.$sidebar_id.'" value="'.$key.'"> '.$value.'</label></br>';
														$count ++;
													} ?>
												</p>
												<p><a href="" data-sidebar-id="<?php echo $sidebar_id; ?>" class="button button-primary wd-button-with-loading wd-create-sidebar-template">
														<?php echo esc_html__("Do action", 'wd_package'); ?>
														<span class="wd-image-loading">
															<img src="<?php echo WDADMIN_IMAGE.'/loading.gif'; ?>" alt="<?php echo esc_html__("Loading Icon", 'wd_package'); ?>">
														</span>
													</a>
												</p>
											</td>
										</tr>
									<?php } ?>
								</table>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php
		} //end content admin page

		public function get_default_widgets(){
			return $add_to_sidebar =  array(
				'left_sidebar'=> array(),
				'right_sidebar'=> array(
					array(
						'id_base' => 'wd_profile',
						'instance' => array(
							'widget_title'  => 'ABOUT ME',
							'image'			=> WDADMIN_TEMPLATE_URI.'/images/sidebar/about-sidebar.jpg',
							'image_size'	=> 'full',
							'website'		=> '#',
							'target'		=> '_blank',
							'job'			=> '',
							'title'			=> '',
							'desc'			=> '',
							'display_logo'	=> 1,
							'text_align'	=> 'text-center',
							'about'			=> 'I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ullamcorper mattis, pulvinar dapibus.',
                			'display_sign'	=> 1,
							'sign_image'	=> WDADMIN_TEMPLATE_URI.'/images/pages/sign.png',
							'class' 		=> ''
						),
					), 
					array(
						'id_base' => 'search',
						'instance' => array(
							'title' => '',
						),
					), 
					array(
						'id_base' => 'email-subscribers-form',
						'instance' => array(
							'title' => 'Subscribe',
							'short_desc' => '',
							'display_name' => 'No',
							'subscribers_group' => '1',
						),
					), 
					array(
						'id_base' => 'media_image',
						'instance' => array(
							'url' => WDADMIN_TEMPLATE_URI.'/images/sidebar/sidebar-banner.jpg',
							'title' => '',
							'size' => 'full',
							'caption' => '',
							'alt' => '',
							'link_type' => 'custom',
							'link_url' => '',
							'image_classes' => '',
							'link_classes' => '',
							'link_rel' => '',
							'link_target_blank' => false,
							'image_title' => '',
						),
					), 
					array(
						'id_base' => 'wd_instagram',
						'instance' => array(
							'widget_title'				=> 'INSTAGRAM',
							'insta_title'				=> '',
							'insta_desc'				=> '',
							'insta_follow'				=> '0',
							'insta_follow_text'			=> 'Follow Me',
							'insta_style'				=> "style-insta-1",
							'insta_hover_style'			=> '',
							'insta_columns'				=> 2,
							'columns_tablet'			=> 2,
							'columns_mobile'			=> 2,
							'insta_number'				=> 6,
							'insta_padding'				=> 'mini',
							'insta_size'				=> 'low_resolution',
							'insta_sortby'				=> 'none',
							'insta_action_click_item'	=> 'lightbox',
							'insta_open_win'			=> '_blank',
							'is_slider'					=> '0',
							'show_loadmore'				=> '0',
							'show_nav'					=> '1',
							'auto_play'					=> '1',
							'per_slide'					=> '1',
							'class' 					=> '',
						),
					), 
					array(
						'id_base' => 'wd_social_icons',
						'instance' => array(
							'widget_title' => 'Follow me',
							'style' => 'nav-user',
							'show_title' => '0',
							'rss_id' => '#',
							'twitter_id' => '#',
							'facebook_id' => '#',
							'google_id' => '#',
							'pin_id' => '#',
							'youtube_id' => '#',
							'instagram_id' => '#',
							'class' => '',
							'item_align' => 'wd-flex-justify-center',
						),
					), 
					array(
						'id_base' => 'archives',
						'instance' => array(
							'title' => '',
							'count' => 0,
							'dropdown' => 0,
						),
					), 
					array(
						'id_base' => 'wd_blog_special',
						'instance' => array(
							'widget_title'			=> 'Popular Posts',
							'layout'				=> 'title',
							'style'					=> 'grid',
							'id_category'			=> '-1',
							'data_show'				=> 'recent-post',
							'number_blogs'			=> 4,
							'sort'					=> 'DESC',
							'order_by'				=> 'date',
							'columns'				=> 1,
							'columns_tablet'		=> 1,
							'columns_mobile'		=> 1,
							'show_thumbnail'  		=> '1',
							'show_placeholder_image'  => '1',
							'image_size'  			=> 'post-thumbnail',
							'grid_hover_style'		=> 'normal',
							'number_excerpt'		=> '10',
							'is_slider'				=> '0',
							'show_nav'				=> '1',	
							'auto_play'				=> '1',
							'class'					=> ''
						),
					)
				),
				'left_sidebar_product'=> array()
			);
		}

		//Get list current widgets of a sidebar
		public function get_actived_widgets($sidabar_id = 'all') {
			$sidebars_widgets = get_option('sidebars_widgets');
			if ($sidabar_id === 'all') {
                return $sidebars_widgets;
            }else if($sidabar_id !== 'all' && !empty($sidebars_widgets[$sidabar_id])){
                return $sidebars_widgets[$sidabar_id];
            }else{
                return false;
            }
		}

		//**************************************************************//
		/*						        AJAX			                */
		//**************************************************************//
        public function sidebar_manager_action_ajax(){
            $sidebar_id	= $_REQUEST['sidebar_id'];
            $action = $_REQUEST['sidebar_action'];
            $result = false;
			if ($action) {
				$this->auto_add_sidebar_widgets($sidebar_id, $action);
				$result = true;
            }
			wp_send_json_success($result);
			die(); //stop "0" from being output
        }

		// Programmatically add widgets to sidebars
		// @param  string $sidebar_id: if empty will do actions for all sidebar
		// @param  string $action: empty / insert_if_empty / insert_after / replace
		// https://gist.github.com/moyarich/59471494a38c4d5d7073
		public function auto_add_sidebar_widgets($sidebar_id = '', $action = 'empty'){
			switch ($action) {
				case 'empty':
					$this->empty_sidebar($sidebar_id);
					break;
				case 'insert_after':
					$this->insert_after($sidebar_id, false);
					break;
				case 'insert_if_empty':
					$this->insert_after($sidebar_id, true);
					break;
				case 'replace':
					$this->empty_sidebar($sidebar_id);
					$this->insert_after($sidebar_id);
					break;
			}
		}

		//Insert default widgets to end of sidebar
		//$sidebar_id : if empty : insert after all sidebar
		public function insert_after($sidebar_id = '', $ignore_sidebar_with_content = true) {
			//Get list default widgets
			$default_widgets = $this->get_default_widgets();
			if(empty($default_widgets)) return;

			$sidebars_widgets = $this->get_actived_widgets();

			foreach($default_widgets as $key => $widgets){
				if ((!empty($sidebar_id) && $key !== $sidebar_id) || (!empty($sidebars_widgets[$key]) && $ignore_sidebar_with_content)) continue;
				if (!empty($widgets)) {
					foreach ($widgets as $index => $widget){
						$widget_id_base   = $widget['id_base'];
						$widget_instance  = $widget['instance'];

						$widget_instances = get_option('widget_'.$widget_id_base);

						if(!is_array($widget_instances)){
							$widget_instances = array();
						}

						$count = count($widget_instances) + 1;
						$sidebars_widgets[$key][] = $widget_id_base.'-'.$count;
						$widget_instances[$count] = $widget_instance;

						//** save widget options
						update_option('widget_'.$widget_id_base, $widget_instances);
					}
				}
			} 

			//** save sidebar options:
			update_option('sidebars_widgets', $sidebars_widgets);  
		}
		

		//Empty sidebar
		//$sidebar_id : if empty : empty all sidebar
		public function empty_sidebar($sidebar_id = '') {
			$this->remove_inactive_widgets($sidebar_id);
			$sidebars_widgets = $this->get_actived_widgets();

			if ($sidebar_id && isset($sidebars_widgets[$sidebar_id])) {
				$sidebars_widgets[$sidebar_id] = array();
			}else if(!$sidebar_id){
				$sidebars_widgets = array();
			}
			
			//** save sidebar options:
			update_option('sidebars_widgets', $sidebars_widgets);
		}

		//Remove inactive widgets
		//$sidebar_id : if empty : remove all inactive widgets
		public function remove_inactive_widgets($sidebar_id = '') {
			$sidebars_widgets = $this->get_actived_widgets();

			if (!empty($sidebars_widgets)) {
				foreach ($sidebars_widgets as $key => $value) {
					if (!empty($sidebar_id) && $sidebar_id !== $key) continue;

					foreach ($value as $widget_id) {
						$pieces = explode('-', $widget_id);
						$multi_number = array_pop($pieces);
						$id_base = implode('-', $pieces);
						$widget = get_option('widget_' . $id_base);
	
						//Here it deletes the widget customizations that are linked to an id
						unset($widget[$multi_number]);
	
						//** save widget options
						update_option('widget_' . $id_base, $widget);
					}
	
					//Here it erases all the page's widget. Set ampty array.
					$sidebars_widgets[$key] = array();
				}
			}

			//** save sidebar options:
			update_option('sidebars_widgets', $sidebars_widgets);
		}

		//**************************************************************//
		/*						        EXPORT			                */
		//**************************************************************//
		public function export_widgets_data($sidebar_id = 'right_sidebar'){
			$actived_widgets = $this->get_actived_widgets($sidebar_id);
			$widgets_args = array(); 
			if (!empty($actived_widgets)) {
				foreach ($actived_widgets as $key => $widget_id) {
					$pieces = explode('-', $widget_id);
					$multi_number = array_pop($pieces);
					$id_base = implode('-', $pieces);

					$widget_meta = get_option('widget_' . $id_base);
					if ($widget_meta) {
						$widgets_args[] = array(
							'id_base'=> $id_base,
							'instance' => $widget_meta[$multi_number]
						);
					}
				}
			}
			echo '<pre>';
			var_export($widgets_args);
			echo '</pre>';
		}
	}
	WD_Sidebar_Installation::get_instance();  // Start an instance of the plugin class 
}