
		</div>


		<!-- footer -->
		<footer id="footer">

			<?php
			$newsletter_enabled = (bool)get_iron_option('footer_newsletter_enabled');
			$newsletter_home_only = (bool)get_iron_option('footer_newsletter_home_only');
			$newsletter_title = get_iron_option('footer_newsletter_title');
			$newsletter_description = get_iron_option('footer_newsletter_description');
			$newsletter_id = get_iron_option('footer_newsletter_id');
			if ($newsletter_enabled && !empty($newsletter_id) && function_exists('is_plugin_active') && is_plugin_active('nmedia-mailchimp-widget/nm_mailchimp.php')) : ?>

				<?php if(!($newsletter_home_only && !is_front_page())): ?>
				<div class="newsletter-wrap">

					<?php if(!empty($newsletter_title)): ?>
					<div class="newsletter-title-wrap">
						<div class="topwave"></div>
						<h3><?php echo $newsletter_title; ?></h3>
						<div class="botwave"></div>
					</div>
					<?php endif; ?>

					<?php if(!empty($newsletter_description)): ?>
					<div class="newsletter-description-wrap">
						<p><?php echo $newsletter_description; ?></p>
					</div>
					<?php endif; ?>

					<?php echo do_shortcode('[nm-mc-form fid="'.$newsletter_id.'"]'); ?>

				</div>
				<?php endif; ?>

			<?php endif; ?>



			<?php
			$footer_area = get_iron_option('footer-area_id');
			if ( is_active_sidebar( $footer_area ) ) :
				$widget_area = get_iron_option('widget_areas', $footer_area);
			?>
						<div class="footer__widgets widget-area widget-area--<?php echo esc_attr( $footer_area ); if ( $widget_area['sidebar_grid'] > 1 ) echo ' grid-cols grid-cols--' . $widget_area['sidebar_grid']; ?>">
			<?php
				do_action('before_ironband_footer_dynamic_sidebar');

				dynamic_sidebar( $footer_area );

				do_action('after_ironband_footer_dynamic_sidebar');
			?>
						</div>
			<?php
			endif;
			?>

			<?php
			$social_media = (bool)get_iron_option('footer_social_media_enabled');
			?>
			<?php if($social_media): ?>
			<div class="footer-block share">
				<!-- links-box -->
				<div class="links-box">
				<?php get_template_part('parts/networks'); ?>
				</div>
			</div>
			<?php endif; ?>

			<!-- footer-row -->
			<div class="footer-row">
				<div class="footer-wrapper">
					<?php
		if ( get_iron_option('footer_bottom_logo') ) :
			$output = '<img src="' . esc_url( get_iron_option('footer_bottom_logo') ) . '" alt="">';

			if ( get_iron_option('footer_bottom_link') )
				$output = sprintf('<a target="_blank" href="%s">%s</a>', esc_url( get_iron_option('footer_bottom_link') ), $output);

			echo $output . "\n";
		endif;
					?>
					<div class="text"><?php echo apply_filters('the_content', get_iron_option('footer_copyright') ); ?></div>
					<div class="clear"></div>
				</div>
			</div>
		</footer>

	</div>
<?php wp_footer(); ?>
</body>
</html>