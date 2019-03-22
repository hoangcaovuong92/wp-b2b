<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Comments')) {
	class WD_Comments {
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

			
			/* Display Comment Form */
			// do_action('wd_hook_display_comment_form');
			add_action('wd_hook_display_comment_form', array($this, 'display_comment_form'), 5);
		}

		public function display_comment_form(){
			/**
			 * package: comment
			 * var: layout_style
			 * var: comment_status
			 * var: comment_mode
			 * var: num_comment
			 */
			extract(apply_filters('wd_filter_get_data_package', 'comment'));

			//If comments are open or we have at least one comment, load up the comment template
			if ( ! post_password_required() && (comments_open() || '0' != get_comments_number()) ){ ?>
				<div class="wd-comment-form-wrap wd-comment-form-style-<?php echo $layout_style ? 'tab' : 'normal'; ?>">
					<?php
					foreach ($comment_status as $key => $value) {
						if ($value) {
							$this->display_comment_form_by_mode($key);
						}
					} ?>		
				</div>
			<?php
			}
		}
	
		//Display comment form
		public function display_comment_form_by_mode($comment_mode = 'wordpress'){
			if ($comment_mode == 'wordpress') {
				if (!is_singular('product' )) {
					 comments_template('');
				}
			} elseif ($comment_mode == 'facebook') {
				echo $this->get_comment_form_facebook();
			} 
		}
	
		//Get HTML of comment form facebook
		public function get_comment_form_facebook(){ 
			/**
			 * package: comment
			 * var: layout_style
			 * var: comment_status
			 * var: comment_mode
			 * var: num_comment
			 */
			extract(apply_filters('wd_filter_get_data_package', 'comment'));
			$content = '';
			ob_start(); 
			?>
			<div class="wd-facebook-comment-form">
				<?php if ($comment_mode): ?>
					<div class="fb-comments" xid="<?php the_ID(); ?>" data-numposts="<?php echo esc_attr($num_comment); ?>" data-colorscheme="light" data-width="100%" data-version="v2.3"></div>
				<?php else: ?>
					<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-numposts="<?php echo esc_attr($num_comment); ?>" width="100%" data-colorscheme="light" data-width="100%" data-version="v2.3"></div>
				<?php endif ?>
			</div>
			<?php
			$content = ob_get_clean();
			return $content;
		}
	
		//Get facebook comment count of post
		public function get_comment_facebook_count(){
			global $post;
			/**
			 * package: comment
			 * var: layout_style
			 * var: comment_status
			 * var: comment_mode
			 * var: num_comment
			 */
			extract(apply_filters('wd_filter_get_data_package', 'comment'));
			if ($comment_mode) { ?>
				 <span class="fb-comments-count" data-href="<?php the_permalink(); ?>"></span>
			 <?php
			} elseif ($comment_mode == 'facebook') { ?>
				<span class="fb-comments-count" xid="<?php the_ID(); ?>></span>
			<?php 
			} 
		}
	
		// Comment theme
		public function comment_item( $comment, $args, $depth ) {
			$GLOBALS['comment'] = $comment;
			switch ( $comment->comment_type ) :
				case '' : ?>
					 <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
					 	<div class="wd-comment-author-wrap">
							<div class="comment-author vcard">
								<?php echo get_avatar($comment, 70 ); ?>
							</div><!-- .comment-author .vcard -->
							<div class="comment-text">
								<div class="comment-info-container">
									<div class="comment-author-name">
										<?php printf(  '%s <span class="says"></span>', sprintf( '<span class="fn">%s</span>', get_comment_author_link() ) ); ?>
									</div>
								</div>

								<div class="comment-body"><?php comment_text(); ?></div>
								<?php if ( $comment->comment_approved == '0' ) : ?>
									<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'feellio' ); ?></em><br/>
								<?php endif; ?>
								
								<div class="wd-comment-meta">
									<div class="wd-comment-meta-item comment-date">
										<?php printf( esc_html__( '%1$s', 'feellio' ), get_comment_date()); ?>
									</div>

									<?php if (get_edit_comment_link()) { ?>
										<div class="wd-comment-meta-item comment-edit">
											<?php edit_comment_link('<i class="lnr lnr-pencil wd-icon"></i>'.esc_html__( 'Edit', 'feellio' ), ' ' );?>
										</div>
									<?php } ?>
									
									<?php 
									$edit_comment_link = get_comment_reply_link(array_merge(array('reply_text' => esc_html__('Reply', 'feellio' )) , array( 'depth' => $depth, 'max_depth' => $args['max_depth'])));
									if ($edit_comment_link) { ?>
										<div class="wd-comment-meta-item comment-reply">
											<span class="reply"><i class="fa fa-pencil-square-o wd-icon"></i><?php comment_reply_link( array_merge( array( 'reply_text' => esc_html__( 'Reply', 'feellio' ) ) , array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
										</div><!-- .reply -->
									<?php } ?>
								</div>
							</div>
						</div>
					<?php
					break;
				case 'pingback'  :
				case 'trackback' :
				break;
			endswitch;
		} // End Function
	
		// Comments List
		public function comment_list() { 
			global $post; ?>
			<?php if ( have_comments() ) : ?>
				<div class="wd-title">
					<h3 class="wd-title-heading">
						<?php
							$comment_number = get_comments_number() < 10 && get_comments_number() > 0 ? '0'.get_comments_number() : get_comments_number() ;
							printf( _n( '%s Comment on "%s"', '%s Comments on "%s"', $comment_number, 'feellio' ), $comment_number, get_the_title() );
						?>
					</h3>
				</div>
				
				<!-- Are there comments to navigate through ? -->
				<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
					<div class="navigation">
						<div class="nav-previous"><?php previous_comments_link( wp_kses(__( '<span class="meta-nav">&larr;</span> Older Comments', 'feellio' ),array()) ); ?></div>
						<div class="nav-next"><?php next_comments_link( wp_kses(__( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'feellio' ),array()) ); ?></div>
					</div> <!-- .navigation -->
				<?php endif; // check for comment navigation ?>
	
				<div class="wd-post-pingbacks-and-trackbacks">
					<?php
					wp_list_comments( array(
						'style'             => 'ul',
						'type'              => 'pings',
						'short_ping'        => true,
					) ); ?>
				</div>
	
				<ol class="wd-comment-list">
					<?php
						wp_list_comments( array(
							'type'       	=> 'comment',
							'short_ping'  	=> true,
							'callback'		=> array($this, 'comment_item')
						) );	
					?>
				</ol>
	
				<!-- Are there comments to navigate through ? -->
				<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
					<div class="navigation">
						<div class="nav-previous"><?php previous_comments_link( wp_kses(__( '<span class="meta-nav">&larr;</span> Older Comments', 'feellio' ),array()) ); ?></div>
						<div class="nav-next"><?php next_comments_link( wp_kses(__( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'feellio' ),array()) ); ?></div>
					</div> <!-- .navigation -->
				<?php endif; // check for comment navigation ?>
	
			<?php else: ?>
				<?php if (!comments_open()) : ?>
					<p class="nocomments"><?php esc_html_e( 'Comments are closed.', 'feellio' ); ?></p>
				<?php endif; // end ! comments_open() ?>
			<?php endif; // end have_comments() ?>
		<?php
		} // End Function
	
		//Default wordpress comment form
		public function comment_form( $args = array(), $post_id = null ) {
			global $user_identity, $id;
	
			if ( null === $post_id )
				$post_id = $id;
			else
				$id = $post_id;
	
			$commenter 				= wp_get_current_commenter();
			$req 					= get_option( 'require_name_email' );
			$area_req 				= " aria-required='true' required";
			$area_html_req 			= ' <span class="required">*</span>';
			$name_email_req 		= $req ? $area_req : '';
			$name_email_html_req 	= $req ? $area_html_req : '';
			$label 					= array(
				'comment_author'		=>	esc_html__('Name', 'feellio'),
				'comment_author_email'	=>	esc_html__('Email', 'feellio'),
				'comment_author_url'	=>	esc_html__('Website', 'feellio'),	
				'comment_field'			=>	esc_html__('Comment', 'feellio'),	
			);
			$placeholder 	= array(
				'comment_author'		=>	esc_html__('Your name', 'feellio'),
				'comment_author_email'	=>	esc_html__('Your email', 'feellio'),
				'comment_author_url'	=>	esc_html__('Your website', 'feellio'),
				'comment_field'			=>	esc_html__('Your Comment', 'feellio'),
			);
			extract(array_filter(array(
				'comment_author'		=>	esc_attr($commenter['comment_author']),
				'comment_author_email'	=>	esc_attr($commenter['comment_author_email']),
				'comment_author_url'	=>	esc_attr($commenter['comment_author_url'])
			)),EXTR_OVERWRITE);
			
			$fields =  array(
				'author' => '<div class="wd-comment-author-item"><span class="label">'.$label['comment_author'].$name_email_html_req.'</span><p class="comment-form-author">' . '<input id="author" class="input-text" name="author" type="text" placeholder="' .$placeholder['comment_author']. '" size="30"' . $name_email_req . ' />' .'</p></div>',
				'email'  => '<div class="wd-comment-author-item"><span class="label">'.$label['comment_author_email'].$name_email_html_req.'</span><p class="comment-form-email"><input id="email" class="input-text" name="email" type="text" placeholder="'. $placeholder['comment_author_email']. '" size="30"' . $name_email_req . ' />'.'</p></div>',
				'url'    => '<div class="wd-comment-author-item"><span class="label">'.$label['comment_author_url'].'</span><p class="comment-form-url"><input id="url" class="input-text" name="url" type="text" placeholder="' .$placeholder['comment_author_url']. '" size="30" />' .'</p></div>',
			);
			
			if( !is_user_logged_in() ){
				$fields['author'] = '<div class="comment-author-wrapper">'.$fields['author'];
				$fields['url'] = $fields['url'].'</div>';
			}
	
			$defaults = array(
				'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
				'comment_field'        => '<span class="label">'.$label['comment_field'].$area_html_req.'</span><p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" ' . $area_req . ' placeholder="' .$placeholder['comment_field']. '" ></textarea></p>',
				'must_log_in'          => '<p class="must-log-in">' .  sprintf(__( 'You must be <a href="%s">logged in</a> to post a comment.', 'feellio' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
				'logged_in_as'         => '<p class="logged-in-as">' . sprintf(__( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'feellio'), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
				'comment_notes_before' => '',
				'comment_notes_after'  => '',
				'id_form'              => 'wd-comment-respond-form',
				'id_submit'            => 'submit',
				'title_reply'          => esc_html__( 'Leave a comment', 'feellio' ),
				'title_reply_to'       => esc_html__( 'Leave a reply to %s', 'feellio'),
				'cancel_reply_link'    => esc_html__( 'Cancel reply', 'feellio' ),
				'label_submit'         => esc_html__( 'POST COMMENT', 'feellio' ),
				'label_infomation'	   => esc_html__( 'Please note: comments may need to be approved before publishing!', 'feellio' )
			);
			
			if( !is_user_logged_in() ){
				$defaults['comment_field'] = '<div class="comment-message-wrapper">'.$defaults['comment_field'].'</div>';
			}
	
			$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );
	
			?>
				<?php if ( comments_open() ) : ?>
					<?php do_action( 'comment_form_before' ); ?>
					<div id="respond">
						<div class="wd-title">
							<h3 class="wd-title-heading">
								<?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?>
							</h3>
							<?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?>
						</div>
						
						<?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
							<?php echo esc_attr($args['must_log_in']); ?>
							<?php do_action( 'comment_form_must_log_in_after' ); ?>
						<?php else : ?>
							<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
								<?php do_action( 'comment_form_top' ); ?>
								<?php if ( is_user_logged_in() ) : ?>
									<?php echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ); ?>
									<?php do_action( 'comment_form_logged_in_after', $commenter, $user_identity ); ?>
									<?php echo apply_filters( 'comment_form_field_comment', $args['comment_field'] ); ?>
								<?php else : ?>
									<?php echo esc_attr($args['comment_notes_before']); ?>
									<?php
									do_action( 'comment_form_before_fields' );
									//Textarea field
									echo apply_filters( 'comment_form_field_comment', $args['comment_field'] );
									//User fields
									foreach ( (array) $args['fields'] as $name => $field ) {
										echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
									} ?>
								<?php endif; ?>
								<?php echo esc_attr($args['comment_notes_after']); ?>
	
								<p class="info"><span class="lnr lnr-checkmark-circle wd-icon"></span><?php echo esc_attr( $args['label_infomation'] ); ?></p>
	
								<?php if ( !is_user_logged_in() ) do_action( 'comment_form_after_fields' );?>
								<p class="form-submit">
									<button class="button" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>"><span><span><?php echo esc_attr( $args['label_submit'] ); ?></span></span></button>
	
									<?php comment_id_fields( $post_id ); ?>
								</p>
								<?php do_action( 'comment_form', $post_id ); ?>
							</form>
						<?php endif; ?>
					</div><!-- #respond -->
					<?php do_action( 'comment_form_after' ); ?>
				<?php else : ?>
					<?php do_action( 'comment_form_comments_closed' ); ?>
				<?php endif; ?>
			<?php
		} // End Function
		
	}
	WD_Comments::get_instance();  // Start an instance of the plugin class 
}