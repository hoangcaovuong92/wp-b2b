<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Blog')) {
	class WD_Blog {
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

			$this->include_libs();
			
			// Custom blog form protect password
			add_filter( 'the_password_form', array($this, 'password_protect_form') );

			//Display post page link (page break)
			//do_action('wd_hook_page_link'); //Display page link:
			add_action('wd_hook_page_link', array($this, 'display_blog_page_link'), 5);
			
			//ARCHIVE BLOG========================

			//Display blog content:
			// echo apply_filters('wd_filter_blog_content', array('thumbnail_size' => $thumbnail_size, 'post_format' => $post_format, 'custom_placeholder_set' => $custom_placeholder_set, 'custom_class' => $custom_class));
			add_filter( 'wd_filter_blog_content', array($this, 'get_blog_content' ), 10, 2);

			//Single blog
			//echo apply_filters('wd_filter_blog_single', true);
			add_filter( 'wd_filter_blog_single', array($this, 'get_blog_single' ), 10, 2);

			//CONTENT BLOG========================
			
			//Display blog thumbnail
			//echo apply_filters('wd_filter_blog_thumbnail', $image_size, $display, $custom_class);
			add_filter( 'wd_filter_blog_thumbnail', array($this, 'display_blog_thumbnail' ), 10, 2);

			//Get blog thumbnail gallery
			// echo apply_filters('wd_filter_blog_thumbnail_html', array('thumbnail_size' => 'full', 'show_thumbnail' => false, 'num' => 1, 'placeholder' => false, 'custom_class' => '')); 
			add_filter( 'wd_filter_blog_thumbnail_html', array($this, 'get_post_thumbnail_html' ), 10, 2);

			//Display blog author information
			//echo apply_filters('wd_filter_blog_author_information', $display, $custom_class); 
			add_filter( 'wd_filter_blog_author_information', array($this, 'display_author_information' ), 10, 2);

			//Display blog title
			//echo apply_filters('wd_filter_blog_meta', $echo); 
			add_filter( 'wd_filter_blog_meta', array($this, 'display_post_meta' ), 10, 2);
			
 			//Display blog title
			//echo apply_filters('wd_filter_blog_title', $custom_class);
			add_filter( 'wd_filter_blog_title', array($this, 'display_blog_title' ), 10, 2);

			//Display blog sticky
			//echo apply_filters('wd_filter_blog_sticky', true);
			add_filter( 'wd_filter_blog_sticky', array($this, 'display_blog_sticky' ), 10, 2);

			//Display blog author
			//echo apply_filters('wd_filter_blog_author', $display, $custom_class);
			add_filter( 'wd_filter_blog_author', array($this, 'display_blog_author' ), 10, 2);

			//Display blog category
			//echo apply_filters('wd_filter_blog_category', $display);
			add_filter( 'wd_filter_blog_category', array($this, 'display_blog_category' ), 10, 2);

			//Display blog date
			//echo apply_filters('wd_filter_blog_date', $display, $custom_class);
			add_filter( 'wd_filter_blog_date', array($this, 'display_blog_date' ), 10, 2);

			//Display blog number comment
			//echo apply_filters('wd_filter_blog_number_comment', $display, $custom_class);
			add_filter( 'wd_filter_blog_number_comment', array($this, 'display_blog_number_comment' ), 10, 2);

			//Display blog tag
			//echo apply_filters('wd_filter_blog_tag', $display, $custom_class);
			add_filter( 'wd_filter_blog_tag', array($this, 'display_blog_tag' ), 10, 2);

			//Display blog excerpt
			//echo apply_filters('wd_filter_blog_excerpt', $display, $number_excerpt, $custom_class);
			add_filter( 'wd_filter_blog_excerpt', array($this, 'display_blog_excerpt' ), 10, 2);

			//Display blog readmore
			//echo apply_filters('wd_filter_blog_readmore', $display, $custom_class);
			add_filter( 'wd_filter_blog_readmore', array($this, 'display_blog_readmore' ), 10, 2);

			/* do_action('wd_hook_blog_archive_toggle_button'); */
			add_action('wd_hook_blog_archive_toggle_button', array($this, 'blog_archive_toggle_button'), 5);
		}
		
		public function include_libs() {
			if(file_exists(WD_THEME_FUNCTIONS. "/blog/post_like/wd_post_like.php")){
				require_once WD_THEME_FUNCTIONS. "/blog/post_like/wd_post_like.php";
			}
		}

		/**
		 * Show article content with customization in the Theme Option
		 * $post_format : 'gallery', 'image', 'video', 'audio', 'quote', 'link'
		 * $custom_placeholder_set (true or false) : If you do not adjust this parameter, the blog will get the default settings in the Blog Config
		 */  
		public function get_blog_content( $args = array() ) {
			$default = array(
				'thumbnail_size' => 'full', 
				'post_format' => 'auto', 
				'custom_placeholder_set' => '', 
				'custom_class' => ''
			);
			extract(wp_parse_args($args, $default));

			/**
			 * package: content-blog
			 * var: show_title
			 * var: show_thumbnail
			 * var: show_by_post_format
			 * var: placeholder_image
			 * var: show_author
			 * var: show_category
			 * var: show_number_comments
			 * var: show_tag 
			 * var: show_like 
			 * var: show_view
			 * var: show_share
			 * var: show_excerpt
			 * var: number_excerpt
			 * var: show_readmore
			 */
			extract(apply_filters('wd_filter_get_data_package', 'content-blog' ));

			//If post_format haven't set, check show_by_post_format to call post format property.
			if ($post_format == 'auto') {
				$post_format = ($show_by_post_format) ? get_post_format() : '';
			}

			//placeholder_image : true/false . If custom_placeholder_set is set, will return custom_placeholder_set value instead of.
			$placeholder_image = (is_bool($custom_placeholder_set)) ? $custom_placeholder_set : $placeholder_image;
			ob_start();
				global $post;
				$class_content = ((has_post_thumbnail() || $this->get_post_attachment() || 
						$placeholder_image) && $post_format != 'audio' && $post_format != 'video') 
						? 'wd-post-has-thumbnail' : 'wd-post-without-thumbnail';
						
				$class_wrap = array('wd-blog-item', 'wd-blog-item-wrap');
				if ($custom_class) {
					$class_wrap[] = $custom_class;
				} ?>
				<article itemscope itemtype="http://schema.org/Article" id="post-<?php the_ID(); ?>" <?php post_class($class_wrap); ?>>
					<?php if ($post_format == 'audio') { ?>
						<div class="wd-content-post-format wd-content-post-format--audio <?php echo esc_attr($class_content); ?>">
							<?php 
							if(!is_home()){
								echo $this->get_embedded_media( array('audio','iframe'), '50%' );
							} ?>
							<div class="wd-blog-content-wrap">
								<?php
								$this->display_blog_category($show_category);
								$this->display_blog_title($show_title);
								$this->display_post_meta('loop');
								$this->display_blog_excerpt($show_excerpt, $number_excerpt);
								$this->display_blog_readmore($show_readmore); ?>
							</div>
						</div>
					<?php
					} elseif ($post_format == 'gallery') { ?>
						<div class="wd-content-post-format wd-content-post-format--gallery <?php echo esc_attr($class_content); ?>">
							<?php if (!is_home()): ?>
								<?php echo $this->display_blog_thumbnail_gallery($thumbnail_size, $show_thumbnail, $placeholder_image); ?>
							<?php endif ?>
							<div class="wd-blog-content-wrap">
								<?php
								$this->display_blog_category($show_category);
								$this->display_blog_title($show_title);
								$this->display_post_meta('loop');
								$this->display_blog_excerpt($show_excerpt, $number_excerpt);
								$this->display_blog_readmore($show_readmore); ?>
							</div>
						</div>
					<?php
					} elseif ($post_format == 'link') { ?>
						<div class="wd-content-post-format wd-content-post-format--link <?php echo esc_attr($class_content); ?>">
							<?php 
							$link = $this->get_links_from_content();
							the_title( '<a href="' . $link . '" target="_blank">', '<div class="wd-link-icon"><span class="wd-post-icon wd-post-link"></span></div></a>');  ?>
						</div>
					<?php
					} elseif ($post_format == 'quote') { ?>
						<div class="wd-content-post-format wd-content-post-format--quote <?php echo esc_attr($class_content); ?>">
							<div class="wd-blog-content-wrap">	
								<?php if (is_home()): ?>
									<?php
									$this->display_blog_category($show_category);
									$this->display_blog_title($show_title);
									$this->display_post_meta('loop'); ?>
								<?php endif ?>
								
								<div class="wd-content-quote-info">
									<?php the_excerpt() ?>
								</div>
								<div class="wd-content-quote-author">
									<?php the_author_posts_link(); ?>
								</div>
								<?php $this->display_blog_readmore($show_readmore); ?>
							</div>
						</div>
					<?php
					} elseif ($post_format == 'video') { ?>
						<div class="wd-content-post-format wd-content-post-format--video <?php echo esc_attr($class_content); ?>">
							<?php echo $this->get_embedded_media( array('video','iframe') ); ?>
							<div class="wd-blog-content-wrap">
								<?php
								$this->display_blog_category($show_category);
								$this->display_blog_title($show_title);
								$this->display_post_meta('loop');
								$this->display_blog_excerpt($show_excerpt, $number_excerpt);
								$this->display_blog_readmore($show_readmore); ?>
							</div>
						</div>
					<?php
					} else { ?>
						<div class="wd-content-post-format wd-content-post-format--none <?php echo esc_attr($class_content); ?>">
							<?php echo $this->get_post_thumbnail_html(array(
									'thumbnail_size' => $thumbnail_size, 
									'show_thumbnail' => $show_thumbnail,
									'num' => 1,
									'placeholder' => $placeholder_image,
									'custom_class' => ''
								)); ?>
							<div class="wd-blog-content-wrap">
								<?php
								$this->display_blog_category($show_category);
								$this->display_blog_title($show_title);
								$this->display_post_meta('loop');
								$this->display_blog_excerpt($show_excerpt, $number_excerpt);
								$this->display_blog_readmore($show_readmore); ?>
							</div>
						</div>
					<?php } ?>
				</article>
			<?php return ob_get_clean();
		}

		public function get_blog_single() {
			/**
			 * package: single-blog-content
			 * var: show_author_information'
			 * var: show_previous_next_btn'
			 * var: show_title  	
			 * var: show_thumbnail  
			 * var: show_author  	
			 * var: show_number_comments 
			 * var: show_tag 
			 * var: show_like 
			 * var: show_view
			 * var: show_share
			 * var: show_category  
			 * var: show_excerpt  	
			 * var: number_excerpt  
			 * var: show_readmore  
			 */
			extract(apply_filters('wd_filter_get_data_package', 'single-blog-content' )); 
			global $post;
			$thumbnail_class = (has_post_thumbnail()) ? 'wd-single-blog-has-thumbnail' : 'wd-single-blog-without-thumbnail';
			ob_start(); ?>
				<div class="wd-blog-content-wrap <?php echo esc_attr($thumbnail_class); ?>">
					<div class="wd-blog-single-top">
						<?php 
						$this->display_blog_category($show_category);
						$this->display_blog_single_title($show_title);
						$this->display_post_meta('single'); 
						$this->display_post_share($show_share); ?>
					</div>
					<?php
					$this->display_blog_thumbnail('full', $show_thumbnail); ?>

					<div class="wd-blog-desc">
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="entry-content">

								<?php if ( !post_password_required() /* || current_user_can('editor') || current_user_can('administrator') */) { 
									the_content( sprintf(__( 'Continue reading<span class="wd-screen-reader-text"> "%s"</span>', 'feellio' ),
										get_the_title()
									) );

									/**
									 * wd_hook_page_link hook.
									 *
									 * @hooked display_blog_page_link - 5
									 */
									do_action('wd_hook_page_link'); 
								}else{
									echo get_the_password_form();
								} ?>

							</div><!-- .entry-content -->

							<?php 
							$this->display_blog_tag($show_tag);
							$this->display_author_information($show_author_information); ?>
							
						</article><!-- End Article -->
					</div>
					<?php $this->display_blog_previous_next_btn($show_previous_next_btn); ?>
				</div>
			<?php
			return ob_get_clean();
		}

		public function blog_archive_toggle_button(){ ?>
			<div class="wd-layout-toggle-wrap wd-layout-toggle-blog wd-desktop-screen">
				<ul class="option-set">
					<li data-layout="grid" class="wd-grid-list-toggle-action" data-toggle="tooltip" title="<?php _e('Grid view', 'feellio'); ?>">
						<i class="lnr lnr-menu wd-icon"></i>
					</li>
					<li data-layout="list" class="wd-grid-list-toggle-action" data-toggle="tooltip" title="<?php _e('List view', 'feellio'); ?>">
						<i class="lnr lnr-list wd-icon"></i>
					</li>
				</ul>
			</div>
		<?php 
		}

		//type: loop / single
		public function display_post_meta($type = 'loop'){
			/**
			 * package: content-blog
			 * var: show_title
			 * var: show_thumbnail
			 * var: show_by_post_format
			 * var: placeholder_image
			 * var: show_author
			 * var: show_category
			 * var: show_number_comments
			 * var: show_tag 
			 * var: show_like 
			 * var: show_view
			 * var: show_share
			 * var: show_excerpt
			 * var: number_excerpt
			 * var: show_readmore
			 */
			extract(apply_filters('wd_filter_get_data_package', 'content-blog' ));
			ob_start(); ?>
			<?php if ($type == 'single') { ?>
				<div class="wd-blog-meta-wrap">
					<div class="wd-blog-meta-content">
						<?php 
						$this->display_blog_author($show_author);
						$this->display_blog_number_comment($show_number_comments);
						$this->set_post_views($show_view);
						$this->display_post_views($show_view);
						$this->display_post_like($show_like);
						$this->display_blog_edit_link(); ?>
					</div>
				</div>
			<?php } else { ?>
				<div class="wd-blog-meta-wrap">
					<?php 
					//$this->display_blog_sticky();
					$this->display_blog_author($show_author); ?>
					<div class="wd-blog-meta-content">
						<?php 
						$this->display_blog_number_comment($show_number_comments);
						$this->display_post_views($show_view); ?>
					</div>
				</div>
			<?php } ?>
			<?php 
			echo ob_get_clean();
		}

		/* Return object attachment of current post */
		public function get_post_attachment($num = 1, $post_id = ''){
			global $post;
			$post_id 		= ($post_id) ? $post_id : get_the_ID();
			$attachment_ids = array();
			
			$attachments = get_post_galleries($post, false);
			if ($attachments && isset($attachments[0]['ids'])) {
				$attachment_ids = explode(',', $attachments[0]['ids']);
			}

			if ( count($attachment_ids) == 0 ) {
				$attachments = get_posts(array(
					'post_type' => 'attachment',
					'posts_per_page' => $num,
					'post_parent' => $post_id,
				));
				if ($attachments){
					foreach ($attachments as $attachment){
						$attachment_ids[] = $attachment->ID; 
					}
				}
			}

			if (has_post_thumbnail() && get_the_post_thumbnail()) {
				if (!is_single()) {
					array_unshift($attachment_ids, get_post_thumbnail_id(get_the_ID()));
				}else{
					$attachment_ids[] = get_post_thumbnail_id(get_the_ID());
				}
			}

			$attachment_ids = count($attachment_ids) > $num ? array_slice($attachment_ids, 0, $num) : $attachment_ids;
			return $attachment_ids;
		}
		
		/* Return html of thumbnail image */
		public function get_post_thumbnail_html($setting = array() ) {
			$default = array(
				'thumbnail_size' => 'full', 
				'show_thumbnail' => false,
				'num' => 1,
				'placeholder' => false,
				'custom_class' => ''
			);
			extract(wp_parse_args($setting, $default));
			global $post;
			$output = '';
			$slider = ( 1 < $num ) ? 'owl-carousel' : '';
			if ( $show_thumbnail ) {
				if ( has_post_thumbnail() && get_the_post_thumbnail() && 1 == $num ) {
					ob_start(); ?>
					<div class="wd-thumbnail-post <?php echo $slider; ?> <?php echo esc_attr($custom_class); ?>">
						<a class="wd-thumbnail" href="<?php echo get_permalink(); ?>">
							<?php echo get_the_post_thumbnail( null, $thumbnail_size ); ?>
						</a>
					</div><!-- .wd-thumbnail-post -->
					<?php
					$output = ob_get_clean();
				} else {
					$attachments = $this->get_post_attachment($num);
					if ( $attachments && 1 == $num ) {
						ob_start(); ?>
						<div class="wd-thumbnail-post <?php echo $slider; ?> <?php echo esc_attr($custom_class); ?>">
							<a class="wd-thumbnail" href="<?php echo get_permalink(); ?>">
								<?php echo wp_get_attachment_image( $attachments[0], $thumbnail_size ); ?>
							</a>
						</div><!-- .wd-thumbnail-post -->
						<?php
						$output = ob_get_clean();
					} elseif ( $attachments && 1 < $num ) {
						ob_start(); ?>
						<div class="wd-thumbnail-post <?php echo $slider; ?> <?php echo esc_attr($custom_class); ?>">
							<?php foreach ( $attachments as $attachment ) { ?>
								<div class="wd-thumbnail">
									<?php echo wp_get_attachment_image( $attachment, $thumbnail_size ); ?>
								</div>
							<?php } ?>
						</div><!-- .wd-thumbnail-post -->
						<?php
						$output = ob_get_clean();
					} else {
						if ($placeholder && !is_home()) {
							$output = $this->get_placeholder_image('html');
						} 
					}
				}
			}
			return $output;
		}

		/* $num = 1: Return url of thumbnail or the first attachment images
		* $num > 1: Return object attachment images */
		public function get_post_thumbnail($image_size = 'full', $num = 1, $placeholder = false){
			global $post;
			$output = array();
			$attachments = $this->get_post_attachment($num);

			if ($attachments){
				foreach ($attachments as $attachment){
					$image_thumb = wp_get_attachment_image_src( $attachment, $image_size );
					$output[] = $image_thumb[0]; 
				}
			}

			if (count($attachments) == 0 && $placeholder && !is_home()) {
				$output[] = $this->get_placeholder_image('url');
			}
			return $output;
		}

		public function get_bs_slides($attachments, $image_size = 'full'){
			$output = array();
			$count = count($attachments) - 1;
			for ($i = 0; $i <= $count; ++$i){
				$currentImg = $attachments[$i];
				$active 	= ($i == 0 ? ' active' : '');
				$next_key 	= ($i == $count ? 0 : $i + 1);
				$nextImg 	= $attachments[$next_key];
				$prev_key 	= ($i == 0 ? $count : $i - 1);
				$prevImg 	= $attachments[$prev_key];
				
				$output[$i] = array(
						'class' 	=> $active,
						'url' 		=> $currentImg,
						'next_img' 	=> $nextImg,
						'prev_img' 	=> $prevImg,
					);
			}
			return $output;
		}

		/* Return placeholder image to display when post no thumbnail
		* $type = 'html': Return html of placeholer image
		* $type = 'url'	: Return url of placeholer image */
		public function get_placeholder_image($type = 'html', $image_size = 'post-thumbnail', $custom_class = ''){
			global $post;
			$demo_image_id = apply_filters('wd_filter_demo_image', true);
			$output = '';
			$wrap_class = 'wd-image-placeholder';
			$image_class = 'attachment-'.$image_size.' size-'.$image_size.' wp-post-image';

			if ($demo_image_id) {
				$image_placeholder = wp_get_attachment_image_src($demo_image_id, $image_size)[0];
			} else {
				$post_thumb_size = $this->get_dimension_image_size($image_size);
				$image_placeholder = 'http://via.placeholder.com/'.$post_thumb_size['width'].'x'.$post_thumb_size['height'];
			}

			if ($type == 'html') {
				ob_start(); ?>
				<div class="wd-thumbnail-post <?php echo esc_attr($custom_class); ?>">
					<a class="wd-thumbnail <?php echo esc_attr($wrap_class); ?>" href="<?php echo get_permalink(); ?>">
						<img class="<?php echo esc_attr($image_class); ?>" src="<?php echo esc_url($image_placeholder); ?>" alt="<?php echo get_the_title(); ?>" title="<?php echo get_the_title(); ?>" />
						<span class="lnr lnr-picture wd-icon"></span>
					</a>
				</div><!-- .wd-thumbnail-post -->
				<?php
				$output = ob_get_clean();
			} elseif ($type == 'url') {
				$output = $image_placeholder;
			}
			return $output;
		}

		// Return array width/height of image size
		public function get_dimension_image_size($image_size = 'post-thumbnail'){
			$image_size = ($image_size == 'full' || $image_size == '') ? 'post-thumbnail' : $image_size; 
			global $_wp_additional_image_sizes;
			$image_size_arr = array();
			$post_thumb_width_default 	= $_wp_additional_image_sizes[$image_size]['width'];
			$post_thumb_height_default 	= $_wp_additional_image_sizes[$image_size]['height'];
			$image_size_arr['width'] 	= $post_thumb_width_default;
			$image_size_arr['height'] 	= $post_thumb_height_default;
			return $image_size_arr;
		}

		public function get_embedded_media($type = array(), $height = '50%'){
			global $post;
			$content 	= do_shortcode(apply_filters('the_content', get_the_content()));
			$embed 		= get_media_embedded_in_content($content, $type);
			$output 	= '';
			if ($embed) {
				if (in_array('audio', $type)){
					$output = '<div class="wd-post-embed-audio">';
					$output .= str_replace('?visual=true', '?visual=false', $embed[0]);
					$output = str_replace('height="400"', 'height="'.$height.'"', $output);
					$output .= '</div>';
				} elseif(in_array('video', $type)) {
					$output = '<div class="wd-post-embed-audio">';
					$output .= '<div class="embed-responsive embed-responsive-16by9">';
					$output .= $embed[0];
					$output .= '</div></div>';
				}else {
					$output = $embed[0];
				}
			}
			
			return $output;
		}

		public function get_current_uri(){
			$http = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://');
			$referer = $http.$_SERVER['HTTP_HOST'];
			$archive_url = $referer.$_SERVER['REQUEST_URI'];

			return $archive_url;
		}

		public function display_blog_single_title($display = '1', $custom_class = ''){ 
			ob_start();
			global $post; ?>
			<?php if ($display == '1'):
				$class_sticky = is_sticky() ? ' wd-post-sticky' : ''; ?>
				<div class="wd-title-blog-wrap <?php echo esc_attr($custom_class); ?>">
					<?php $title_content = (get_the_title() != '') ? esc_html(get_the_title()) : esc_html__('View detail (No title)', 'feellio'); ?>
					<h1 class="wd-title-blog">
						<span class="wd-title-blog-link<?php echo esc_attr($class_sticky); ?>">
							<?php echo $title_content; ?>
						</span>
					</h1>
				</div>
			<?php endif ?>
			<?php 
			echo ob_get_clean();
		}

		/****************** SINGLE BLOG *******************/
		public function get_links_from_content(){
			if (!preg_match('/<a\s[^>]*?href=[\'"](.+?)[\'"]/i', get_the_content(), $links)) {
				return false;
			}
			return esc_url_raw($links[1]);
		}
		
		public function display_blog_previous_next_btn($display = '1', $custom_class = ''){ 
			ob_start();
			global $post; ?>
			<?php if ($display == '1'): ?>
				<?php
				$next_post = get_next_post();
				$previous  = get_previous_post(); ?>
				<?php if ($next_post || $previous): ?>
					<div class="wd-next-previous-post <?php echo esc_attr($custom_class); ?>">
						
						<?php if($previous){
							$title = esc_html__('Previous Post', 'feellio'); ?>
							<div class="wd-navi-prev">
								<a class="wd-navi-title" data-toggle="tooltip" title="<?php echo $title; ?>" href="<?php echo get_permalink( $previous->ID ); ?>">
									<span><i class="lnr lnr-chevron-left wd-icon"></i> <?php echo $title; ?></span>
								</a>
							</div>
						<?php } ?>
						<?php if($next_post){ 
							$title = esc_html__('Next Post', 'feellio'); ?>
							<div class="wd-navi-next">
								<a class="wd-navi-title" data-toggle="tooltip" title="<?php echo $title; ?>" href="<?php echo get_permalink( $next_post->ID ); ?>">
									<span><?php echo $title; ?> <i class="lnr lnr-chevron-right wd-icon"></i></span>
								</a>
							</div>
						<?php } ?>
					</div>
				<?php endif ?>
			<?php endif ?>
			<?php 
			echo ob_get_clean();
		}

		public function remove_all_image_from_content($content = ''){ 
			ob_start();
			global $post;
			$content = ($content) ? $content : apply_filters( 'the_content', get_post_field('post_content', get_the_ID()) );
			$content = preg_replace('/<img[^>]+./', '', $content);
			//add gallery to content
			$content .= $this->display_blog_thumbnail_gallery('full', 1, false, 10);
			return $content;
		}

		/****************** CONTENT OF BLOG *******************/
		public function set_post_views($display = '1'){
			if (!$display) return;
			/**
			 * wd_hook_set_post_views hook.
			 *
			 * @hooked set_post_views - 5
			 */
			do_action('wd_hook_set_post_views');
		}

		public function display_post_views($display = '1'){
			if (!$display) return;
			ob_start(); ?>
				<div class="wd-blog-meta-item wd-blog-meta--view">
					<span class="lnr lnr-eye"></span>
					<?php
					/**
					 * wd_hook_get_post_views hook.
					 *
					 * @hooked get_post_views - 5
					 */
					do_action('wd_hook_get_post_views'); ?>
				</div>
			<?php
			echo ob_get_clean();
		}

		public function display_post_like($display = '1'){
			if (!$display) return;
			ob_start(); ?>
				<div class="wd-blog-meta-item wd-blog-meta--like">
					<?php
					/**
					* wd_hook_post_like hook.
					*
					* @hooked add_action - 5
					*/
					do_action('wd_hook_post_like'); ?>
				</div>
			<?php
			echo ob_get_clean();
		}

		public function display_post_share($display = '1'){
			if (!$display) return;
			ob_start();
				/**
				 * wd_hook_social_sharing hook.
				 *
				 * @hooked social_sharing - 5
				 */
				do_action('wd_hook_social_sharing');
			echo ob_get_clean();
		}

		public function display_blog_thumbnail($image_size = 'full', $display = '1', $custom_class = ''){ 
			ob_start();
			global $post;
			?>
			<?php if( $display == '1' && has_post_thumbnail() && get_the_post_thumbnail()): ?>
				<div class="wd-thumbnail-post <?php echo esc_attr($custom_class); ?>">
					<a class="wd-thumbnail" href="<?php the_permalink(); ?>">
						<?php
							the_post_thumbnail($image_size);
						?>
					</a>
				</div>
			<?php endif; // End If ?>
			<?php 
			echo ob_get_clean();
		}

		//display slider gallery for post gallery
		public function display_blog_thumbnail_gallery($image_size = 'full', $display = '1', $placeholder = false, $num = '5', $custom_class = ''){ 
			ob_start();
			global $post;
			?>
			<?php if ($display): ?>
				<?php if( $this->get_post_thumbnail() ): ?>
					<?php 
					$slider_id 		= 'wd-post-gallery-'.get_the_ID(); 
					$attachments 	= $this->get_bs_slides( $this->get_post_thumbnail($image_size, $num), $image_size ); 
					$slider_options = json_encode(array(
						'slider_type' => 'owl',
						'column_desktop' => 1,
						'column_tablet' => 1,
						'column_mobile' => 1,
					)); ?>
					<div class="wd-thumbnail-post wd-thumbnail-post--gallery <?php echo esc_attr($custom_class); ?>">
						<div id="<?php echo esc_attr($slider_id); ?>">
							<!-- Wrapper for slides -->
							<ul class="wd-blog-gallery-list wd-slider-wrap wd-slider-wrap--post-gallery" 
								data-slider-options='<?php echo $slider_options; ?>'>
								<?php 
								$i = 1;
								foreach( $attachments as $attachment ): ?>
									<li class="item">
										<a class="wd-thumbnail" href="<?php the_permalink(); ?>">
											<img src="<?php echo $attachment['url']; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
										</a>
									</li>
								<?php $i++; ?>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				<?php elseif($placeholder && !is_home()): ?>
					<?php echo $this->get_placeholder_image('html'); ?>
				<?php endif; ?>
			<?php endif ?>
			
			<?php 
			echo ob_get_clean();
		}

		public function display_author_information($display = '1', $custom_class = ''){ 
			ob_start(); 
			global $post; ?>
			<?php if( $display == '1'): ?>
				<div class="wd-infomation-author">
					<div class="wd-author-avatar">
						<?php echo get_avatar(get_the_author_meta( 'ID' ), 120  ); ?>
					</div>
					<div class="wd-author-info">
						<div class="wd-author-name">
							<span itemprop="author"><?php echo the_author_posts_link(); ?></span>
						</div>
						<div class="wd-author-desc">
							<?php echo get_the_author_meta('description'); ?>
						</div>
					</div>
				</div>		
			<?php endif; // End If ?>
			<?php 
			echo ob_get_clean();
		}

		public function display_blog_title($display = '1', $custom_class = ''){ 
			ob_start();
			global $post;
			if ($display == '1'):
				$class_sticky = is_sticky() ? ' wd-post-sticky' : ''; ?>
				<div class="wd-title-blog-wrap <?php echo esc_attr($custom_class); ?>">
					<h2 class="wd-title-blog">
						<?php $title_content = (get_the_title() != '') ? esc_html(get_the_title()) : esc_html__('View detail (No title)', 'feellio'); ?>
						<a class="wd-title-blog-link<?php echo esc_attr($class_sticky); ?>" itemprop="name" href="<?php the_permalink() ; ?>"><?php echo $title_content; ?></a>
					</h2>
				</div>
			<?php endif ?>
			<?php 
			echo ob_get_clean();
		}

		public function display_blog_sticky($custom_class = ''){ 
			ob_start();
			global $post; ?>
			<?php if ( is_sticky() ) : ?>
				<span class="sticky-post <?php echo esc_attr($custom_class); ?>">
					<?php esc_html_e( 'Sticky', 'feellio' ); ?>
				</span>
			<?php endif ?>
			<?php 
			echo ob_get_clean();
		}

		public function display_blog_author($display = '1', $custom_class = ''){ 
			ob_start();
			global $post; ?>
			<?php if ($display == '1'): ?>
				<div class="wd-blog-meta-item wd-blog-meta--author <?php echo esc_attr($custom_class); ?>">
					<?php esc_html_e( 'Posted by', 'feellio' ); ?>
					<?php the_author_posts_link(); ?>
					<?php the_time('M j Y'); ?>
				</div>
			<?php endif ?>
			<?php 
			echo ob_get_clean();
		}

		public function display_blog_date($display = '1', $custom_class = ''){ 
			ob_start();
			global $post; ?>
			<?php if ($display == '1'): ?>
				<div class="wd-blog-meta-item wd-blog-meta--date <?php echo esc_attr($custom_class); ?>">
					<div class="wd-date-post-day"><?php the_time('j') ?></div>
					<div class="wd-date-post-my">
						<span><?php the_time('M'); ?></span>
						<span><?php the_time('Y'); ?></span>
					</div>
				</div>
			<?php endif ?>
			<?php 
			echo ob_get_clean();
		}

		public function display_blog_category($display = '1', $custom_class = ''){ 
			ob_start();
			global $post; ?>
			<?php if ($display == '1' && has_category()): ?>
				<div class="wd-blog-meta-item wd-blog-meta--category <?php echo esc_attr($custom_class); ?>">
					<?php the_category(esc_html__(' / ', 'feellio')); ?>
				</div>
			<?php endif ?>
			<?php 
			echo ob_get_clean();
		}

		public function display_blog_number_comment($display = '1', $custom_class = ''){ 
			ob_start();
			global $post; ?>
			<?php if ($display == '1'): ?>
				<div class="wd-blog-meta-item wd-blog-meta--comment <?php echo esc_attr($custom_class); ?>">
					<i class="lnr lnr-bubble wd-icon"></i>
					<?php
						echo $comment_number = get_comments_number() < 10 && get_comments_number() > 0 ? '0'.get_comments_number() : get_comments_number() ;
						//printf( _n( '%s Comment', '%s Comments', $comment_number, 'feellio' ), $comment_number);
					?>
				</div>
			<?php endif ?>
			<?php 
			echo ob_get_clean();
		}

		public function display_blog_tag($display = '1', $custom_class = ''){ 
			ob_start();
			global $post; ?>
			<?php if ($display == '1'): ?>
				<?php if (has_tag()): ?>
					<div class="wd-blog-meta-item wd-blog-meta--tag <?php echo esc_attr($custom_class); ?>">
						<?php the_tags('', ''); ?>
					</div>
				<?php endif ?>
			<?php endif ?>
			<?php 
			echo ob_get_clean();
		}

		public function display_blog_excerpt($display = '1', $number_excerpt = 20, $custom_class = ''){ 
			ob_start();
			global $post;
			$class = ($display == '0' && !is_home()) ? 'wd-blog-desc--hidden' : 'wd-blog-desc--show';
			$class .= (!$number_excerpt || $number_excerpt == '-1') ? ' wd-blog-desc--full' : ' wd-blog-desc--limit-word';
			$class .= ' '.esc_attr($custom_class); ?>
			<div itemprop="description" class="wd-blog-desc <?php echo esc_attr($class); ?>">
				<div class="entry-content">
					<?php if (get_the_content() || get_the_excerpt()): ?>
						<?php if (!post_password_required()): ?>
								<?php echo apply_filters('wd_filter_excerpt_limit_word_length', array('word_limit' => $number_excerpt)); ?>
						<?php else: ?>
							<?php echo get_the_password_form(); ?>
						<?php endif ?>
					<?php endif ?>
				</div>
			</div>
			<?php 
			echo ob_get_clean();
		}

		public function display_blog_readmore($display = '1', $custom_class = ''){ 
			ob_start();
			global $post;
			?>
			<?php if ($display == '1' && !is_home()): ?>
				<div class="readmore <?php echo esc_attr($custom_class); ?>">
					<a itemprop="sameAs" class="readmore_link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read More', 'feellio') ?></a>
				</div>
			<?php endif ?>
			<?php 
			echo ob_get_clean();
		}

		/* Edit link on single post / page */
		public function display_blog_edit_link() {
			edit_post_link(esc_html__( 'Edit', 'feellio' ), '<div class="wd-blog-meta-item wd-blog-meta--edit"><span class="lnr lnr-pencil wd-icon"></span> ', '</div>' );
		}

		public function display_blog_page_link(){ 
			wp_link_pages( array(
				'before'      => '<div class="wd-page-links"><span class="wd-page-links-title">' . esc_html__( 'Pages:', 'feellio' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
		}

		//Custom HTML post password protect form
		public function password_protect_form() {
			global $post;
			$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
			$password_protect_form = '<div class="wd-password-protect-form"><form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">';
			$password_protect_form .= '<p>' . esc_html__( 'To view this protected post, enter the password below: ', 'feellio' ) . '</p>';
			$password_protect_form .= '<label for="' . $label . '">' . esc_html__( 'Password: ', 'feellio' ) . ' </label>';
			$password_protect_form .= '<input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" /><input type="submit" name="Submit" value="' . esc_attr__( 'Submit ', 'feellio' ) . '" />';
			$password_protect_form .= '</form></div>';
			return $password_protect_form;
		}

	}
	WD_Blog::get_instance();  // Start an instance of the plugin class 
}