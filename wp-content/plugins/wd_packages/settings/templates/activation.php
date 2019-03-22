<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Activation')) {
	class WD_Activation {
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

        //Theme active 
		private $purchase_code_option_name 			= 'wd_verify_purchase'; //option field name save to database
		private $remote_request_status_option_name 	= 'wd_check_update_remote_request'; //If = 0, remote request to server
		private $customer_info_option_name 			= 'wd_customer_info_by_purchase_code'; //Save customer information

		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
            static::$called = true;
			
			$this->theme_activation_init();
			add_action('admin_menu', array($this, 'admin_page_register'));
		}

		//****************************************************************//
		/*							THEME ACTIVATION					  */
		//****************************************************************//
		public function admin_page_register(){
            add_submenu_page( 
                'wd-package-setting', 
                esc_html__('Theme Activation', 'wd_package'),
                esc_html__('Theme Activation', 'wd_package'),
                'manage_options',
                'wd-active-theme',
                array($this, 'admin_page_callback')
            );
            //call register settings function
            add_action( 'admin_init', array($this, 'setting_fields_register') );
		}

		public function setting_fields_register() {
		    //register our settings
		    register_setting( 'wd-active-page-setting', $this->purchase_code_option_name );
		    register_setting( 'wd-active-page-setting', $this->remote_request_status_option_name );
		}

		public function admin_page_callback(){ ?>
		    <div class="wrap wd-admin-page-wrap">
				<!-- GET STARTED -->
				<?php settings_errors(); ?>	
				<div class="tab-content card">
					<div id="setting" class="tab-pane fade in active">
						<?php WD_Packages_Admin_Page::setting_page_tabs(); ?>
						<h3 class="wd-admin-page-heading"><span class="dashicons dashicons-star-filled"></span> <?php esc_html_e("THEME ACTIVATION", 'wd_packages'); ?></h3>
						<h4>
							<a id="wd_update_checker_server_status" href="#"><?php esc_html_e("Check Server Status", 'wd_packages'); ?></a> | 
							<a target="_blank" href="https://wpdance.kayako.com/conversation/new"><?php esc_html_e("Contact Supporter", 'wd_packages'); ?></a>
						</h4>
						<p class="wd-form-desc"><?php esc_html_e("- Activate the theme to unlock the auto update function for theme and WD Packages plugin.", 'wd_packages'); ?></p>
						<p class="wd-form-desc"><?php esc_html_e("- De-activating the theme will reset the theme activation.", 'wd_packages'); ?></p>
						<p class="wd-form-desc"><?php esc_html_e("- Activation may be limited by the access of themeforest servers (maximum 1 time/min). If you make sure your information is correct, please try again in a few minutes.", 'wd_packages'); ?></p>
						<hr/>
						<form id="wd-activation-form" method="post" action="options.php">
						   	<?php 
						   	settings_fields('wd-active-page-setting');
						    do_settings_sections('wd-active-page-setting');
							$verify_purchase 	= get_option($this->purchase_code_option_name);
							$purchase_verify 	= get_option($this->customer_info_option_name);

							ob_start();
							if( isset($purchase_verify['verify-purchase']['buyer']) ) {
								echo esc_html__('Item ID: ', 'wd_packages') . $purchase_verify['verify-purchase']['item_id'] . '<br/>';
								echo esc_html__('Item Name: ', 'wd_packages') . $purchase_verify['verify-purchase']['item_name'] . '<br/>';
								echo esc_html__('Buyer: ', 'wd_packages') . $purchase_verify['verify-purchase']['buyer']. '<br/>';
								echo esc_html__('License: ', 'wd_packages') . $purchase_verify['verify-purchase']['licence'] . '<br/>';
								echo esc_html__('Created At: ', 'wd_packages') . $purchase_verify['verify-purchase']['created_at'] . '<br/>'; 
							}
							$customer_info = ob_get_clean(); ?>
							
						    <table class="table wd-package-admin-page-form" style="width: 100%">
					    		<tr valign="top">
									<td class="wd-package-admin-page-label" style="width: 15%;"><?php echo esc_html__('Buyer (themeforest): ', 'wd_packages'); ?></td>
					    			<td>
					    				<input 
											id="wd-activation-username" type="text" 
											class="wd-activation-form-input"
						    				placeholder="<?php echo esc_html__('Enter your themeforest\'s username...', 'wd_packages') ?>" 
						    				name="<?php echo esc_attr($this->purchase_code_option_name); ?>[username]" class="form-control is-valid" 
											value="<?php echo ( !empty($verify_purchase) ) ? $verify_purchase['username'] : ''; ?>"
											autocomplete="off" <?php echo ( !empty($verify_purchase) ) ? 'disabled' : ''; ?>>
									</td>
								</tr>
								<tr valign="top">
									<td class="wd-package-admin-page-label" style="width: 15%;"><?php echo esc_html__('Purchase Code: ', 'wd_packages'); ?></td>
					    			<td>
					    				<input 
											id="wd-activation-purchase-code" type="text" 
											class="wd-activation-form-input"
						    				placeholder="<?php echo esc_html__('Enter your purchase code...', 'wd_packages') ?>" 
						    				name="<?php echo esc_attr($this->purchase_code_option_name); ?>[purchase_code]" class="form-control is-valid" 
											value="<?php echo ( !empty($verify_purchase) ) ? $verify_purchase['purchase_code'] : ''; ?>" 
											autocomplete="off" <?php echo ( !empty($verify_purchase) ) ? 'disabled' : ''; ?>>
						    			<input type="hidden" name="<?php echo esc_attr($this->remote_request_status_option_name); ?>" value="0">
										<?php if (!$customer_info) { ?>
											<p class="wd-form-desc">
												<a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">
													<?php echo esc_html__('How to get your purchase code?', 'wd_packages'); ?>
												</a>
											</p>
										<?php } ?>
									</td>
								</tr>
								<tr valign="top">
									<td class="wd-package-admin-page-label" style="width: 15%;"><?php echo esc_html__('Rewrite Update Server: ', 'wd_packages'); ?></td>
					    			<td>
										<?php
										$custom_server_value = (!empty($verify_purchase) && !empty($verify_purchase['custom_server'])) ? $verify_purchase['custom_server'] : esc_html__('(Default Server)', 'wd_packages'); 
										$custom_server_value = (empty($verify_purchase)) ? '' : $custom_server_value;
										?>
					    				<input 
											id="wd-activation-custom-server" type="text" 
											class="wd-activation-form-input"
						    				placeholder="<?php echo esc_html__('Enter the server URL...', 'wd_packages') ?>" 
						    				name="<?php echo esc_attr($this->purchase_code_option_name); ?>[custom_server]" class="form-control is-valid" 
											value="<?php echo $custom_server_value; ?>" 
											autocomplete="off" <?php echo ( !empty($verify_purchase) ) ? 'disabled' : ''; ?>>
										<?php if (!$customer_info) { ?>
											<p class="wd-form-desc"><?php echo esc_html__('Leave blank if the current server is still working.', 'wd_packages'); ?></p>
										<?php } ?>								
									</td>
								</tr>
								<?php if ($customer_info) { ?>
									<tr valign="top">
										<td class="wd-package-admin-page-label" style="width: 15%;"><?php echo esc_html__('Customer information: ', 'wd_packages'); ?></td>
										<td>
											<?php echo $customer_info; ?>
										</td>
									</tr>
								<?php } ?>
						    </table>
						</form>
						<p class="submit-button">
							<button name="submit" id="wd-activation-submit-button" class="button button-primary" 
									<?php echo ( !empty($verify_purchase) ) ? 'disabled' : ''; ?>>
									<?php echo ( !empty($verify_purchase) ) ? esc_html__("Activated", 'wd_packages') : esc_html__("Activate", 'wd_packages'); ?>
							</button>
							<?php if (!empty($verify_purchase)): ?>
								<a id="wd-activation-reset" class="button" href="#"> <?php esc_html_e("Remove", 'wd_packages'); ?></a>
							<?php endif ?> 
						</p>
					</div>
				</div>
			</div>
		<?php
		} //end content admin page

		public function theme_activation_init(){
			//Ajax reset activation
			add_action('wp_ajax_nopriv_wd_reset_activation', array($this, 'ajax_wd_reset_activation'));
			add_action('wp_ajax_wd_reset_activation', array($this, 'ajax_wd_reset_activation'));
		}

		//Ajax reset activation
		public function ajax_wd_reset_activation() {
			$this->reset_activation();
			die();
		}
	}
	WD_Activation::get_instance();  // Start an instance of the plugin class 
}