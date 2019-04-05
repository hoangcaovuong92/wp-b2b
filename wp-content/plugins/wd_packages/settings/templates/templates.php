<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Template_Parts')) {
	class WD_Template_Parts {
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

            //add_action('pre_post_update', array($this, 'pre_post_update'), 10, 2);
			add_action('deleted_post', array($this, 'delete_post'));

            //Create home page template
			add_action('wp_ajax_nopriv_create_template_part', array($this, 'create_template_part_ajax'));
            add_action('wp_ajax_create_template_part', array($this, 'create_template_part_ajax'));

            add_action('admin_menu', array($this, 'admin_page_register'));
        }

        //**************************************************************//
		/*							PAGE TEMPLATE						*/
        //**************************************************************//
        public function admin_page_register(){
            add_submenu_page( 
                'wd-package-setting', 
                esc_html__('Demo Templates', 'wd_package'), 
                esc_html__('Demo Templates', 'wd_package'), 
                'manage_options', 
                'wd-template-part-setting', 
                array($this, 'admin_page_callback')
            );
        }
        
		public function admin_page_callback(){ 
			$templates = $this->template_part(); ?>
		    <div class="wrap wd-admin-page-wrap">
				<div class="tab-content card">
					<div id="setting" class="tab-pane fade in active">
                        <?php WD_Packages_Admin_Page::setting_page_tabs(); ?>
						<h3 class="wd-admin-page-heading"><span class="dashicons dashicons-admin-home"></span> <?php esc_html_e("Create or restore the default content of special templates:", 'wd_package'); ?></h3>
						<?php 
						if (is_array($templates) && count($templates)) { ?>
							<div class="wd-table-responsive">
								<table class="table wd-page-admin-page-form">
									<tr valign="top">
										<th><?php esc_html_e("Information", 'wd_package'); ?></th>
										<th><?php esc_html_e("Preview", 'wd_package'); ?></th>
										<th><?php esc_html_e("Status", 'wd_package'); ?></th>
										<th><?php esc_html_e("Action", 'wd_package'); ?></th>
									</tr>
									<?php foreach ($templates as $template => $data) { 
										$post_id = $this->check_template_part_exit($template); 
										$editor_list = array(
											'visual_composer' => esc_html__("Visual Composer Editor", 'wd_package'),
											'gutenberg' => esc_html__("Gutenberg Editor", 'wd_package'),
										); ?>
										<tr valign="top">
											<td>
												<h3><?php echo esc_html($data['title']); ?></h3>
												<p><?php printf(__('Post type: <strong>%s</strong>', 'wd_package'), esc_html($data['post_type'])) ?></p>
												<p><?php printf(__('Slug: <strong>%s</strong>', 'wd_package'), esc_html($template)) ?></p>

												<?php if (!empty($data['desc'])) { ?>
													<p><?php printf(__('Description: <strong>%s</strong>', 'wd_package'), esc_html($data['desc'])) ?></p>
												<?php } ?>
											</td>
											<td>
												<img src="<?php echo esc_url($data['thumbnail']); ?>" alt="<?php echo esc_html($data['title']); ?>">
											</td>
											<td>
												<?php 
												if ($post_id) {
													echo '<p>'.sprintf(__('This page already exists with the name: </br><span class="wd-form-notice">%1$s (#%2$s)</span>', 'wd_package'), get_the_title($post_id), $post_id).'</p>';
													echo '<p><a target="_blank" href="'.get_the_permalink($post_id).'">'.esc_html__("View", 'wd_package').'</a> / <a target="_blank" href="'.get_edit_post_link($post_id).'">'.esc_html__("Edit", 'wd_package').'</a></p>';
												}else{
													echo '<p class="wd-form-desc">'.esc_html__("Does not exist", 'wd_package').'</p>';
												} ?>
											</td>
											<td>
												<p>	
													<?php 
													$count = 1;
													foreach ($editor_list as $key => $value) {
														$checked = $count == 1 ? 'checked="checked"' : '';
														echo '<label><input type="radio" '.$checked.' name="set_editor_action_'.str_replace(' ', '-', $template).'" value="'.$key.'"> '.$value.'</label><br/>';
														$count ++;
													} ?>
												</p>
												
												<p><a data-template="<?php echo esc_html($template); ?>" data-template-exist="<?php echo $this->check_template_part_exit($template) ? 'true' : 'false'; ?>" href="" class="button button-primary wd-button-with-loading wd-template-parts-template">
														<?php echo $this->check_template_part_exit($template) 
																? esc_html__("Restore Template", 'wd_package') 
																: esc_html__("Create Template", 'wd_package'); ?>
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

		public function template_part($template = 'all'){
			$demo_image_id = apply_filters('wd_filter_demo_image', true);
			$admin_email = get_option('admin_email', 'vuong.hoang@codespot.vn');
			$blog_name = get_option('blogname', 'Wordpress Site');
			$home_url = get_option('siteurl', '');
			$data = array(
                'homepage-1-before-front-page' => array(
					'title'      	=> esc_html__( 'Homepage 1 - Before front page', 'wd_package' ),
					'desc'      	=> esc_html__( 'Content before home1 main content.', 'wd_package' ),
					'thumbnail'     => WDADMIN_TEMPLATE_URI.'/images/others/before_home1.jpg',
                    'content'       => '[vc_row full_width="stretch_row_content_no_spaces"][vc_column][wd_blog_slider id_category="-1" columns="1" columns_tablet="2" columns_mobile="1" image_size="wd_image_size_large" grid_hover_style="grid-inner"][vc_empty_space height="50px"][/vc_column][/vc_row][vc_row full_width="stretch_row_content"][vc_column][wd_title heading_element="h2" display_button="0" title="POPULAR POSTS"][wd_blog_special layout="category, title" id_category="-1" number_blogs="5" columns="5" columns_tablet="2" columns_mobile="1" padding="small" show_placeholder_image="1" is_slider="0"][/vc_column][/vc_row]',
                    'content_gutenberg' => '<!-- wp:shortcode -->[vc_row full_width="stretch_row_content_no_spaces"][vc_column][wd_blog_slider id_category="-1" columns="1" columns_tablet="2" columns_mobile="1" image_size="wd_image_size_large" grid_hover_style="grid-inner"][vc_empty_space height="50px"][/vc_column][/vc_row][vc_row full_width="stretch_row_content"][vc_column][wd_title heading_element="h2" display_button="0" title="POPULAR POSTS"][wd_blog_special layout="category, title" id_category="-1" number_blogs="5" columns="5" columns_tablet="2" columns_mobile="1" padding="small" show_placeholder_image="1" is_slider="0"][/vc_column][/vc_row]<!-- /wp:shortcode -->',
                    'post_type'     => 'wd_banner',
					'meta_data'   	=> array(
						'_wd_custom_layout_config' => array(
							'custom_class'			=> '',
							'custom_id'				=> ''
						),
						'wd_banner_meta_data' => array(
							'wd_banner' => array( 
								'position' => array(
									0 => 'wd_hook_banner_front_page_before'
								), 
								'banner' => '', 
								'link' => '#', 
								'target' => '_blank', 
							)
						),
                    ),
				),
				'main-contact-form' => array(
					'title'      	=> esc_html__( 'Main Contact Form', 'wd_package' ),
					'desc'      	=> esc_html__( 'The contact form display on contact page (Contact Form 7).', 'wd_package' ),
					'thumbnail'     => WDADMIN_TEMPLATE_URI.'/images/others/contact_form.jpg',
                    'content'       => '',
					'content_gutenberg' => '',
                    'post_type'     => 'wpcf7_contact_form',
					'meta_data'   	=> array(
						'_form' => '<div class="wd-contact-form"><div class="wd-contact-form-message">[textarea your-message placeholder "Your Message *"]</div><div class="wd-contact-form-info">[text* your-name placeholder "Your Name *"][email* your-email placeholder "Your Email *"][tel* your-phone placeholder "Phone Number *"]</div><div class="wd-contact-form-alert"><span class="lnr lnr-checkmark-circle wd-icon"></span>I agree, by using this form you agree with the storage and handling of your data by this website.</div><div class="wd-contact-form-submit">[submit "Send Message"]</div></div>',
						'_mail'	=> array ( 
							'active' => true, 
							'subject' => $blog_name.' "[your-name] - [your-email]"', 
							'sender' => '[your-name] <'.$admin_email.'>', 
							'recipient' => $admin_email, 
							'body' => 'From: [your-name] <[your-email]>
Phone: [your-phone]

Message Body:
[your-message]

-- 
This e-mail was sent from a contact form on '.$blog_name.' ('.$home_url.')', 
							'additional_headers' => 'Reply-To: [your-email]', 
							'attachments' => '', 
							'use_html' => false, 'exclude_blank' => false,
						),
						'_mail_2'	=> array ( 
							'active' => false, 
							'subject' => $blog_name.' "[your-name] - [your-email]"', 
							'sender' => '[your-name] <'.$admin_email.'>', 
							'recipient' => '[your-email]', 
							'body' => 'Message Body
:[your-message]

-- 
This e-mail was sent from a contact form on '.$blog_name.' ('.$home_url.')', 
							'additional_headers' => 'Reply-To: '.$admin_email, 
							'attachments' => '', 
							'use_html' => false, 
							'exclude_blank' => false,
						),
						'_messages' => array ( 
							'mail_sent_ok' => 'Thank you for your message. It has been sent.', 'mail_sent_ng' => 'There was an error trying to send your message. Please try again later.', 
							'validation_error' => 'One or more fields have an error. Please check and try again.', 
							'spam' => 'There was an error trying to send your message. Please try again later.', 
							'accept_terms' => 'You must accept the terms and conditions before sending your message.', 
							'invalid_required' => 'The field is required.', 
							'invalid_too_long' => 'The field is too long.', 
							'invalid_too_short' => 'The field is too short.', 
							'invalid_date' => 'The date format is incorrect.', 
							'date_too_early' => 'The date is before the earliest one allowed.', 
							'date_too_late' => 'The date is after the latest one allowed.', 
							'upload_failed' => 'There was an unknown error uploading the file.', 
							'upload_file_type_invalid' => 'You are not allowed to upload files of this type.', 
							'upload_file_too_large' => 'The file is too big.', 
							'upload_failed_php_error' => 'There was an error uploading the file.', 
							'invalid_number' => 'The number format is invalid.', 
							'number_too_small' => 'The number is smaller than the minimum allowed.', 
							'number_too_large' => 'The number is larger than the maximum allowed.', 
							'quiz_answer_not_correct' => 'The answer to the quiz is incorrect.', 
							'captcha_not_match' => 'Your entered code is incorrect.', 
							'invalid_email' => 'The e-mail address entered is invalid.', 
							'invalid_url' => 'The URL is invalid.', 
							'invalid_tel' => 'The telephone number is invalid.',),
						'_additional_settings' => '',
						'_locale' 	=> 'en_US',
					),
                ),
				'megamenu-1' => array(
					'title'      	=> esc_html__( 'Megamenu 1', 'wd_package' ),
					'desc'      	=> esc_html__( 'Megamenu layout demo.', 'wd_package' ),
					'thumbnail'     => WDADMIN_TEMPLATE_URI.'/images/others/megamenu.jpg',
                    'content'       => '[vc_row][vc_column][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][wd_pages_list ids="701,748,202,201" copyright="0"][/vc_column][/vc_row]',
                    'content_gutenberg' => '<!-- wp:shortcode -->[vc_row][vc_column][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][wd_pages_list ids="701,748,202,201" copyright="0"][/vc_column][/vc_row]<!-- /wp:shortcode -->',
                    'post_type'     => 'wd_megamenu',
					'meta_data'   	=> array(),
                ),
            );
            if ($template === 'all') {
                return $data;
            }else if($template !== 'all' && !empty($data[$template])){
                return $data[$template];
            }else{
                return false;
            }
        }

        //Check if template part page is created
        //Return true if already exist
        public function check_template_part_exit($template){
            $template_data = $this->template_part($template);
            if (empty($template_data)) return;

            $args = array(
                'name'          => $template,
                'post_type'     => $template_data['post_type'],
                'post_status'   => 'publish'
            );

            $posts = new WP_Query( $args );
            $result = false;
            if( $posts->have_posts() ) {
                //$result = $posts->post_count;
                while( $posts->have_posts() ) {
                    $posts->the_post(); global $post;
                    $result = $post->ID;
                }
            }
            wp_reset_postdata();
            return $result;
        }

        //Renew content of template part or create new
        //$template : name of template (a part of $this->template_part())
        public function create_template_part($template = '', $editor = 'visual_composer'){
            $post_id = $this->check_template_part_exit($template);
            $template_data = $this->template_part($template);
			if (empty($template_data)) return;

			$post_type = $template_data['post_type'];
            $content = $template_data['content'];
            $content = ($editor === 'gutenberg' && !empty($template_data['content_gutenberg'])) ? $template_data['content_gutenberg'] : $content;

            if ($post_id) {
                $post_id = wp_update_post(array(
                    'ID'            =>  $post_id,
                    'post_status'   => 'publish',
                    'post_content'  => $content,
                ));
            }else{
                $args = array(
                    'post_title'    => $template_data['title'],
                    'post_status'   => 'publish',
                    'post_content'  => $content,
                    'post_type'     => $post_type,
                );
                $post_id = wp_insert_post($args);
            }
            //Update post meta
            if ($post_id && !empty($template_data['meta_data'])) {
				foreach ($template_data['meta_data'] as $key => $value) {
					update_post_meta($post_id, $key, $value);
				}
            }
            return $content;
        }

        //**************************************************************//
		/*						        AJAX			                */
		//**************************************************************//
        public function create_template_part_ajax(){
            $template	= $_REQUEST['template'];
            $editor = $_REQUEST['editor'];
			if ($template) {
                $result['html'] = $this->create_template_part($template, $editor);
            }
			wp_send_json_success($result);
			die(); //stop "0" from being output
        }
        
        public function delete_post( $post_id ){
			delete_post_meta($post_id, '_wd_custom_layout_config');
		}
	}
	WD_Template_Parts::get_instance();  // Start an instance of the plugin class 
}