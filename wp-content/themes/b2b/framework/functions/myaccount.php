<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

/**
 * Usage : 
 * 
 * 
 * 
 */

if (!class_exists('WD_My_Account')) {
	class WD_My_Account {
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
			
			//Hook failed login
			add_action( 'wp_login_failed', array($this, 'login_fail_action' ));

			//Display form user mobile
			//do_action('wd_hook_form_user_mobile'); //Display form user mobile
			add_action('wd_hook_form_user_mobile', array($this, 'get_form_user_mobile'), 5);

			//Display my account icon
			//echo apply_filters('wd_filter_tiny_myaccount', array('show_icon' => 1, 'show_text' => 0, 'class' => '')); //Display my account icon
			add_filter( 'wd_filter_tiny_myaccount', array($this, 'myaccount_content' ), 10, 2);

			//Display my account form
			//echo apply_filters('wd_filter_myaccount_form', $form = 'login'); //Display my account icon
			add_filter( 'wd_filter_myaccount_form', array($this, 'my_account_form' ), 10, 2);
		}

		public function myaccount_content($setting = array()){
			$default = array(
				'show_icon' => 1,
				'show_text' => 0, 
				'show_avatar' => 0,
				'dropdown_position' => 'left', 
				'class' => ''
			);
			extract(wp_parse_args($setting, $default));

			$list_my_account_page  = $this->get_list_my_account_page();
		
			ob_start();
			$_user_logged_class = is_user_logged_in() ? 'wd-user-logged' : 'wd-user-login';

			if ( $show_icon ) {
				$icon_html 	= ($show_avatar && is_user_logged_in()) ? get_avatar( get_current_user_id(), 20) : '<span class="fa fa-user-circle-o wd-icon"></span>';
			}else{
				$icon_html 	= '';
			}
		
			$title 	= esc_html__('Login / Sign in', 'feellio');
			$desc 	= esc_html__('View Login Page', 'feellio');
			if (is_user_logged_in()){
				$current_user = wp_get_current_user();
				$title = !empty($current_user->data->display_name) ? $current_user->data->display_name : '';
				$title = $title == '' && !empty($current_user->data->user_nicename) ? $current_user->data->user_nicename : $title;
				$title = $title == '' && !empty($current_user->data->user_login) ? $current_user->data->user_login : $title;
				$title = $title == '' && !empty($current_user->data->user_email) ? $current_user->data->user_email : $title;
				$title = $title == '' ? esc_html__('My Account', 'feellio') : $title ;
				$desc = esc_html__('View Account Page', 'feellio');
			}
			$title = ($show_text) ? '<span class="wd-navUser-action-text">'.$title.'</span>' : ''; ?>
				
			<div class="wd-navUser-action-wrap wd-dropdown-wrap wd-tini-account-wrap <?php echo esc_attr($class) ?>">
				<a class="wd-navUser-action wd-navUser-action--myaccount" href="<?php echo esc_url($list_my_account_page['login']);?>" title="<?php echo $desc; ?>">
					<?php echo $icon_html.$title; ?>
				</a>	
				<div class="wd-dropdown-container wd-dropdown-container--<?php echo $dropdown_position; ?> wd-dropdown--myaccount <?php echo esc_attr( $_user_logged_class ); ?>">
					<?php if( !is_user_logged_in() ){ ?>
						<div class="wd-dropdown-body">
							<?php echo $this->my_account_form('login'); ?>
						</div>
						<div class="wd-dropdown-footer">
							<p><a class="wd-my-account-action" href="<?php echo esc_url($list_my_account_page['lost_password']); ?>" data-toggle="tooltip" title="<?php esc_html_e('Reset your password', 'feellio'); ?>"><?php esc_html_e('Forgot password?', 'feellio'); ?></a></p>
							<p><a class="wd-my-account-action" href="<?php echo esc_url($list_my_account_page['register']); ?>" data-toggle="tooltip" title="<?php esc_html_e('Create new acccount', 'feellio'); ?>" ><?php esc_html_e('Create account now?', 'feellio'); ?></a></p>
						</div>
					<?php }else{ ?>
						<div class="wd-dropdown-body">
							<ul class="wd-my-account-list">
								<?php if (wd_is_woocommerce()): ?>
									<li><a class="wd-my-account-my-order" href="<?php echo esc_url($list_my_account_page['my_order']); ?>" title="<?php esc_html_e('My Orders', 'feellio');?>"><?php esc_html_e('My Order', 'feellio');?></a></li>
	
									<li><a class="wd-my-account-my-download" href="<?php echo esc_url($list_my_account_page['my_download']); ?>" title="<?php esc_html_e('My Downloads', 'feellio');?>"><?php esc_html_e('My Download', 'feellio');?></a></li>
								<?php endif ?>
								
								<li><a class="wd-my-account-logout" href="<?php echo esc_url($list_my_account_page['logout']); ?>" title="<?php esc_html_e('Logout', 'feellio');?>"><?php esc_html_e('Logout', 'feellio');?></a></li>
							</ul>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php 
			return ob_get_clean();
		}

		public function get_list_my_account_page(){
			$list_my_account_page = array();
			$myaccount_id 	= get_option( 'woocommerce_myaccount_page_id' );
			$myacount_url 	= ( $myaccount_id ) ? get_permalink( $myaccount_id ) : '';
			$myacount_url 	= is_ssl() ? str_replace( 'http:', 'https:', $myacount_url ) : str_replace( 'https:', 'http:', $myacount_url ) ;
		
		
			$myorder_url 	= $myacount_url ? $myacount_url.get_option( 'woocommerce_myaccount_orders_endpoint' ) : '#';
			$mydownload_url = $myacount_url ? $myacount_url.get_option( 'woocommerce_myaccount_downloads_endpoint' ) : '#';
			$logout_url 	= wp_logout_url( esc_url(home_url('/')) ) ? wp_logout_url( esc_url(home_url('/'))) : '#';
			$lost_pw_url 	= wp_lostpassword_url( esc_url(home_url('/')) ) ? wp_lostpassword_url( esc_url(home_url('/')) ) : '#';
		
			$list_my_account_page['my_order'] 		= $myorder_url ? $myorder_url : '#';
			$list_my_account_page['my_download'] 	= $mydownload_url ? $mydownload_url : '#';
			$list_my_account_page['logout'] 		= $logout_url ? $logout_url : '#';
			$list_my_account_page['login'] 			= $myacount_url ? $myacount_url : '#';
			$list_my_account_page['register'] 		= $myacount_url ? $myacount_url : '#';
			$list_my_account_page['lost_password'] 	= $lost_pw_url ? $lost_pw_url : '#';
		
			/**
			 * package: myaccount-url / custom my account URL from Theme Option
			 * var: login_url
			 * var: register_url
			 * var: forgot_password_url
			 */
			extract(apply_filters('wd_filter_get_data_package', 'myaccount-url' )); 
			if ($login_url && get_permalink( $login_url )) {
				$list_my_account_page['login'] = get_permalink( $login_url );
			}
			if ($register_url && get_permalink( $register_url )) {
				$list_my_account_page['register'] = get_permalink( $register_url );
			}
			if ($forgot_password_url && get_permalink( $forgot_password_url )) {
				$list_my_account_page['lost_password'] = get_permalink( $forgot_password_url );
			}
		
			return $list_my_account_page;
		}

		//Display my account form
		public function my_account_form($form = 'login'){
			if ($form === 'login') {
				return $this->get_login_form_content();
			}elseif ($form === 'register'){
				return $this->get_register_form_content();
			}elseif ($form === 'forgot-password'){
				return $this->get_forgot_password_form_content();
			}
		}
		
		public function get_login_form_content(){
			$list_my_account_page  	= $this->get_list_my_account_page();
			$random_id 				= 'wd-login-form-'.mt_rand();
		
			$args = array(
				'username_label'		=> esc_html__( 'Username:', 'feellio' ),
				'username_placeholder'	=> esc_html__( 'Email Address / Username', 'feellio' ),
				'password_label'		=> esc_html__( 'Password:', 'feellio' ),
				'password_placeholder'	=> esc_html__( 'Enter your password...', 'feellio' ),
				'remember_text'			=> esc_html__( 'Remember me?', 'feellio' ),
				'btn_text'				=> esc_html__( 'SIGN IN', 'feellio' ),
			);
			//Check whether the user is already logged in  
			if (is_user_logged_in()) {  
				
				// They're already logged in, so we bounce them back to the homepage.  
				
				//header( 'Location:' . esc_url(home_url('/')) );  
				$this->logged_user_information();
				
			} else { ?>
				<form method="post" class="login wd-login-form wd-login-form-with-validate-ajax" id="<?php echo esc_attr($random_id); ?>" >
					<p class="wd-my-account-field-wrap login-username">
						<label for="<?php echo esc_attr($random_id); ?>-username"><?php echo $args['username_label']; ?></label>
						<input type="text" size="20" name="username"
								id="<?php echo esc_attr($random_id); ?>-username"
								class="wd-login-input wd-login-form-field wd-login-username" 
								autocomplete="off" value="" 
								placeholder="<?php echo $args['username_placeholder']; ?>">
						<span class="wd-alert wd-login-username-alert hidden"></span>
					</p>
					<p class="wd-my-account-field-wrap login-password">
						<label for="<?php echo esc_attr($random_id); ?>-password"><?php echo $args['password_label']; ?></label>
						<input type="password" size="20" name="password" 
								id="<?php echo esc_attr($random_id); ?>-password" 
								class="wd-login-input wd-login-form-field wd-login-password" 
								autocomplete="off" value="" 
								placeholder="<?php echo $args['password_placeholder']; ?>">
						<span class="wd-alert wd-login-password-alert hidden"></span>
					</p>
					<p class="login-remember">
						<label><input name="rememberme" class="wd-login-form-field wd-login-rememberme" type="checkbox" value="forever"> <?php echo $args['remember_text']; ?></label> 
					</p>
		
					<p class="wd-alert wd-login-success-alert hidden"></p>
					<p class="wd-alert wd-login-error-alert hidden"></p>
		
					<div class="clear"></div>
					
					<p class="login-submit">
						<span class="wd-loading wd-loading--login-form hidden">
							<img src="<?php echo WD_THEME_IMAGES.'/loading.gif'; ?>" alt="<?php echo esc_html__( 'Loading Icon' , 'feellio'); ?>">
						</span>
						<input type="submit" class="wd-my-account-btn wd-login-form-field wd-login-btn" name="login" data-form_id="<?php echo esc_attr($random_id); ?>"  value="<?php echo $args['btn_text']; ?>" />
					</p>
				</form>	
			<?php } 
		}
		
		public function get_register_form_content(){
			$random_id = 'wd-register-form-'.mt_rand();
			//Check whether the user is already logged in  
			if (is_user_logged_in()) {  
				
				// They're already logged in, so we bounce them back to the homepage.  
				
				//header( 'Location:' . esc_url(home_url('/')) );  
				$this->logged_user_information();
				
			} else { ?>
				<?php if (get_option( 'users_can_register', true )): ?>
					<?php 
					$args = array(
						'username_label'		=> esc_html__( 'Username:', 'feellio' ),
						'username_placeholder'	=> esc_html__( 'Your username...', 'feellio' ),
						'email_label'			=> esc_html__( 'Email:', 'feellio' ),
						'email_placeholder'		=> esc_html__( 'Your email...', 'feellio' ),
						'password_label'		=> esc_html__( 'Password:', 'feellio' ),
						'password_placeholder'	=> esc_html__( 'Enter your password...', 'feellio' ),
						'password2_label'		=> esc_html__( 'Password Confirmation:', 'feellio' ),
						'password2_placeholder'	=> esc_html__( 'Retype your password...', 'feellio' ),
						'terms_text'			=> esc_html__( 'I agree to the Terms of Service!', 'feellio' ),
						'btn_text'				=> esc_html__( 'Create New Account', 'feellio' ),
					); ?>
					<form method="post" class="register wd-register-form wd-register-form-with-validate-ajax" id="<?php echo esc_attr($random_id); ?>" >
						<p class="wd-my-account-field-wrap register-username">
							<label for="username"><?php echo $args['username_label']; ?></label>  
							<input type="text" size="20" class="wd-register-input wd-register-form-field wd-register-username" name="username" autocomplete="off" value="" placeholder="<?php echo $args['username_placeholder']; ?>">
							<span class="wd-alert wd-register-username-alert hidden"></span>
						</p>
						<p class="wd-my-account-field-wrap register-email">
							<label for="email"><?php echo $args['email_label']; ?></label>  
							<input type="text" size="20" class="wd-register-input wd-register-form-field wd-register-email" name="email" autocomplete="off" value="" placeholder="<?php echo $args['email_placeholder']; ?>">
							<span class="wd-alert wd-register-email-alert hidden"></span>
						</p>
		
						<p class="wd-my-account-field-wrap register-password">
							<label for="username"><?php echo $args['password_label']; ?></label>  
							<input type="password" size="20" value="" class="wd-register-input wd-register-form-field wd-register-password" name="password" autocomplete="off" placeholder="<?php echo $args['password_placeholder']; ?>">
							<span class="wd-alert wd-register-password-alert hidden"></span>
						</p>
		
						<p class="wd-my-account-field-wrap register-password-confirmation">
							<label for="username"><?php echo $args['password2_label']; ?></label>  
							<input type="password" size="20" value="" class="wd-register-input wd-register-form-field wd-register-password-confirmation" name="password_confirmation" autocomplete="off" placeholder="<?php echo $args['password2_placeholder']; ?>">
							<span class="wd-alert wd-register-password-confirmation-alert hidden"></span>
						</p>
		
						<p class="wd-my-account-field-wrap register-terms">
							<label><input name="terms" class="wd-register-input wd-register-form-field wd-register-terms" type="checkbox"> <?php echo $args['terms_text']; ?></label> 
							<span class="wd-alert wd-register-terms-alert hidden"></span>
						</p>
		
						<p class="wd-alert wd-register-success-alert hidden"></p>
						<p class="wd-alert wd-register-error-alert hidden"></p>
		
						<p class="register-submit">
							<span class="wd-loading wd-loading--register-form hidden">
								<img src="<?php echo WD_THEME_IMAGES.'/loading.gif'; ?>" alt="<?php echo esc_html__( 'Loading Icon' , 'feellio'); ?>">
							</span>
							<input type="submit" class="wd-my-account-btn wd-register-form-field wd-register-btn" name="submit" data-form_id="<?php echo esc_attr($random_id); ?>"  value="<?php echo $args['btn_text']; ?>" />
						</p>
					</form>
				<?php else: ?>
					<div class="wd-message wd-message-warning">
						<p><?php esc_html_e( 'Member registration is disabled!', 'feellio' ); ?></p>
						<span class="wd-message-closebtn">&times;</span> 
					</div>
				<?php endif ?>
			<?php 
			}  
		}
		
		public function get_forgot_password_form_content() {
			$random_id = 'wd-forgot-password-form-'.mt_rand();
			//Check whether the user is already logged in  
			if (is_user_logged_in()) {  
				
				// They're already logged in, so we bounce them back to the homepage.  
				
				//header( 'Location:' . esc_url(home_url('/')) );  
				$this->logged_user_information();
				
			} else{ ?>
				<?php 
				$args = array(
					'username_label'		=> esc_html__( 'Username:', 'feellio' ),
					'username_placeholder'	=> esc_html__( 'Username or E-mail...', 'feellio' ),
					'desc'					=> esc_html__( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'feellio' ),
					'btn_text'				=> esc_html__( 'Get New Password', 'feellio' ),
				); ?>
				<form method="post" class="forgot-password wd-forgot-password-form wd-forgot-password-form-with-validate-ajax" id="<?php echo esc_attr($random_id); ?>" >
					<p class="wd-my-account-field-wrap forgot-password-username">
						<label for="username"><?php echo $args['username_label']; ?></label>  
						<input type="text" size="20" 
								class="wd-forgot-password-input wd-forgot-password-form-field wd-forgot-password-username" 
								name="user_login" autocomplete="off" value="" 
								placeholder="<?php echo $args['username_placeholder']; ?>">
						<span class="wd-alert wd-forgot-password-username-alert hidden"></span>
					</p>
					<p><?php echo $args['desc']; ?></p>
					<input type="hidden" class="wd-forgot-password-input wd-forgot-password-action" name="action" value="reset" />
					
					<p class="wd-alert wd-forgot-password-success-alert hidden"></p>
					<p class="wd-alert wd-forgot-password-error-alert hidden"></p>
		
					<div class="clear"></div>
					
					<p class="forgot-password-submit">
						<span class="wd-loading wd-loading--forgot-password-form hidden">
							<img src="<?php echo WD_THEME_IMAGES.'/loading.gif'; ?>" alt="<?php echo esc_html__( 'Loading Icon' , 'feellio'); ?>">
						</span>
						<input type="submit" class="wd-my-account-btn wd-forgot-password-form-field wd-forgot-password-btn" name="submit" data-form_id="<?php echo esc_attr($random_id); ?>"  value="<?php echo $args['btn_text']; ?>" />
					</p>
				</form>
			<?php 
			}  
		}
		
		public function logged_user_information($image_size = '100'){ ?>
			<?php if (is_user_logged_in()): ?>
				<?php 
				$current_user 	= wp_get_current_user();
				$avatar_img 	= get_avatar( get_current_user_id(), $image_size);
				$name 			= !empty($current_user->data->display_name) ? $current_user->data->display_name : '';
				$name 			= $name == '' && !empty($current_user->data->user_nicename) ? $current_user->data->user_nicename : $name;
				$name 			= $name == '' && !empty($current_user->data->user_login) ? $current_user->data->user_login : $name;
				$email 			= $current_user->data->user_email;
				?>
				<div class="wd-info-user-logged-wrap">
					<div class="wd-info-user-logged-avatar">
						<?php echo $avatar_img; ?>	
					</div>
					<div class="wd-info-user-logged-name">
						<?php echo $name; ?>		
					</div>
					<div class="wd-info-user-logged-email">
						<?php echo $email; ?>		
					</div>
				</div>
			<?php endif ?>
			<?php
		}
		
		public function get_form_user_mobile(){ 
			if(wd_is_woocommerce()) return; ?>
			<div class="wd-login-user-action">
				<?php $list_my_account_page  = $this->get_list_my_account_page(); ?>
				<?php if(is_user_logged_in()): ?>	
					<a class="button wd-button-primary" href="<?php echo esc_url($list_my_account_page['login']); ?>" title="<?php esc_html_e('My Account', 'feellio');?>">
						<span><?php esc_html_e('My Account', 'feellio');?></span> 
					</a>
					<a class="button wd-button-secondary" href="<?php echo esc_url($list_my_account_page['logout']); ?>" title="<?php esc_html_e('Logout', 'feellio');?>">
						<span><?php esc_html_e('Logout', 'feellio');?></span> 
					</a>
				<?php else:?>
					<a class="button wd-button-primary" href="<?php echo esc_url($list_my_account_page['login']); ?>" title="<?php esc_html_e('Login or Register', 'feellio');?>">
						<span><?php esc_html_e('Login / Register', 'feellio');?></span>
					</a>
				<?php endif;?>		
			</div>
			<?php
		}
		
		public function login_fail_action( $username ) {
			if(isset( $_REQUEST['redirect_to']) && ($_REQUEST['redirect_to'] == admin_url())){
				return;
			}
			if(isset( $_REQUEST['redirect_to']) && strripos($_REQUEST['redirect_to'],'wp-admin') > 0 ){
				return;
			}
			if ( !wd_is_woocommerce() ) {
				return esc_html__('Woocommerce Plugin do not active', 'feellio');
			}
			global $woocommerce;
			$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
			if ( $myaccount_page_id ) {
				$myaccount_page_url = get_permalink( $myaccount_page_id );
				wp_safe_redirect( $myaccount_page_url );
				exit;
			}		
		}
	}
	WD_My_Account::get_instance();  // Start an instance of the plugin class 
}