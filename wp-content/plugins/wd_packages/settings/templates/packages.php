<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Package_Settings')) {
	class WD_Package_Settings {
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
        
		protected $package_option = array();

		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
            static::$called = true;

            $this->package_option = apply_filters('wd_filter_packages_option', 'all');
            add_action('admin_menu', array($this, 'admin_page_register'));
        }

        //****************************************************************//
		/*							PACKAGES							  */
        //****************************************************************//
        public function admin_page_register(){
            add_menu_page( //or add_theme_page
		        esc_html__('WD Settings', 'wd_package'),     // page title
		        esc_html__('WD Settings', 'wd_package'),     // menu title
		        'manage_options',   // capability
		        'wd-package-setting',     // menu slug
		       	'', // callback function
		        WDADMIN_IMAGE.'/icon.png', //icon (dashicons-universal-access-alt)
		        22 //position
			);
            add_submenu_page( 
                'wd-package-setting', 
                esc_html__('Packages', 'wd_package'),
                esc_html__('Packages', 'wd_package'),
                'manage_options',
                'wd-package-setting',
                array($this, 'admin_page_callback')
            );
            //call register settings function
            add_action( 'admin_init', array($this, 'setting_fields_register') );
		}

		public function setting_fields_register() {
		    //register our settings
		    register_setting( 'wd-package-admin-page-setting', 'wd_packages' );
		}

		public function admin_page_callback(){ ?>
		    <div class="wrap wd-admin-page-wrap">
				<!-- GET STARTED -->
				<?php settings_errors(); ?>
				<div class="tab-content card">
					<div id="setting" class="tab-pane fade in active">
						<?php WD_Packages_Admin_Page::setting_page_tabs(); ?>
						<form method="post" action="options.php">
						    <?php 
						    settings_fields('wd-package-admin-page-setting');
						    do_settings_sections('wd-package-admin-page-setting');
							$options = get_option('wd_packages');

							$optimize_list = array(
								'wd_auto_save_image' => array(
									'label' => esc_html__("Enable Auto Save Image (Classic Editor): ", 'wd_package'),
									'desc' => esc_html__("Downloading automatically image from a post to gallery", 'wd_package'),
								),
								'wd_auto_set_featured_image' => array(
									'label' => esc_html__("Enable Auto Set Featured Image: ", 'wd_package'),
									'desc' => esc_html__("Set featured images automatically when saving posts (if not any)", 'wd_package'),
								),
								'wd_disable_compress_image' => array(
									'label' => esc_html__("Disable Auto Compress Image: ", 'wd_package'),
									'desc' => esc_html__("Disable image compression automatically when upload image to library.", 'wd_package'),
								),
								'wd_disable_wpautop' => array(
									'label' => esc_html__("Disable WP Autop: ", 'wd_package'),
									'desc' => esc_html__("Disable the p tag removal from the content.", 'wd_package'),
								),
								'wd_disable_remove_version' => array(
									'label' => esc_html__("Disable Remove Version: ", 'wd_package'),
									'desc' => esc_html__("Disable the version number removal on the libraries.", 'wd_package'),
								),
								'wd_disable_all_default_widgets' => array(
									'label' => esc_html__("Disable All Default Widgets: ", 'wd_package'),
									'desc' => esc_html__("Hide all of default widgets on sidebar.", 'wd_package'),
								),
								'wd_disable_all_widgets' => array(
									'label' => esc_html__("Disable All Widgets: ", 'wd_package'),
									'desc' => esc_html__("Hide all widgets on sidebar.", 'wd_package'),
								),
								// 'wd_html_permalink' => array(
								// 	'label' => esc_html__("Add .html To Page Permalink: ", 'wd_package'),
								// 	'desc' => '',
								// ),
							);

							$gutenberg_post_type_list = array(
								'page' => esc_html__("Disable on Page: ", 'wd_package'),
								'post' => esc_html__("Disable on Post: ", 'wd_package'),
							);
							if (post_type_exists('wd_megamenu')) {
								$gutenberg_post_type_list['wd_megamenu'] = esc_html__("Disable on WD Megamenu: ", 'wd_package');
							}
							if (post_type_exists('wd_header')) {
								$gutenberg_post_type_list['wd_header'] = esc_html__("Disable on WD Header: ", 'wd_package');
							}
							if (post_type_exists('wd_footer')) {
								$gutenberg_post_type_list['wd_footer'] = esc_html__("Disable on WD Footer: ", 'wd_package');
							}
							if (post_type_exists('wd_banner')) {
								$gutenberg_post_type_list['wd_banner'] = esc_html__("Disable on WD Banner: ", 'wd_package');
							}
							if (post_type_exists('wd_template')) {
								$gutenberg_post_type_list['wd_template'] = esc_html__("Disable on WD Templates: ", 'wd_package');
							} ?>
							<div class="wd-table-responsive">
								<h3 class="wd-admin-page-heading"><span class="dashicons dashicons-lightbulb"> </span><?php esc_html_e("Optimize settings:", 'wd_package'); ?></h3>
								<table class="table wd-package-admin-page-form wd-table-fullwidth">
									<?php foreach ($optimize_list as $key => $data) { ?>
										<tr>
											<td class="wd-package-admin-page-label">
												<?php echo $data['label']; ?>
												<p class="wd-form-desc"><?php echo $data['desc']; ?></p>
											</td>
											<td>
												<?php $checked = (!empty($options[$key])) ? 'checked="checked"' : ''; ?>
												<div class="wd-checkbox-style-1">
													<div class="toggle">
														<input type="checkbox" id="<?php echo $key; ?>-package" name="wd_packages[<?php echo $key; ?>]" value="1" <?php echo $checked; ?> />
														<span class="toggle__content toggle__positive" data-content="Yes"><?php esc_html_e("Yes", 'wd_package'); ?></span>
														<span class="toggle__content  toggle__negative" data-content="No"><?php esc_html_e("No", 'wd_package'); ?></span>
													</div>
												</div>
											</td>
										</tr>
									<?php } ?>
								</table>

								<h3 class="wd-admin-page-heading"><span class="dashicons dashicons-update"></span> <?php esc_html_e("Gutenberg Settings:", 'wd_package'); ?></h3>
								<table class="table wd-package-admin-page-form wd-table-fullwidth">
									<?php foreach ($gutenberg_post_type_list as $key => $title) { ?>
										<tr>
											<td class="wd-package-admin-page-label"><?php echo $title; ?></td>
											<td>
												<?php $checked = (!empty($options['wd_disable_gutenberg'][$key])) ? 'checked="checked"' : ''; ?>
												<div class="wd-checkbox-style-1">
													<div class="toggle">
														<input type="checkbox" id="wd_disable_gutenberg_<?php echo $key; ?>-package" name="wd_packages[wd_disable_gutenberg][<?php echo $key; ?>]" value="1" <?php echo $checked; ?> />
														<span class="toggle__content toggle__positive" data-content="Yes"><?php esc_html_e("Yes", 'wd_package'); ?></span>
														<span class="toggle__content  toggle__negative" data-content="No"><?php esc_html_e("No", 'wd_package'); ?></span>
													</div>
												</div>
											</td>
										</tr>
									<?php } ?>
								</table>
								
								<h3 class="wd-admin-page-heading"><span class="dashicons dashicons-share"></span> <?php esc_html_e("Package Settings:", 'wd_package'); ?></h3>
								<ul class="wd-table-packages-list">
									<?php $i = 1; ?>
									<?php 
									foreach ($this->package_option as $key => $value) {
										$title = $value['title'];
										$checked = (!empty($options[$key])) ? 'checked="checked"' : ''; ?>
										<li>
											<div class="wd-checkbox-style-2">
												<input id="<?php echo $key; ?>-package" class="switch-input" type="checkbox" name="wd_packages[<?php echo $key; ?>]" value="1" <?php echo $checked; ?> >
												<label for="<?php echo $key; ?>-package"><?php echo $title; ?></label>
											</div>
										</li>
										<?php 
										echo ($i%4 == 0) ? '</tr></tr>' : '';
										$i++; 
									} ?>
								</ul>
								<div class="wd-package-page-actions">
									<input type="hidden" name="wd_packages[verify_submit]" value="1" >
									<p class="submit-button">
										<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e("SAVE PACKAGES SETTINGS", 'wd_package'); ?>">
									</p>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		<?php
		} //end content admin page
	}
	WD_Package_Settings::get_instance();  // Start an instance of the plugin class 
}