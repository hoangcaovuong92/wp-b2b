			<?php
			/**
			 * The template for displaying the footer.
			 *
			 * Contains the closing of the #content div and all content after
			 *
			 * @package Wordpress
			 * @since wpdance
			 */
			?>
			<footer id="footer" class="footer">
				<?php 
				/**
				 * wd_hook_footer_init_action hook.
				 *
				 * @hooked html_footer_init - 5
				 * @hooked back_to_top_button - 10
				 * @hooked facebook_chatbox_content - 15 (setting)
				 * @hooked email_subscribe_popup_content - 20 (setting)
				 * @hooked product_effect - 25
				 */ 
				do_action('wd_hook_footer_init_action'); ?>
			</footer> <!-- END FOOOTER  -->
		</div>
		<?php wp_footer(); ?>
	</body>
</html>