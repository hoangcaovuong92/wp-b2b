<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Plugins')) {
	class WD_Plugins {
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
            $this->load_library();
            add_action('tgmpa_register', array($this, 'register_tgmpa_plugin'));
        }

        // Load File
		protected function load_library(){
            if(file_exists(WD_THEME_FUNCTIONS."/required/class/class-tgm-plugin-activation.php")){
                require_once WD_THEME_FUNCTIONS."/required/class/class-tgm-plugin-activation.php";
            }
        }

        public function plugins_list(){
            return $this->framework_plugins();
        }

        public function framework_plugins(){
            return array(
                array(
                    'name'                  => esc_html__('WD Packages', 'feellio'), // The plugin name
                    'desc'                  => esc_html__('Provide shortcodes, widgets, gutenberg blocks... for you to build a great website...', 'feellio'), // The plugin description
                    'slug'                  => 'wd_packages', // The plugin slug (typically the folder name)
                    'source'                => WD_THEME_PLUGINS . '/wd_packages.zip', // The plugin source
                    'required'              => true, // If false, the plugin is only 'recommended' instead of required
                    'version'               => '1.0.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                    'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                    'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                    'external_url'          => '', // If set, overrides default API URL and points to an external URL
                ),
                array(
                    'name'                  => esc_html__('WPBakery Page Builder', 'feellio'), // The plugin name
                    'desc'                  => esc_html__('Drag and drop page builder for WordPress. Take full control over your WordPress site, build any layout you can imagine, no programming knowledge required.', 'feellio'), // The plugin description
                    'slug'                  => 'js_composer', // The plugin slug (typically the folder name)
                    'source'                => WD_THEME_PLUGINS . '/js_composer.zip', // The plugin source
                    'required'              => true, // If false, the plugin is only 'recommended' instead of required
                    'version'               => '5.7', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                    'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                    'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                    'external_url'          => '', // If set, overrides default API URL and points to an external URL
                ),
                array(
                    'name'                  => esc_html__('Redux Framework', 'feellio'), // The plugin name
                    'desc'                  => esc_html__('Redux is a simple, truly extensible options framework for WordPress themes and plugins.', 'feellio'), // The plugin description
                    'slug'                  => 'redux-framework', // The plugin slug (typically the folder name)
                    'required'              => true, // If false, the plugin is only 'recommended' instead of required
                ),
                array(
                    'name'                  => esc_html__('Autoptimize', 'feellio'), // The plugin name
                    'desc'                  => esc_html__('Optimize your website\'s performance: JS, CSS, HTML, images, Google Fonts and more!', 'feellio'), // The plugin description
                    'slug'                  => 'autoptimize', // The plugin slug (typically the folder name)
                    'required'              => false, // If false, the plugin is only 'recommended' instead of required
                ),
                array(
                    'name'                  => esc_html__('WP Fastest Cache', 'feellio'), // The plugin name
                    'desc'                  => esc_html__('WP Fastest Cache accelerate the performance of your website. It aims to optimize page load times by creating and storing a static copy of your posts and pages, reducing the number of database queries required to render your site and associated server load.', 'feellio'), // The plugin description
                    'slug'                  => 'wp-fastest-cache', // The plugin slug (typically the folder name)
                    'required'              => false, // If false, the plugin is only 'recommended' instead of required
                ),
                array(
                    'name'                  => esc_html__('a3 Lazy Load', 'feellio'), // The plugin name
                    'desc'                  => esc_html__('Speed up your site and enhance frontend user\'s visual experience in PC\'s, Tablets and mobile with a3 Lazy Load.', 'feellio'), // The plugin description
                    'slug'                  => 'a3-lazy-load', // The plugin slug (typically the folder name)
                    'required'              => false, // If false, the plugin is only 'recommended' instead of required
                ),
                array(
                    'name'                  => esc_html__('Post SMTP Mailer/Email Log', 'feellio'), // The plugin name
                    'desc'                  => esc_html__('Email not reliable? Post SMTP is the first and only WordPress SMTP plugin to implement OAuth 2.0 for Gmail, Hotmail and Yahoo Mail. Setup is a breeze with the Configuration Wizard and integrated Port Tester. Enjoy worry-free delivery even if your password changes!', 'feellio'), // The plugin description
                    'slug'                  => 'post-smtp', // The plugin slug (typically the folder name)
                    'required'              => false, // If false, the plugin is only 'recommended' instead of required
                ),
                array(
                    'name'                  => esc_html__('Loco Translate', 'feellio'), // The plugin name
                    'desc'                  => esc_html__('Provides in-browser editing of WordPress translation files.', 'feellio'), // The plugin description
                    'slug'                  => 'loco-translate', // The plugin slug (typically the folder name)
                    'required'              => false, // If false, the plugin is only 'recommended' instead of required
                ),
                array(
                    'name'                  => esc_html__('Contact Form 7', 'feellio'), // The plugin name
                    'desc'                  => esc_html__('Just another contact form plugin. Simple but flexible.', 'feellio'), // The plugin description
                    'slug'                  => 'contact-form-7', // The plugin slug (typically the folder name)
                    'required'              => false, // If false, the plugin is only 'recommended' instead of required
                ),
                array(
                    'name'                  => esc_html__('Email Subscribers', 'feellio'), // The plugin name
                    'desc'                  => esc_html__('Add subscription forms on website, send HTML newsletters & automatically notify subscribers about new blog posts once it is published.', 'feellio'), // The plugin description
                    'slug'                  => 'email-subscribers', // The plugin slug (typically the folder name)
                    'required'              => false, // If false, the plugin is only 'recommended' instead of required
                ),
             ); //End plugins
        }

        public function register_tgmpa_plugin(){
            $plugins_list = $this->framework_plugins();
    
            $tmpa_configs = array(
                'default_path'      => '',
                'menu'              => 'tgmpa-install-plugins',
                'has_notices'       => true,
                'dismissable'       => true,
                'dismiss_msg'       => '',
                'is_automatic'      => false,
                'message'           => '',
                'strings' => array(
                    'page_title'                        => esc_html__('Install Required Plugins', 'feellio'),
                    'menu_title'                        => esc_html__('Install Plugins', 'feellio'),
                    'installing'                        => esc_html__('Installing Plugin: %s', 'feellio'),
                    'oops'                              => esc_html__('Something went wrong with the plugin API.', 'feellio'),
                    'notice_can_install_required'       => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'feellio'),
                    'notice_can_install_recommended'    => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'feellio'),
                    'notice_cannot_install'             => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'feellio'),
                    'notice_can_activate_required'      => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'feellio'),
                    'notice_can_activate_recommended'   => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'feellio'),
                    'notice_cannot_activate'            => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'feellio'),
                    'notice_ask_to_update'              => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'feellio'),
                    'notice_cannot_update'              => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'feellio'),
                    'install_link'                      => _n_noop('Begin installing plugin', 'Begin installing plugins', 'feellio'),
                    'activate_link'                     => _n_noop('Begin activating plugin', 'Begin activating plugins', 'feellio'),
                    'return'                            => esc_html__('Return to Required Plugins Installer', 'feellio'),
                    'plugin_activated'                  => esc_html__('Plugin activated successfully.', 'feellio'),
                    'complete'                          => esc_html__('All plugins installed and activated successfully. %s', 'feellio'),
                    'nag_type'                          => 'updated'
                )
            );
            tgmpa($plugins_list, $tmpa_configs);
        }
	}
	WD_Plugins::get_instance();  // Start an instance of the plugin class 
}