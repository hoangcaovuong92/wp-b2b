<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Ajax')) {
	class WD_Ajax {
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

			//Disable email popup
			add_action('wp_ajax_nopriv_wd_ajax_disabled_email_popup', array($this, 'disabled_email_popup'));
			add_action('wp_ajax_wd_ajax_disabled_email_popup', array($this, 'disabled_email_popup'));

			//Empty shopping cart
			add_action('wp_ajax_nopriv_wd_woocommerce_empty_cart', array($this, 'empty_cart'));
			add_action('wp_ajax_wd_woocommerce_empty_cart', array($this, 'empty_cart'));

			//login with validate ajax
			add_action('wp_ajax_nopriv_login_with_validate_ajax', array($this, 'login_with_validate_ajax'));
			add_action('wp_ajax_login_with_validate_ajax', array($this, 'login_with_validate_ajax'));

			//Register with validate ajax
			add_action('wp_ajax_nopriv_register_with_validate_ajax', array($this, 'register_with_validate_ajax'));
			add_action('wp_ajax_register_with_validate_ajax', array($this, 'register_with_validate_ajax'));

			//Forgot Password with ajax
			add_action('wp_ajax_nopriv_forgot_password_with_ajax', array($this, 'forgot_password_with_ajax'));
			add_action('wp_ajax_forgot_password_with_ajax', array($this, 'forgot_password_with_ajax'));
		}

		public function disabled_email_popup(){
			$expire		= $_REQUEST['expire'];
			$type 		= session_id() ? 'session' : 'transient'; 
			$key		= 'wd_disabled_email_popup';

			if ($type == 'transient') {
				$value			= array(
					'disabled_time'	=> time(),
					'expire'		=> $expire,
				);
				$expire			= ($expire == -1) ? YEAR_IN_SECONDS : $expire * 60;
				set_transient( $key, $value, $expire );
			}else{
				$expire			= ($expire == -1) ? strtotime('+1 years') : time() + ($expire * 60);
				$_SESSION[$key] = $expire; 
			}
			die(); //stop "0" from being output
		}

		public function empty_cart(){
			global $woocommerce;
			$woocommerce->cart->empty_cart();
			die(); //stop "0" from being output
		}

		public function login_with_validate_ajax(){
			$result 	= array();
			$mess  		= array();
			$success 	= false;
			if($_POST){  
				$username = esc_sql($_REQUEST['username']);  
				$password = esc_sql($_REQUEST['password']);  
				$remember = esc_sql($_REQUEST['rememberme']);  

				$remember 						= ($remember) ? "true" : "false";
				$login_data 					= array();  
				$login_data['user_login'] 		= $username;  
				$login_data['user_password'] 	= $password;  
				$login_data['remember'] 		= $remember;  

				$user = !is_email($username) ? get_user_by( 'login', $username ) : get_user_by( 'email', $username );
				if ( $user && wp_check_password( $password, $user->data->user_pass, $user->ID ) ){ //check username & password
					$user_signon = wp_signon( $login_data, false ); //login process
					if ( is_wp_error($user_signon) ){
						$mess['error'] = esc_html__( "Unknown error!", 'feellio' );  
					} else {
						$mess['success'] = esc_html__( "Login successful, redirecting...", 'feellio' );  
						$success 		 = true;
					}
				}else{
					$mess['error'] = esc_html__( "Wrong username or password!", 'feellio' );  
				}
			} 
			$result['mess'] 		= $mess;
			$result['success'] 		= $success;
			echo json_encode($result);
			die(); //stop "0" from being output
		}
		
		function register_with_validate_ajax() {
			$result 	= array();
			$mess  		= array();
			$success 	= false;
			if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {  
				// Check username is present and not already in use  
				$username = esc_sql($_REQUEST['username']);  
				if ( strpos($username, ' ') !== false ){   
					$mess['username'] = esc_html__( "Sorry, no spaces allowed in usernames", 'feellio' );  
				}  
				if(empty($username)){   
					$mess['username'] = esc_html__( "Please enter a username", 'feellio' );  
				} elseif( username_exists( $username ) ) {  
					$mess['username'] = esc_html__( "Username already exists, please try another", 'feellio' );  
				}  
		
				// Check email address is present and valid  
				$email = esc_sql($_REQUEST['email']);  
				if( !is_email( $email ) ) {   
					$mess['email'] = esc_html__( "Please enter a valid email", 'feellio' );  
				} elseif( email_exists( $email ) ) {  
					$mess['email'] = esc_html__( "This email address is already in use", 'feellio' );  
				}  
		
				// Check password is valid  
				if(0 === preg_match("/.{6,}/", $_POST['password'])){  
				$mess['password'] = esc_html__( "Password must be at least six characters", 'feellio' );  
				}  
		
				// Check password confirmation_matches  
				if(0 !== strcmp($_POST['password'], $_POST['password_confirmation'])){  
				$mess['password-confirmation'] = esc_html__( "Passwords do not match", 'feellio' );  
				}  
		
				// Check terms of service is agreed to  
				if(empty($_POST['terms']) || $_POST['terms'] != 'on'){  
					$mess['terms'] = esc_html__( "You must agree to Terms of Service!", 'feellio' );  
				}  
				
				if(count($mess) == 0 && !is_wp_error($mess))  {  
					$success 			= true;
					$mess['success'] 	= esc_html__( "Register account to success!", 'feellio' );  
					$password 			= $_POST['password'];  
					$new_user_id 		= wp_create_user( $username, $password, $email );  
		
					// You could do all manner of other things here like send an email to the user, etc. I leave that to you.  
					//$success = 1;  
					//header( 'Location:' . get_bloginfo('url') . '/login/?success=1&u=' . $username );  
				}
			} 
			$result['mess'] 		= $mess;
			$result['success'] 		= $success;
			echo json_encode($result);
			die(); //stop "0" from being output
		}
		
		function forgot_password_with_ajax() {
			if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {  
				$result 	= array();
				$mess  		= array();
				$success 	= false;

				// check if we're in reset form
				if( isset( $_POST['action_form'] ) && 'reset' == $_POST['action_form'] ){
					$email = trim(esc_sql($_REQUEST['username']));
					if( empty( $email ) ) {
						$mess['username'] 	= esc_html__( 'Enter a username or e-mail address..', 'feellio' );
					} else if( ! is_email( $email )) {
						$mess['username'] 	= esc_html__( 'Invalid username or e-mail address.', 'feellio' );
					} else if( ! email_exists( $email ) ) {
						$mess['error'] 		= esc_html__( 'There is no user registered with that email address.', 'feellio' );
					} else {

						$random_password 	= wp_generate_password( 12, false );
						$user 				= get_user_by( 'email', $email );

						$update_user = wp_update_user( array (
								'ID' => $user->ID,
								'user_pass' => $random_password
							)
						);

						// if  update user return true then lets send user an email containing the new password
						if( $update_user ) {
							$to 		= $email;
							$subject 	= esc_html__( 'Your new password', 'feellio' );
							$sender 	= get_option('name');

							$message 	= esc_html__( 'Your new password is: ', 'feellio' ).$random_password;

							$headers[] 	= 'MIME-Version: 1.0' . "\r\n";
							$headers[] 	= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							$headers[] 	= "X-Mailer: PHP \r\n";
							$headers[] 	= 'From: '.$sender.' < '.$email.'>' . "\r\n";

							$mail 		= wp_mail( $to, $subject, $message, $headers );
							if( !$mail ) {
								$mess['error'] 		= esc_html__( 'Error when sending email...', 'feellio' );
							}else{
								$success 			= true;
								$mess['successs'] 	= esc_html__( 'Check your email address for you new password.', 'feellio' );
							}

						} else {
							$mess['error'] 	= esc_html__( 'Oops something went wrong updaing your account.', 'feellio' );
						}
					}

					$result['mess'] 		= $mess;
					$result['success'] 		= $success;
				}
				echo json_encode($result);
			} 
			die(); //stop "0" from being output
		}
	}
	WD_Ajax::get_instance();  // Start an instance of the plugin class 
}