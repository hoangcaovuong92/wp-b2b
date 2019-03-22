<section class="no-results not-found">
	<header class="page-header">
		<h2 class="page-title"><?php esc_html_e( 'Nothing Found', 'feellio' ); ?></h2>
	</header><!-- .page-header -->
	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p><?php printf( esc_html__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'feellio' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
		<?php elseif ( is_search() ) : ?>
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'feellio' ); ?></p>
			<?php 
			/**
			 * wd_hook_search_form hook.
			 *
			 * @hooked facebook_api - 5
			 */ 
			do_action('get_search_form'); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'feellio' ); ?></p>
			<?php 
			/**
			 * wd_hook_search_form hook.
			 *
			 * @hooked facebook_api - 5
			 */ 
			do_action('get_search_form'); ?>
		<?php endif; ?>

	</div><!-- .page-content -->

</section><!-- .no-results -->