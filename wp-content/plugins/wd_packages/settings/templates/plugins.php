<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Plugin_Installation')) {
	class WD_Plugin_Installation {
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
			
			$this->plugins_install_ajax();
			add_action('admin_menu', array($this, 'admin_page_register'));
		}

		//**************************************************************//
		/*								PLUGINS						 	*/
		//**************************************************************//
		public function admin_page_register(){
            add_submenu_page( 
                'wd-package-setting', 
                esc_html__('Demo Plugins', 'wd_package'),
                esc_html__('Demo Plugins', 'wd_package'),
                'manage_options',
                'wd-plugin-setting',
                array($this, 'admin_page_callback')
            );
		}

		public function admin_page_callback(){ 
			$plugins_class = WD_Plugins::get_instance();
			$required_plugins = $plugins_class->plugins_list(); ?>
		    <div class="wrap wd-admin-page-wrap">
				<div class="tab-content card">
					<div id="setting" class="tab-pane fade in active">
						<?php WD_Packages_Admin_Page::setting_page_tabs(); ?>
						<h3 class="wd-admin-page-heading"><span class="dashicons dashicons-admin-plugins"></span> <?php esc_html_e("Manage the installation of required plugins:", 'wd_package'); ?></h3>
						<?php 
						if (is_array($required_plugins) && count($required_plugins)) { ?>
							<div class="wd-table-responsive">
								<table class="table wd-page-admin-page-form">
									<tr valign="top">
										<th style="background: #c4c4c4;">
											<div class="wd-checkbox-style-2">
												<input id="wd-select-all-plugin-package" class="switch-input" type="checkbox" value="1">
												<label for="wd-select-all-plugin-package"></label>
											</div>
										</th>
										<th><?php esc_html_e("Information", 'wd_package'); ?></th>
										<th style="width: 40%;"><?php esc_html_e("Description", 'wd_package'); ?></th>
										<th><?php esc_html_e("Status", 'wd_package'); ?></th>
										<th><?php esc_html_e("Action", 'wd_package'); ?></th>
									</tr>
									<?php foreach ($required_plugins as $plugin) {
										$plugin_path = $this->get_plugin_path($plugin['slug']);
										$plugin_version = $this->get_plugin_version_html($plugin['slug']);
										$plugin_status = $this->get_plugin_status($plugin_path, 'number'); ?>
										<tr valign="top">
											<td>
												<div class="wd-checkbox-style-2">
													<input id="<?php echo $plugin['slug']; ?>-package" class="switch-input wd-plugins-item-checkbox" type="checkbox" value="<?php echo $plugin['slug']; ?>">
													<label for="<?php echo $plugin['slug']; ?>-package"></label>
												</div>
											</td>
											<td class="wd-package-admin-page-label">
												<?php echo $plugin['name']; ?> <?php echo $plugin_version; ?> <?php echo (isset($plugin['required']) && $plugin['required']) ? '<sup class="wd-version">'.esc_html__("(Required)", 'wd_package').'</sup>' : ''; ?>
											</td>
											<td>
												<p style="text-align:left;"><?php echo $plugin['desc']; ?></p>
											</td>
											<td class="wd-plugin-status">
												<?php echo $this->get_plugin_status($plugin_path, 'html'); ?>
											</td>
											<td>
												<p>
													<?php if ($plugin_status == 0) { ?>
														<a href="#" data-plugin-slug="<?php echo $plugin['slug']; ?>" data-action="install-active" class="button button-primary wd-button-with-loading wd-install-plugin-action">
															<?php echo esc_html__("Install & Active", 'wd_package'); ?>
														</a>
													<?php }else if ($plugin_status == 1){ ?>
														<a href="#" data-plugin-slug="<?php echo $plugin['slug']; ?>" data-action="active" class="button button-primary wd-button-with-loading wd-install-plugin-action">
															<?php echo esc_html__("Active", 'wd_package'); ?>
														</a>
														<a href="#" data-plugin-slug="<?php echo $plugin['slug']; ?>" data-action="delete" class="button wd-button-with-loading wd-install-plugin-action">
															<?php echo esc_html__("Delete", 'wd_package'); ?>
														</a>
													<?php }else if ($plugin_status == 2){ ?>
														<a href="#" data-plugin-slug="<?php echo $plugin['slug']; ?>" data-action="deactive" class="button wd-button-with-loading wd-install-plugin-action">
															<?php echo esc_html__("Deactive", 'wd_package'); ?>
														</a>
													<?php } ?>
												</p>
											</td>
										</tr>
									<?php } ?>
								</table>
								<div class="wd-package-page-actions">
									<p class="submit-button">
										<a href="#" data-action="install-active" class="button button-primary wd-button-with-loading wd-install-selected-plugins-action" disabled="disabled">
											<?php echo esc_html__("Install & Active", 'wd_package'); ?>
										</a>
										<a href="#" data-action="active" class="button button-primary wd-button-with-loading wd-install-selected-plugins-action" disabled="disabled">
											<?php echo esc_html__("Active", 'wd_package'); ?>
										</a>
										<a href="#" data-action="deactive" class="button wd-button-with-loading wd-install-selected-plugins-action" disabled="disabled">
											<?php echo esc_html__("Deactive", 'wd_package'); ?>
										</a>
										<a href="#" data-action="delete" class="button wd-button-with-loading wd-install-selected-plugins-action" disabled="disabled">
											<?php echo esc_html__("Delete", 'wd_package'); ?>
										</a>
									</p>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php
		} //end content admin page

		public function plugins_install_ajax(){
			//Ajax install and active plugin
			add_action('wp_ajax_nopriv_wd_install_plugin', array($this, 'ajax_wd_install_plugin'));
			add_action('wp_ajax_wd_install_plugin', array($this, 'ajax_wd_install_plugin'));
		}

		//Ajax check username and purchase code
		public function ajax_wd_install_plugin(){
			$slug = isset($_REQUEST['plugin_slug']) ? $_REQUEST['plugin_slug'] : '';
			$action = isset($_REQUEST['plugin_action']) ? $_REQUEST['plugin_action'] : '';
			$plugin_path = $this->get_plugin_path($slug);
			$result = array();
			switch ($action) {
				case 'install-active':
					$this->do_install_plugin($slug);
					$result['mess'] = esc_html__("Installed and Actived", 'wd_package');
					$result['status'] = $this->get_plugin_status($plugin_path);
					break;
				case 'active':
					$this->active_plugin($slug);
					$result['mess'] = esc_html__("Actived", 'wd_package');
					$result['status'] = $this->get_plugin_status($plugin_path);
					break;
				case 'delete':
					$this->delete_plugin($slug);
					$result['mess'] = esc_html__("Deleted", 'wd_package');
					$result['status'] = $this->get_plugin_status($plugin_path);
					break;
				case 'deactive':
					$this->deactive_plugin($slug);
					$result['mess'] = esc_html__("Deactived", 'wd_package');
					$result['status'] = $this->get_plugin_status($plugin_path);
					break;
			}
			wp_send_json_success($result);
			die();	
		}

		/** @param array $zip **/
		public function do_install_theme($zip){
			if(empty($zip) || !is_array($zip) || count($zip) == 0) return;
			require_once trailingslashit(ABSPATH) . "wp-admin/includes/plugin.php";
			WP_Filesystem();
			$themes         = wp_get_themes(array("errors" => null));
			$themesBefore   = array();
			foreach($themes as $theme){
				$themesBefore   = $theme->display("ThemeURI");
			}
			$errors         = array();
			foreach($zip as $file){
				$tmp    = download_url($file);
				if(is_wp_error($tmp)){
					@unlink($tmp);
					continue;
				}
				$unzipped = unzip_file($tmp, get_theme_root());
				@unlink($tmp);
				if(!$unzipped && is_wp_error($unzipped)){
					continue;
				}
			}
			return $errors;
		}

		/** @param array $slug **/
		public function do_install_plugin($slug){
			$direct_link = array($this->get_plugin_direct_link($slug));
			$plugin_status = $this->get_plugin_status($this->get_plugin_path($slug), 'number');
			if(empty($direct_link) || !is_array($direct_link) || count($direct_link) == 0) return;
			require_once trailingslashit(ABSPATH) . "wp-admin/includes/plugin.php";

			//Install if plugin is not exist
			if ($plugin_status == 0) {
				require_once trailingslashit(ABSPATH) . 'wp-admin/includes/file.php';
				WP_Filesystem();
				$pluginsBefore  = array_keys(get_plugins());
				$errors         = array();
				foreach($direct_link as $file){
					$tmp    = download_url($file);
					if(is_wp_error($tmp)){
						//echo $tmp->get_error_message();
						continue;
					}
					$unzipped = unzip_file($tmp, WP_PLUGIN_DIR);
					@unlink($tmp);
					if(!$unzipped){
						continue;
					}
				}
				wp_clean_plugins_cache();
			}
			
			//Active plugin
			$pluginsNow = get_plugins();
			foreach($pluginsNow as $file => $array){
				if(in_array($file, $pluginsBefore)) continue;
				activate_plugin($file);
			}
			return $errors;
		}

		//Deactive and delete plugin folder
		private function delete_plugin($slug){
			$plugin_path = $this->get_plugin_path($slug);

			//Deactive
			if ($this->check_plugin_active($plugin_path)) {
				deactivate_plugins($plugin_path, true);
			}

			delete_plugins(array($plugin_path), '');
		}

		//Deactive plugin
		private function active_plugin($slug){
			$plugin_path = $this->get_plugin_path($slug);
			//Active
			activate_plugin($plugin_path);
		}

		//Deactive plugin
		private function deactive_plugin($slug){
			$plugin_path = $this->get_plugin_path($slug);
			//Deactive
			if ($this->check_plugin_active($plugin_path)) {
				deactivate_plugins($plugin_path, true);
			}
		}

		private function get_plugin_path($slug){
			$plugin_info = get_plugins('/'.$slug);
			$plugin_path = '';
			if (!empty($plugin_info)) {
				foreach ($plugin_info as $key => $value) {
					$plugin_path = $slug.'/'.$key;
				}
			}
			return $plugin_path;
		}

		private function get_plugin_direct_link($slug){
			$plugins_class = WD_Plugins::get_instance();
			$required_plugins = $plugins_class->plugins_list();
			$direct_link = '';
			foreach ($required_plugins as $plugin) {
				if ($plugin['slug'] === $slug) {
					$direct_link = isset($plugin['source']) ? $plugin['source'] : 'https://downloads.wordpress.org/plugins/'.trim($slug).'.zip';
				}
			}
			return $direct_link;
		}

		private function get_plugin_version_html($slug){
			$plugin_info = get_plugins('/'.$slug);
			$plugin_version = '';
			if (!empty($plugin_info)) {
				foreach ($plugin_info as $key => $value) {
					$plugin_version = '<sup class="wd-version">'.$value['Version'].'</sup>';
				}
			}
			return $plugin_version;
		}

		//Return plugin status
		//$type : html, number (0 : not exist, 1 : installed but not active, 2 : installed and actived)
		private function get_plugin_status($plugin_path, $type = "html"){
			$status = $type === "html" ? '<p class="wd-form-notice">'.esc_html__("Not available!", 'wd_package').'</p>' : 0;
			$exist_plugins = get_plugins();
			if (isset($exist_plugins[$plugin_path])) {
				$status = $type === "html" ? '<p class="wd-form-notice">'.esc_html__("Installed / Not Activated", 'wd_package').'</p>' : 1;
			}

			if ($this->check_plugin_active($plugin_path)) {
				$status = $type === "html" ? '<kbd>'.esc_html__("Installed & Actived", 'wd_package').'</kbd>' : 2;
			}
			return $status;
		}

		protected function check_plugin_active($plugin_path){
			$plugins_actived = apply_filters('active_plugins', get_option('active_plugins'));
			if(in_array($plugin_path, $plugins_actived)){
				return true;
			}else{
				return false;
			}
		}
	}
	WD_Plugin_Installation::get_instance();  // Start an instance of the plugin class 
}